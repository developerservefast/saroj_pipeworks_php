<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\ServicePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductPageController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductPageController::class, 'show'])->name('products.show');
Route::get('/services', [ServicePageController::class, 'index'])->name('services.index');
Route::post('/services/request', [ServicePageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('services.request');

Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.send');

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('admin.settings.update');

        Route::get('password', [PasswordController::class, 'edit'])->name('admin.password.edit');
        Route::put('password', [PasswordController::class, 'update'])->name('admin.password.update');

        Route::get('products/import', [ProductImportController::class, 'create'])->name('admin.products.import');
        Route::get('products/import/template', [ProductImportController::class, 'template'])->name('admin.products.import.template');
        Route::post('products/import', [ProductImportController::class, 'store'])->middleware('throttle:10,1')->name('admin.products.import.store');

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

        Route::resource('sizes', SizeController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('admin.sizes');

        Route::resource('services', ServiceController::class)
            ->except('show')
            ->names('admin.services');

        Route::resource('service-requests', ServiceRequestController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->names('admin.service-requests');

        Route::resource('contact-messages', ContactMessageController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('admin.contact-messages');
    });
});