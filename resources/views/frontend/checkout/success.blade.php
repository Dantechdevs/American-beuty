@extends('layouts.app')
@section('title','Order Confirmed!')

@push('styles')
<style>
:root{--rose:#c8847a;--sand:#f0e8df;--border:#e8ddd6;--charcoal:#2c2c2c;}
.success-wrap{max-width:680px;margin:4rem auto;padding:0 1.5rem;text-align:center;}
.success-card{background:#fff;border-radius:24px;padding:3rem;box-shadow:0 20px 60px rgba(0,0,0,.07);}
.success-icon{font-size:4rem;margin-bottom:1.2rem;}
.success-card h1{font-family:'Cormorant Garamond',serif;font-size:2.2rem;margin-bottom:.8rem;}
.success-card>p{color:#666;font-size:.95rem;line-height:1.7;margin-bottom:1.5rem;}
.order-meta{background:var(--sand);border-radius:14px;padding:1.2rem 1.5rem;text-align:left;margin-bottom:1.5rem;}
.order-meta h4{font-size:.8rem;letter-spacing:.1em;text-transform:uppercase;color:#888;margin-bottom:.8rem;}
.meta-row{display:flex;justify-content:space-between;font-size:.88rem;padding:.35rem 0;border-bottom:1px solid var(--border);}
.meta-row:last-child{border:none;}
.meta-row span:first-child{color:#666;}
.meta-row span:last-child{font-weight:600;}
.order-items{text-align:left;margin-bottom:1.5rem;}
.order-items h4{font-size:.8rem;letter-spacing:.1em;text-transform:uppercase;color:#888;margin-bottom:.8rem;}
.order-item-row{display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid var(--border);font-size:.88rem;}
.order-item-row:last-child{border:none;}
.btns{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1rem;}
.btn-primary{background:var(--rose);color:#fff;padding:.8rem 2rem;border-radius:30px;font-size:.9rem;display:inline-block;font-weight:500;}
.btn-outline{border:1.5px solid var(--charcoal);color:var(--charcoal);padding:.78rem 2rem;border-radius:30px;font-size:.9rem;display:inline-block;}
</style>
@endpush

@section('content')
<div class="success-wrap">
    <div class="success-card">
        <div class="success-icon">🎉</div>
        <h1>Order Confirmed!</h1>
        <p>Thank you for shopping with American Beauty! Your order has been placed and we'll send you a confirmation email shortly.</p>

        <div class="order-meta">
            <h4>Order Details</h4>
            <div class="meta-row"><span>Order Number</span><span>{{ $order->order_number }}</span></div>
            <div class="meta-row"><span>Date</span><span>{{ $order->created_at->format('d M Y') }}</span></div>
            <div class="meta-row"><span>Payment</span><span>{{ strtoupper($order->payment_method) }}</span></div>
            <div class="meta-row"><span>Status</span><span style="color:var(--rose)">{{ ucfirst($order->payment_status) }}</span></div>
            <div class="meta-row"><span>Total</span><span>KSh {{ number_format($order->total, 0) }}</span></div>
            <div class="meta-row"><span>Delivery To</span><span>{{ $order->city }}, {{ $order->country }}</span></div>
        </div>

        <div class="order-items">
            <h4>Items Ordered</h4>
            @foreach($order->items as $item)
                <div class="order-item-row">
                    <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                    <span>KSh {{ number_format($item->subtotal, 0) }}</span>
                </div>
            @endforeach
        </div>

        <div class="btns">
            <a href="{{ route('home') }}" class="btn-primary">Continue Shopping</a>
            <a href="{{ route('products.index') }}" class="btn-outline">Browse Products</a>
        </div>

        <p style="font-size:.78rem;color:#aaa;margin-top:1.5rem">Questions? Email us at <a href="mailto:info@americanbeauty.com" style="color:var(--rose)">info@americanbeauty.com</a></p>
    </div>
</div>
@endsection
