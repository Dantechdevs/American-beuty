@extends('layouts.app')
@section('title','Your Cart')

@push('styles')
<style>
:root{--rose:#c8847a;--cream:#faf7f4;--sand:#f0e8df;--charcoal:#2c2c2c;--border:#e8ddd6;}
.cart-wrap{max-width:1100px;margin:2.5rem auto;padding:0 1.5rem;display:grid;grid-template-columns:1fr 340px;gap:2rem;}
h1.page-title{font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:400;margin-bottom:1.5rem;}
.cart-table{background:#fff;border-radius:16px;overflow:hidden;border:1px solid var(--border);}
.cart-header{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;padding:1rem 1.5rem;background:var(--sand);font-size:.78rem;letter-spacing:.1em;text-transform:uppercase;color:#888;font-weight:600;}
.cart-row{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;padding:1.2rem 1.5rem;align-items:center;border-bottom:1px solid var(--border);gap:.5rem;}
.cart-row:last-child{border-bottom:none;}
.cart-product{display:flex;align-items:center;gap:1rem;}
.cart-thumb{width:64px;height:64px;background:linear-gradient(135deg,var(--sand),#e8d5cc);border-radius:10px;overflow:hidden;flex-shrink:0;}
.cart-thumb img{width:100%;height:100%;object-fit:cover;}
.cart-thumb-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:#a08070;}
.cart-product-name{font-size:.9rem;font-weight:500;line-height:1.4;}
.cart-product-category{font-size:.75rem;color:#888;margin-top:.2rem;}
.qty-control{display:flex;align-items:center;gap:.4rem;}
.qty-btn{width:28px;height:28px;border:1px solid var(--border);background:#fff;border-radius:6px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;transition:all .2s;}
.qty-btn:hover{border-color:var(--rose);color:var(--rose);}
.qty-input{width:40px;text-align:center;border:1px solid var(--border);border-radius:6px;padding:.3rem;font-size:.9rem;font-family:inherit;}
.remove-btn{color:#ccc;background:none;border:none;cursor:pointer;font-size:.85rem;transition:color .2s;}
.remove-btn:hover{color:#e74c3c;}

.cart-summary{background:#fff;border-radius:16px;padding:1.5rem;border:1px solid var(--border);align-self:start;position:sticky;top:90px;}
.cart-summary h3{font-family:'Cormorant Garamond',serif;font-size:1.4rem;margin-bottom:1.2rem;}
.summary-row{display:flex;justify-content:space-between;font-size:.9rem;margin-bottom:.7rem;color:#666;}
.summary-row.total{font-weight:700;font-size:1.05rem;color:var(--charcoal);padding-top:.7rem;border-top:1px solid var(--border);margin-top:.5rem;}
.coupon-input{display:flex;gap:.5rem;margin:1rem 0;}
.coupon-input input{flex:1;padding:.55rem .8rem;border:1px solid var(--border);border-radius:10px;font-size:.88rem;font-family:inherit;}
.coupon-input button{padding:.55rem 1rem;background:var(--charcoal);color:#fff;border:none;border-radius:10px;cursor:pointer;font-family:inherit;font-size:.85rem;white-space:nowrap;}
.btn-checkout{width:100%;background:var(--rose);color:#fff;padding:.9rem;border:none;border-radius:12px;font-size:1rem;font-weight:600;cursor:pointer;font-family:inherit;transition:background .2s;margin-top:1rem;}
.btn-checkout:hover{background:#a05e56;}
.shipping-note{font-size:.78rem;color:#888;text-align:center;margin-top:.8rem;}
.empty-cart{text-align:center;padding:5rem 1.5rem;color:#888;}
.empty-cart .icon{font-size:4rem;margin-bottom:1.2rem;}
@media(max-width:768px){.cart-wrap{grid-template-columns:1fr;} .cart-header,.cart-row{grid-template-columns:2fr 1fr 1fr;} .cart-row>*:last-child{display:none;}}
</style>
@endpush

@section('content')
<div class="cart-wrap">
    <div>
        <h1 class="page-title">Shopping Bag</h1>
        @if($items->isEmpty())
            <div class="empty-cart">
                <div class="icon">🛍️</div>
                <h3>Your bag is empty</h3>
                <p style="margin:.5rem 0 1.5rem">Discover our beautiful collection and add items to your bag.</p>
                <a href="{{ route('products.index') }}" style="background:var(--rose);color:#fff;padding:.8rem 2rem;border-radius:30px;display:inline-block;font-size:.9rem">Start Shopping</a>
            </div>
        @else
            <div class="cart-table">
                <div class="cart-header">
                    <span>Product</span><span>Price</span><span>Quantity</span><span>Total</span>
                </div>
                @foreach($items as $item)
                <div class="cart-row">
                    <div class="cart-product">
                        <div class="cart-thumb">
                            @if($item->product->thumbnail)
                                <img src="{{ asset('storage/'.$item->product->thumbnail) }}" alt="{{ $item->product->name }}">
                            @else
                                <div class="cart-thumb-placeholder">✦</div>
                            @endif
                        </div>
                        <div>
                            <div class="cart-product-name">{{ $item->product->name }}</div>
                            <div class="cart-product-category">{{ $item->product->category->name ?? '' }}</div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="remove-btn" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                    <div>KSh {{ number_format($item->product->getCurrentPrice(), 0) }}</div>
                    <div>
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="qty-form">
                            @csrf @method('PATCH')
                            <div class="qty-control">
                                <button type="button" class="qty-btn" onclick="changeQty(this,-1)">−</button>
                                <input type="number" name="quantity" class="qty-input" value="{{ $item->quantity }}" min="1" max="99">
                                <button type="button" class="qty-btn" onclick="changeQty(this,1)">+</button>
                            </div>
                        </form>
                    </div>
                    <div>KSh {{ number_format($item->product->getCurrentPrice() * $item->quantity, 0) }}</div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:1.2rem">
                <a href="{{ route('products.index') }}" style="color:var(--rose);font-size:.9rem">← Continue Shopping</a>
            </div>
        @endif
    </div>

    @if(!$items->isEmpty())
    <div class="cart-summary">
        <h3>Order Summary</h3>
        <div class="summary-row"><span>Subtotal</span><span>KSh {{ number_format($subtotal,0) }}</span></div>
        <div class="summary-row"><span>Shipping</span><span>{{ $shipping == 0 ? 'Free' : 'KSh '.number_format($shipping,0) }}</span></div>
        @if(session('coupon_code'))
            <div class="summary-row" style="color:green"><span>Coupon ({{ session('coupon_code') }})</span><span>-KSh {{ number_format(session('coupon_discount',0),0) }}</span></div>
        @endif
        <div class="summary-row total"><span>Total</span><span>KSh {{ number_format($total - session('coupon_discount',0),0) }}</span></div>

        @auth
            @if(!session('coupon_code'))
            <form action="{{ route('checkout.coupon.apply') }}" method="POST" class="coupon-input">
                @csrf
                <input type="text" name="code" placeholder="Coupon code">
                <button type="submit">Apply</button>
            </form>
            @else
            <div style="display:flex;justify-content:space-between;align-items:center;margin:1rem 0;font-size:.85rem;background:#d4edda;padding:.6rem .9rem;border-radius:8px;color:#155724">
                <span>✓ {{ session('coupon_code') }} applied</span>
                <form action="{{ route('checkout.coupon.remove') }}" method="POST">@csrf @method('DELETE') <button style="background:none;border:none;color:#c00;cursor:pointer;font-size:.8rem">Remove</button></form>
            </div>
            @endif
        @endauth

        @auth
            <a href="{{ route('checkout') }}" class="btn-checkout" style="display:block;text-align:center;text-decoration:none;line-height:1;">Proceed to Checkout</a>
        @else
            <a href="{{ route('login') }}" class="btn-checkout" style="display:block;text-align:center;text-decoration:none;line-height:1;">Login to Checkout</a>
        @endauth

        <p class="shipping-note">🔒 Secure checkout &nbsp;|&nbsp; Free returns</p>
        <p class="shipping-note" style="margin-top:.3rem">Accepted: <strong style="color:var(--rose)">M-PESA &nbsp; COD</strong></p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function changeQty(btn, delta) {
    const form = btn.closest('.qty-form');
    const input = form.querySelector('.qty-input');
    const newVal = Math.max(1, parseInt(input.value) + delta);
    input.value = newVal;
    fetch(form.action, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({_method:'PATCH', quantity: newVal})
    }).then(r=>r.json()).then(d=>{
        if(d.success) { location.reload(); }
    });
}
</script>
@endpush
