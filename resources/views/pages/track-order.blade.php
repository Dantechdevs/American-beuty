@extends('layouts.app')
@section('title','Track My Order – American Beauty')
@section('content')
<div class="container" style="max-width:560px;margin:3rem auto;padding:0 1.2rem;text-align:center">
    <h1 style="color:var(--purple-dk);margin-bottom:.5rem">Track My Order</h1>
    <p style="color:#666;margin-bottom:2rem">Enter your order number to check your delivery status.</p>
    @auth
        <p>Visit your <a href="{{ route('orders.index') }}" style="color:var(--pink-lt);font-weight:600">My Orders</a> page to see live order status and tracking updates.</p>
    @else
        <p>Please <a href="{{ route('login') }}" style="color:var(--pink-lt);font-weight:600">log in</a> to track your order, or contact us at <a href="mailto:americanbeautyshop1@gmail.com" style="color:var(--pink-lt)">americanbeautyshop1@gmail.com</a> / <a href="tel:+254722794265" style="color:var(--pink-lt)">+254 722 794 265</a>.</p>
    @endauth
</div>
@endsection
