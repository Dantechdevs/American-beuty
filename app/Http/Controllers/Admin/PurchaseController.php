<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // ── List all purchases ─────────────────────────────────────────
    public function index(Request $request)
    {
        $purchases = Purchase::with(['supplier', 'items'])
            ->when($request->supplier_id, fn($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->payment_status, fn($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->date_from, fn($q) => $q->whereDate('purchase_date', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('purchase_date', '<=', $request->date_to))
            ->latest()
            ->paginate(20);

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total_purchases' => Purchase::count(),
            'total_paid'      => Purchase::where('payment_status', 'paid')->sum('total_amount'),
            'total_unpaid'    => Purchase::where('payment_status', 'unpaid')->sum('total_amount'),
            'total_returns'   => PurchaseReturn::count(),
        ];

        return view('admin.purchases.index', compact('purchases', 'suppliers', 'stats'));
    }

    // ── Show create form ───────────────────────────────────────────
    public function create()
    {
        $suppliers  = Supplier::where('is_active', true)->orderBy('name')->get();
        $products   = Product::orderBy('name')->get();
        $invoice_no = Purchase::generateInvoiceNo();

        return view('admin.purchases.create', compact('suppliers', 'products', 'invoice_no'));
    }

    // ── Store new purchase ─────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'invoice_no'     => 'required|unique:purchases,invoice_no',
            'purchase_date'  => 'required|date',
            'payment_status' => 'required|in:paid,partial,unpaid',
            'paid_amount'    => 'required|numeric|min:0',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_cost'  => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = collect($request->items)
                ->sum(fn($item) => $item['quantity'] * $item['unit_cost']);

            $purchase = Purchase::create([
                'invoice_no'     => $request->invoice_no,
                'supplier_id'    => $request->supplier_id,
                'created_by'     => Auth::id(),
                'purchase_date'  => $request->purchase_date,
                'payment_time'   => $request->payment_time ?? null,
                'payment_status' => $request->payment_status,
                'total_amount'   => $total,
                'paid_amount'    => $request->paid_amount,
                'notes'          => $request->notes,
            ]);

            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'unit_cost'   => $item['unit_cost'],
                    'subtotal'    => $item['quantity'] * $item['unit_cost'],
                ]);

                // Update product stock
                Product::find($item['product_id'])->increment('stock', $item['quantity']);
            }
        });

        return redirect()->route('admin.purchase.index')
            ->with('success', 'Purchase recorded successfully.');
    }

    // ── Show single purchase ───────────────────────────────────────
    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.product', 'returns.product', 'createdBy'])
            ->findOrFail($id);

        return view('admin.purchases.show', compact('purchase'));
    }

    // ── Show edit form ─────────────────────────────────────────────
    public function edit($id)
    {
        $purchase  = Purchase::with('items.product')->findOrFail($id);
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products  = Product::orderBy('name')->get();

        return view('admin.purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    // ── Update purchase ────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:paid,partial,unpaid',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_time'   => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $purchase->update([
            'payment_status' => $request->payment_status,
            'paid_amount'    => $request->paid_amount,
            'payment_time'   => $request->payment_time,
            'notes'          => $request->notes,
        ]);

        return redirect()->route('admin.purchase.show', $purchase->id)
            ->with('success', 'Purchase updated successfully.');
    }

    // ── Soft delete ────────────────────────────────────────────────
    public function destroy($id)
    {
        Purchase::findOrFail($id)->delete();

        return redirect()->route('admin.purchase.index')
            ->with('success', 'Purchase deleted.');
    }

    // ── Return form ────────────────────────────────────────────────
    public function returnForm($id)
    {
        $purchase = Purchase::with('items.product')->findOrFail($id);

        return view('admin.purchases.return', compact('purchase'));
    }

    // ── Store return ───────────────────────────────────────────────
    public function returnStore(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'returns'              => 'required|array|min:1',
            'returns.*.product_id' => 'required|exists:products,id',
            'returns.*.quantity'   => 'required|integer|min:1',
            'returns.*.reason'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $purchase) {
            foreach ($request->returns as $item) {
                PurchaseReturn::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'reason'      => $item['reason'] ?? null,
                    'returned_at' => now(),
                    'created_by'  => Auth::id(),
                ]);

                // Deduct stock on return
                Product::find($item['product_id'])->decrement('stock', $item['quantity']);
            }
        });

        return redirect()->route('admin.purchase.show', $purchase->id)
            ->with('success', 'Return processed successfully.');
    }
}