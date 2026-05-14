<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class InvoiceController extends Controller
{
    public function adminView(Order $order)
    {
        $order->load(['items.product', 'user']);

        return view('invoices.Invoice', [
            'order'   => $order,
            'backUrl' => route('admin.orders.show', $order->id),
            'pdfUrl'  => route('admin.orders.invoice.pdf', $order->id),
        ]);
    }

    public function downloadPdf(Order $order)
    {
        $order->load(['items.product', 'user']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.Invoice', [
            'order'   => $order,
            'backUrl' => '#',
            'pdfUrl'  => '#',
        ]);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}