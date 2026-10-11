<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductPageController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::orderBy('sort_order')->get();
        $current = $categories->firstWhere('slug', $request->category);

        $products = Product::with('category')
            ->where('is_active', true)
            ->when($current, fn ($q) => $q->where('product_category_id', $current->id))
            ->when($request->q, fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->orderBy('product_category_id')->orderBy('sort_order')
            ->paginate(12)->withQueryString();

        return view('products.index', compact('categories', 'current', 'products'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        $product->load('category');

        $related = Product::where('is_active', true)
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('sort_order')->take(4)->get();

        return view('products.show', compact('product', 'related'));
    }
}
