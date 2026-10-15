<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $row = fn (string $flag) => Product::with('brandRecord')
            ->where('is_active', true)->where($flag, true)
            ->latest()->take(8)->get();

        return view('home', [
            'categories' => ProductCategory::whereNull('parent_id')->where('is_active', true)
                ->orderBy('sort_order')->orderBy('name')->get(),
            'services' => Service::where('is_active', true)->orderBy('sort_order')->take(6)->get(),
            'rows' => [
                'Best Sellers' => $row('is_bestseller'),
                'Trending' => $row('is_trending'),
                'Latest Products' => $row('is_latest'),
            ],
        ]);
    }
}