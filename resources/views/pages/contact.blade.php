@extends('layouts.app')
@section('title','Contact Us – American Beauty')
@section('content')
<div class="container" style="max-width:900px;margin:3rem auto;padding:0 1.2rem">
    <h1 style="color:var(--purple-dk);margin-bottom:1.5rem">Contact Us</h1>

    @if(session('success'))
        <div style="background:#d4edda;color:#155724;padding:1rem 1.2rem;border-radius:8px;margin-bottom:1.5rem">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2.5rem">

        {{-- Contact Info --}}
        <div>
            <h3 style="margin-bottom:1rem">Get in Touch</h3>
            <p style="line-height:2.2">
                📧 <a href="mailto:americanbeautyshop1@gmail.com" style="color:var(--pink-lt)">americanbeautyshop1@gmail.com</a><br>
                📞 <a href="tel:+254722794265" style="color:var(--pink-lt)">+254 722 794 265</a><br>
                💬 <a href="https://wa.me/254722794265" target="_blank" style="color:var(--pink-lt)">WhatsApp Us</a><br>
                📍 Bazaar Plaza, Mezzanine 1 Rm 4<br>
                &nbsp;&nbsp;&nbsp;&nbsp;Biashara Street, Nairobi CBD
            </p>
            <h3 style="margin-top:1.5rem;margin-bottom:.5rem">Business Hours</h3>
            <p style="line-height:2">
                Mon – Sat: 8:00 AM – 7:00 PM<br>
                Sunday: 10:00 AM – 5:00 PM
            </p>
            <div style="margin-top:1.5rem">
                <iframe src="https://maps.google.com/maps?q=Bazaar+Plaza+Biashara+Street+Nairobi&output=embed"
                    width="100%" height="220" style="border:0;border-radius:10px" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>

        {{-- Contact Form --}}
        <div>
            <h3 style="margin-bottom:1rem">Send Us a Message</h3>
            <form action="{{ route('contact.send') }}" method="POST" style="display:flex;flex-direction:column;gap:1rem">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required
                        style="width:100%;padding:.7rem 1rem;border:1px solid #ddd;border-radius:8px;font-size:.95rem">
                    @error('name')<span style="color:red;font-size:.8rem">{{ $message }}</span>@enderror
                </div>
                <div>
                    <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required
                        style="width:100%;padding:.7rem 1rem;border:1px solid #ddd;border-radius:8px;font-size:.95rem">
                    @error('email')<span style="color:red;font-size:.8rem">{{ $message }}</span>@enderror
                </div>
                <div>
                    <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}" required
                        style="width:100%;padding:.7rem 1rem;border:1px solid #ddd;border-radius:8px;font-size:.95rem">
                    @error('subject')<span style="color:red;font-size:.8rem">{{ $message }}</span>@enderror
                </div>
                <div>
                    <textarea name="message" placeholder="Your message..." rows="5" required
                        style="width:100%;padding:.7rem 1rem;border:1px solid #ddd;border-radius:8px;font-size:.95rem;resize:vertical">{{ old('message') }}</textarea>
                    @error('message')<span style="color:red;font-size:.8rem">{{ $message }}</span>@enderror
                </div>
                <button type="submit"
                    style="background:var(--pink-lt);color:#fff;border:none;padding:.75rem 2rem;border-radius:8px;font-size:1rem;font-weight:600;cursor:pointer">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
