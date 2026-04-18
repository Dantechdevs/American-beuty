<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\MpesaTransaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Core Stats (your original columns preserved) ─────────
        $stats = [
            'total_orders'     => Order::count(),
            'total_revenue'    => Order::where('payment_status', 'paid')->sum('total'),
            'total_customers'  => User::where('role', 'customer')->count(),
            'total_products'   => Product::where('is_active', true)->count(),
            'pending_orders'   => Order::where('status', 'pending')->count(),
            'low_stock'        => Product::where('track_stock', true)
                                         ->where('stock_quantity', '<=', 5)
                                         ->count(),

            // ── POS Stats (today) ────────────────────────────────
            'pos_revenue'      => Order::where('source', 'pos')
                                       ->whereDate('created_at', today())
                                       ->where('payment_status', 'paid')
                                       ->sum('total'),
            'pos_orders_today' => Order::where('source', 'pos')
                                       ->whereDate('created_at', today())
                                       ->count(),
        ];

        // ── Recent Orders ────────────────────────────────────────
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // ── Monthly Sales Chart (your original) ──────────────────
        $monthlySales = Order::where('payment_status', 'paid')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Recent M-PESA ────────────────────────────────────────
        $recentMpesa = MpesaTransaction::with('order')->latest()->take(5)->get();

        // ── Today's POS Orders ───────────────────────────────────
        $recentPosOrders = Order::where('source', 'pos')
                                ->whereDate('created_at', today())
                                ->with('user')
                                ->latest()
                                ->take(5)
                                ->get();

        // ── Low Stock Products (your original columns) ────────────
        $lowStockProducts = Product::where('track_stock', true)
                                   ->where('stock_quantity', '<=', 5)
                                   ->where('stock_quantity', '>', 0)
                                   ->where('is_active', true)
                                   ->orderBy('stock_quantity')
                                   ->take(5)
                                   ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentOrders',
            'monthlySales',
            'recentMpesa',
            'recentPosOrders',
            'lowStockProducts'
        ));
    }
}