<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'categories' => ProductCategory::withCount('products')->orderBy('sort_order')->get(),
            'services' => Service::where('is_active', true)->orderBy('sort_order')->take(6)->get(),
        ]);
    }
}
