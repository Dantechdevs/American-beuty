@extends('layouts.app')
@section('title','Waiting for M-PESA Payment')

@push('styles')
<style>
:root{--rose:#c8847a;--sand:#f0e8df;}
.mpesa-wait{max-width:520px;margin:5rem auto;padding:0 1.5rem;text-align:center;}
.mpesa-card{background:#fff;border-radius:24px;padding:3rem 2.5rem;box-shadow:0 20px 60px rgba(0,0,0,.08);}
.mpesa-icon{font-size:4rem;margin-bottom:1.2rem;animation:pulse 1.5s ease-in-out infinite;}
@keyframes pulse{0%,100%{transform:scale(1);}50%{transform:scale(1.1);}}
.mpesa-card h2{font-family:'Cormorant Garamond',serif;font-size:1.8rem;margin-bottom:.8rem;}
.mpesa-card p{color:#666;font-size:.95rem;line-height:1.7;margin-bottom:.8rem;}
.steps{text-align:left;background:var(--sand);border-radius:14px;padding:1.2rem 1.5rem;margin:1.5rem 0;}
.steps h4{font-size:.82rem;letter-spacing:.1em;text-transform:uppercase;color:#888;margin-bottom:.8rem;}
.steps ol{padding-left:1.2rem;color:#555;font-size:.88rem;line-height:2;}
.status-indicator{display:flex;align-items:center;justify-content:center;gap:.6rem;margin:1.5rem 0;font-size:.9rem;font-weight:600;}
.spinner{width:18px;height:18px;border:2px solid #ddd;border-top-color:var(--rose);border-radius:50%;animation:spin .8s linear infinite;}
@keyframes spin{to{transform:rotate(360deg);}}
.status-text{color:var(--rose);}
.order-ref{font-size:.78rem;color:#aaa;margin-top:.5rem;}
.btn-cancel{margin-top:1rem;color:#aaa;font-size:.85rem;text-decoration:underline;cursor:pointer;background:none;border:none;font-family:inherit;}
</style>
@endpush

@section('content')
<div class="mpesa-wait">
    <div class="mpesa-card">
        <div class="mpesa-icon">📱</div>
        <h2>Check Your Phone</h2>
        <p>An M-PESA payment request has been sent to your phone. Please enter your PIN to complete the payment.</p>

        <div class="steps">
            <h4>Follow These Steps</h4>
            <ol>
                <li>Check your phone for an M-PESA prompt</li>
                <li>Enter your <strong>M-PESA PIN</strong></li>
                <li>Wait for confirmation SMS</li>
                <li>This page will update automatically ✓</li>
            </ol>
        </div>

        <div class="status-indicator">
            <div class="spinner" id="spinner"></div>
            <span class="status-text" id="status-text">Waiting for payment...</span>
        </div>

        <p class="order-ref">Order: <strong>{{ $orderNumber }}</strong></p>
        <button class="btn-cancel" onclick="window.location='{{ route("home") }}'">Cancel and return home</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
const checkoutRequestId = '{{ $checkoutRequestId }}';
const orderNumber       = '{{ $orderNumber }}';
let attempts = 0;
const maxAttempts = 60; // 5 minutes

function poll() {
    if (attempts++ > maxAttempts) {
        document.getElementById('status-text').textContent = 'Payment timed out. Please try again.';
        document.getElementById('spinner').style.display = 'none';
        return;
    }

    fetch('{{ route("checkout.mpesa.status") }}?checkout_request_id=' + checkoutRequestId)
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('status-text').textContent = '✓ Payment received! Redirecting...';
                document.getElementById('spinner').style.display = 'none';
                setTimeout(() => {
                    window.location = '/order/success/' + (data.order_number || orderNumber);
                }, 1500);
            } else if (data.status === 'failed' || data.status === 'cancelled') {
                document.getElementById('status-text').textContent = '✗ Payment ' + data.status + '. Please try again.';
                document.getElementById('spinner').style.borderTopColor = '#e74c3c';
                setTimeout(() => { window.location = '{{ route("checkout") }}'; }, 3000);
            } else {
                setTimeout(poll, 5000); // poll every 5 seconds
            }
        })
        .catch(() => setTimeout(poll, 5000));
}

setTimeout(poll, 5000); // first check after 5 seconds
</script>
@endpush
