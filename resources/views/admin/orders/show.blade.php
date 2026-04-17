@extends('layouts.admin')
@section('title','Order '.$order->order_number)

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h2 style="font-size:1.3rem;font-weight:700">Order {{ $order->order_number }}</h2>
        <p style="color:#888;font-size:.82rem;margin-top:.2rem">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">
    <div>
        <!-- ORDER ITEMS -->
        <div class="card" style="margin-bottom:1.5rem">
            <div class="card-header"><h3>Order Items</h3></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight:500;font-size:.88rem">{{ $item->product_name }}</div>
                                @if($item->product)<div style="font-size:.75rem;color:#888">SKU: {{ $item->product->sku }}</div>@endif
                            </td>
                            <td>KSh {{ number_format($item->price,0) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><strong>KSh {{ number_format($item->subtotal,0) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border)">
                <div style="display:flex;justify-content:flex-end">
                    <div style="width:260px;font-size:.88rem">
                        <div style="display:flex;justify-content:space-between;padding:.35rem 0;color:#666"><span>Subtotal</span><span>KSh {{ number_format($order->subtotal,0) }}</span></div>
                        <div style="display:flex;justify-content:space-between;padding:.35rem 0;color:#666"><span>Shipping</span><span>{{ $order->shipping==0 ? 'Free' : 'KSh '.number_format($order->shipping,0) }}</span></div>
                        @if($order->discount > 0)<div style="display:flex;justify-content:space-between;padding:.35rem 0;color:green"><span>Discount</span><span>-KSh {{ number_format($order->discount,0) }}</span></div>@endif
                        @if($order->tax > 0)<div style="display:flex;justify-content:space-between;padding:.35rem 0;color:#666"><span>Tax (VAT)</span><span>KSh {{ number_format($order->tax,0) }}</span></div>@endif
                        <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-top:1px solid var(--border);font-weight:700;font-size:1rem;margin-top:.3rem"><span>Total</span><span>KSh {{ number_format($order->total,0) }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- M-PESA TRANSACTION -->
        @if($order->mpesa)
        <div class="card">
            <div class="card-header"><h3>📱 M-PESA Transaction</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;font-size:.88rem">
                    <div><span style="color:#888">Phone</span><br><strong>{{ $order->mpesa->phone_number }}</strong></div>
                    <div><span style="color:#888">Amount</span><br><strong>KSh {{ number_format($order->mpesa->amount,0) }}</strong></div>
                    <div><span style="color:#888">Receipt #</span><br><strong>{{ $order->mpesa->mpesa_receipt_number ?? '—' }}</strong></div>
                    <div><span style="color:#888">Status</span><br>
                        <span class="badge {{ $order->mpesa->status==='success'?'badge-success':($order->mpesa->status==='pending'?'badge-warning':'badge-danger') }}">
                            {{ ucfirst($order->mpesa->status) }}
                        </span>
                    </div>
                    <div><span style="color:#888">Transaction Date</span><br><strong>{{ $order->mpesa->transaction_date ?? '—' }}</strong></div>
                    <div><span style="color:#888">Checkout Request ID</span><br><span style="font-size:.75rem;color:#888">{{ $order->mpesa->checkout_request_id }}</span></div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div>
        <!-- ORDER STATUS -->
        <div class="card" style="margin-bottom:1.2rem">
            <div class="card-header"><h3>Update Status</h3></div>
            <div class="card-body">
                <div style="margin-bottom:1rem">
                    <span class="badge badge-{{ $order->status_badge }}" style="font-size:.85rem;padding:.4rem .9rem">{{ ucfirst($order->status) }}</span>
                    &nbsp;
                    <span class="badge {{ $order->payment_status==='paid'?'badge-success':'badge-warning' }}" style="font-size:.85rem;padding:.4rem .9rem">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <form action="{{ route('admin.orders.status',$order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label>Order Status</label>
                        <select name="status">
                            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Update Status</button>
                </form>
            </div>
        </div>

        <!-- CUSTOMER INFO -->
        <div class="card" style="margin-bottom:1.2rem">
            <div class="card-header"><h3>Customer</h3></div>
            <div class="card-body" style="font-size:.87rem;display:flex;flex-direction:column;gap:.5rem">
                <div><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></div>
                <div style="color:#666"><i class="fas fa-envelope" style="width:16px"></i> {{ $order->email }}</div>
                <div style="color:#666"><i class="fas fa-phone" style="width:16px"></i> {{ $order->phone }}</div>
            </div>
        </div>

        <!-- SHIPPING ADDRESS -->
        <div class="card">
            <div class="card-header"><h3>Shipping Address</h3></div>
            <div class="card-body" style="font-size:.87rem;color:#555;line-height:1.8">
                {{ $order->address_line_1 }}<br>
                @if($order->address_line_2){{ $order->address_line_2 }}<br>@endif
                {{ $order->city }}@if($order->county), {{ $order->county }}@endif<br>
                {{ $order->country }}
                @if($order->notes)<hr style="margin:.8rem 0;border:none;border-top:1px solid var(--border)"><strong>Note:</strong> {{ $order->notes }}@endif
            </div>
        </div>
    </div>
</div>
@endsection
