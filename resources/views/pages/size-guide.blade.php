@extends('layouts.app')
@section('title','Size Guide – American Beauty')
@section('content')
<div class="container" style="max-width:860px;margin:3rem auto;padding:0 1.2rem">
    <h1 style="color:var(--purple-dk);margin-bottom:1.5rem">Size Guide</h1>
    <h3>Skincare & Serums</h3>
    <table style="width:100%;border-collapse:collapse;margin-top:.75rem">
        <thead style="background:var(--purple-dk);color:#fff">
            <tr><th style="padding:.6rem 1rem;text-align:left">Size</th><th style="padding:.6rem 1rem;text-align:left">Volume</th><th style="padding:.6rem 1rem;text-align:left">Best For</th></tr>
        </thead>
        <tbody>
            <tr style="border-bottom:1px solid #eee"><td style="padding:.6rem 1rem">Travel</td><td style="padding:.6rem 1rem">15–30 ml</td><td style="padding:.6rem 1rem">Trying a new product</td></tr>
            <tr style="border-bottom:1px solid #eee"><td style="padding:.6rem 1rem">Standard</td><td style="padding:.6rem 1rem">50–75 ml</td><td style="padding:.6rem 1rem">Daily routine (1–2 months)</td></tr>
            <tr><td style="padding:.6rem 1rem">Full Size</td><td style="padding:.6rem 1rem">100–150 ml</td><td style="padding:.6rem 1rem">Best value, 3+ months</td></tr>
        </tbody>
    </table>
    <p style="margin-top:1.5rem;color:#666">Not sure which size to pick? <a href="{{ route('contact') }}" style="color:var(--pink-lt)">Contact us</a> and we'll help you choose.</p>
</div>
@endsection
