<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\StockAdjustment;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsReportController extends Controller
{
    public function index(Request $request)
    {
        $period   = $request->get('period', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        [$from, $to] = $this->resolveDates($period, $dateFrom, $dateTo);

        // ── Overview stats ────────────────────────────────────────
        $stats = [
            'total_products'   => Product::count(),
            'active_products'  => Product::where('is_active', 1)->count(),
            'out_of_stock'     => Product::where('track_stock', 1)->where('stock_quantity', 0)->count(),
            'low_stock'        => Product::where('track_stock', 1)->whereBetween('stock_quantity', [1, 10])->count(),
            'total_stock_value'=> Product::where('is_active', 1)
                                    ->selectRaw('SUM(stock_quantity * COALESCE(sale_price, price)) as val')
                                    ->value('val') ?? 0,
            'featured'         => Product::where('is_featured', 1)->count(),
            'new_arrivals'     => Product::where('is_new_arrival', 1)->count(),
            'best_sellers'     => Product::where('is_best_seller', 1)->count(),
        ];

        // ── Top selling products (by revenue in period) ───────────
        $topSelling = OrderItem::select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as units_sold'),
                DB::raw('SUM(subtotal) as revenue')
            )
            ->whereHas('order', fn($q) =>
                $q->whereBetween('created_at', [$from, $to])
                  ->where('payment_status', 'paid')
            )
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // ── Revenue by category ───────────────────────────────────
        $revenueByCategory = OrderItem::select(
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->join('products',   'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id',   '=', 'categories.id')
            ->whereHas('order', fn($q) =>
                $q->whereBetween('created_at', [$from, $to])
                  ->where('payment_status', 'paid')
            )
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();

        // ── Stock value by category ───────────────────────────────
        $stockByCategory = Product::select(
                'categories.name as category_name',
                DB::raw('COUNT(products.id) as product_count'),
                DB::raw('SUM(products.stock_quantity) as total_units'),
                DB::raw('SUM(products.stock_quantity * COALESCE(products.sale_price, products.price)) as stock_value')
            )
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.is_active', 1)
            ->where('products.track_stock', 1)
            ->groupBy('categories.name')
            ->orderByDesc('stock_value')
            ->get();

        // ── Low stock products ────────────────────────────────────
        $lowStock = Product::with('category')
            ->where('track_stock', 1)
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity')
            ->limit(15)
            ->get();

        // ── Out of stock ──────────────────────────────────────────
        $outOfStock = Product::with('category')
            ->where('track_stock', 1)
            ->where('stock_quantity', 0)
            ->where('is_active', 1)
            ->orderBy('name')
            ->limit(15)
            ->get();

        // ── Stock movement in period ──────────────────────────────
        $stockMovement = StockAdjustment::select(
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(quantity) as total_qty')
            )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('type')
            ->orderByDesc('total_qty')
            ->get();

        // ── Damaged / expired in period ───────────────────────────
        $damaged = StockAdjustment::select(
                'products.name as product_name',
                'stock_adjustments.type',
                DB::raw('SUM(stock_adjustments.quantity) as total_qty')
            )
            ->join('products', 'stock_adjustments.product_id', '=', 'products.id')
            ->whereIn('stock_adjustments.type', ['damaged', 'expired'])
            ->whereBetween('stock_adjustments.created_at', [$from, $to])
            ->groupBy('products.name', 'stock_adjustments.type')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // ── Product performance (reviews + rating) ────────────────
        $productPerformance = Product::select(
                'products.id',
                'products.name',
                'products.price',
                'products.sale_price',
                'products.stock_quantity',
                'products.is_active',
                DB::raw('COUNT(pr.id) as review_count'),
                DB::raw('AVG(pr.rating) as avg_rating'),
                DB::raw('SUM(oi.quantity) as units_sold'),
                DB::raw('SUM(oi.subtotal) as revenue')
            )
            ->leftJoin('product_reviews as pr', fn($j) =>
                $j->on('pr.product_id', '=', 'products.id')
                  ->where('pr.is_approved', 1)
            )
            ->leftJoin('order_items as oi', 'oi.product_id', '=', 'products.id')
            ->where('products.is_active', 1)
            ->groupBy(
                'products.id', 'products.name', 'products.price',
                'products.sale_price', 'products.stock_quantity', 'products.is_active'
            )
            ->orderByDesc('revenue')
            ->limit(20)
            ->get();

        // ── Chart: stock movement over time ───────────────────────
        $chartData = $this->getChartData($period, $from, $to);

        return view('admin.reports.products', compact(
            'stats', 'topSelling', 'revenueByCategory', 'stockByCategory',
            'lowStock', 'outOfStock', 'stockMovement', 'damaged',
            'productPerformance', 'chartData',
            'period', 'from', 'to'
        ));
    }

    // ── Helpers ───────────────────────────────────────────────────

    private function resolveDates(string $period, ?string $dateFrom, ?string $dateTo): array
    {
        if ($period === 'custom' && $dateFrom && $dateTo) {
            return [
                \Carbon\Carbon::parse($dateFrom)->startOfDay(),
                \Carbon\Carbon::parse($dateTo)->endOfDay(),
            ];
        }

        return match($period) {
            'daily'   => [now()->startOfDay(),   now()->endOfDay()],
            'weekly'  => [now()->startOfWeek(),  now()->endOfWeek()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            default   => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    private function getChartData(string $period, $from, $to): array
    {
        $groupFormat = match($period) {
            'daily'  => '%H:00',
            'weekly' => '%a',
            default  => '%d %b',
        };

        $rows = StockAdjustment::select(
                DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as label"),
                DB::raw("SUM(CASE WHEN direction='in'  THEN quantity ELSE 0 END) as stock_in"),
                DB::raw("SUM(CASE WHEN direction='out' THEN quantity ELSE 0 END) as stock_out")
            )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('label')
            ->orderBy('created_at')
            ->get();

        return [
            'labels'    => $rows->pluck('label')->toArray(),
            'stock_in'  => $rows->pluck('stock_in')->map(fn($v) => (int)$v)->toArray(),
            'stock_out' => $rows->pluck('stock_out')->map(fn($v) => (int)$v)->toArray(),
        ];
    }
}