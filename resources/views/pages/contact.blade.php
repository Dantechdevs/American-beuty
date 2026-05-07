@extends('layouts.app')
@section('title','Contact Us – American Beauty')
@section('content')
<div class="container" style="max-width:860px;margin:3rem auto;padding:0 1.2rem">
    <h1 style="color:var(--purple-dk);margin-bottom:1.5rem">Contact Us</h1>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem">
        <div>
            <h3>Get in Touch</h3>
            <p style="line-height:2;margin-top:.75rem">
                📧 <a href="mailto:americanbeautyshop1@gmail.com" style="color:var(--pink-lt)">americanbeautyshop1@gmail.com</a><br>
                📞 <a href="tel:+254722794265" style="color:var(--pink-lt)">+254 722 794 265</a><br>
                📍 Bazaar Plaza, Mezzanine 1, Rm 4<br>Biashara Street, Nairobi CBD, Kenya
            </p>
            <h3 style="margin-top:1.5rem">Business Hours</h3>
            <p style="line-height:2;margin-top:.75rem">Mon – Sat: 8:00 AM – 7:00 PM<br>Sunday: 10:00 AM – 5:00 PM</p>
        </div>
        <div>
            <iframe src="https://maps.google.com/maps?q=Bazaar+Plaza+Biashara+Street+Nairobi&output=embed"
                width="100%" height="280" style="border:0;border-radius:10px" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>
</div>
@endsection
