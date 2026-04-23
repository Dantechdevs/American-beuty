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
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ReturnOrderController as AdminReturnOrderController;
use App\Http\Controllers\Customer\ReturnOrderController as CustomerReturnOrderController;
use App\Http\Controllers\Admin\SalesReportController;

use Illuminate\Support\Facades\Route;

// ─── Frontend ────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products',        [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart',            [CartController::class, 'index'])->name('cart');
Route::post('/cart/add',       [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}',     [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}',    [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count',      [CartController::class, 'count'])->name('cart.count');

// Checkout (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/checkout',                    [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/coupon',            [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon.apply');
    Route::delete('/checkout/coupon',          [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
    Route::post('/checkout/place-order',       [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
    Route::get('/checkout/mpesa/wait/{order}', [CheckoutController::class, 'mpesaWait'])->name('checkout.mpesa.wait');
    Route::get('/checkout/mpesa/status',       [CheckoutController::class, 'mpesaStatus'])->name('checkout.mpesa.status');
    Route::get('/order/success/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');
});

// M-PESA Callback (no CSRF — Safaricom posts here)
Route::post('/mpesa/callback', [MpesaCallbackController::class, 'handle'])
    ->name('mpesa.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ─── Auth ────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',           [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',          [AuthController::class, 'login']);
    Route::get('/register',        [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',       [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Customer ────────────────────────────────────────────────
Route::middleware(['auth'])->name('customer.')->group(function () {
    Route::get('/my-returns',                  [CustomerReturnOrderController::class, 'index'])  ->name('return-orders.index');
    Route::get('/orders/{order}/return',       [CustomerReturnOrderController::class, 'create']) ->name('return-orders.create');
    Route::post('/my-returns',                 [CustomerReturnOrderController::class, 'store'])  ->name('return-orders.store');
    Route::get('/my-returns/{returnOrder}',    [CustomerReturnOrderController::class, 'show'])   ->name('return-orders.show');
});

// ─── Admin ───────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Products ─────────────────────────────────────────────
    Route::get('/products',                    [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',             [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products',                   [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit',     [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}',          [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',       [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{product}/toggle', [AdminProductController::class, 'toggleStatus'])->name('products.toggle');

    // ─── Orders ───────────────────────────────────────────────
    Route::get('/orders',                  [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',          [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    // ─── Return Orders ────────────────────────────────────────
    Route::get('/return-orders',                          [AdminReturnOrderController::class, 'index'])       ->name('return-orders.index');
    Route::post('/return-orders',                         [AdminReturnOrderController::class, 'store'])       ->name('return-orders.store');
    Route::get('/return-orders/{returnOrder}',            [AdminReturnOrderController::class, 'show'])        ->name('return-orders.show');
    Route::patch('/return-orders/{returnOrder}/status',   [AdminReturnOrderController::class, 'updateStatus'])->name('return-orders.update-status');
    Route::delete('/return-orders/{returnOrder}',         [AdminReturnOrderController::class, 'destroy'])     ->name('return-orders.destroy');

    // ─── Categories ───────────────────────────────────────────
    Route::get('/categories',                 [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create',          [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories',                [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}',      [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',   [CategoryController::class, 'destroy'])->name('categories.destroy');

    // ─── Users ────────────────────────────────────────────────
    Route::get('/users/administrators',        [UserController::class, 'administrators'])->name('users.administrators');
    Route::get('/users/managers',              [UserController::class, 'managers'])      ->name('users.managers');
    Route::get('/users/pos-operators',         [UserController::class, 'posOperators'])  ->name('users.pos-operators');
    Route::get('/users/delivery',              [UserController::class, 'delivery'])      ->name('users.delivery');
    Route::get('/users/create',                [UserController::class, 'create'])        ->name('users.create');
    Route::post('/users',                      [UserController::class, 'store'])         ->name('users.store');
    Route::get('/users/{user}/edit',           [UserController::class, 'edit'])          ->name('users.edit');
    Route::put('/users/{user}',                [UserController::class, 'update'])        ->name('users.update');
    Route::patch('/users/{user}/toggle',       [UserController::class, 'toggleStatus'])  ->name('users.toggle');
    Route::delete('/users/{user}',             [UserController::class, 'destroy'])       ->name('users.destroy');
    Route::get('/users',                       [UserController::class, 'index'])         ->name('users.index');

    // ─── Profile ──────────────────────────────────────────────
    Route::get('/profile/edit',     [ProfileController::class, 'edit'])          ->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])        ->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])      ->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/activity', [ProfileController::class, 'activity'])      ->name('profile.activity');

    // ─── Settings ─────────────────────────────────────────────
    Route::get('/settings',                      [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings',                     [SettingsController::class, 'update'])->name('settings.update');
    Route::patch('/settings/gateways/{gateway}', [SettingsController::class, 'updateGateway'])->name('settings.gateway');

    // ─── Coupons ──────────────────────────────────────────────
    Route::get('/coupons/generate',          [CouponController::class, 'generate'])->name('coupons.generate');
    Route::get('/coupons',                   [CouponController::class, 'index'])   ->name('coupons.index');
    Route::post('/coupons',                  [CouponController::class, 'store'])   ->name('coupons.store');
    Route::put('/coupons/{coupon}',          [CouponController::class, 'update'])  ->name('coupons.update');
    Route::patch('/coupons/{coupon}/toggle', [CouponController::class, 'toggle'])  ->name('coupons.toggle');
    Route::delete('/coupons/{coupon}',       [CouponController::class, 'destroy']) ->name('coupons.destroy');

    // ─── Promotions ───────────────────────────────────────────
    Route::get('/promotions',                      [PromotionController::class, 'index'])  ->name('promotions.index');
    Route::post('/promotions',                     [PromotionController::class, 'store'])  ->name('promotions.store');
    Route::put('/promotions/{promotion}',          [PromotionController::class, 'update']) ->name('promotions.update');
    Route::patch('/promotions/{promotion}/toggle', [PromotionController::class, 'toggle']) ->name('promotions.toggle');
    Route::delete('/promotions/{promotion}',       [PromotionController::class, 'destroy'])->name('promotions.destroy');

    // ─── Transactions ─────────────────────────────────────────
    Route::get('/transactions/export',                 [TransactionController::class, 'export'])      ->name('transactions.export');
    Route::get('/transactions',                        [TransactionController::class, 'index'])       ->name('transactions.index');
    Route::get('/transactions/{transaction}',          [TransactionController::class, 'show'])        ->name('transactions.show');
    Route::patch('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');

    // ─── Reports ──────────────────────────────────────────────
    Route::get('/reports/sales',        [SalesReportController::class, 'index'])  ->name('reports.sales');
    Route::get('/reports/sales/export', [SalesReportController::class, 'export']) ->name('reports.sales.export');

    // ─── POS ──────────────────────────────────────────────────
    Route::get('/pos',                 [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/sale',           [PosController::class, 'processSale'])->name('pos.sale');
    Route::get('/pos/orders',          [PosController::class, 'orders'])->name('pos.orders');
    Route::get('/pos/receipt/{order}', [PosController::class, 'receipt'])->name('pos.receipt');
    Route::get('/pos/products/search', [PosController::class, 'searchProducts'])->name('pos.products.search');
    Route::get('/pos/customer/lookup', [PosController::class, 'lookupCustomer'])->name('pos.customer.lookup');

    // ─── Purchases ────────────────────────────────────────────
    Route::get('/purchases',              [PurchaseController::class, 'index'])      ->name('purchase.index');
    Route::get('/purchases/create',       [PurchaseController::class, 'create'])     ->name('purchase.create');
    Route::post('/purchases',             [PurchaseController::class, 'store'])      ->name('purchase.store');
    Route::get('/purchases/{id}',         [PurchaseController::class, 'show'])       ->name('purchase.show');
    Route::get('/purchases/{id}/edit',    [PurchaseController::class, 'edit'])       ->name('purchase.edit');
    Route::put('/purchases/{id}',         [PurchaseController::class, 'update'])     ->name('purchase.update');
    Route::delete('/purchases/{id}',      [PurchaseController::class, 'destroy'])    ->name('purchase.destroy');
    Route::get('/purchases/{id}/return',  [PurchaseController::class, 'returnForm']) ->name('purchase.return.form');
    Route::post('/purchases/{id}/return', [PurchaseController::class, 'returnStore'])->name('purchase.return.store');

    // ─── Suppliers ────────────────────────────────────────────
    Route::get('/suppliers',               [SupplierController::class, 'index'])  ->name('supplier.index');
    Route::get('/suppliers/create',        [SupplierController::class, 'create']) ->name('supplier.create');
    Route::post('/suppliers',              [SupplierController::class, 'store'])  ->name('supplier.store');
    Route::get('/suppliers/{id}/edit',     [SupplierController::class, 'edit'])   ->name('supplier.edit');
    Route::put('/suppliers/{id}',          [SupplierController::class, 'update']) ->name('supplier.update');
    Route::delete('/suppliers/{id}',       [SupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::patch('/suppliers/{id}/toggle', [SupplierController::class, 'toggle']) ->name('supplier.toggle');

    // ─── Stock ────────────────────────────────────────────────
    Route::get('/stock',                   [StockController::class, 'index'])    ->name('stock.index');
    Route::get('/stock/history',           [StockController::class, 'history'])  ->name('stock.history');
    Route::get('/stock/low-stock',         [StockController::class, 'lowStock']) ->name('stock.low');
    Route::get('/stock/damaged',           [StockController::class, 'damaged'])  ->name('stock.damaged');
    Route::get('/stock/{product}/adjust',  [StockController::class, 'adjust'])   ->name('stock.adjust');
    Route::post('/stock/{product}/adjust', [StockController::class, 'store'])    ->name('stock.store');
    Route::post('/stock/{product}/alert',  [StockController::class, 'setAlert']) ->name('stock.alert');

    // ─── Employees ────────────────────────────────────────────
    Route::get('/employees',                     [EmployeeController::class, 'index'])  ->name('employees.index');
    Route::get('/employees/create',              [EmployeeController::class, 'create']) ->name('employees.create');
    Route::post('/employees',                    [EmployeeController::class, 'store'])  ->name('employees.store');
    Route::get('/employees/{employee}',          [EmployeeController::class, 'show'])   ->name('employees.show');
    Route::get('/employees/{employee}/edit',     [EmployeeController::class, 'edit'])   ->name('employees.edit');
    Route::put('/employees/{employee}',          [EmployeeController::class, 'update']) ->name('employees.update');
    Route::delete('/employees/{employee}',       [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::patch('/employees/{employee}/toggle', [EmployeeController::class, 'toggle']) ->name('employees.toggle');

    // ─── Shifts ───────────────────────────────────────────────
    Route::get('/shifts',              [ShiftController::class, 'index'])  ->name('shifts.index');
    Route::get('/shifts/create',       [ShiftController::class, 'create']) ->name('shifts.create');
    Route::post('/shifts',             [ShiftController::class, 'store'])  ->name('shifts.store');
    Route::get('/shifts/{shift}/edit', [ShiftController::class, 'edit'])   ->name('shifts.edit');
    Route::put('/shifts/{shift}',      [ShiftController::class, 'update']) ->name('shifts.update');
    Route::delete('/shifts/{shift}',   [ShiftController::class, 'destroy'])->name('shifts.destroy');

    // ─── Attendance ───────────────────────────────────────────
    Route::get('/attendance/terminal',              [AttendanceController::class, 'terminal'])  ->name('attendance.terminal');
    Route::get('/attendance/today',                 [AttendanceController::class, 'today'])     ->name('attendance.today');
    Route::get('/attendance/report',                [AttendanceController::class, 'report'])    ->name('attendance.report');
    Route::get('/attendance/export',                [AttendanceController::class, 'export'])    ->name('attendance.export');
    Route::get('/attendance/pin/lookup',            [AttendanceController::class, 'pinLookup']) ->name('attendance.pin.lookup');
    Route::post('/attendance/clock-in',             [AttendanceController::class, 'clockIn'])   ->name('attendance.clock-in');
    Route::post('/attendance/clock-out',            [AttendanceController::class, 'clockOut'])  ->name('attendance.clock-out');
    Route::post('/attendance/manual',               [AttendanceController::class, 'manual'])    ->name('attendance.manual');
    Route::put('/attendance/{attendance}/override', [AttendanceController::class, 'override'])  ->name('attendance.override');
    Route::delete('/attendance/{attendance}',       [AttendanceController::class, 'destroy'])   ->name('attendance.destroy');
    Route::get('/attendance',                       [AttendanceController::class, 'index'])     ->name('attendance.index');
    Route::get('/attendance/{employee}',            [AttendanceController::class, 'show'])      ->name('attendance.show');

});