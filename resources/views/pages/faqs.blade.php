@extends('layouts.app')
@section('title','FAQs – American Beauty')
@section('content')
<div class="container" style="max-width:860px;margin:3rem auto;padding:0 1.2rem">
    <h1 style="color:var(--purple-dk);margin-bottom:1.5rem">Frequently Asked Questions</h1>

    <details style="margin-bottom:1rem;border:1px solid #e0d6f7;border-radius:8px;padding:1rem">
        <summary style="font-weight:600;cursor:pointer">How long does delivery take?</summary>
        <p style="margin-top:.75rem;color:#555">Nairobi CBD: same day or next day. Rest of Kenya: 2–4 business days via courier.</p>
    </details>
    <details style="margin-bottom:1rem;border:1px solid #e0d6f7;border-radius:8px;padding:1rem">
        <summary style="font-weight:600;cursor:pointer">What payment methods do you accept?</summary>
        <p style="margin-top:.75rem;color:#555">M-PESA, Visa, and Mastercard.</p>
    </details>
    <details style="margin-bottom:1rem;border:1px solid #e0d6f7;border-radius:8px;padding:1rem">
        <summary style="font-weight:600;cursor:pointer">Are your products authentic?</summary>
        <p style="margin-top:.75rem;color:#555">Yes. All products are sourced directly from authorised distributors and brand partners.</p>
    </details>
    <details style="margin-bottom:1rem;border:1px solid #e0d6f7;border-radius:8px;padding:1rem">
        <summary style="font-weight:600;cursor:pointer">Can I return a product?</summary>
        <p style="margin-top:.75rem;color:#555">Yes, within 7 days of delivery if the product is unopened. See our <a href="{{ route('returns-refunds') }}" style="color:var(--pink-lt)">Returns & Refunds</a> policy.</p>
    </details>
    <details style="margin-bottom:1rem;border:1px solid #e0d6f7;border-radius:8px;padding:1rem">
        <summary style="font-weight:600;cursor:pointer">How do I track my order?</summary>
        <p style="margin-top:.75rem;color:#555">Visit the <a href="{{ route('track-order') }}" style="color:var(--pink-lt)">Track My Order</a> page and enter your order number.</p>
    </details>
</div>
@endsection
