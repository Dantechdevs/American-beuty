@extends('layouts.admin')
@section('title', 'Return Orders')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.3rem;font-weight:700">Return Orders</h2>
</div>

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-rotate-left"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Returns</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
            <div class="stat-label">Approved</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <div class="stat-value">{{ $stats['refunded'] }}</div>
            <div class="stat-label">Refunded</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card">
    <div class="card-header">
        <form method="GET" style="display:flex;gap:.8rem;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Return # or customer..."
                style="padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;width:200px">

            <select name="status" style="padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit">
                <option value="">All Statuses</option>
                @foreach(['pending','reviewing','approved','rejected','refunded','closed'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            <select name="reason" style="padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit">
                <option value="">All Reasons</option>
                @foreach(\App\Models\ReturnOrder::REASONS as $key => $label)
                    <option value="{{ $key }}" {{ request('reason') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            @if(request()->hasAny(['search','status','reason']))
                <a href="{{ route('admin.return-orders.index') }}" class="btn btn-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Return #</th>
                    <th>Customer</th>
                    <th>Order</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Reason</th>
                    <th>Refund</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                <tr>
                    <td><strong>{{ $return->return_number }}</strong></td>
                    <td>
                        <div style="font-weight:500;font-size:.88rem">{{ $return->user->name ?? '—' }}</div>
                        <div style="font-size:.76rem;color:#888">{{ $return->user->email ?? '' }}</div>
                    </td>
                    <td style="font-size:.85rem">
                        <a href="{{ route('admin.orders.show', $return->order) }}" style="color:var(--primary)">
                            {{ $return->order->order_number ?? '—' }}
                        </a>
                    </td>
                    <td style="font-size:.85rem">{{ $return->product->name ?? '—' }}</td>
                    <td style="font-size:.85rem">{{ $return->quantity }}</td>
                    <td style="font-size:.85rem">{{ \App\Models\ReturnOrder::REASONS[$return->reason] ?? ucfirst($return->reason) }}</td>
                    <td>
                        @if($return->refund_amount)
                            <strong>KSh {{ number_format($return->refund_amount, 0) }}</strong>
                            <div style="font-size:.75rem;color:#888">{{ ucfirst(str_replace('_',' ',$return->refund_method ?? '')) }}</div>
                        @else
                            <span style="color:#aaa">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $return->getStatusBadgeClass() }}">
                            {{ ucfirst($return->status) }}
                        </span>
                    </td>
                    <td style="font-size:.8rem;color:#888">{{ $return->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('admin.return-orders.show', $return) }}" class="btn btn-outline btn-sm">View</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:#aaa;padding:3rem">No return orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $returns->links() }}</div>
</div>
@endsection