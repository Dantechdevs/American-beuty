<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['order.user'])
            ->when($request->search, fn($q) =>
                $q->where('transaction_id', 'like', '%'.$request->search.'%')
                  ->orWhereHas('order.user', fn($q) =>
                      $q->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('email', 'like', '%'.$request->search.'%')
                  )
            )
            ->when($request->gateway, fn($q) =>
                $q->where('gateway', $request->gateway)
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->date_from, fn($q) =>
                $q->whereDate('created_at', '>=', $request->date_from)
            )
            ->when($request->date_to, fn($q) =>
                $q->whereDate('created_at', '<=', $request->date_to)
            )
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $stats = [
            'total'    => Transaction::count(),
            'revenue'  => Transaction::where('status', 'success')->sum('amount'),
            'pending'  => Transaction::where('status', 'pending')->count(),
            'failed'   => Transaction::where('status', 'failed')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('order.user');
        return response()->json([
            'id'             => $transaction->id,
            'transaction_id' => $transaction->transaction_id,
            'gateway'        => $transaction->gateway,
            'amount'         => number_format($transaction->amount, 2),
            'currency'       => $transaction->currency,
            'status'         => $transaction->status,
            'order_id'       => $transaction->order_id,
            'order_number'   => optional($transaction->order)->order_number,
            'customer'       => optional(optional($transaction->order)->user)->name,
            'email'          => optional(optional($transaction->order)->user)->email,
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

        return back()->with('success', 'Transaction #'.$transaction->id.' status updated to '.$request->status.'.');
    }

    public function export(Request $request): StreamedResponse
    {
        $transactions = Transaction::with(['order.user'])
            ->when($request->gateway, fn($q) => $q->where('gateway', $request->gateway))
            ->when($request->status,  fn($q) => $q->where('status',  $request->status))
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