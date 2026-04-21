<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockAlert;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    // ── Stock List ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $products = Product::with(['category', 'stockAlert'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%')
            )
            ->when($request->stock_status === 'out', fn($q) =>
                $q->where('stock_quantity', '<=', 0)
            )
            ->when($request->stock_status === 'low', fn($q) =>
                $q->where('stock_quantity', '>', 0)
                  ->where('stock_quantity', '<=', 10)
            )
            ->when($request->stock_status === 'ok', fn($q) =>
                $q->where('stock_quantity', '>', 10)
            )
            ->when($request->category_id, fn($q) =>
                $q->where('category_id', $request->category_id)
            )
            ->orderBy('stock_quantity', 'asc')
            ->paginate(20);

        $stats = [
            'total_products' => Product::count(),
            'out_of_stock'   => Product::where('stock_quantity', '<=', 0)->count(),
            'low_stock'      => Product::where('stock_quantity', '>', 0)
                                       ->where('stock_quantity', '<=', 10)->count(),
            'total_units'    => Product::sum('stock_quantity'),
        ];

        $categories = Category::orderBy('name')->get();

        return view('admin.stock.index', compact('products', 'stats', 'categories'));
    }

    // ── Adjust Stock Form ──────────────────────────────────────
    public function adjust(Product $product)
    {
        $history = StockAdjustment::where('product_id', $product->id)
            ->with('createdBy')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.stock.adjust', compact('product', 'history'));
    }

    // ── Store Adjustment ───────────────────────────────────────
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'type'     => 'required|in:manual_add,manual_deduct,damaged,expired',
            'quantity' => 'required|integer|min:1',
            'note'     => 'nullable|string|max:500',
        ]);

        $direction = $request->type === 'manual_add' ? 'in' : 'out';

        StockService::adjust(
            $product->id,
            $request->type,
            $direction,
            $request->quantity,
            $request->note,
            null,
            Auth::id()
        );

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock updated for ' . $product->name . '.');
    }

    // ── Stock History / Audit Log ──────────────────────────────
    public function history(Request $request)
    {
        $adjustments = StockAdjustment::with(['product', 'createdBy'])
            ->when($request->type,       fn($q) => $q->where('type',       $request->type))
            ->when($request->product_id, fn($q) => $q->where('product_id', $request->product_id))
            ->when($request->direction,  fn($q) => $q->where('direction',  $request->direction))
            ->when($request->date_from,  fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,    fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(25);

        $products = Product::orderBy('name')->get();

        $stats = [
            'total_in'  => StockAdjustment::where('direction', 'in')->sum('quantity'),
            'total_out' => StockAdjustment::where('direction', 'out')->sum('quantity'),
            'damaged'   => StockAdjustment::where('type', 'damaged')->sum('quantity'),
            'expired'   => StockAdjustment::where('type', 'expired')->sum('quantity'),
        ];

        return view('admin.stock.history', compact('adjustments', 'products', 'stats'));
    }

    // ── Low Stock Report ───────────────────────────────────────
    public function lowStock(Request $request)
    {
        $threshold = $request->threshold ?? 10;

        $products = Product::with(['category', 'stockAlert'])
            ->where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity', 'asc')
            ->paginate(20);

        return view('admin.stock.low-stock', compact('products', 'threshold'));
    }

    // ── Damaged / Expired ──────────────────────────────────────
    public function damaged(Request $request)
    {
        $adjustments = StockAdjustment::with(['product', 'createdBy'])
            ->whereIn('type', ['damaged', 'expired'])
            ->when($request->type,      fn($q) => $q->where('type', $request->type))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(20);

        $stats = [
            'damaged_qty'   => StockAdjustment::where('type', 'damaged')->sum('quantity'),
            'expired_qty'   => StockAdjustment::where('type', 'expired')->sum('quantity'),
            'damaged_count' => StockAdjustment::where('type', 'damaged')->count(),
            'expired_count' => StockAdjustment::where('type', 'expired')->count(),
        ];

        return view('admin.stock.damaged', compact('adjustments', 'stats'));
    }

    // ── Set Low Stock Alert Threshold ──────────────────────────
    public function setAlert(Request $request, Product $product)
    {
        $request->validate([
            'low_stock_threshold' => 'required|integer|min:1',
        ]);

        StockAlert::updateOrCreate(
            ['product_id' => $product->id],
            [
                'low_stock_threshold' => $request->low_stock_threshold,
                'is_active'           => true,
            ]
        );

        return back()->with('success', 'Alert threshold updated for ' . $product->name . '.');
    }
}