<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    // ── Order-based invoices (existing) ──────────────────────────

    public function adminView(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('invoices.invoice', [
            'order'   => $order,
            'backUrl' => route('admin.orders.show', $order->id),
            'pdfUrl'  => route('admin.orders.invoice.pdf', $order->id),
        ]);
    }

    public function adminPdf(Order $order)
    {
        $order->load(['items.product', 'user']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.invoice', [
            'order'   => $order,
            'backUrl' => '#',
            'pdfUrl'  => '#',
            'isPdf'   => true,
        ]);
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    // ── Standalone Invoice CRUD ───────────────────────────────────

    public function index(Request $request)
    {
        $query = Invoice::with('creator')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('invoice_number', 'like', "%{$q}%")
                   ->orWhere('client_name',  'like', "%{$q}%")
                   ->orWhere('client_phone', 'like', "%{$q}%");
            });
        }

        $invoices = $query->paginate(20)->withQueryString();
        $stats = [
            'total'     => Invoice::count(),
            'draft'     => Invoice::where('status', 'draft')->count(),
            'paid'      => Invoice::where('status', 'paid')->count(),
            'sent'      => Invoice::where('status', 'sent')->count(),
            'cancelled' => Invoice::where('status', 'cancelled')->count(),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    public function create()
    {
        return view('admin.invoices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name'    => 'required|string|max:150',
            'client_phone'   => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:255',
            'invoice_date'   => 'required|date',
            'due_date'       => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'status'         => 'required|in:draft,sent,paid,cancelled',
            'notes'          => 'nullable|string',
            'discount'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'served_by'      => 'nullable|exists:employees,id',
            'items'          => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            $discount = (float) ($request->discount ?? 0);
            $tax      = (float) ($request->tax ?? 0);
            $total    = max(0, $subtotal - $discount + $tax);

            $invoice = Invoice::create([
                'client_name'    => $request->client_name,
                'client_phone'   => $request->client_phone,
                'client_address' => $request->client_address,
                'invoice_date'   => $request->invoice_date,
                'due_date'       => $request->due_date,
                'payment_method' => $request->payment_method,
                'status'         => $request->status,
                'notes'          => $request->notes,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total'          => $total,
                'paid_at'        => $request->status === 'paid' ? now() : null,
                'created_by'     => Auth::id(),
                'served_by'      => $request->served_by ?: null,
            ]);

            foreach ($request->items as $i => $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'subtotal'    => $item['quantity'] * $item['unit_price'],
                    'sort_order'  => $i,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.invoices.show', $invoice)
                             ->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'creator', 'order']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        return view('admin.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'client_name'    => 'required|string|max:150',
            'client_phone'   => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:255',
            'invoice_date'   => 'required|date',
            'due_date'       => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'status'         => 'required|in:draft,sent,paid,cancelled',
            'notes'          => 'nullable|string',
            'discount'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'items'          => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            $discount = (float) ($request->discount ?? 0);
            $tax      = (float) ($request->tax ?? 0);
            $total    = max(0, $subtotal - $discount + $tax);

            $invoice->update([
                'client_name'    => $request->client_name,
                'client_phone'   => $request->client_phone,
                'client_address' => $request->client_address,
                'invoice_date'   => $request->invoice_date,
                'due_date'       => $request->due_date,
                'payment_method' => $request->payment_method,
                'status'         => $request->status,
                'notes'          => $request->notes,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total'          => $total,
                'paid_at'        => $request->status === 'paid' ? ($invoice->paid_at ?? now()) : null,
            ]);

            $invoice->items()->delete();
            foreach ($request->items as $i => $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'subtotal'    => $item['quantity'] * $item['unit_price'],
                    'sort_order'  => $i,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.invoices.show', $invoice)
                             ->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load('items');
        return view('admin.invoices.print', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load('items');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.print', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Invoice marked as paid.');
    }
}
