<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Order;
use App\Models\Appointment;
use Illuminate\Support\Collection;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->search;
        $source   = $request->source;
        $method   = $request->method_filter;
        $status   = $request->status;
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;

        // 1. Gateway transactions
        $gatewayTxns = Transaction::with(['order.user'])
            ->when($search, fn($q) =>
                $q->where('transaction_id', 'like', "%$search%")
                  ->orWhereHas('order.user', fn($u) =>
                      $u->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                  )
            )
            ->when($method && $method !== 'mpesa', fn($q) => $q->whereRaw('0=1'))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->map(fn($t) => (object)[
                'row_type'       => 'gateway',
                'id'             => 'G-'.$t->id,
                'raw_id'         => $t->id,
                'customer_name'  => optional($t->order?->user)->name ?? '—',
                'customer_email' => optional($t->order?->user)->email ?? '—',
                'customer_phone' => '—',
                'reference'      => $t->order?->order_number ?? '#'.$t->order_id,
                'reference_link' => $t->order_id ? route('admin.orders.show', $t->order_id) : null,
                'method'         => $t->gateway,
                'txn_code'       => $t->transaction_id,
                'amount'         => $t->amount,
                'status'         => $t->status,
                'date'           => $t->created_at,
                'source_label'   => 'Gateway',
            ]);

        // 2. Cash/MPESA orders
        $orderTxns = Order::with('user')
            ->where('payment_status', 'paid')
            ->whereIn('payment_method', ['cash', 'mpesa', 'cash_on_delivery'])
            ->when($search, fn($q) =>
                $q->where('order_number', 'like', "%$search%")
                  ->orWhereHas('user', fn($u) =>
                      $u->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                  )
                  ->orWhere('first_name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
            )
            ->when($method, fn($q) => $q->where('payment_method', $method))
            ->when($status === 'failed', fn($q) => $q->whereRaw('0=1'))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->map(fn($o) => (object)[
                'row_type'       => 'order',
                'id'             => 'O-'.$o->id,
                'raw_id'         => $o->id,
                'customer_name'  => $o->user?->name ?? trim($o->first_name.' '.$o->last_name),
                'customer_email' => $o->user?->email ?? $o->email ?? '—',
                'customer_phone' => $o->phone ?? '—',
                'reference'      => $o->order_number,
                'reference_link' => route('admin.orders.show', $o->id),
                'method'         => $o->payment_method,
                'txn_code'       => null,
                'amount'         => $o->total,
                'status'         => 'success',
                'date'           => $o->created_at,
                'source_label'   => 'Order',
            ]);

        // 3. Cash/MPESA appointments
        $apptTxns = Appointment::where('payment_status', 'paid')
            ->whereIn('payment_method', ['cash', 'mpesa'])
            ->when($search, fn($q) =>
                $q->where('client_name', 'like', "%$search%")
                  ->orWhere('client_phone', 'like', "%$search%")
                  ->orWhere('mpesa_code', 'like', "%$search%")
            )
            ->when($method, fn($q) => $q->where('payment_method', $method))
            ->when($status === 'failed', fn($q) => $q->whereRaw('0=1'))
            ->when($dateFrom, fn($q) => $q->whereDate('appointment_date', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('appointment_date', '<=', $dateTo))
            ->get()
            ->map(fn($a) => (object)[
                'row_type'       => 'appointment',
                'id'             => 'A-'.$a->id,
                'raw_id'         => $a->id,
                'customer_name'  => $a->client_name,
                'customer_email' => $a->client_email ?? '—',
                'customer_phone' => $a->client_phone,
                'reference'      => $a->service_name,
                'reference_link' => route('admin.appointments.show', $a->id),
                'method'         => $a->payment_method,
                'txn_code'       => $a->mpesa_code,
                'amount'         => $a->service_price,
                'status'         => 'success',
                'date'           => $a->created_at,
                'source_label'   => 'Appointment',
            ]);

        // Merge & filter by source
        $all = collect();
        if (!$source || $source === 'gateway')    $all = $all->merge($gatewayTxns);
        if (!$source || $source === 'order')       $all = $all->merge($orderTxns);
        if (!$source || $source === 'appointment') $all = $all->merge($apptTxns);
        $all = $all->sortByDesc('date');

        // Manual pagination
        $page  = $request->get('page', 1);
        $perPage = 25;
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $all->forPage($page, $perPage)->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $stats = [
            'total'   => $all->count(),
            'revenue' => $all->where('status', 'success')->sum('amount'),
            'pending' => $all->where('status', 'pending')->count(),
            'failed'  => $all->where('status', 'failed')->count(),
            'cash'    => $all->whereIn('method', ['cash','cash_on_delivery'])->sum('amount'),
            'mpesa'   => $all->where('method', 'mpesa')->sum('amount'),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }
    public function show(Transaction $transaction)
    {
        $transaction->load('order.user');

        return response()->json([
            'id'             => $transaction->id,
            'transaction_id' => $transaction->transaction_id ?? '—',
            'gateway'        => $transaction->gateway,
            'amount'         => number_format($transaction->amount, 2),
            'currency'       => $transaction->currency,
            'status'         => $transaction->status,
            'order_id'       => $transaction->order_id,
            'order_number'   => optional($transaction->order)->order_number,
            'customer'       => optional(optional($transaction->order)->user)->name  ?? '—',
            'email'          => optional(optional($transaction->order)->user)->email ?? '—',
            'payload'        => $transaction->payload,
            'created_at'     => $transaction->created_at->format('d M Y, H:i'),
        ]);
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed',
        ]);

        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Transaction status updated to '.$request->status.'.');
    }

    public function export(Request $request): StreamedResponse
    {
        $transactions = Transaction::with(['order.user'])
            ->when($request->gateway,  fn($q) => $q->where('gateway', $request->gateway))
            ->when($request->status,   fn($q) => $q->where('status',  $request->status))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID','Transaction Ref','Order ID','Customer','Email','Gateway','Amount','Currency','Status','Date']);
            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->id,
                    $t->transaction_id ?? '—',
                    $t->order_id,
                    optional(optional($t->order)->user)->name  ?? '—',
                    optional(optional($t->order)->user)->email ?? '—',
                    $t->gateway,
                    $t->amount,
                    $t->currency,
                    $t->status,
                    $t->created_at->format('d M Y H:i'),
                ]);
            }
            fclose($handle);
        }, 'transactions-'.now()->format('Y-m-d').'.csv');
    }
}