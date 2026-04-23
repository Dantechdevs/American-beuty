<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // ── Date range ────────────────────────────────────────────
        $period   = $request->get('period', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        [$from, $to] = $this->resolveDates($period, $dateFrom, $dateTo);

        // ── Base query scope ──────────────────────────────────────
        $ordersInRange = Order::whereBetween('created_at', [$from, $to]);
        $paidInRange   = Order::whereBetween('created_at', [$from, $to])
                               ->where('payment_status', 'paid');

        // ── Stats ─────────────────────────────────────────────────
        $stats = [
            'total_revenue'  => (clone $paidInRange)->sum('total'),
            'total_orders'   => (clone $ordersInRange)->count(),
            'paid_orders'    => (clone $paidInRange)->count(),
            'avg_order'      => (clone $paidInRange)->avg('total') ?? 0,
            'total_discount' => (clone $ordersInRange)->sum('discount'),
            'online_revenue' => (clone $paidInRange)->where('source', 'online')->sum('total'),
            'pos_revenue'    => (clone $paidInRange)->where('source', 'pos')->sum('total'),
        ];

        // ── Revenue chart data ────────────────────────────────────
        $chartData = $this->getChartData($period, $from, $to);

        // ── Order status breakdown ────────────────────────────────
        $statusBreakdown = (clone $ordersInRange)
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();

        // ── Payment method breakdown ──────────────────────────────
        $gatewayBreakdown = (clone $paidInRange)
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total) as revenue'))
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get();

        // ── Source breakdown (online vs POS) ──────────────────────
        $sourceBreakdown = (clone $ordersInRange)
            ->select('source', DB::raw('count(*) as count'), DB::raw('sum(total) as revenue'))
            ->groupBy('source')
            ->get();

        // ── Top products ──────────────────────────────────────────
        $topProducts = OrderItem::select(
                'product_id',
                'product_name',
                DB::raw('sum(quantity) as units_sold'),
                DB::raw('sum(subtotal) as revenue')
            )
            ->whereHas('order', fn($q) =>
                $q->whereBetween('created_at', [$from, $to])
                  ->where('payment_status', 'paid')
            )
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // ── Top categories ────────────────────────────────────────
        $topCategories = OrderItem::select(
                'products.category_id',
                'categories.name as category_name',
                DB::raw('sum(order_items.quantity) as units_sold'),
                DB::raw('sum(order_items.subtotal) as revenue')
            )
            ->join('products',   'order_items.product_id',  '=', 'products.id')
            ->join('categories', 'products.category_id',    '=', 'categories.id')
            ->whereHas('order', fn($q) =>
                $q->whereBetween('created_at', [$from, $to])
                  ->where('payment_status', 'paid')
            )
            ->groupBy('products.category_id', 'categories.name')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get();

        // ── Transaction summary ───────────────────────────────────
        $txnStats = [
            'success' => Transaction::whereBetween('created_at', [$from, $to])->where('status', 'success')->count(),
            'pending' => Transaction::whereBetween('created_at', [$from, $to])->where('status', 'pending')->count(),
            'failed'  => Transaction::whereBetween('created_at', [$from, $to])->where('status', 'failed')->count(),
        ];

        return view('admin.reports.sales', compact(
            'stats', 'chartData', 'statusBreakdown', 'gatewayBreakdown',
            'sourceBreakdown', 'topProducts', 'topCategories', 'txnStats',
            'period', 'from', 'to'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $period   = $request->get('period', 'monthly');
        [$from, $to] = $this->resolveDates($period, $request->date_from, $request->date_to);

        $orders = Order::with('items')
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Order #', 'Date', 'Customer', 'Email', 'Source',
                'Status', 'Payment Method', 'Payment Status',
                'Subtotal', 'Discount', 'Shipping', 'Tax', 'Total',
            ]);
            foreach ($orders as $o) {
                fputcsv($handle, [
                    $o->order_number,
                    $o->created_at->format('d M Y H:i'),
                    trim($o->first_name . ' ' . $o->last_name),
                    $o->email,
                    $o->source,
                    $o->status,
                    $o->payment_method ?? '—',
                    $o->payment_status,
                    $o->subtotal,
                    $o->discount,
                    $o->shipping,
                    $o->tax,
                    $o->total,
                ]);
            }
            fclose($handle);
        }, 'sales-report-' . now()->format('Y-m-d') . '.csv');
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

        $rows = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as label"),
                DB::raw('sum(total) as revenue'),
                DB::raw('count(*) as orders')
            )
            ->groupBy('label')
            ->orderBy('created_at')
            ->get();

        return [
            'labels'  => $rows->pluck('label')->toArray(),
            'revenue' => $rows->pluck('revenue')->map(fn($v) => (float)$v)->toArray(),
            'orders'  => $rows->pluck('orders')->toArray(),
        ];
    }
}