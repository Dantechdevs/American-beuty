<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MpesaTransaction;
use Illuminate\Http\Request;

class LogController extends Controller
{
    // ── M-Pesa Logs ───────────────────────────────────────────
    public function mpesa(Request $request)
    {
        $logs = MpesaTransaction::with('order')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where(fn($q) =>
                $q->where('phone_number', 'like', '%'.$request->search.'%')
                  ->orWhere('mpesa_receipt_number', 'like', '%'.$request->search.'%')
            ))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $stats = [
            'total'     => MpesaTransaction::count(),
            'completed' => MpesaTransaction::where('status', 'completed')->count(),
            'pending'   => MpesaTransaction::where('status', 'pending')->count(),
            'failed'    => MpesaTransaction::where('status', 'failed')->count(),
            'revenue'   => MpesaTransaction::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.logs.mpesa', compact('logs', 'stats'));
    }

    // ── Customer Logs ─────────────────────────────────────────
    public function customers(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->where('role', 'customer')
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->search, fn($q) => $q->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            ))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $stats = $this->activityStats('customer');

        return view('admin.logs.customers', compact('logs', 'stats'));
    }

    // ── Manager Logs ──────────────────────────────────────────
    public function managers(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->where('role', 'manager')
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->search, fn($q) => $q->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
            ))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $stats = $this->activityStats('manager');

        return view('admin.logs.managers', compact('logs', 'stats'));
    }

    // ── POS Operator Logs ─────────────────────────────────────
    public function posOperators(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->where('role', 'pos_operator')
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->search, fn($q) => $q->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
            ))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $stats = $this->activityStats('pos_operator');

        return view('admin.logs.pos_operators', compact('logs', 'stats'));
    }

    // ── Shared stats helper ───────────────────────────────────
    private function activityStats(string $role): array
    {
        $base = ActivityLog::where('role', $role);

        return [
            'total'   => (clone $base)->count(),
            'today'   => (clone $base)->whereDate('created_at', today())->count(),
            'logins'  => (clone $base)->where('action', 'login')->count(),
            'logouts' => (clone $base)->where('action', 'logout')->count(),
            'actions' => (clone $base)->whereNotIn('action', ['login', 'logout'])->count(),
        ];
    }
}