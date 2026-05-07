@extends('layouts.app')
@section('title','Cookie Policy – American Beauty')
@section('content')
<div class="container" style="max-width:860px;margin:3rem auto;padding:0 1.2rem">
    <h1 style="color:var(--purple-dk);margin-bottom:.5rem">Cookie Policy</h1>
    <p style="color:#888;margin-bottom:2rem">Last updated: {{ date('F Y') }}</p>

    <h3>What Are Cookies</h3>
    <p style="margin:.75rem 0 1.2rem">Cookies are small text files stored on your device when you visit our website. They help us provide a better experience.</p>

    <h3>Cookies We Use</h3>
    <table style="width:100%;border-collapse:collapse;margin:.75rem 0 1.2rem">
        <thead style="background:var(--purple-dk);color:#fff">
            <tr>
                <th style="padding:.6rem 1rem;text-align:left">Cookie</th>
                <th style="padding:.6rem 1rem;text-align:left">Purpose</th>
                <th style="padding:.6rem 1rem;text-align:left">Duration</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border-bottom:1px solid #eee">
                <td style="padding:.6rem 1rem">Session cookie</td>
                <td style="padding:.6rem 1rem">Keeps you logged in</td>
                <td style="padding:.6rem 1rem">Session</td>
            </tr>
            <tr style="border-bottom:1px solid #eee">
                <td style="padding:.6rem 1rem">CSRF token</td>
                <td style="padding:.6rem 1rem">Security — prevents form attacks</td>
                <td style="padding:.6rem 1rem">Session</td>
            </tr>
            <tr>
                <td style="padding:.6rem 1rem">Cart cookie</td>
                <td style="padding:.6rem 1rem">Remembers your cart items</td>
                <td style="padding:.6rem 1rem">7 days</td>
            </tr>
        </tbody>
    </table>

    <h3>Managing Cookies</h3>
    <p style="margin:.75rem 0 1.2rem">You can disable cookies in your browser settings. Note that disabling cookies may affect site functionality such as login and cart.</p>

    <h3>Contact</h3>
    <p style="margin:.75rem 0">Questions? Email <a href="mailto:americanbeautyshop1@gmail.com" style="color:var(--pink-lt)">americanbeautyshop1@gmail.com</a></p>
</div>
@endsection
