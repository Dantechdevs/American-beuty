@extends('layouts.admin')
@section('title','Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon rose"><i class="fas fa-shopping-bag"></i></div>
        <div><div class="stat-value">{{ number_format($stats['total_orders']) }}</div><div class="stat-label">Total Orders</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
        <div><div class="stat-value">KSh {{ number_format($stats['total_revenue'],0) }}</div><div class="stat-label">Total Revenue</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div><div class="stat-value">{{ number_format($stats['total_customers']) }}</div><div class="stat-label">Customers</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-box"></i></div>
        <div><div class="stat-value">{{ number_format($stats['total_products']) }}</div><div class="stat-label">Active Products</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value">{{ $stats['pending_orders'] }}</div><div class="stat-label">Pending Orders</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
        <div><div class="stat-value">{{ $stats['low_stock'] }}</div><div class="stat-label">Low Stock Items</div></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start">
    <!-- RECENT ORDERS -->
    <div class="card">
        <div class="card-header">
            <h3>Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user?->name ?? $order->first_name.' '.$order->last_name }}</td>
                        <td>KSh {{ number_format($order->total,0) }}</td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-warning">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td style="color:#888;font-size:.8rem">{{ $order->created_at->format('d M') }}</td>
                        <td><a href="{{ route('admin.orders.show',$order) }}" class="btn btn-outline btn-sm">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MPESA TRANSACTIONS -->
    <div class="card">
        <div class="card-header"><h3>Recent M-PESA</h3></div>
        <div class="card-body" style="padding:0">
            @foreach($recentMpesa as $txn)
            <div style="padding:.9rem 1.2rem;border-bottom:1px solid var(--border);font-size:.85rem">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <strong>{{ $txn->phone_number }}</strong>
                    <span class="badge {{ $txn->status === 'success' ? 'badge-success' : ($txn->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                        {{ ucfirst($txn->status) }}
                    </span>
                </div>
                <div style="color:#888;margin-top:.2rem;display:flex;justify-content:space-between">
                    <span>KSh {{ number_format($txn->amount,0) }}</span>
                    <span>{{ $txn->created_at->diffForHumans() }}</span>
                </div>
                @if($txn->mpesa_receipt_number)
                    <div style="font-size:.75rem;color:#27ae60;margin-top:.2rem">{{ $txn->mpesa_receipt_number }}</div>
                @endif
            </div>
            @endforeach
            @if($recentMpesa->isEmpty())
                <p style="padding:1.5rem;color:#aaa;font-size:.88rem;text-align:center">No M-PESA transactions yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
