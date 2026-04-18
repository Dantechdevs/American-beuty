@extends('layouts.admin')
@section('title','Dashboard')

@section('content')

{{-- ── Greeting ─────────────────────────────────────────────── --}}
<div style="
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:1rem;
    margin-bottom:1.75rem;
    padding:1.5rem 1.75rem;
    background:linear-gradient(135deg,#f0fdf4 0%,#eff6ff 100%);
    border-radius:16px;
    border:1px solid #e2e8f0;
    box-shadow:0 2px 8px rgba(0,0,0,.04);
">
    <div>
        <h2 style="font-size:1.6rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-.5px">
            Good
            @php
                $hour = now()->hour;
                if ($hour < 12)       $greeting = 'Morning';
                elseif ($hour < 17)   $greeting = 'Afternoon';
                else                  $greeting = 'Evening';
            @endphp
            {{ $greeting }},
            <span style="color:var(--primary)">{{ auth()->user()->name }}</span>! 👋
        </h2>
        <p style="color:#64748b;margin:.35rem 0 0;font-size:.93rem">
            <i class="fas fa-calendar-alt" style="margin-right:.35rem;color:var(--primary);opacity:.75"></i>
            {{ now()->format('l, d F Y') }}
            &nbsp;·&nbsp;
            <i class="fas fa-clock" style="margin-right:.35rem;color:var(--primary);opacity:.75"></i>
            {{ now()->format('h:i A') }}
        </p>
    </div>
    <div style="display:flex;align-items:center;gap:.65rem">
        <div style="
            width:48px;height:48px;border-radius:50%;
            background:linear-gradient(135deg,var(--primary),#3b82f6);
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:1.2rem;font-weight:700;
            box-shadow:0 4px 12px rgba(0,0,0,.15);
        ">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <div style="font-weight:700;font-size:.9rem;color:#1e293b">{{ auth()->user()->name }}</div>
            <div style="font-size:.78rem;color:#94a3b8">{{ auth()->user()->email }}</div>
        </div>
    </div>
</div>

{{-- ── Stats Grid ──────────────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon rose"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <div class="stat-value">KSh {{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
            <div class="stat-label">Customers</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-box"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <div class="stat-label">Active Products</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $stats['pending_orders'] }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="stat-value">{{ $stats['low_stock'] }}</div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-cash-register"></i></div>
        <div>
            <div class="stat-value">KSh {{ number_format($stats['pos_revenue'] ?? 0, 0) }}</div>
            <div class="stat-label">POS Revenue Today</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['pos_orders_today'] ?? 0) }}</div>
            <div class="stat-label">POS Sales Today</div>
        </div>
    </div>
</div>

{{-- ── Quick Actions ───────────────────────────────────────── --}}
<div style="display:flex;gap:.75rem;margin-bottom:1.5rem;flex-wrap:wrap">
    <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
        <i class="fas fa-cash-register"></i> &nbsp;Open POS Terminal
    </a>
    <a href="{{ route('admin.pos.orders') }}" class="btn btn-outline">
        <i class="fas fa-receipt"></i> &nbsp;POS Orders
    </a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">
        <i class="fas fa-shopping-bag"></i> &nbsp;All Orders
    </a>
    <a href="{{ route('admin.products.create') }}" class="btn btn-outline">
        <i class="fas fa-plus"></i> &nbsp;Add Product
    </a>
</div>

{{-- ── Main Content Grid ───────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start">

    {{-- ── Recent Orders ──────────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <h3>Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Source</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user?->name ?? $order->first_name.' '.$order->last_name }}</td>
                        <td>KSh {{ number_format($order->total, 0) }}</td>
                        <td>
                            @if(($order->source ?? 'online') === 'pos')
                                <span class="badge" style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0">
                                    <i class="fas fa-cash-register" style="font-size:.7rem"></i> POS
                                </span>
                            @else
                                <span class="badge" style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe">
                                    <i class="fas fa-globe" style="font-size:.7rem"></i> Online
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-warning">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->status_badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td style="color:#888;font-size:.8rem">{{ $order->created_at->format('d M') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2rem;color:#aaa">No orders yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Right Column ────────────────────────────────────── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem">

        {{-- ── Recent M-PESA ───────────────────────────────── --}}
        <div class="card">
            <div class="card-header"><h3>Recent M-PESA</h3></div>
            <div class="card-body" style="padding:0">
                @forelse($recentMpesa as $txn)
                <div style="padding:.9rem 1.2rem;border-bottom:1px solid var(--border);font-size:.85rem">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <strong>{{ $txn->phone_number }}</strong>
                        <span class="badge {{
                            $txn->status === 'success' ? 'badge-success' :
                            ($txn->status === 'pending' ? 'badge-warning' : 'badge-danger')
                        }}">{{ ucfirst($txn->status) }}</span>
                    </div>
                    <div style="color:#888;margin-top:.2rem;display:flex;justify-content:space-between">
                        <span>KSh {{ number_format($txn->amount, 0) }}</span>
                        <span>{{ $txn->created_at->diffForHumans() }}</span>
                    </div>
                    @if($txn->mpesa_receipt_number)
                        <div style="font-size:.75rem;color:#27ae60;margin-top:.2rem">
                            {{ $txn->mpesa_receipt_number }}
                        </div>
                    @endif
                </div>
                @empty
                <p style="padding:1.5rem;color:#aaa;font-size:.88rem;text-align:center">
                    No M-PESA transactions yet.
                </p>
                @endforelse
            </div>
        </div>

        {{-- ── Today's POS Summary ─────────────────────────── --}}
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-cash-register" style="color:var(--primary);margin-right:.4rem"></i> Today's POS</h3>
                <a href="{{ route('admin.pos.orders') }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="card-body" style="padding:0">
                @forelse($recentPosOrders as $order)
                <div style="padding:.85rem 1.2rem;border-bottom:1px solid var(--border);font-size:.85rem">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <strong>{{ $order->order_number }}</strong>
                        <strong style="color:var(--primary)">KSh {{ number_format($order->total, 0) }}</strong>
                    </div>
                    <div style="color:#888;margin-top:.2rem;display:flex;justify-content:space-between">
                        <span>{{ $order->user?->name ?? $order->first_name.' '.$order->last_name }}</span>
                        <span>{{ $order->created_at->format('h:i A') }}</span>
                    </div>
                    <div style="margin-top:.25rem">
                        <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.7rem">
                            {{ strtoupper($order->payment_method) }}
                        </span>
                    </div>
                </div>
                @empty
                <p style="padding:1.5rem;color:#aaa;font-size:.88rem;text-align:center">
                    No POS sales today yet.<br>
                    <a href="{{ route('admin.pos.index') }}" style="color:var(--primary);font-weight:600">
                        Open Terminal →
                    </a>
                </p>
                @endforelse
            </div>
        </div>

    </div>{{-- end right column --}}
</div>

{{-- ── Low Stock Warning ───────────────────────────────────── --}}
@if(isset($lowStockProducts) && $lowStockProducts->count())
<div class="card" style="margin-top:1.5rem">
    <div class="card-header">
        <h3 style="color:#dc2626">
            <i class="fas fa-exclamation-triangle"></i> &nbsp;Low Stock Alert
        </h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">Manage Products</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Product</th><th>SKU</th><th>Stock</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $product)
                <tr>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td style="color:#888">{{ $product->sku }}</td>
                    <td>
                        <span class="badge badge-danger">{{ $product->stock_quantity }} left</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm">
                            Update Stock
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection