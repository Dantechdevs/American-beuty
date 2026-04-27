<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MpesaCallbackController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\AppointmentController;
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
use App\Http\Controllers\Admin\ProductsReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\RoleController;

use Illuminate\Support\Facades\Route;

// ─── Frontend ────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products',        [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart',         [CartController::class, 'index'])->name('cart');
Route::post('/cart/add',    [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}',  [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count',   [CartController::class, 'count'])->name('cart.count');

// ─── Booking (public) ────────────────────────────────────────
Route::get('/book',                       [AppointmentController::class, 'index'])  ->name('book.index');
Route::post('/book',                      [AppointmentController::class, 'store'])  ->name('book.store');
Route::get('/book/success/{appointment}', [AppointmentController::class, 'success'])->name('book.success');

// Newsletter (public)
Route::post('/subscribe', [SubscriberController::class, 'publicSubscribe'])->name('subscribers.subscribe');

// Checkout (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/checkout',                    [CheckoutController::class, 'index'])      ->name('checkout');
    Route::post('/checkout/coupon',            [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon.apply');
    Route::delete('/checkout/coupon',          [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
    Route::post('/checkout/place-order',       [CheckoutController::class, 'placeOrder']) ->name('checkout.place-order');
    Route::get('/checkout/mpesa/wait/{order}', [CheckoutController::class, 'mpesaWait'])  ->name('checkout.mpesa.wait');
    Route::get('/checkout/mpesa/status',       [CheckoutController::class, 'mpesaStatus'])->name('checkout.mpesa.status');
    Route::get('/order/success/{orderNumber}', [CheckoutController::class, 'success'])    ->name('order.success');
});

// M-PESA Callback (no CSRF)
Route::post('/mpesa/callback', [MpesaCallbackController::class, 'handle'])
    ->name('mpesa.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ─── Auth ────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',           [AuthController::class, 'showLogin'])        ->name('login');
    Route::post('/login',          [AuthController::class, 'login']);
    Route::get('/register',        [AuthController::class, 'showRegister'])     ->name('register');
    Route::post('/register',       [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Customer ────────────────────────────────────────────────
Route::middleware(['auth'])->name('customer.')->group(function () {
    Route::get('/my-returns',               [CustomerReturnOrderController::class, 'index']) ->name('return-orders.index');
    Route::get('/orders/{order}/return',    [CustomerReturnOrderController::class, 'create'])->name('return-orders.create');
    Route::post('/my-returns',              [CustomerReturnOrderController::class, 'store']) ->name('return-orders.store');
    Route::get('/my-returns/{returnOrder}', [CustomerReturnOrderController::class, 'show'])  ->name('return-orders.show');
});

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // ── Dashboard ─────────────────────────────────────────────
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Profile (all admin users — no permission needed) ──────
    Route::get('/profile/edit',     [ProfileController::class, 'edit'])          ->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])        ->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])      ->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/activity', [ProfileController::class, 'activity'])      ->name('profile.activity');

    // ── Notifications (read — all admin users, no permission) ─
    Route::get('/notifications/unread-count',         [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent',               [NotificationController::class, 'recent'])     ->name('notifications.recent');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])   ->name('notifications.mark-read');
    Route::post('/notifications/read-all',            [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // ── Notifications (send/manage) ───────────────────────────
    Route::middleware('permission:notifications.manage')->group(function () {
        Route::get('/notifications',                                [NotificationController::class, 'index'])           ->name('notifications.index');
        Route::post('/notifications/send',                          [NotificationController::class, 'sendNow'])         ->name('notifications.send');
        Route::post('/notifications/schedule',                      [NotificationController::class, 'schedule'])        ->name('notifications.schedule');
        Route::patch('/notifications/scheduled/{scheduled}/cancel', [NotificationController::class, 'cancelScheduled']) ->name('notifications.scheduled.cancel');
        Route::delete('/notifications/scheduled/{scheduled}',       [NotificationController::class, 'destroyScheduled'])->name('notifications.scheduled.destroy');
    });

    // ── Products ──────────────────────────────────────────────
    Route::middleware('permission:products.view')->group(function () {
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    });
    Route::middleware('permission:products.create')->group(function () {
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products',       [AdminProductController::class, 'store']) ->name('products.store');
    });
    Route::middleware('permission:products.edit')->group(function () {
        Route::get('/products/{product}/edit',     [AdminProductController::class, 'edit'])        ->name('products.edit');
        Route::put('/products/{product}',          [AdminProductController::class, 'update'])      ->name('products.update');
        Route::patch('/products/{product}/toggle', [AdminProductController::class, 'toggleStatus'])->name('products.toggle');
    });
    Route::middleware('permission:products.delete')->group(function () {
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    });

    // ── Orders ────────────────────────────────────────────────
    Route::middleware('permission:orders.view')->group(function () {
        Route::get('/orders',         [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show']) ->name('orders.show');
    });
    Route::middleware('permission:orders.manage')->group(function () {
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    });

    // ── Return Orders ─────────────────────────────────────────
    Route::middleware('permission:orders.view')->group(function () {
        Route::get('/return-orders',               [AdminReturnOrderController::class, 'index'])->name('return-orders.index');
        Route::get('/return-orders/{returnOrder}', [AdminReturnOrderController::class, 'show']) ->name('return-orders.show');
    });
    Route::middleware('permission:orders.manage')->group(function () {
        Route::post('/return-orders',                       [AdminReturnOrderController::class, 'store'])        ->name('return-orders.store');
        Route::patch('/return-orders/{returnOrder}/status', [AdminReturnOrderController::class, 'updateStatus']) ->name('return-orders.update-status');
        Route::delete('/return-orders/{returnOrder}',       [AdminReturnOrderController::class, 'destroy'])      ->name('return-orders.destroy');
    });

    // ── Categories ────────────────────────────────────────────
    Route::middleware('permission:categories.view')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    });
    Route::middleware('permission:categories.create')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories',       [CategoryController::class, 'store'])  ->name('categories.store');
    });
    Route::middleware('permission:categories.edit')->group(function () {
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])  ->name('categories.edit');
        Route::put('/categories/{category}',      [CategoryController::class, 'update'])->name('categories.update');
    });
    Route::middleware('permission:categories.delete')->group(function () {
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // ── Users ─────────────────────────────────────────────────
    Route::middleware('permission:users.view')->group(function () {
        Route::get('/users',                [UserController::class, 'index'])         ->name('users.index');
        Route::get('/users/administrators', [UserController::class, 'administrators'])->name('users.administrators');
        Route::get('/users/managers',       [UserController::class, 'managers'])      ->name('users.managers');
        Route::get('/users/pos-operators',  [UserController::class, 'posOperators'])  ->name('users.pos-operators');
        Route::get('/users/delivery',       [UserController::class, 'delivery'])      ->name('users.delivery');
    });
    Route::middleware('permission:users.create')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users',       [UserController::class, 'store']) ->name('users.store');
    });
    Route::middleware('permission:users.edit')->group(function () {
        Route::get('/users/{user}/edit',     [UserController::class, 'edit'])        ->name('users.edit');
        Route::put('/users/{user}',          [UserController::class, 'update'])      ->name('users.update');
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    });
    Route::middleware('permission:users.delete')->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // ── Roles & Permissions ───────────────────────────────────
    Route::middleware('permission:roles.manage')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::post('users/{user}/roles', [RoleController::class, 'assignToUser'])->name('users.roles.assign');
    });

    // ── Settings ──────────────────────────────────────────────
    Route::middleware('permission:settings.view')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    });
    Route::middleware('permission:settings.edit')->group(function () {
        Route::post('/settings',                           [SettingsController::class, 'update'])              ->name('settings.update');
        Route::patch('/settings/roles/{role}/permissions', [SettingsController::class, 'updateRolePermissions'])->name('settings.role-permissions');
        Route::patch('/settings/users/{user}/roles',       [SettingsController::class, 'updateUserRoles'])     ->name('settings.user-roles');
    });
    Route::middleware('permission:settings.payment')->group(function () {
        Route::patch('/settings/gateways/{gateway}', [SettingsController::class, 'updateGateway'])->name('settings.gateway');
    });

    // ── Coupons ───────────────────────────────────────────────
    Route::middleware('permission:coupons.view')->group(function () {
        Route::get('/coupons',          [CouponController::class, 'index'])   ->name('coupons.index');
        Route::get('/coupons/generate', [CouponController::class, 'generate'])->name('coupons.generate');
    });
    Route::middleware('permission:coupons.manage')->group(function () {
        Route::post('/coupons',                  [CouponController::class, 'store'])  ->name('coupons.store');
        Route::put('/coupons/{coupon}',          [CouponController::class, 'update']) ->name('coupons.update');
        Route::patch('/coupons/{coupon}/toggle', [CouponController::class, 'toggle']) ->name('coupons.toggle');
        Route::delete('/coupons/{coupon}',       [CouponController::class, 'destroy'])->name('coupons.destroy');
    });

    // ── Promotions ────────────────────────────────────────────
    Route::middleware('permission:promotions.view')->group(function () {
        Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    });
    Route::middleware('permission:promotions.manage')->group(function () {
        Route::post('/promotions',                     [PromotionController::class, 'store'])  ->name('promotions.store');
        Route::put('/promotions/{promotion}',          [PromotionController::class, 'update']) ->name('promotions.update');
        Route::patch('/promotions/{promotion}/toggle', [PromotionController::class, 'toggle']) ->name('promotions.toggle');
        Route::delete('/promotions/{promotion}',       [PromotionController::class, 'destroy'])->name('promotions.destroy');
    });

    // ── Transactions ──────────────────────────────────────────
    Route::middleware('permission:transactions.view')->group(function () {
        Route::get('/transactions',               [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show']) ->name('transactions.show');
    });
    Route::middleware('permission:transactions.export')->group(function () {
        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    });
    Route::middleware('permission:transactions.manage')->group(function () {
        Route::patch('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    });

    // ── POS ───────────────────────────────────────────────────
    Route::middleware('permission:pos.access')->group(function () {
        Route::get('/pos',                 [PosController::class, 'index'])         ->name('pos.index');
        Route::post('/pos/sale',           [PosController::class, 'processSale'])   ->name('pos.sale');
        Route::get('/pos/orders',          [PosController::class, 'orders'])        ->name('pos.orders');
        Route::get('/pos/receipt/{order}', [PosController::class, 'receipt'])       ->name('pos.receipt');
        Route::get('/pos/products/search', [PosController::class, 'searchProducts'])->name('pos.products.search');
        Route::get('/pos/customer/lookup', [PosController::class, 'lookupCustomer'])->name('pos.customer.lookup');
    });

    // ── Purchases ─────────────────────────────────────────────
    Route::middleware('permission:purchases.view')->group(function () {
        Route::get('/purchases',      [PurchaseController::class, 'index'])->name('purchase.index');
        Route::get('/purchases/{id}', [PurchaseController::class, 'show']) ->name('purchase.show');
    });
    Route::middleware('permission:purchases.create')->group(function () {
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchase.create');
        Route::post('/purchases',       [PurchaseController::class, 'store']) ->name('purchase.store');
    });
    Route::middleware('permission:purchases.edit')->group(function () {
        Route::get('/purchases/{id}/edit', [PurchaseController::class, 'edit'])  ->name('purchase.edit');
        Route::put('/purchases/{id}',      [PurchaseController::class, 'update'])->name('purchase.update');
    });
    Route::middleware('permission:purchases.delete')->group(function () {
        Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->name('purchase.destroy');
    });
    Route::middleware('permission:purchases.return')->group(function () {
        Route::get('/purchases/{id}/return',  [PurchaseController::class, 'returnForm']) ->name('purchase.return.form');
        Route::post('/purchases/{id}/return', [PurchaseController::class, 'returnStore'])->name('purchase.return.store');
    });

    // ── Suppliers ─────────────────────────────────────────────
    Route::middleware('permission:suppliers.view')->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('supplier.index');
    });
    Route::middleware('permission:suppliers.create')->group(function () {
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/suppliers',       [SupplierController::class, 'store'])  ->name('supplier.store');
    });
    Route::middleware('permission:suppliers.edit')->group(function () {
        Route::get('/suppliers/{id}/edit',     [SupplierController::class, 'edit'])  ->name('supplier.edit');
        Route::put('/suppliers/{id}',          [SupplierController::class, 'update'])->name('supplier.update');
        Route::patch('/suppliers/{id}/toggle', [SupplierController::class, 'toggle'])->name('supplier.toggle');
    });
    Route::middleware('permission:suppliers.delete')->group(function () {
        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    });

    // ── Stock ─────────────────────────────────────────────────
    Route::middleware('permission:stock.view')->group(function () {
        Route::get('/stock',           [StockController::class, 'index'])   ->name('stock.index');
        Route::get('/stock/history',   [StockController::class, 'history']) ->name('stock.history');
        Route::get('/stock/low-stock', [StockController::class, 'lowStock'])->name('stock.low');
        Route::get('/stock/damaged',   [StockController::class, 'damaged']) ->name('stock.damaged');
    });
    Route::middleware('permission:stock.adjust')->group(function () {
        Route::get('/stock/{product}/adjust',  [StockController::class, 'adjust'])   ->name('stock.adjust');
        Route::post('/stock/{product}/adjust', [StockController::class, 'store'])    ->name('stock.store');
        Route::post('/stock/{product}/alert',  [StockController::class, 'setAlert']) ->name('stock.alert');
    });

    // ── Employees ─────────────────────────────────────────────
    Route::middleware('permission:employees.view')->group(function () {
        Route::get('/employees',            [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/{employee}', [EmployeeController::class, 'show']) ->name('employees.show');
    });
    Route::middleware('permission:employees.create')->group(function () {
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees',       [EmployeeController::class, 'store'])  ->name('employees.store');
    });
    Route::middleware('permission:employees.edit')->group(function () {
        Route::get('/employees/{employee}/edit',            [EmployeeController::class, 'edit'])          ->name('employees.edit');
        Route::put('/employees/{employee}',                 [EmployeeController::class, 'update'])        ->name('employees.update');
        Route::patch('/employees/{employee}/toggle',        [EmployeeController::class, 'toggle'])        ->name('employees.toggle');
        Route::post('/employees/{employee}/assign-user',    [EmployeeController::class, 'assignUser'])    ->name('employees.assign-user');
        Route::post('/employees/{employee}/unlink-user',    [EmployeeController::class, 'unlinkUser'])    ->name('employees.unlink-user');
        Route::post('/employees/{employee}/create-account', [EmployeeController::class, 'createAccount']) ->name('employees.create-account');
    });
    Route::middleware('permission:employees.delete')->group(function () {
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // ── Shifts ────────────────────────────────────────────────
    Route::middleware('permission:shifts.view')->group(function () {
        Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    });
    Route::middleware('permission:shifts.manage')->group(function () {
        Route::get('/shifts/create',       [ShiftController::class, 'create']) ->name('shifts.create');
        Route::post('/shifts',             [ShiftController::class, 'store'])  ->name('shifts.store');
        Route::get('/shifts/{shift}/edit', [ShiftController::class, 'edit'])   ->name('shifts.edit');
        Route::put('/shifts/{shift}',      [ShiftController::class, 'update']) ->name('shifts.update');
        Route::delete('/shifts/{shift}',   [ShiftController::class, 'destroy'])->name('shifts.destroy');
    });

    // ── Attendance ────────────────────────────────────────────
    Route::middleware('permission:attendance.view')->group(function () {
        Route::get('/attendance',            [AttendanceController::class, 'index'])  ->name('attendance.index');
        Route::get('/attendance/today',      [AttendanceController::class, 'today'])  ->name('attendance.today');
        Route::get('/attendance/report',     [AttendanceController::class, 'report']) ->name('attendance.report');
        Route::get('/attendance/{employee}', [AttendanceController::class, 'show'])   ->name('attendance.show');
    });
    Route::middleware('permission:attendance.terminal')->group(function () {
        Route::get('/attendance/terminal',   [AttendanceController::class, 'terminal']) ->name('attendance.terminal');
        Route::get('/attendance/pin/lookup', [AttendanceController::class, 'pinLookup'])->name('attendance.pin.lookup');
        Route::post('/attendance/clock-in',  [AttendanceController::class, 'clockIn'])  ->name('attendance.clock-in');
        Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut']) ->name('attendance.clock-out');
    });
    Route::middleware('permission:attendance.manage')->group(function () {
        Route::get('/attendance/export',                [AttendanceController::class, 'export'])  ->name('attendance.export');
        Route::post('/attendance/manual',               [AttendanceController::class, 'manual'])  ->name('attendance.manual');
        Route::put('/attendance/{attendance}/override', [AttendanceController::class, 'override'])->name('attendance.override');
        Route::delete('/attendance/{attendance}',       [AttendanceController::class, 'destroy']) ->name('attendance.destroy');
    });

    // ── Reports ───────────────────────────────────────────────
    Route::middleware('permission:reports.sales')->group(function () {
        Route::get('/reports/sales',        [SalesReportController::class, 'index'])  ->name('reports.sales');
        Route::get('/reports/sales/export', [SalesReportController::class, 'export']) ->name('reports.sales.export');
    });
    Route::middleware('permission:reports.products')->group(function () {
        Route::get('/reports/products', [ProductsReportController::class, 'index'])->name('reports.products');
    });

    // ── Logs ──────────────────────────────────────────────────
    Route::middleware('permission:logs.view')->group(function () {
        Route::get('/logs/mpesa',         [LogController::class, 'mpesa'])       ->name('logs.mpesa');
        Route::get('/logs/customers',     [LogController::class, 'customers'])   ->name('logs.customers');
        Route::get('/logs/managers',      [LogController::class, 'managers'])    ->name('logs.managers');
        Route::get('/logs/pos-operators', [LogController::class, 'posOperators'])->name('logs.pos-operators');
    });

    // ── Subscribers ───────────────────────────────────────────
    Route::middleware('permission:subscribers.view')->group(function () {
        Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    });
    Route::middleware('permission:subscribers.manage')->group(function () {
        Route::get('/subscribers/export',          [SubscriberController::class, 'export'])      ->name('subscribers.export');
        Route::post('/subscribers/send-message',   [SubscriberController::class, 'sendMessage']) ->name('subscribers.send-message');
        Route::get('/subscribers/create',          [SubscriberController::class, 'create'])      ->name('subscribers.create');
        Route::post('/subscribers',                [SubscriberController::class, 'store'])       ->name('subscribers.store');
        Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])     ->name('subscribers.destroy');
    });

    // ── Bookings & Appointments ───────────────────────────────
    Route::middleware('permission:appointments.view')->group(function () {
        Route::get('/bookings',                   [BookingController::class, 'index'])         ->name('bookings.index');
        Route::get('/appointments',               [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show']) ->name('appointments.show');
    });
    Route::middleware('permission:appointments.manage')->group(function () {
        Route::patch('/appointments/{appointment}/status',  [AdminAppointmentController::class, 'status'])  ->name('appointments.status');
        Route::patch('/appointments/{appointment}/payment', [AdminAppointmentController::class, 'payment']) ->name('appointments.payment');
        Route::delete('/appointments/{appointment}',        [AdminAppointmentController::class, 'destroy']) ->name('appointments.destroy');
    });

});