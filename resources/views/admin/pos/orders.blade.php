@extends('layouts.admin')
@section('title', 'POS Orders')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-cash-register" style="color:var(--primary);margin-right:.5rem"></i> POS Orders</h3>
        <a href="{{ route('admin.pos.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> New Sale
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Served By</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->user?->name ?? $order->first_name.' '.$order->last_name }}</td>
                    <td>{{ $order->items->count() }} item(s)</td>
                    <td><strong>KSh {{ number_format($order->total, 0) }}</strong></td>
                    <td>
                        <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_method) }} — {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>{{ $order->servedBy?->name ?? 'N/A' }}</td>
                    <td style="color:#888;font-size:.8rem">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.pos.receipt', $order) }}" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-receipt"></i> Receipt
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:#aaa">
                        <i class="fas fa-cash-register" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                        No POS orders yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.2rem">
        {{ $orders->links() }}
    </div>
</div>
@endsection
