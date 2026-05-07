@extends('layouts.app')
@section('title','Privacy Policy – American Beauty')
@section('content')
<div class="container" style="max-width:860px;margin:3rem auto;padding:0 1.2rem">
    <h1 style="color:var(--purple-dk);margin-bottom:.5rem">Privacy Policy</h1>
    <p style="color:#888;margin-bottom:2rem">Last updated: {{ date('F Y') }}</p>

    <h3>1. Information We Collect</h3>
    <p style="margin:.75rem 0 1.2rem">We collect your name, email, phone number, and delivery address when you place an order or create an account. We also collect browsing data to improve your experience.</p>

    <h3>2. How We Use Your Information</h3>
    <p style="margin:.75rem 0 1.2rem">Your information is used to process orders, send delivery updates, and (with your consent) send promotional emails. We never sell your data to third parties.</p>

    <h3>3. Payment Security</h3>
    <p style="margin:.75rem 0 1.2rem">Payments via M-PESA, Visa, and Mastercard are processed through secure, encrypted channels. We do not store your card details.</p>

    <h3>4. Cookies</h3>
    <p style="margin:.75rem 0 1.2rem">We use cookies to keep you logged in and remember your cart. See our <a href="{{ route('cookie-policy') }}" style="color:var(--pink-lt)">Cookie Policy</a> for details.</p>

    <h3>5. Your Rights</h3>
    <p style="margin:.75rem 0 1.2rem">You may request access to, correction of, or deletion of your personal data at any time by contacting us at <a href="mailto:americanbeautyshop1@gmail.com" style="color:var(--pink-lt)">americanbeautyshop1@gmail.com</a>.</p>

    <h3>6. Contact</h3>
    <p style="margin:.75rem 0 1.2rem">American Beauty — Bazaar Plaza, Mezzanine 1 Rm 4, Biashara Street, Nairobi CBD, Kenya.<br>
    📞 <a href="tel:+254722794265" style="color:var(--pink-lt)">+254 722 794 265</a></p>
</div>
@endsection
