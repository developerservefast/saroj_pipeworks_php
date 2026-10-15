<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ServiceRequest;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'hiddenCount' => Product::where('is_active', false)->count(),
            'categoryCount' => ProductCategory::whereNull('parent_id')->count(),
            'newRequests' => ServiceRequest::where('status', 'new')->count(),
            'recent' => ServiceRequest::with('services')->latest()->take(5)->get(),
        ]);
    }
}