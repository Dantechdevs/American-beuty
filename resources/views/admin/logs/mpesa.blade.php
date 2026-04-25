@extends('layouts.admin')
@section('title', 'M-Pesa Logs')

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-mobile-screen-button" style="color:var(--green)"></i> M-Pesa Logs
        </div>
        <div class="page-sub">All M-Pesa payment transactions</div>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(5,1fr);margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-list"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div><div class="stat-value">{{ $stats['completed'] }}</div><div class="stat-label">Completed</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-circle-xmark"></i></div>
        <div><div class="stat-value">{{ $stats['failed'] }}</div><div class="stat-label">Failed</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-coins"></i></div>
        <div><div class="stat-value">KSh {{ number_format($stats['revenue'],0) }}</div><div class="stat-label">Revenue</div></div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1.25rem">
    <div class="card-body" style="padding:.85rem 1.25rem">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
            <div style="display:flex;flex-direction:column;gap:.3rem;flex:1;min-width:160px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Phone or receipt number…"
                       style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:130px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Status</label>
                <select name="status" style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
                    <option value="">All</option>
                    <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completed</option>
                    <option value="pending"   {{ request('status')==='pending'  ?'selected':'' }}>Pending</option>
                    <option value="failed"    {{ request('status')==='failed'   ?'selected':'' }}>Failed</option>
                </select>
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:140px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:140px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
            </div>
            <div style="display:flex;gap:.5rem;align-items:flex-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search','status','date_from','date_to']))
                    <a href="{{ route('admin.logs.mpesa') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-xmark"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-mobile-screen-button"></i> M-Pesa Transactions</h3>
        <span style="font-size:.78rem;color:var(--muted)">{{ $logs->total() }} records</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Receipt #</th>
                    <th>Phone</th>
                    <th>Order</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <span style="font-weight:700;font-size:.82rem;font-family:monospace;color:var(--green)">
                            {{ $log->mpesa_receipt_number ?? '—' }}
                        </span>
                    </td>
                    <td style="font-size:.84rem">{{ $log->phone_number ?? '—' }}</td>
                    <td>
                        @if($log->order)
                            <a href="{{ route('admin.orders.show', $log->order) }}"
                               style="font-weight:600;color:var(--purple);font-size:.82rem">
                                {{ $log->order->order_number }}
                            </a>
                        @else
                            <span style="color:var(--muted);font-size:.82rem">—</span>
                        @endif
                    </td>
                    <td>
                        <strong style="font-size:.88rem">KSh {{ number_format($log->amount, 0) }}</strong>
                    </td>
                    <td>
                        <span class="badge {{
                            $log->status === 'completed' ? 'badge-success' :
                            ($log->status === 'pending'  ? 'badge-warning' : 'badge-danger')
                        }}">
                            {{ ucfirst($log->status) }}
                        </span>
                    </td>
                    <td style="font-size:.78rem;color:var(--muted);max-width:200px">
                        {{ $log->result_description ?? '—' }}
                    </td>
                    <td style="font-size:.78rem;color:var(--muted);white-space:nowrap">
                        {{ $log->created_at->format('d M Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-mobile-screen-button"></i>
                            <p>No M-Pesa transactions found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $logs->withQueryString()->links() }}</div>
</div>
@endsection