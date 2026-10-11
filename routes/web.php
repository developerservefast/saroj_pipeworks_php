<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductPageController;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Controllers\ServicePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductPageController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductPageController::class, 'show'])->name('products.show');
Route::get('/services', [ServicePageController::class, 'index'])->name('services.index');
Route::post('/services/request', [ServicePageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('services.request');

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', fn () => view('admin.dashboard', [
            'productCount' => Product::count(),
            'categoryCount' => ProductCategory::count(),
        ]))->name('admin.dashboard');

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::resource('products', ProductController::class)
            ->except('show')
            ->names('admin.products');

        Route::resource('services', ServiceController::class)
            ->except('show')
            ->names('admin.services');

        Route::resource('service-requests', ServiceRequestController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->names('admin.service-requests');
    });
});
