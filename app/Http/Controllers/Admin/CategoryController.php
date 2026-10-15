<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $r)
    {
        $categories = ProductCategory::whereNull('parent_id')
            ->withCount(['children', 'products'])
            ->when($r->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($r->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($r->q, fn ($q, $t) => $q->where('name', 'like', "%{$t}%"))
            ->orderBy('sort_order')->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('product_categories', 'name')->whereNull('parent_id')],
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ], ['name.unique' => 'A category with this name already exists.']);

        if ($r->hasFile('image')) {
            $data['image'] = \App\Support\Img::store($r->file('image'), 'categories');
        }
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $r->boolean('is_active');
        $data['slug'] = ProductCategory::makeSlug($data['name']);

        ProductCategory::create($data);

        return back()->with('success', 'Category added.');
    }

    public function update(Request $r, ProductCategory $category)
    {
        $data = $r->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('product_categories', 'name')->whereNull('parent_id')->ignore($category->id)],
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ], ['name.unique' => 'A category with this name already exists.']);

        if ($r->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $data['image'] = \App\Support\Img::store($r->file('image'), 'categories');
        } else {
            unset($data['image']);
        }
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $r->boolean('is_active');

        $category->update($data);

        return back()->with('success', 'Category saved.');
    }

    public function destroy(ProductCategory $category)
    {
        if ($category->children()->exists()) {
            return back()->with('error', "Can't delete \"{$category->name}\": it has sub categories. Delete or move them first.");
        }
        if ($category->products()->exists()) {
            return back()->with('error', "Can't delete \"{$category->name}\": it still has products.");
        }
        if ($category->image) Storage::disk('public')->delete($category->image);
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}