@extends('layouts.app')
@section('title','Login')

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

.login-card {
  display: flex;
  border-radius: 22px;
  overflow: hidden;
  border: 1.5px solid rgba(255,10,108,.22);
  width: 100%; max-width: 820px;
  min-height: 560px;
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

/* Brand text — sits above gradient */
.panel-brand {
  position: relative;
  z-index: 2;
  text-align: center;
  padding: 0 1.2rem 1.8rem;
}
.panel-brand-name {
  font-size: 22px; font-weight: 900; color: #fff;
  letter-spacing: .01em; text-shadow: 0 2px 12px rgba(0,0,0,.6);
}
.panel-brand-name span { color: #FF0A6C; }
.panel-tagline {
  font-size: 10px; color: rgba(255,255,255,.65);
  letter-spacing: .1em; text-transform: uppercase; margin-top: 5px;
  text-shadow: 0 1px 6px rgba(0,0,0,.5);
}
.panel-dots { display:flex; gap:5px; justify-content:center; margin-top:10px; }
.panel-dots .pd { width:6px; height:6px; border-radius:50%; background:rgba(255,255,255,.25); }
.panel-dots .pd.on { background:#FF0A6C; width:20px; border-radius:3px; }

/* ── RIGHT FORM PANEL ── */
.form-panel {
  flex:1; background:#12002A;
  padding: 2rem 2.4rem;
  display:flex; flex-direction:column; justify-content:center;
}
.f-head { text-align:center; margin-bottom:1.4rem; }
.f-head h2 { font-size:24px; font-weight:900; color:#fff; }
.f-head h2 span { color:#FF0A6C; }
.f-head p { font-size:12px; color:rgba(255,255,255,.35); margin-top:4px; }

.lf-label {
  display:flex; justify-content:space-between; align-items:center;
  font-size:10px; font-weight:700; color:rgba(255,255,255,.42);
  text-transform:uppercase; letter-spacing:.07em; margin-bottom:6px;
}
.lf-label a {
  color:#FF6FB0; text-decoration:none; text-transform:none;
  font-size:11px; font-weight:600;
  display:flex; align-items:center; gap:3px;
  transition: color .2s;
}
.lf-label a:hover { color:#ff3d8e; }

.lf-input-wrap { position:relative; margin-bottom:13px; }

/* ── Input icons (email / lock) ── */
.lf-input-wrap .lf-ico {
  position:absolute; left:13px; top:50%; transform:translateY(-50%);
  width:15px; height:15px; pointer-events:none;
  color: #FF6FB0;
  opacity: .75;
  transition: opacity .2s, color .2s;
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
.lf-input-wrap input:focus ~ .lf-ico,
.lf-input-wrap input:focus + .lf-ico {
  opacity: 1;
  color: #FF0A6C;
}
.lf-error { font-size:.7rem; color:#ff7070; margin-top:3px; display:block; }

/* ── Eye toggle button ── */
.lf-eye {
  position:absolute; right:10px; top:50%; transform:translateY(-50%);
  background: rgba(255,10,108,.12);
  border: 1.5px solid rgba(255,10,108,.4);
  border-radius: 8px;
  padding: 5px 6px;
  cursor:pointer;
  color: #FF6FB0;
  display:flex; align-items:center; justify-content:center;
  transition: background .2s, color .2s, border-color .2s, transform .15s;
  z-index: 2;
}
.lf-eye:hover {
  background: rgba(255,10,108,.28);
  border-color: #FF0A6C;
  color: #fff;
  transform: translateY(-50%) scale(1.08);
}
.lf-eye:active { transform: translateY(-50%) scale(.95); }
.lf-eye svg { width:15px; height:15px; display:block; }

/* shift input right padding so text doesn't sit under the eye */
.lf-input-wrap.has-eye input { padding-right: 50px; }

.lf-middle {
  display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;
}
.lf-check { display:flex; align-items:center; gap:6px; font-size:12px; color:rgba(255,255,255,.38); cursor:pointer; }
.lf-check input { accent-color:#FF0A6C; }
.lf-forgot { font-size:12px; color:#FF6FB0; text-decoration:none; font-weight:600; }

.btn-signin {
  width:100%; padding:13px; border:none; border-radius:50px;
  font-size:14px; font-weight:900; font-family:'Poppins',sans-serif;
  background:#FF0A6C; color:#fff; cursor:pointer; letter-spacing:.05em;
  transition:background .2s, transform .15s;
}
.btn-signin:hover { background:#d6005a; transform:translateY(-1px); }

.lf-signup {
  text-align:center; margin-top:11px;
  font-size:12px; color:rgba(255,255,255,.35);
}
.lf-signup a { color:#FF6FB0; font-weight:700; text-decoration:none; }

.ql-divider {
  display:flex; align-items:center; gap:8px;
  margin:16px 0 11px; font-size:10px; font-weight:700;
  color:rgba(255,255,255,.2); text-transform:uppercase; letter-spacing:.08em;
}
.ql-divider::before,.ql-divider::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.07); }

.quick-grid { display:grid; grid-template-columns:1fr 1fr; gap:7px; }

.qbtn {
  padding:10px 10px; border-radius:50px; border:none;
  font-size:11px; font-weight:800; font-family:'Poppins',sans-serif;
  cursor:pointer; letter-spacing:.03em;
  display:flex; align-items:center; justify-content:center; gap:7px;
  transition:opacity .18s, transform .15s;
}
.qbtn:hover { opacity:.85; transform:translateY(-2px); }
.qbtn:active { transform:scale(.97); }
.qbtn .qi {
  width:22px; height:22px; border-radius:50%;
  display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.qbtn .qi svg { width:11px; height:11px; }

.q-admin    { background:#FF0A6C; color:#fff; }
.q-admin .qi { background:rgba(255,255,255,.2); }
.q-customer { background:#1A0035; color:#FF6FB0; border:1.5px solid rgba(255,10,108,.45); }
.q-customer .qi { background:rgba(255,111,176,.15); }
.q-manager  { background:#FFD700; color:#3D1F00; }
.q-manager .qi { background:rgba(0,0,0,.12); }
.q-pos      { background:#7C3AED; color:#fff; }
.q-pos .qi  { background:rgba(255,255,255,.18); }
.q-delivery { grid-column:1/-1; background:#1A0035; color:#FF6FB0; border:1.5px solid rgba(255,10,108,.3); }
.q-delivery .qi { background:rgba(255,10,108,.18); }

@media(max-width:640px){
  .login-card { flex-direction:column; }
  .img-panel  { width:100%; height:280px; }
  .img-panel .product-img { height:65%; }
}
</style>
@endpush

@section('content')
<div class="auth-page-wrap">
  <div class="login-card">

    {{-- LEFT: logo image + gradient + brand text --}}
    <div class="img-panel">

      {{-- Your AB logo — fully visible --}}
      <img class="product-img"
           src="{{ asset('images/logincards.png') }}"
           alt="American Beauty Logo">

      {{-- Dark fade at the bottom for text readability --}}
      <div class="img-gradient"></div>

      {{-- Brand text over gradient --}}
      <div class="panel-brand">
        <div class="panel-brand-name">American<span>Beauty</span></div>
        <div class="panel-tagline">Love the skin you're in</div>
        <div class="panel-dots">
          <span class="pd on"></span><span class="pd"></span><span class="pd"></span>
        </div>
      </div>
    </div>

    {{-- RIGHT: login form --}}
    <div class="form-panel">
      <div class="f-head">
        <h2>Sign <span>In</span></h2>
        <p>Welcome back — continue shopping</p>
      </div>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- EMAIL / PHONE LABEL --}}
        <div class="lf-label" id="input-label-row">
          <span id="input-label-text">Email address</span>
          <a href="#" id="toggle-mode-link" onclick="toggleInputMode(event)">
            {{-- Phone icon (shown when in email mode — click to switch to phone) --}}
            <svg id="toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/>
            </svg>
            <span id="toggle-mode-text">Use phone instead</span>
          </a>
        </div>

        {{-- EMAIL / PHONE INPUT --}}
        <div class="lf-input-wrap" id="email-wrap">
          {{-- Icon inside input — swaps between email & phone --}}
          <svg class="lf-ico" id="input-field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/>
          </svg>
          <input type="email" name="email" id="auth-email"
            value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
          @error('email')<span class="lf-error">{{ $message }}</span>@enderror
        </div>

        {{-- PASSWORD --}}
        <div class="lf-label">Password</div>
        <div class="lf-input-wrap has-eye">
          <svg class="lf-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          <input type="password" name="password" id="auth-pass"
            placeholder="••••••••" required>

          {{-- Eye toggle — visible pink button --}}
          <button type="button" class="lf-eye" id="eye-toggle" aria-label="Toggle password visibility">
            {{-- Eye open (shown when password is hidden) --}}
            <svg id="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
            {{-- Eye closed (shown when password is visible) --}}
            <svg id="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
              <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
              <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
          </button>

          @error('password')<span class="lf-error">{{ $message }}</span>@enderror
        </div>

        <div class="lf-middle">
          <label class="lf-check"><input type="checkbox" name="remember"> Remember me</label>
          <a class="lf-forgot" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="btn-signin">Sign In</button>
      </form>

      <div class="lf-signup">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
      </div>

      <div class="ql-divider">Quick Login</div>

      <div class="quick-grid">
        <button type="button" class="qbtn q-admin" onclick="quickFill('admin@americanbeauty.com')">
          <span class="qi">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
              <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
            </svg>
          </span>
          Admin
        </button>

        <button type="button" class="qbtn q-customer" onclick="quickFill('customer@example.com')">
          <span class="qi">
            <svg viewBox="0 0 24 24" fill="none" stroke="#FF6FB0" stroke-width="2.5">
              <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
            </svg>
          </span>
          Customer
        </button>

        <button type="button" class="qbtn q-manager" onclick="quickFill('manager@americanbeauty.com')">
          <span class="qi">
            <svg viewBox="0 0 24 24" fill="none" stroke="#3D1F00" stroke-width="2.5">
              <rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 12h6M12 9v6"/>
            </svg>
          </span>
          Manager
        </button>

        <button type="button" class="qbtn q-pos" onclick="quickFill('pos@americanbeauty.com')">
          <span class="qi">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
              <rect x="2" y="6" width="20" height="14" rx="2"/><path d="M6 6V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/>
            </svg>
          </span>
          POS Operator
        </button>

        <button type="button" class="qbtn q-delivery" onclick="quickFill('delivery@americanbeauty.com')">
          <span class="qi">
            <svg viewBox="0 0 24 24" fill="none" stroke="#FF6FB0" stroke-width="2.5">
              <path d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/>
              <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
          </span>
          Delivery Personnel
        </button>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
/* ── Quick fill ── */
function quickFill(email) {
  var e = document.getElementById('auth-email');
  var p = document.getElementById('auth-pass');
  // If currently in phone mode, switch back to email first
  if (e.type === 'tel') toggleInputMode({ preventDefault: function(){} });
  e.value = email;
  p.value = 'password';
  [e, p].forEach(function(el) {
    el.style.borderColor = '#FF0A6C';
    el.style.boxShadow   = '0 0 0 3px rgba(255,10,108,.18)';
    el.style.background  = 'rgba(255,10,108,.07)';
    setTimeout(function() {
      el.style.borderColor = '';
      el.style.boxShadow   = '';
      el.style.background  = '';
    }, 1400);
  });
}

/* ── Password eye toggle ── */
document.getElementById('eye-toggle').addEventListener('click', function () {
  var input   = document.getElementById('auth-pass');
  var isHidden = input.type === 'password';
  input.type  = isHidden ? 'text' : 'password';
  document.getElementById('eye-open').style.display   = isHidden ? 'none' : '';
  document.getElementById('eye-closed').style.display = isHidden ? ''     : 'none';
});

/* ── Email ↔ Phone toggle ── */
var _inputMode = 'email'; // track current mode

function toggleInputMode(e) {
  e.preventDefault();

  var input      = document.getElementById('auth-email');
  var fieldIcon  = document.getElementById('input-field-icon');
  var labelText  = document.getElementById('input-label-text');
  var toggleText = document.getElementById('toggle-mode-text');
  var toggleIcon = document.getElementById('toggle-icon');

  if (_inputMode === 'email') {
    /* ── Switch TO phone ── */
    _inputMode        = 'phone';
    input.type        = 'tel';
    input.name        = 'phone';
    input.placeholder = '+254 7XX XXX XXX';
    input.value       = '';

    labelText.textContent  = 'Phone number';
    toggleText.textContent = 'Use email instead';

    /* Field icon → phone handset */
    fieldIcon.innerHTML = '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/>';

    /* Toggle icon → envelope */
    toggleIcon.innerHTML = '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/>';

  } else {
    /* ── Switch TO email ── */
    _inputMode        = 'email';
    input.type        = 'email';
    input.name        = 'email';
    input.placeholder = 'you@example.com';
    input.value       = '';

    labelText.textContent  = 'Email address';
    toggleText.textContent = 'Use phone instead';

    /* Field icon → envelope */
    fieldIcon.innerHTML = '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/>';

    /* Toggle icon → phone handset */
    toggleIcon.innerHTML = '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/>';
  }

  /* Briefly highlight the field to draw attention */
  input.style.borderColor = '#FF0A6C';
  input.style.boxShadow   = '0 0 0 3px rgba(255,10,108,.15)';
  input.focus();
  setTimeout(function() {
    input.style.borderColor = '';
    input.style.boxShadow   = '';
  }, 900);
}
</script>
@endpush