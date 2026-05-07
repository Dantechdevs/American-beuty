@extends('layouts.app')
@section('title','Terms of Service – American Beauty')
@section('content')
<div class="container" style="max-width:860px;margin:3rem auto;padding:0 1.2rem">
    <h1 style="color:var(--purple-dk);margin-bottom:.5rem">Terms of Service</h1>
    <p style="color:#888;margin-bottom:2rem">Last updated: {{ date('F Y') }}</p>

    <h3>1. Acceptance</h3>
    <p style="margin:.75rem 0 1.2rem">By using americanbeauty.co.ke you agree to these terms. If you disagree, please do not use the site.</p>

    <h3>2. Products & Pricing</h3>
    <p style="margin:.75rem 0 1.2rem">All prices are in Kenyan Shillings (KES) and include VAT where applicable. We reserve the right to change prices at any time. Orders are confirmed only after successful payment.</p>

    <h3>3. Delivery</h3>
    <p style="margin:.75rem 0 1.2rem">We deliver across Kenya. Delivery timelines are estimates and may vary. See our <a href="{{ route('shipping-policy') }}" style="color:var(--pink-lt)">Shipping Policy</a> for details.</p>

    <h3>4. Returns</h3>
    <p style="margin:.75rem 0 1.2rem">Returns are accepted within 7 days for unopened products. See our <a href="{{ route('returns-refunds') }}" style="color:var(--pink-lt)">Returns & Refunds Policy</a>.</p>

    <h3>5. Intellectual Property</h3>
    <p style="margin:.75rem 0 1.2rem">All content on this site — logos, images, text — is owned by American Beauty and may not be reproduced without permission.</p>

    <h3>6. Limitation of Liability</h3>
    <p style="margin:.75rem 0 1.2rem">We are not liable for any indirect or consequential losses arising from use of our products or website.</p>

    <h3>7. Governing Law</h3>
    <p style="margin:.75rem 0 1.2rem">These terms are governed by the laws of Kenya.</p>

    <h3>8. Contact</h3>
    <p style="margin:.75rem 0 1.2rem">Questions? Email <a href="mailto:americanbeautyshop1@gmail.com" style="color:var(--pink-lt)">americanbeautyshop1@gmail.com</a></p>
</div>
@endsection
