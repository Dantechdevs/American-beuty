@extends('layouts.admin')
@section('title','Orders')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.3rem;font-weight:700">All Orders</h2>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" style="display:flex;gap:.8rem;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order number..." style="padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;width:200px">
            <select name="status" style="padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit">
                <option value="">All Statuses</option>
                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            @if(request()->hasAny(['search','status']))<a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Clear</a>@endif
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>
                        <div style="font-weight:500;font-size:.88rem">{{ $order->first_name }} {{ $order->last_name }}</div>
                        <div style="font-size:.76rem;color:#888">{{ $order->email }}</div>
                    </td>
                    <td style="font-size:.85rem">{{ $order->items->count() ?? '—' }}</td>
                    <td><strong>KSh {{ number_format($order->total,0) }}</strong></td>
                    <td>
                        <span class="badge {{ $order->payment_status==='paid' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                        <br><span style="font-size:.75rem;color:#888">{{ strtoupper($order->payment_method) }}</span>
                    </td>
                    <td><span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="font-size:.8rem;color:#888">{{ $order->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('admin.orders.show',$order) }}" class="btn btn-outline btn-sm">View</a></td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:#aaa;padding:3rem">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $orders->links() }}</div>
</div>
@endsection
