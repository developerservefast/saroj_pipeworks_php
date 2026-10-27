<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductPageController extends Controller
{
   public function index(\Illuminate\Http\Request $request)
{
    $tree = \App\Models\ProductCategory::whereNull('parent_id')->where('is_active', true)->with(['children' => fn ($q) => $q->where('is_active', true)])
        ->orderBy('sort_order')->orderBy('name')->get();

    $active = $request->filled('category') ? \App\Models\ProductCategory::with('children')->find($request->category) : null;
    $search = trim((string) $request->query('q'));
    $sort = in_array($request->query('sort'), ['new', 'az', 'za'], true) ? $request->query('sort') : '';

    $products = null;
    if ($active || $search !== '') {
        $q = \App\Models\Product::with(['brandRecord', 'variants'])->where('is_active', true)->whereHas('category', fn ($c) => $c->where('is_active', true));
        if ($active) $q->whereIn('product_category_id', $active->children->pluck('id')->push($active->id));
        if ($search !== '') {
        foreach (preg_split('/\s+/', mb_substr($search, 0, 100)) as $w) {
            $w = addcslashes($w, '%_\\');
            $q->where(fn ($s) => $s->where('name', 'like', "%{$w}%")
                ->orWhere('brand', 'like', "%{$w}%")
                ->orWhereHas('brandRecord', fn ($b) => $b->where('name', 'like', "%{$w}%"))
                ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$w}%")));
        }
    }
        match ($sort) { 'new' => $q->latest(), 'az' => $q->orderBy('name'), 'za' => $q->orderByDesc('name'), default => $q->orderBy('sort_order')->orderBy('name') };
        $products = $q->paginate(24)->withQueryString();
    }

    return view('products.index', compact('tree', 'active', 'products', 'search', 'sort'));
}
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        $product->load(['category.parent', 'images', 'variants', 'sizes', 'brandRecord']);
        abort_unless($product->category->is_active && (! $product->category->parent || $product->category->parent->is_active), 404);

        $related = Product::where('is_active', true)
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('sort_order')->take(4)->get();

        return view('products.show', compact('product', 'related'));
    }
}
