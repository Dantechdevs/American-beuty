@extends('layouts.app')
@section('title','Forgot Password')

@push('styles')
<style>
:root{--rose:#c8847a;--border:#e8ddd6;}
.auth-wrap{min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem 1.5rem;background:linear-gradient(135deg,#fdf0ec,#f5e6de);}
.auth-card{background:#fff;border-radius:24px;padding:2.8rem;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(200,132,122,.15);}
.auth-logo{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:600;text-align:center;margin-bottom:.4rem;}
.auth-logo span{color:var(--rose);}
.auth-subtitle{text-align:center;color:#888;font-size:.88rem;margin-bottom:2rem;line-height:1.6;}
.form-group{display:flex;flex-direction:column;gap:.35rem;margin-bottom:1rem;}
.form-group label{font-size:.82rem;font-weight:600;color:#555;}
.form-group input{padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:12px;font-size:.92rem;font-family:inherit;transition:border-color .2s;}
.form-group input:focus{outline:none;border-color:var(--rose);}
.btn-auth{width:100%;background:var(--rose);color:#fff;padding:.85rem;border:none;border-radius:12px;font-size:1rem;font-weight:600;cursor:pointer;font-family:inherit;margin-top:.5rem;}
.auth-footer{text-align:center;margin-top:1.5rem;font-size:.88rem;color:#666;}
.auth-footer a{color:var(--rose);font-weight:500;}
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-logo">American<span>Beauty</span></div>
        <p class="auth-subtitle">Enter your email address and we'll send you a link to reset your password.</p>
        <form method="POST" action="#">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required autofocus>
            </div>
            <button type="submit" class="btn-auth">Send Reset Link</button>
        </form>
        <div class="auth-footer"><a href="{{ route('login') }}">← Back to login</a></div>
    </div>
</div>
@endsection
