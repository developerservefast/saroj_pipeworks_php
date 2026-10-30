<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\ServicePageController;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductPageController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductPageController::class, 'show'])->name('products.show');
Route::get('/services', [ServicePageController::class, 'index'])->name('services.index');
Route::post('/services/request', [ServicePageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('services.request');

Route::view('/about', 'about')->name('about');
Route::get('/contact', fn () => view('contact'))->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.send');

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('admin.settings.edit');
        Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

        Route::get('password', [\App\Http\Controllers\Admin\PasswordController::class, 'edit'])->name('admin.password.edit');
        Route::put('password', [\App\Http\Controllers\Admin\PasswordController::class, 'update'])->name('admin.password.update');

        Route::post('products/bulk', [ProductController::class, 'bulk'])->name('admin.products.bulk');
        Route::post('products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('admin.products.duplicate');

        Route::resource('products', ProductController::class)
            ->except('show')
            ->names('admin.products');

        Route::resource('categories', CategoryController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.categories');

        Route::resource('subcategories', SubCategoryController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.subcategories');

        Route::resource('brands', BrandController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.brands');

        Route::resource('sizes', \App\Http\Controllers\Admin\SizeController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.sizes');

        Route::resource('services', ServiceController::class)
            ->except('show')
            ->names('admin.services');

        Route::resource('service-requests', ServiceRequestController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->names('admin.service-requests');

        Route::resource('contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('admin.contact-messages');
    });
});
