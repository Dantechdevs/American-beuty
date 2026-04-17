@extends('layouts.app')
@section('title','Login')

@push('styles')
<style>
:root{--rose:#c8847a;--sand:#f0e8df;--border:#e8ddd6;--charcoal:#2c2c2c;}
.auth-wrap{min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem 1.5rem;background:linear-gradient(135deg,#fdf0ec,#f5e6de);}
.auth-card{background:#fff;border-radius:24px;padding:2.8rem;width:100%;max-width:440px;box-shadow:0 20px 60px rgba(200,132,122,.15);}
.auth-logo{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:600;text-align:center;margin-bottom:.4rem;}
.auth-logo span{color:var(--rose);}
.auth-subtitle{text-align:center;color:#888;font-size:.88rem;margin-bottom:2rem;}
.form-group{display:flex;flex-direction:column;gap:.35rem;margin-bottom:1rem;}
.form-group label{font-size:.82rem;font-weight:600;color:#555;letter-spacing:.04em;}
.form-group input{padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:12px;font-size:.92rem;font-family:inherit;transition:border-color .2s;}
.form-group input:focus{outline:none;border-color:var(--rose);}
.form-error{font-size:.77rem;color:#e74c3c;}
.btn-auth{width:100%;background:var(--rose);color:#fff;padding:.85rem;border:none;border-radius:12px;font-size:1rem;font-weight:600;cursor:pointer;font-family:inherit;transition:background .2s;margin-top:.5rem;}
.btn-auth:hover{background:#a05e56;}
.auth-footer{text-align:center;margin-top:1.5rem;font-size:.88rem;color:#666;}
.auth-footer a{color:var(--rose);font-weight:500;}
.remember-row{display:flex;justify-content:space-between;align-items:center;font-size:.85rem;margin-bottom:.5rem;}
.remember-row label{display:flex;align-items:center;gap:.4rem;cursor:pointer;color:#555;}
.remember-row a{color:var(--rose);}
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-logo">American<span>Beauty</span></div>
        <p class="auth-subtitle">Sign in to your account</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required autofocus>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="remember-row">
                <label><input type="checkbox" name="remember"> Remember me</label>
                <a href="{{ route('password.request') }}">Forgot password?</a>
            </div>
            <button type="submit" class="btn-auth">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
    </div>
</div>
@endsection
