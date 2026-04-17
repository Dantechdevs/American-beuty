<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MpesaCallbackController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

// ─── Frontend ────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products',          [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}',   [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart',              [CartController::class, 'index'])->name('cart');
Route::post('/cart/add',         [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}',       [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}',      [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count',        [CartController::class, 'count'])->name('cart.count');

// Checkout (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/checkout',                 [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/coupon',         [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon.apply');
    Route::delete('/checkout/coupon',       [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
    Route::post('/checkout/place-order',    [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
    Route::get('/checkout/mpesa/wait/{order}', [CheckoutController::class, 'mpesaWait'])->name('checkout.mpesa.wait');
    Route::get('/checkout/mpesa/status',    [CheckoutController::class, 'mpesaStatus'])->name('checkout.mpesa.status');
    Route::get('/order/success/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');
});

// M-PESA Callback (no CSRF — Safaricom posts here)
Route::post('/mpesa/callback', [MpesaCallbackController::class, 'handle'])
    ->name('mpesa.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ─── Auth ────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin ───────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                  [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::get('/products',          [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',   [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products',         [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}',[AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{product}/toggle', [AdminProductController::class, 'toggleStatus'])->name('products.toggle');

    // Orders
    Route::get('/orders',            [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',    [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    // Categories
    Route::get('/categories',        [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories',       [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Users
    Route::get('/users',             [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');

    // Settings
    Route::get('/settings',          [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings',         [SettingsController::class, 'update'])->name('settings.update');
    Route::patch('/settings/gateways/{gateway}', [SettingsController::class, 'updateGateway'])->name('settings.gateway');
});
