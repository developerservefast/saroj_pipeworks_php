<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubCategoryController extends Controller
{
    public function index(Request $r)
    {
        $parents = ProductCategory::whereNull('parent_id')->orderBy('sort_order')->orderBy('name')->get();

        $subs = ProductCategory::whereNotNull('parent_id')
            ->with('parent')->withCount('products')
            ->when($r->category, fn ($q, $id) => $q->where('parent_id', $id))
            ->when($r->q, fn ($q, $t) => $q->where('name', 'like', "%{$t}%"))
            ->orderBy('parent_id')->orderBy('sort_order')->orderBy('name')
            ->paginate(30)->withQueryString();

        return view('admin.subcategories.index', compact('parents', 'subs'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'parent_id' => ['required', Rule::exists('product_categories', 'id')->whereNull('parent_id')],
            'name' => ['required', 'string', 'max:120', Rule::unique('product_categories', 'name')->where(fn ($q) => $q->where('parent_id', $r->parent_id))],
            'sort_order' => 'nullable|integer|min:0',
        ], ['name.unique' => 'This sub category already exists under the selected category.']);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $r->boolean('is_active');
        $data['slug'] = ProductCategory::makeSlug($data['name']);

        ProductCategory::create($data);

        return back()->with('success', 'Sub category added.');
    }

    public function update(Request $r, ProductCategory $subcategory)
    {
        $data = $r->validate([
            'parent_id' => ['required', Rule::exists('product_categories', 'id')->whereNull('parent_id')],
            'name' => ['required', 'string', 'max:120', Rule::unique('product_categories', 'name')->where(fn ($q) => $q->where('parent_id', $r->parent_id))->ignore($subcategory->id)],
            'sort_order' => 'nullable|integer|min:0',
        ], ['name.unique' => 'This sub category already exists under the selected category.']);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $r->boolean('is_active');

        $subcategory->update($data);

        return back()->with('success', 'Sub category saved.');
    }

    public function destroy(ProductCategory $subcategory)
    {
        if ($subcategory->products()->exists()) {
            return back()->with('error', "Can't delete \"{$subcategory->name}\": it still has products.");
        }
        $subcategory->delete();

        return back()->with('success', 'Sub category deleted.');
    }
}