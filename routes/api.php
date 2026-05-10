<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- 1. IMPORT ADMIN CONTROLLERS (Alias: Admin...) ---
use App\Http\Controllers\Api\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\CustomerController as AdminManageCustomerController;
use App\Http\Controllers\Api\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\SliderController as AdminSliderController;

// --- 2. IMPORT CUSTOMER CONTROLLERS (Alias: Customer...) ---
use App\Http\Controllers\Api\Customer\LoginController as CustomerLoginController;
use App\Http\Controllers\Api\Customer\RegisterController as CustomerRegisterController;
use App\Http\Controllers\Api\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Api\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Api\Customer\InvoiceController as CustomerInvoiceController;

// --- 3. IMPORT WEB CONTROLLERS (Alias: Web...) ---
use App\Http\Controllers\Api\Web\ProductController as WebProductController;
use App\Http\Controllers\Api\Web\SliderController as WebSliderController;
use App\Http\Controllers\Api\Web\CartController as WebCartController;
// Perhatikan: Category untuk Web kita pakai punya Admin saja (Read-only) agar efisien


// --- GROUP API WEB (api/web/...) ---
Route::prefix('web')->group(function () {
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::get('/categories/{slug}', [AdminCategoryController::class, 'show']);
    Route::get('/products', [WebProductController::class, 'index']);
    Route::get('/products/{slug}', [WebProductController::class, 'show']);
    Route::get('/sliders', [WebSliderController::class, 'index']);
    Route::get('/carts',[WebCartController::class, 'index']);
    Route::post('/carts', [WebCartController::class, 'store']);
    Route::get('/carts/total_price', [WebCartController::class, 'getCartPrice']);
    Route::get('/carts/total_weight', [WebCartController::class, 'getCartWeight']);
    Route::post('/carts/remove', [WebCartController::class, 'removeCart']);
});

// --- GROUP API CUSTOMER (api/customer/...) ---
Route::prefix('customer')->group(function () {
    // Public Routes
    Route::post('/register', [CustomerRegisterController::class, 'register']);
    Route::post('/login', [CustomerLoginController::class, 'index']);
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::get('/categories/{slug}', [AdminCategoryController::class, 'show']);

    // Private Routes
    Route::middleware('auth:api_customer')->group(function () {
        Route::get('/user', [CustomerLoginController::class, 'getUser']);
        Route::get('/refresh', [CustomerLoginController::class, 'refreshToken']);
        Route::post('/logout', [CustomerLoginController::class, 'logout']);
        Route::get('/dashboard', [CustomerDashboardController::class, 'index']);
        Route::get('/invoices', [CustomerInvoiceController::class, 'index']);
        Route::get('/invoices/{snap_token}', [CustomerInvoiceController::class, 'show']);
        Route::post('/reviews', [CustomerReviewController::class, 'store']);
    });
});

// --- GROUP API ADMIN (api/admin/...) ---
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminLoginController::class, 'index'])->name('admin.login');

    Route::middleware('auth:api_admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);
        Route::get('/user', [AdminLoginController::class, 'getUser']);       
        Route::apiResource('/categories', AdminCategoryController::class);
        Route::apiResource('/products', AdminProductController::class);
        Route::apiResource('/invoices', AdminInvoiceController::class)->only(['index', 'show']);
        Route::apiResource('/sliders', AdminSliderController::class);
        Route::apiResource('/customers', AdminManageCustomerController::class); 
    });
});
