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
        $stats = [
            'total_orders'    => Order::count(),
            'total_revenue'   => Order::where('payment_status', 'paid')->sum('total'),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_products'  => Product::where('is_active', true)->count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'low_stock'       => Product::where('track_stock', true)->where('stock_quantity', '<=', 5)->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();

        $monthlySales = Order::where('payment_status', 'paid')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $recentMpesa = MpesaTransaction::with('order')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentOrders', 'monthlySales', 'recentMpesa'));
    }
}
