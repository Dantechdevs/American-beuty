@extends('layouts.app')
@section('title','Checkout')

@push('styles')
<style>
:root{--rose:#c8847a;--cream:#faf7f4;--sand:#f0e8df;--charcoal:#2c2c2c;--border:#e8ddd6;}
.checkout-wrap{max-width:1100px;margin:2.5rem auto;padding:0 1.5rem;display:grid;grid-template-columns:1fr 360px;gap:2rem;}
h1.page-title{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:400;margin-bottom:1.5rem;}
.checkout-section{background:#fff;border-radius:16px;padding:1.8rem;border:1px solid var(--border);margin-bottom:1.2rem;}
.checkout-section h2{font-family:'Cormorant Garamond',serif;font-size:1.3rem;margin-bottom:1.2rem;padding-bottom:.6rem;border-bottom:1px solid var(--border);}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
.form-grid.full{grid-template-columns:1fr;}
.form-group{display:flex;flex-direction:column;gap:.35rem;}
.form-group label{font-size:.82rem;font-weight:600;color:#555;letter-spacing:.04em;}
.form-group input,.form-group select,.form-group textarea{padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.9rem;font-family:inherit;transition:border-color .2s;background:#fff;}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:var(--rose);}
.form-error{font-size:.78rem;color:#e74c3c;margin-top:.2rem;}

/* PAYMENT */
.payment-options{display:flex;flex-direction:column;gap:.8rem;}
.payment-option{border:2px solid var(--border);border-radius:12px;padding:1rem 1.2rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:1rem;}
.payment-option:hover,.payment-option.selected{border-color:var(--rose);background:#fff7f6;}
.payment-option input[type=radio]{accent-color:var(--rose);}
.payment-icon{font-size:1.6rem;width:40px;text-align:center;}
.payment-label{font-weight:600;font-size:.92rem;}
.payment-desc{font-size:.78rem;color:#888;margin-top:.15rem;}
.mpesa-phone-field{display:none;margin-top:.8rem;padding:1rem;background:var(--sand);border-radius:10px;}
.mpesa-phone-field.show{display:block;}

/* ORDER SUMMARY */
.order-summary{background:#fff;border-radius:16px;padding:1.5rem;border:1px solid var(--border);position:sticky;top:90px;}
.order-summary h3{font-family:'Cormorant Garamond',serif;font-size:1.3rem;margin-bottom:1.2rem;}
.order-items{max-height:240px;overflow-y:auto;margin-bottom:1rem;}
.order-item{display:flex;gap:.8rem;padding:.6rem 0;border-bottom:1px solid var(--border);align-items:center;}
.order-item:last-child{border:none;}
.order-item-img{width:48px;height:48px;background:var(--sand);border-radius:8px;flex-shrink:0;overflow:hidden;}
.order-item-img img{width:100%;height:100%;object-fit:cover;}
.order-item-name{font-size:.85rem;font-weight:500;flex:1;line-height:1.3;}
.order-item-qty{font-size:.78rem;color:#888;}
.order-item-price{font-size:.88rem;font-weight:600;}
.summary-row{display:flex;justify-content:space-between;font-size:.88rem;margin-bottom:.6rem;color:#666;}
.summary-row.total{font-weight:700;font-size:1rem;color:var(--charcoal);padding-top:.6rem;border-top:1px solid var(--border);margin-top:.4rem;}
.btn-place-order{width:100%;background:var(--rose);color:#fff;padding:1rem;border:none;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .2s;margin-top:1rem;letter-spacing:.02em;}
.btn-place-order:hover{background:#a05e56;}
.secure-note{text-align:center;font-size:.76rem;color:#aaa;margin-top:.7rem;}
@media(max-width:768px){.checkout-wrap{grid-template-columns:1fr;} .form-grid{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
<div class="checkout-wrap">
    <div>
        <h1 class="page-title">Checkout</h1>

        <form action="{{ route('checkout.place-order') }}" method="POST" id="checkout-form">
            @csrf

            <!-- CONTACT -->
            <div class="checkout-section">
                <h2>Contact Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $address->first_name ?? auth()->user()->name) }}" required>
                        @error('first_name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $address->last_name ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="tel" name="phone" id="phone-field" value="{{ old('phone', $address->phone ?? auth()->user()->phone) }}" placeholder="e.g. 0712345678" required>
                    </div>
                </div>
            </div>

            <!-- SHIPPING ADDRESS -->
            <div class="checkout-section">
                <h2>Shipping Address</h2>
                <div class="form-grid">
                    <div class="form-group form-grid full">
                        <label>Address Line 1 *</label>
                        <input type="text" name="address_line_1" value="{{ old('address_line_1', $address->address_line_1 ?? '') }}" required>
                    </div>
                    <div class="form-group form-grid full">
                        <label>Address Line 2</label>
                        <input type="text" name="address_line_2" value="{{ old('address_line_2', $address->address_line_2 ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" name="city" value="{{ old('city', $address->city ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>County</label>
                        <input type="text" name="county" value="{{ old('county', $address->county ?? '') }}">
                    </div>
                </div>
            </div>

            <!-- PAYMENT -->
            <div class="checkout-section">
                <h2>Payment Method</h2>
                <div class="payment-options">
                    @foreach($gateways as $gw)
                    <label class="payment-option {{ $loop->first ? 'selected' : '' }}" onclick="selectPayment('{{ $gw->slug }}', this)">
                        <input type="radio" name="payment_method" value="{{ $gw->slug }}" {{ $loop->first ? 'checked' : '' }}>
                        <div class="payment-icon">
                            @if($gw->slug == 'mpesa') 📱
                            @elseif($gw->slug == 'cod') 💵
                            @elseif($gw->slug == 'stripe') 💳
                            @else 💰
                            @endif
                        </div>
                        <div>
                            <div class="payment-label">{{ $gw->name }}</div>
                            <div class="payment-desc">
                                @if($gw->slug == 'mpesa') STK Push sent to your phone — enter your M-PESA PIN
                                @elseif($gw->slug == 'cod') Pay cash when your order arrives
                                @elseif($gw->slug == 'stripe') Pay securely by card (Visa, Mastercard)
                                @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="mpesa-phone-field {{ $gateways->first()?->slug === 'mpesa' ? 'show' : '' }}" id="mpesa-phone-field">
                    <label style="font-size:.82rem;font-weight:600;color:#555;display:block;margin-bottom:.4rem">M-PESA Phone Number</label>
                    <input type="tel" id="mpesa-phone" placeholder="0712 345 678" style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.9rem;font-family:inherit;">
                    <p style="font-size:.75rem;color:#888;margin-top:.4rem">Enter the number to receive the STK push. You will be prompted to enter your M-PESA PIN.</p>
                </div>

                <div class="form-group" style="margin-top:1rem">
                    <label>Order Notes (optional)</label>
                    <textarea name="notes" rows="2" placeholder="Any special instructions..."></textarea>
                </div>
            </div>

            <button type="submit" class="btn-place-order" id="place-order-btn">Place Order →</button>
        </form>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="order-summary">
        <h3>Your Order</h3>
        <div class="order-items">
            @foreach(app(\App\Services\CartService::class)->items() as $item)
            <div class="order-item">
                <div class="order-item-img">
                    @if($item->product->thumbnail)
                        <img src="{{ asset('storage/'.$item->product->thumbnail) }}" alt="">
                    @endif
                </div>
                <div style="flex:1">
                    <div class="order-item-name">{{ $item->product->name }}</div>
                    <div class="order-item-qty">Qty: {{ $item->quantity }}</div>
                </div>
                <div class="order-item-price">KSh {{ number_format($item->product->getCurrentPrice() * $item->quantity, 0) }}</div>
            </div>
            @endforeach
        </div>
        <div class="summary-row"><span>Subtotal</span><span>KSh {{ number_format($subtotal, 0) }}</span></div>
        <div class="summary-row"><span>Shipping</span><span>{{ $shipping == 0 ? 'Free' : 'KSh '.number_format($shipping,0) }}</span></div>
        @if(session('coupon_discount'))
            <div class="summary-row" style="color:green"><span>Discount</span><span>-KSh {{ number_format(session('coupon_discount'),0) }}</span></div>
        @endif
        <div class="summary-row total">
            <span>Total</span>
            <span>KSh {{ number_format($total - session('coupon_discount',0), 0) }}</span>
        </div>
        <p class="secure-note">🔒 Secure & Encrypted Checkout</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectPayment(slug, el) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    const mpesaField = document.getElementById('mpesa-phone-field');
    if (slug === 'mpesa') {
        mpesaField.classList.add('show');
    } else {
        mpesaField.classList.remove('show');
    }
}

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const method = document.querySelector('input[name=payment_method]:checked')?.value;
    if (method === 'mpesa') {
        const mpesaPhone = document.getElementById('mpesa-phone').value.trim();
        const mainPhone  = document.getElementById('phone-field');
        if (mpesaPhone) {
            mainPhone.value = mpesaPhone;
        }
    }
    document.getElementById('place-order-btn').textContent = 'Processing...';
    document.getElementById('place-order-btn').disabled = true;
});
</script>
@endpush
