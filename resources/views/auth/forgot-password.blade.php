@extends('layouts.app')
@section('title','Forgot Password')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap');

.auth-page-wrap {
  min-height: 85vh;
  display: flex; align-items: center; justify-content: center;
  padding: 2rem 1rem;
  background: #0D001F;
  font-family: 'Poppins', sans-serif;
}

.forgot-card {
  display: flex;
  border-radius: 22px;
  overflow: hidden;
  border: 1.5px solid rgba(255,10,108,.22);
  width: 100%; max-width: 820px;
  min-height: 480px;
}

/* ── LEFT IMAGE PANEL ── */
.img-panel {
  width: 310px; flex-shrink: 0;
  background: #f7f0fb;
  position: relative;
  display: flex; flex-direction: column;
  align-items: stretch; justify-content: flex-end;
  overflow: hidden;
}

.img-panel .product-img {
  position: absolute;
  top: 0; left: 0; right: 0;
  width: 100%; height: 73%;
  object-fit: contain;
  object-position: center center;
  display: block;
  z-index: 0;
  padding: 1rem;
}

.img-panel .img-gradient {
  position: absolute; bottom: 0; left: 0; right: 0;
  height: 35%;
  background: linear-gradient(to top, rgba(10,0,28,.97) 0%, rgba(10,0,28,.4) 70%, transparent 100%);
  z-index: 1;
}

.panel-brand {
  position: relative; z-index: 2;
  text-align: center;
  padding: 0 1.2rem 1.8rem;
}
.panel-brand-name { font-size:22px; font-weight:900; color:#fff; letter-spacing:.01em; text-shadow:0 2px 12px rgba(0,0,0,.6); }
.panel-brand-name span { color:#FF0A6C; }
.panel-tagline {
  font-size:10px; color:rgba(255,255,255,.65);
  letter-spacing:.1em; text-transform:uppercase; margin-top:5px;
  text-shadow:0 1px 6px rgba(0,0,0,.5);
}
.panel-dots { display:flex; gap:5px; justify-content:center; margin-top:10px; }
.panel-dots .pd { width:6px; height:6px; border-radius:50%; background:rgba(255,255,255,.25); }
.panel-dots .pd.on { background:#FF0A6C; width:20px; border-radius:3px; }

/* ── RIGHT FORM PANEL ── */
.form-panel {
  flex:1; background:#12002A;
  padding: 2.5rem 2.4rem;
  display:flex; flex-direction:column; justify-content:center;
}

/* Icon circle */
.fp-icon-wrap {
  width: 64px; height: 64px; border-radius: 50%;
  background: rgba(255,10,108,.12);
  border: 1.5px solid rgba(255,10,108,.3);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 1.4rem;
}
.fp-icon-wrap svg { width: 28px; height: 28px; }

.f-head { text-align:center; margin-bottom:1.6rem; }
.f-head h2 { font-size:24px; font-weight:900; color:#fff; }
.f-head h2 span { color:#FF0A6C; }
.f-head p { font-size:12px; color:rgba(255,255,255,.38); margin-top:6px; line-height:1.6; max-width:280px; margin-left:auto; margin-right:auto; }

/* Alert */
@if(session('status'))
.fp-alert {
  background: rgba(34,197,94,.1);
  border: 1px solid rgba(34,197,94,.25);
  border-radius: 11px;
  padding: 12px 16px;
  font-size: 12px; color: #6ee7a0;
  margin-bottom: 1.2rem; text-align: center; line-height: 1.5;
}
@endif

.lf-label {
  display:block; font-size:10px; font-weight:700;
  color:rgba(255,255,255,.42); text-transform:uppercase;
  letter-spacing:.07em; margin-bottom:6px;
}
.lf-input-wrap { position:relative; margin-bottom:1.4rem; }
.lf-input-wrap .lf-ico {
  position:absolute; left:13px; top:50%; transform:translateY(-50%);
  width:14px; height:14px; opacity:.28; pointer-events:none;
}
.lf-input-wrap input {
  width:100%; padding:11px 12px 11px 40px;
  background:rgba(255,255,255,.06);
  border:1.5px solid rgba(255,255,255,.09); border-radius:11px;
  font-size:13px; font-family:'Poppins',sans-serif;
  color:#fff; outline:none;
  transition:border-color .2s, background .2s;
}
.lf-input-wrap input::placeholder { color:rgba(255,255,255,.2); }
.lf-input-wrap input:focus {
  border-color:#FF0A6C;
  background:rgba(255,10,108,.07);
  box-shadow:0 0 0 3px rgba(255,10,108,.1);
}
.lf-error { font-size:.7rem; color:#ff7070; margin-top:3px; display:block; }

.btn-send {
  width:100%; padding:13px; border:none; border-radius:50px;
  font-size:14px; font-weight:900; font-family:'Poppins',sans-serif;
  background:#FF0A6C; color:#fff; cursor:pointer; letter-spacing:.05em;
  transition:background .2s, transform .15s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-send:hover { background:#d6005a; transform:translateY(-1px); }

.fp-back {
  text-align:center; margin-top:1.2rem;
  font-size:12px; color:rgba(255,255,255,.35);
}
.fp-back a { color:#FF6FB0; font-weight:700; text-decoration:none; }
.fp-back a:hover { color:#FF0A6C; }

@media(max-width:640px){
  .forgot-card { flex-direction:column; }
  .img-panel { width:100%; height:280px; }
  .img-panel .product-img { height:65%; }
}
</style>
@endpush

@section('content')
<div class="auth-page-wrap">
  <div class="forgot-card">

    {{-- LEFT: logo + branding --}}
    <div class="img-panel">
      <img class="product-img"
           src="{{ asset('images/american-logo.jpeg') }}"
           alt="American Beauty Logo">
      <div class="img-gradient"></div>
      <div class="panel-brand">
        <div class="panel-brand-name">American<span>Beauty</span></div>
        <div class="panel-tagline">Love the skin you're in</div>
        <div class="panel-dots">
          <span class="pd"></span><span class="pd"></span><span class="pd on"></span>
        </div>
      </div>
    </div>

    {{-- RIGHT: form --}}
    <div class="form-panel">

      {{-- Lock icon --}}
      <div class="fp-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="#FF0A6C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          <circle cx="12" cy="16" r="1" fill="#FF0A6C"/>
        </svg>
      </div>

      <div class="f-head">
        <h2>Reset <span>Password</span></h2>
        <p>Enter your email and we'll send you a link to get back into your account.</p>
      </div>

      {{-- Success message --}}
      @if(session('status'))
        <div class="fp-alert">
          ✓ {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label class="lf-label">Email Address</label>
        <div class="lf-input-wrap">
          <svg class="lf-ico" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/>
          </svg>
          <input type="email" name="email"
            value="{{ old('email') }}"
            placeholder="you@example.com" required autofocus>
          @error('email')<span class="lf-error">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn-send">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
            <path d="M22 2L11 13"/><path d="M22 2L15 22l-4-9-9-4 20-7z"/>
          </svg>
          Send Reset Link
        </button>
      </form>

      <div class="fp-back">
        <a href="{{ route('login') }}">← Back to Sign In</a>
      </div>

    </div>
  </div>
</div>
@endsection