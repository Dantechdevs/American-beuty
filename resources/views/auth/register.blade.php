@extends('layouts.app')
@section('title','Create Account')

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

.register-card {
  display: flex;
  border-radius: 22px;
  overflow: hidden;
  border: 1.5px solid rgba(255,10,108,.22);
  width: 100%; max-width: 820px;
}

/* ── LEFT IMAGE PANEL ── */
.img-panel {
  width: 300px; flex-shrink: 0;
  background: #1A0035;
  position: relative;
  display: flex; flex-direction: column;
  align-items: center; justify-content: flex-end;
  padding-bottom: 32px; overflow: hidden;
}
.img-panel::before {
  content:''; position:absolute;
  width:280px; height:280px; border-radius:50%;
  background:#FF0A6C; opacity:.12; top:-80px; left:-80px;
}
.img-panel::after {
  content:''; position:absolute;
  width:200px; height:200px; border-radius:50%;
  background:#FFD700; opacity:.08; bottom:60px; right:-60px;
}
.img-glow3 {
  position:absolute; width:120px; height:120px; border-radius:50%;
  background:#7C3AED; opacity:.14; bottom:-20px; left:10px;
}
.img-panel .product-img {
  position:absolute; inset:0;
  width:100%; height:100%;
  object-fit:cover; object-position:center top; display:block;
}
.panel-brand { position:relative; z-index:2; text-align:center; }
.panel-brand-name { font-size:22px; font-weight:900; color:#fff; }
.panel-brand-name span { color:#FF0A6C; }
.panel-tagline {
  font-size:10px; color:rgba(255,255,255,.38);
  letter-spacing:.1em; text-transform:uppercase; margin-top:5px;
}
.panel-dots { display:flex; gap:5px; justify-content:center; margin-top:12px; }
.panel-dots .pd { width:6px; height:6px; border-radius:50%; background:rgba(255,255,255,.2); }
.panel-dots .pd.on { background:#FF0A6C; width:20px; border-radius:3px; }

/* ── RIGHT FORM PANEL ── */
.form-panel {
  flex:1; background:#12002A;
  padding: 2rem 2.2rem;
  display:flex; flex-direction:column; justify-content:center;
}
.f-head { text-align:center; margin-bottom:1.2rem; }
.f-head h2 { font-size:22px; font-weight:900; color:#fff; }
.f-head h2 span { color:#FF0A6C; }
.f-head p { font-size:12px; color:rgba(255,255,255,.35); margin-top:4px; }

/* Perks strip */
.perks-strip {
  display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:1.3rem;
}
.perk {
  background:rgba(255,10,108,.08);
  border:1px solid rgba(255,10,108,.15);
  border-radius:10px; padding:10px 8px; text-align:center;
}
.perk-icon {
  width:28px; height:28px; border-radius:50%;
  background:#FF0A6C;
  display:flex; align-items:center; justify-content:center; margin:0 auto 5px;
}
.perk-icon svg { width:13px; height:13px; }
.perk-text { font-size:10px; color:rgba(255,255,255,.48); line-height:1.4; }

/* Form fields */
.row-2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.fg { margin-bottom:11px; }

.rf-label {
  display:block; font-size:10px; font-weight:700;
  color:rgba(255,255,255,.42); text-transform:uppercase;
  letter-spacing:.07em; margin-bottom:5px;
}
.rf-input-wrap { position:relative; }
.rf-input-wrap .rf-ico {
  position:absolute; left:13px; top:50%; transform:translateY(-50%);
  width:14px; height:14px; opacity:.28; pointer-events:none;
}
.rf-input-wrap input {
  width:100%; padding:10px 12px 10px 40px;
  background:rgba(255,255,255,.06);
  border:1.5px solid rgba(255,255,255,.09); border-radius:11px;
  font-size:13px; font-family:'Poppins',sans-serif;
  color:#fff; outline:none;
  transition:border-color .2s, background .2s;
}
.rf-input-wrap input::placeholder { color:rgba(255,255,255,.2); }
.rf-input-wrap input:focus {
  border-color:#FF0A6C;
  background:rgba(255,10,108,.07);
  box-shadow:0 0 0 3px rgba(255,10,108,.1);
}
.rf-error { font-size:.7rem; color:#ff7070; margin-top:3px; display:block; }

/* Terms */
.terms-row {
  display:flex; align-items:flex-start; gap:8px;
  font-size:11px; color:rgba(255,255,255,.35);
  margin:12px 0 14px; line-height:1.5;
}
.terms-row input { accent-color:#FF0A6C; margin-top:2px; flex-shrink:0; }
.terms-row a { color:#FF6FB0; text-decoration:none; font-weight:600; }

.btn-register {
  width:100%; padding:13px; border:none; border-radius:50px;
  font-size:14px; font-weight:900; font-family:'Poppins',sans-serif;
  background:#FF0A6C; color:#fff; cursor:pointer; letter-spacing:.05em;
  transition:background .2s, transform .15s;
}
.btn-register:hover { background:#d6005a; transform:translateY(-1px); }

.rf-signin {
  text-align:center; margin-top:12px;
  font-size:12px; color:rgba(255,255,255,.35);
}
.rf-signin a { color:#FF6FB0; font-weight:700; text-decoration:none; }

@media(max-width:640px){
  .register-card { flex-direction:column; }
  .img-panel { width:100%; height:200px; justify-content:center; padding-bottom:0; padding-top:20px; }
  .row-2 { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
<div class="auth-page-wrap">
  <div class="register-card">

    {{-- LEFT: image + branding --}}
    <div class="img-panel">
      <div class="img-glow3"></div>
      <img class="product-img" src="{{ asset('images/logincards.png') }}" alt="American Beauty products">
      <div class="panel-brand">
        <div class="panel-brand-name">American<span>Beauty</span></div>
        <div class="panel-tagline">Love the skin you're in</div>
        <div class="panel-dots">
          <span class="pd"></span><span class="pd on"></span><span class="pd"></span>
        </div>
      </div>
    </div>

    {{-- RIGHT: form --}}
    <div class="form-panel">
      <div class="f-head">
        <h2>Create <span>Account</span></h2>
        <p>Join thousands of beauty lovers today</p>
      </div>

      {{-- Perks --}}
      <div class="perks-strip">
        <div class="perk">
          <div class="perk-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 3H8L6 7h12z"/></svg>
          </div>
          <div class="perk-text">Free shipping over KSh 3,000</div>
        </div>
        <div class="perk">
          <div class="perk-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <div class="perk-text">Earn loyalty points</div>
        </div>
        <div class="perk">
          <div class="perk-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><path d="M9 12l2 2 4-4"/><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/></svg>
          </div>
          <div class="perk-text">100% authentic products</div>
        </div>
      </div>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name + Phone row --}}
        <div class="row-2">
          <div class="fg">
            <label class="rf-label">Full Name</label>
            <div class="rf-input-wrap">
              <svg class="rf-ico" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              <input type="text" name="name" value="{{ old('name') }}" placeholder="Jane Doe" required autofocus>
            </div>
            @error('name')<span class="rf-error">{{ $message }}</span>@enderror
          </div>
          <div class="fg">
            <label class="rf-label">Phone (optional)</label>
            <div class="rf-input-wrap">
              <svg class="rf-ico" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.09 5.18 2 2 0 0 1 5.07 3h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L9.09 10.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 23 18v-.08z"/></svg>
              <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="0712 345 678">
            </div>
          </div>
        </div>

        {{-- Email --}}
        <div class="fg">
          <label class="rf-label">Email Address</label>
          <div class="rf-input-wrap">
            <svg class="rf-ico" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
          </div>
          @error('email')<span class="rf-error">{{ $message }}</span>@enderror
        </div>

        {{-- Password row --}}
        <div class="row-2">
          <div class="fg">
            <label class="rf-label">Password</label>
            <div class="rf-input-wrap">
              <svg class="rf-ico" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" name="password" placeholder="Min 8 characters" required>
            </div>
            @error('password')<span class="rf-error">{{ $message }}</span>@enderror
          </div>
          <div class="fg">
            <label class="rf-label">Confirm Password</label>
            <div class="rf-input-wrap">
              <svg class="rf-ico" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" name="password_confirmation" placeholder="Repeat password" required>
            </div>
          </div>
        </div>

        {{-- Terms --}}
        <div class="terms-row">
          <input type="checkbox" name="terms" required>
          <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a> of American Beauty</span>
        </div>

        <button type="submit" class="btn-register">Create Account</button>
      </form>

      <div class="rf-signin">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
      </div>
    </div>

  </div>
</div>
@endsection