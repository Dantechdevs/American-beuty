@extends('layouts.app')
@section('title','Login')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.auth-page-wrap {
  min-height: 80vh;
  display: flex; align-items: center; justify-content: center;
  padding: 2rem 1rem;
  background: #f0f0f0;
}

.login-card {
  display: flex;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 32px rgba(0,0,0,.13);
  width: 100%; max-width: 760px;
  min-height: 490px;
}

/* LEFT IMAGE PANEL */
.img-panel {
  width: 300px; flex-shrink: 0;
  position: relative;
  background: #f9e8f0;
  overflow: hidden;
}
.img-panel .product-img {
  width: 100%; height: 100%;
  object-fit: cover; object-position: center top;
  display: block;
}
.img-panel .logo-overlay {
  position: absolute; top: 18px; left: 50%; transform: translateX(-50%);
  width: 170px;
  filter: drop-shadow(0 2px 6px rgba(0,0,0,.18));
}

/* RIGHT FORM PANEL */
.form-panel {
  flex: 1;
  padding: 2.2rem 2.4rem;
  display: flex; flex-direction: column; justify-content: center;
  font-family: 'Poppins', sans-serif;
}
.form-panel h2 {
  text-align: center;
  font-size: 1.5rem; font-weight: 700;
  color: #22c55e; margin-bottom: .25rem;
}
.form-panel .form-sub {
  text-align: center; font-size: .82rem; color: #666;
  margin-bottom: 1.4rem;
}

.lf-group { margin-bottom: .9rem; }
.lf-group label {
  display: flex; justify-content: space-between; align-items: center;
  font-size: .78rem; font-weight: 500; color: #333; margin-bottom: .3rem;
}
.lf-group label a { color: #22c55e; font-weight: 500; font-size: .74rem; text-decoration: none; }
.lf-group input[type=email],
.lf-group input[type=password] {
  width: 100%; padding: .6rem .85rem;
  border: 1.5px solid #d1d5db; border-radius: 6px;
  font-size: .88rem; font-family: 'Poppins', sans-serif;
  color: #111; background: #fff;
  transition: border-color .18s, box-shadow .18s;
}
.lf-group input:focus {
  outline: none; border-color: #22c55e;
  box-shadow: 0 0 0 3px rgba(34,197,94,.12);
}
.lf-error { font-size: .74rem; color: #e74c3c; margin-top: .25rem; display: block; }

.lf-remember {
  display: flex; justify-content: space-between; align-items: center;
  font-size: .8rem; margin-bottom: 1.1rem; color: #555;
}
.lf-remember label { display: flex; align-items: center; gap: .4rem; cursor: pointer; }
.lf-remember input[type=checkbox] { accent-color: #22c55e; }
.lf-remember a { color: #22c55e; font-size: .78rem; text-decoration: none; font-weight: 500; }

.btn-lf-signin {
  width: 100%; padding: .72rem; border: none; border-radius: 25px;
  font-size: .95rem; font-weight: 700; font-family: 'Poppins', sans-serif;
  background: #22c55e; color: #fff; cursor: pointer;
  transition: background .2s, transform .15s;
}
.btn-lf-signin:hover { background: #16a34a; transform: translateY(-1px); }

.lf-signup {
  text-align: center; margin-top: .85rem;
  font-size: .82rem; color: #555; font-family: 'Poppins', sans-serif;
}
.lf-signup a { color: #22c55e; font-weight: 700; text-decoration: none; }

.quick-login-label {
  text-align: center; font-size: .82rem; font-weight: 600;
  color: #222; margin: .95rem 0 .6rem;
  font-family: 'Poppins', sans-serif;
}
.quick-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: .45rem;
}
.quick-grid .qbtn {
  padding: .52rem .5rem; border-radius: 25px; border: none;
  font-size: .8rem; font-weight: 700; font-family: 'Poppins', sans-serif;
  cursor: pointer; color: #fff; transition: opacity .18s, transform .15s;
}
.quick-grid .qbtn:hover { opacity: .88; transform: translateY(-1px); }
.qbtn.q-admin    { background: #e91e8c; }
.qbtn.q-customer { background: #fff; color: #333; border: 1.5px solid #d1d5db; }
.qbtn.q-manager  { background: #22c55e; }
.qbtn.q-pos      { background: #2563eb; }
.qbtn.q-delivery { grid-column: 1/-1; background: linear-gradient(90deg,#a855f7,#7c3aed); }

@media(max-width:620px){
  .login-card { flex-direction: column; }
  .img-panel  { width: 100%; height: 220px; }
}
</style>
@endpush

@section('content')
<div class="auth-page-wrap">
  <div class="login-card">

    {{-- LEFT: product image + logo overlay --}}
    <div class="img-panel">
      <img class="product-img" src="{{ asset('images/logincards.png') }}" alt="American Beauty products">
    
    </div>

    {{-- RIGHT: form --}}
    <div class="form-panel">
      <h2>Sign In</h2>
      <p class="form-sub">Sign in to continue shopping</p>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="lf-group">
          <label>
            Email <span style="color:#e74c3c">*</span>
            <a href="#">Use Phone Instead</a>
          </label>
          <input type="email" name="email" id="auth-email"
            value="{{ old('email') }}" required autofocus>
          @error('email')<span class="lf-error">{{ $message }}</span>@enderror
        </div>

        <div class="lf-group">
          <label>Password <span style="color:#e74c3c">*</span></label>
          <input type="password" name="password" id="auth-pass" required>
          @error('password')<span class="lf-error">{{ $message }}</span>@enderror
        </div>

        <div class="lf-remember">
          <label><input type="checkbox" name="remember"> Remember Me</label>
          <a href="{{ route('password.request') }}">Forgot Password</a>
        </div>

        <button type="submit" class="btn-lf-signin">Sign In</button>
      </form>

      <div class="lf-signup">
        Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
      </div>

      <div class="quick-login-label">Quick Login</div>

      <div class="quick-grid">
        <button class="qbtn q-admin"    onclick="quickFill('admin@americanbeauty.com','password')">Admin</button>
        <button class="qbtn q-customer" onclick="quickFill('customer@example.com','password')">Customer</button>
        <button class="qbtn q-manager"  onclick="quickFill('manager@americanbeauty.com','password')">Manager</button>
        <button class="qbtn q-pos"      onclick="quickFill('pos@americanbeauty.com','password')">POS Operator</button>
        <button class="qbtn q-delivery" onclick="quickFill('delivery@americanbeauty.com','password')">Delivery Personnel</button>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
function quickFill(email, pass) {
  document.getElementById('auth-email').value = email;
  document.getElementById('auth-pass').value  = pass;
  ['auth-email','auth-pass'].forEach(id => {
    const el = document.getElementById(id);
    el.style.borderColor = '#22c55e';
    el.style.boxShadow   = '0 0 0 3px rgba(34,197,94,.15)';
    setTimeout(() => { el.style.borderColor=''; el.style.boxShadow=''; }, 1200);
  });
}
</script>
@endpush