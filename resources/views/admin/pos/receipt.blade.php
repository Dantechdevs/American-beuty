<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Receipt — {{ $order->order_number }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Courier New', monospace;
        font-size: 13px;
        background: #fff;
        color: #111;
        max-width: 320px;
        margin: 0 auto;
        padding: 1.5rem 1rem;
    }
    .center { text-align: center; }
    .bold   { font-weight: bold; }
    .divider { border: none; border-top: 1px dashed #999; margin: .7rem 0; }
    .shop-name { font-size: 1.2rem; font-weight: 800; letter-spacing: .05em; }
    .row { display: flex; justify-content: space-between; margin: .25rem 0; }
    .items-table { width: 100%; margin: .5rem 0; }
    .items-table th { text-align: left; font-size: .8rem; color: #666; padding-bottom: .3rem; }
    .items-table td { padding: .2rem 0; vertical-align: top; }
    .items-table td:last-child { text-align: right; }
    .total-section { margin-top: .5rem; }
    .grand-total { font-size: 1rem; font-weight: 800; }
    .footer { margin-top: 1rem; font-size: .78rem; color: #666; text-align: center; line-height: 1.6; }
    @media print {
        body { margin: 0; padding: .5rem; }
        .no-print { display: none; }
    }
</style>
</head>
<body>

<div class="center" style="margin-bottom:.8rem">
    <div class="shop-name">AMERICAN BEAUTY</div>
    <div style="font-size:.8rem;color:#666;margin-top:.2rem">Your Beauty, Our Passion</div>
    <div style="font-size:.75rem;color:#888">Tel: +254 xxx xxx xxx</div>
</div>

<hr class="divider">

<div class="row">
    <span>Receipt #</span><strong>{{ $order->order_number }}</strong>
</div>
<div class="row">
    <span>Date</span><span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
</div>
<div class="row">
    <span>Customer</span>
    <span>{{ $order->user?->name ?? $order->first_name.' '.$order->last_name }}</span>
</div>
@if($order->phone)
<div class="row">
    <span>Phone</span><span>{{ $order->phone }}</span>
</div>
@endif
<div class="row">
    <span>Served by</span><span>{{ $order->servedBy?->name ?? 'Cashier' }}</span>
</div>

<hr class="divider">

<table class="items-table">
    <thead>
        <tr>
            <th style="width:50%">Item</th>
            <th style="text-align:center">Qty</th>
            <th style="text-align:right">Price</th>
            <th style="text-align:right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name ?? $item->product?->name }}</td>
            <td style="text-align:center">{{ $item->qty }}</td>
            <td style="text-align:right">{{ number_format($item->price, 0) }}</td>
            <td style="text-align:right">{{ number_format($item->total, 0) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr class="divider">

<div class="total-section">
    <div class="row">
        <span>Subtotal</span><span>KSh {{ number_format($order->subtotal, 0) }}</span>
    </div>
    @if($order->discount > 0)
    <div class="row">
        <span>Discount</span><span>- KSh {{ number_format($order->discount, 0) }}</span>
    </div>
    @endif
    <div class="row grand-total" style="margin-top:.4rem;padding-top:.4rem;border-top:1px solid #111">
        <span>TOTAL</span><span>KSh {{ number_format($order->total, 0) }}</span>
    </div>
    <div class="row" style="margin-top:.3rem;color:#555">
        <span>Payment</span><span>{{ strtoupper($order->payment_method) }}</span>
    </div>
    <div class="row" style="color:#555">
        <span>Status</span>
        <span>{{ strtoupper($order->payment_status) }}</span>
    </div>
</div>

<hr class="divider">

<div class="footer">
    Thank you for shopping with us!<br>
    Goods once sold are not returnable.<br>
    <strong>American Beauty Store</strong>
</div>

<div class="no-print" style="margin-top:1.5rem;text-align:center">
    <button onclick="window.print()"
        style="padding:.6rem 1.5rem;background:#111;color:#fff;border:none;border-radius:8px;font-size:.9rem;cursor:pointer">
        <i class="fas fa-print"></i> Print Receipt
    </button>
    <button onclick="window.close()"
        style="padding:.6rem 1.5rem;background:#eee;color:#333;border:none;border-radius:8px;font-size:.9rem;cursor:pointer;margin-left:.5rem">
        Close
    </button>
</div>

<script>
    // Auto-print when opened
    window.addEventListener('load', () => setTimeout(() => window.print(), 500));
</script>
</body>
</html>
