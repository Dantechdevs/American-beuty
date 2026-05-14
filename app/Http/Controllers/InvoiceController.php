<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // ─── Admin: List all invoices ─────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('admin.invoices.index', compact('invoices'));
    }

    // ─── Admin: View single invoice ───────────────────────────────────────────

    public function adminView(Order $order)
    {
        $order->load(['items.product', 'user', 'mpesa']);

        return view('invoices.invoice', [
            'order'   => $order,
            'backUrl' => route('admin.invoices.index'),
            'pdfUrl'  => route('admin.orders.invoice.pdf', $order->id),
        ]);
    }

    public function adminPdf(Order $order)
    {
        $order->load(['items.product', 'user', 'mpesa']);

        $pdf = Pdf::loadView('invoices.invoice', [
            'order'   => $order,
            'backUrl' => '#',
            'pdfUrl'  => '#',
            'isPdf'   => true,
        ]);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    // ─── Customer: View own invoice ───────────────────────────────────────────

    public function customerView(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items.product', 'user', 'mpesa'])
            ->firstOrFail();

        return view('invoices.invoice', [
            'order'   => $order,
            'backUrl' => url()->previous(),
            'pdfUrl'  => route('customer.invoice.pdf', $orderNumber),
        ]);
    }

    public function customerPdf(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with(['items.product', 'user', 'mpesa'])
            ->firstOrFail();

        $pdf = Pdf::loadView('invoices.invoice', [
            'order'   => $order,
            'backUrl' => '#',
            'pdfUrl'  => '#',
            'isPdf'   => true,
        ]);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}