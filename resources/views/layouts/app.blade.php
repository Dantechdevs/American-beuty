<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('favicon-192.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'American Beauty') — Glow Naturally</title>
    <meta name="description" content="@yield('meta_description', 'Premium skincare, cosmetics & beauty products. Delivered across Kenya.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --pink:     #FF0A6C;
            --pink-lt:  #FF6FB0;
            --pink-dk:  #d6005a;
            --bg-deep:  #0D001F;
            --bg-dark:  #12002A;
            --bg-panel: #1A0035;
            --charcoal: #2c2c2c;
            --white:    #ffffff;
            --green:    #22c55e;
            --green-dk: #16a34a;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; background:var(--bg-deep); color:#fff; overflow-x: hidden; }
        a { text-decoration:none; color:inherit; }

        /* ── TOPBAR ── */
        .topbar {
            background: linear-gradient(90deg, #15803d 0%, #16a34a 40%, #22c55e 60%, #16a34a 80%, #15803d 100%);
            border-bottom: 1px solid rgba(0,0,0,.15);
            color: #fff;
            font-size: .8rem;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            padding: .55rem 1.5rem;
            display: flex; align-items: center; justify-content: center;
            gap: 1.2rem; flex-wrap: wrap;
        }
        .topbar-item {
            display: flex; align-items: center; gap: .4rem;
            color: #fff; font-weight: 600;
        }
        .topbar-item i { color: #fff; font-size: .8rem; }
        .topbar-sep { color: rgba(255,255,255,.5); font-size: .9rem; }
        .topbar-cart-btn {
            display: flex; align-items: center; gap: .45rem;
            background: #fff; border: none; color: var(--green-dk);
            padding: .28rem .9rem; border-radius: 20px;
            font-size: .76rem; font-weight: 700; cursor: pointer;
            transition: background .2s, transform .15s;
            font-family: 'Poppins', sans-serif;
            text-decoration: none; letter-spacing: .02em;
        }
        .topbar-cart-btn:hover {
            background: #f0fdf4;
            transform: translateY(-1px);
            color: var(--green-dk);
        }
        .topbar-cart-icon { position: relative; display: flex; align-items: center; }
        .topbar-cart-icon i { font-size: .85rem; }
        .topbar-cart-mini-badge {
            position: absolute; top: -6px; right: -8px;
            background: var(--pink); color: #fff;
            font-size: .55rem; width: 13px; height: 13px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; border: 1.5px solid #fff;
        }

        /* ── NAV ── */
        nav {
            background: var(--bg-dark);
            border-bottom: 1px solid rgba(255,10,108,.18);
            position: sticky; top: 0; z-index: 100;
        }
        .nav-inner {
            max-width: 1280px; margin: auto;
            display: flex; align-items: center; justify-content: space-between;
            padding: .9rem 1.5rem; gap: 1rem;
        }
        .brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem; font-weight: 600;
            color: #fff; letter-spacing: .05em;
        }
        .brand span { color: var(--pink); }
        .nav-links { display: flex; gap: 2rem; font-size: .9rem; font-weight: 500; }
        .hamburger { display: none; }
        .nav-links a { color: rgba(255,255,255,.7); transition: color .2s; }
        .nav-links a:hover { color: #fff; }
        .nav-actions { display: flex; align-items: center; gap: 1.2rem; }
        .nav-icon {
            color: rgba(255,255,255,.7); font-size: 1.05rem;
            position: relative; cursor: pointer; transition: color .2s;
            display: flex; align-items: center;
        }
        .nav-icon:hover { color: #fff; }
        .cart-icon-wrap {
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,10,108,.12);
            border: 1px solid rgba(255,10,108,.3);
            padding: 7px 11px; border-radius: 12px; cursor: pointer;
            transition: background .2s, border-color .2s;
            color: var(--pink-lt); position: relative;
        }
        .cart-icon-wrap:hover {
            background: rgba(255,10,108,.25);
            border-color: rgba(255,10,108,.6); color: #fff;
        }
        .cart-badge {
            position: absolute; top: -7px; right: -7px;
            background: var(--pink); color: #fff;
            font-size: .6rem; width: 17px; height: 17px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; border: 2px solid var(--bg-dark);
        }
        .btn-nav-login {
            background: var(--pink); color: #fff;
            border: none; padding: .45rem 1.1rem;
            border-radius: 30px; font-size: .85rem;
            cursor: pointer; font-family: inherit; font-weight: 600;
            transition: background .2s;
        }
        .btn-nav-login:hover { background: var(--pink-dk); }

        /* ── FLASH ── */
        .flash { padding: .8rem 1.5rem; font-size: .9rem; text-align: center; }
        .flash-success { background: rgba(34,197,94,.12); color: #6ee7a0; border-bottom: 1px solid rgba(34,197,94,.2); }
        .flash-error   { background: rgba(255,10,108,.12); color: #FF6FB0; border-bottom: 1px solid rgba(255,10,108,.2); }

        /* ── FOOTER ── */
        footer {
            background: #2D0060;
            border-top: 1px solid rgba(180,100,255,.3);
            color: rgba(255,255,255,.75);
            padding: 0;
            margin-top: 5rem;
            font-family: 'Poppins', sans-serif;
        }

        /* Newsletter strip */
        .footer-newsletter-strip {
            background: #250050;
            border-bottom: 1px solid rgba(180,100,255,.2);
            padding: 1.6rem 1.5rem;
        }
        .footer-newsletter-inner {
            max-width: 1280px; margin: auto;
            display: flex; align-items: center;
            justify-content: space-between; gap: 1.5rem; flex-wrap: wrap;
        }
        .newsletter-text-group { flex: 1; min-width: 200px; }
        .newsletter-label {
            font-size: .72rem; font-weight: 700; color: #fff;
            text-transform: uppercase; letter-spacing: .12em; margin-bottom: .2rem;
        }
        .newsletter-sub {
            font-size: .78rem; color: rgba(255,255,255,.65);
            line-height: 1.4; margin: 0;
        }
        .newsletter-form {
            display: flex; flex: 0 0 340px;
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 10px; overflow: hidden;
            transition: border-color .2s;
        }
        .newsletter-form:focus-within {
            border-color: #fff;
            box-shadow: 0 0 0 3px rgba(255,255,255,.12);
        }
        .newsletter-form input {
            flex: 1; padding: .6rem .9rem;
            background: rgba(255,255,255,.1);
            border: none; outline: none;
            font-size: .82rem; font-family: 'Poppins', sans-serif; color: #fff;
        }
        .newsletter-form input::placeholder { color: rgba(255,255,255,.45); }
        .newsletter-form button {
            background: var(--pink); color: #fff;
            border: none; padding: .6rem 1.1rem;
            font-size: .78rem; font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer; letter-spacing: .04em;
            transition: background .2s; white-space: nowrap;
        }
        .newsletter-form button:hover { background: var(--pink-dk); }

        /* Main grid */
        .footer-grid {
            max-width: 1280px; margin: auto;
            display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 2rem; padding: 2.2rem 1.5rem 2rem;
        }
        .footer-brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.55rem; color: #fff; margin-bottom: .2rem;
        }
        .footer-brand-name span { color: var(--pink-lt); }
        .footer-tagline {
            font-size: .68rem; color: rgba(255,255,255,.7);
            letter-spacing: .12em; text-transform: uppercase;
            margin-bottom: .65rem; font-weight: 600;
        }
        .footer-desc {
            font-size: .8rem; line-height: 1.7;
            color: rgba(255,255,255,.7); margin-bottom: 1rem;
        }
        .footer h4 {
            color: #fff; margin-bottom: .9rem;
            font-size: .7rem; letter-spacing: .14em;
            text-transform: uppercase; font-weight: 700;
            padding-bottom: .5rem;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        .footer ul { list-style: none; display: flex; flex-direction: column; gap: .45rem; }
        .footer ul li a {
            color: rgba(255,255,255,.7); font-size: .82rem;
            transition: color .2s, padding-left .2s;
            display: inline-flex; align-items: center; gap: .35rem;
        }
        .footer ul li a:hover { color: #fff; padding-left: 4px; }

        /* Social */
        .social-links { display: flex; gap: 7px; margin-bottom: .9rem; }
        .social-links a {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            color: rgba(255,255,255,.8); font-size: .82rem;
            display: flex; align-items: center; justify-content: center;
            transition: background .2s, color .2s, border-color .2s, transform .18s;
        }
        .social-links a:hover {
            background: var(--pink); border-color: var(--pink);
            color: #fff; transform: translateY(-2px);
        }

        /* Pay pills */
        .pay-row { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .pay-label-inline {
            font-size: .68rem; color: rgba(255,255,255,.6);
            text-transform: uppercase; letter-spacing: .1em;
            margin-right: 2px; white-space: nowrap;
        }
        .pay-pill-img { height: 22px; width: auto; border-radius: 4px; background: #fff; padding: 2px 6px; display:inline-flex; align-items:center; }
        .pay-pill {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 5px; padding: .18rem .55rem;
            font-size: .7rem; color: #fff;
            font-weight: 600; letter-spacing: .03em;
        }

        /* Footer bottom */
        .footer-bottom {
            max-width: 1280px; margin: 0 auto;
            border-top: 1px solid rgba(255,255,255,.15);
            padding: 1rem 1.5rem;
            font-size: .74rem; color: rgba(255,255,255,.6);
            display: flex; justify-content: space-between;
            align-items: center; flex-wrap: wrap; gap: .6rem;
            background: #1E0045;
        }
        .footer-bottom-links { display: flex; gap: 1.2rem; }
        .footer-bottom-links a {
            color: rgba(255,255,255,.6); font-size: .74rem; transition: color .2s;
        }
        .footer-bottom-links a:hover { color: #fff; }
        .dev-credit {
            display: flex; align-items: center; gap: .35rem;
            font-size: .72rem; color: rgba(255,255,255,.55);
        }
        .dev-credit a { color: var(--pink-lt); font-weight: 600; transition: color .2s; }
        .dev-credit a:hover { color: #fff; }
        .dev-dot {
            width: 3px; height: 3px; border-radius: 50%;
            background: var(--pink-lt); display: inline-block;
        }

        @media(max-width:900px){
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 1.6rem; }
            .footer-newsletter-inner { flex-direction: column; align-items: flex-start; }
            .newsletter-form { flex: 0 0 100%; width: 100%; }
        }
        @media(max-width:768px){
            .nav-inner { padding: .7rem 1rem; gap: .5rem; }
            .brand { font-size: 1.35rem; }
            .nav-actions { gap: .6rem; }
            .btn-nav-login { padding: .35rem .75rem; font-size: .78rem; }
            .cart-icon-wrap { padding: 6px 9px; }
            .topbar { flex-wrap: nowrap; overflow-x: auto; justify-content: flex-start; padding: .45rem 1rem; gap: .8rem; scrollbar-width: none; }
            .topbar::-webkit-scrollbar { display: none; }
            .topbar-sep { display: none; }
            .topbar-cart-btn { flex-shrink: 0; }
            .nav-links { display: none; }
            .hamburger-desktop { display: none; }
            .hamburger { display: flex; flex-direction: column; gap: 5px; cursor: pointer; background: none; border: none; padding: .5rem; }
            .hamburger span { display: block; width: 24px; height: 2px; background: #fff; border-radius: 2px; transition: all .3s; }
            .nav-links.open { display: flex; flex-direction: column; position: absolute; top: 100%; left: 0; right: 0; background: var(--bg-dark); padding: 1.5rem; gap: 1.2rem; border-top: 1px solid rgba(255,10,108,.18); z-index: 99; }
            .topbar { gap: .5rem; font-size: .74rem; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .footer-bottom-links { justify-content: center; }
        }
        @media(max-width:480px){
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- TOPBAR --}}
    <div class="topbar">
        <span class="topbar-item">
            <i class="fas fa-truck-fast"></i>
            Free delivery on orders over KSh 3,000
        </span>
        <span class="topbar-sep">|</span>
        <span class="topbar-item">
            <i class="fas fa-mobile-screen"></i>
            Pay easily with M-PESA
        </span>
        <span class="topbar-sep">|</span>
        <span class="topbar-item">
            <i class="fas fa-shield-halved"></i>
            100% Genuine Products, Guaranteed
        </span>
        <span class="topbar-sep">|</span>
        <a href="{{ route('cart') }}" class="topbar-cart-btn">
            <span class="topbar-cart-icon">
                <i class="fas fa-bag-shopping"></i>
                <span class="topbar-cart-mini-badge" id="topbar-cart-count">
                    {{ app(\App\Services\CartService::class)->count() }}
                </span>
            </span>
            My Bag
        </a>
    </div>

    {{-- NAV --}}
    <nav>
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="brand">American<span>Beauty</span></a>
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
            <div class="nav-links" id="nav-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('products.index') }}">Shop</a>
                <a href="{{ route('products.index', ['category'=>'skincare']) }}">Skincare</a>
                <a href="{{ route('products.index', ['category'=>'makeup']) }}">Makeup</a>
                <a href="{{ route('products.index', ['filter'=>'sale']) }}">Sale</a>
            </div>
            <div class="nav-actions">
                <a href="{{ route('products.index') }}" class="nav-icon">
                    <i class="fas fa-search"></i>
                </a>
                @auth
                    <a href="{{ route('home') }}" class="nav-icon">
                        <i class="fas fa-heart"></i>
                    </a>
                @endauth
                <a href="{{ route('cart') }}" class="cart-icon-wrap">
                    <i class="fas fa-bag-shopping"></i>
                    <span class="cart-badge" id="cart-count">
                        {{ app(\App\Services\CartService::class)->count() }}
                    </span>
                </a>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('home') }}"
                       class="nav-icon" title="{{ auth()->user()->name }}">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-nav-login">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-login">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif

    @yield('content')

    {{-- FOOTER --}}
    <footer>

      {{-- Newsletter strip --}}
<div class="footer-newsletter-strip">
    <div class="footer-newsletter-inner">
        <div class="newsletter-text-group">
            <div class="newsletter-label">Stay in the glow</div>
            <p class="newsletter-sub">Exclusive deals, new arrivals & beauty tips — straight to your inbox.</p>
        </div>
        <form action="{{ route('subscribers.subscribe') }}" method="POST" class="newsletter-form" onsubmit="handleNewsletter(event)">
            @csrf
            <input type="email" name="email" placeholder="your@email.com" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>
</div>
<div class="footer-newsletter-strip" style="border-top:1px solid rgba(180,100,255,.15);">
    <div class="footer-newsletter-inner">
        <div class="newsletter-text-group">
            <div class="newsletter-label" style="color:#25D366;">&#128242; WhatsApp Updates</div>
            <p class="newsletter-sub">Get deals & beauty tips on WhatsApp.</p>
        </div>
        <form class="newsletter-form" id="whatsapp-form" onsubmit="handleWhatsapp(event)">
            @csrf
            <input type="tel" name="phone" id="whatsapp-phone" placeholder="07XXXXXXXX" maxlength="10" required>
            <button type="submit" style="background:#25D366;">Join</button>
        </form>
    </div>
</div>

        {{-- Main columns --}}
        <div class="footer-grid">

            {{-- Col 1: Brand --}}
            <div>
                <div class="footer-brand-name">American<span>Beauty</span></div>
                <div class="footer-tagline">Glow Naturally</div>
                <p class="footer-desc">Premium skincare & beauty delivered across Kenya. Authentic brands, real results.</p>
                <div class="social-links">
                    <a href="https://instagram.com/celebratewellnessspaworcester?igsh=MXc1YTZnajNib2w5dA==" target="_blank" rel="noopener" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://web.facebook.com/americanbeautysupplierskenya" target="_blank" rel="noopener" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://tiktok.com/@americanbeautyshop1" target="_blank" rel="noopener" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://wa.me/254722794265" target="_blank" rel="noopener" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.youtube.com/@americanbeuty" target="_blank" rel="noopener" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
                <div class="pay-row">
                    <span class="pay-pill-img" title="M-PESA">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/M-PESA_LOGO-01.svg/120px-M-PESA_LOGO-01.svg.png" alt="M-PESA" style="height:18px;width:auto">
                    </span>
                    <span class="pay-pill-img" title="Visa">
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="14" viewBox="0 0 38 14">
                            <text x="0" y="12" font-family="Arial Black,sans-serif" font-size="13" font-weight="900" fill="#1A1F71" letter-spacing="-0.5">VISA</text>
                        </svg>
                    </span>
                    <span class="pay-pill-img" title="Mastercard">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/120px-Mastercard-logo.svg.png" alt="Mastercard" style="height:18px;width:auto">
                    </span>
                </div>
            </div>

            {{-- Col 2: Shop --}}
            <div>
                <h4>Shop</h4>
                <ul>
                    <li><a href="{{ route('products.index', ['category'=>'skincare']) }}">Skincare</a></li>
                    <li><a href="{{ route('products.index', ['category'=>'makeup']) }}">Makeup</a></li>
                    <li><a href="{{ route('products.index', ['category'=>'haircare']) }}">Haircare</a></li>
                    <li><a href="{{ route('products.index', ['category'=>'fragrance']) }}">Fragrances</a></li>
                    <li><a href="{{ route('products.index', ['filter'=>'new']) }}">New Arrivals</a></li>
                    <li><a href="{{ route('products.index', ['filter'=>'sale']) }}">Sale</a></li>
                </ul>
            </div>

            {{-- Col 3: Help --}}
            <div>
                <h4>Help</h4>
                <ul>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                    <li><a href="{{ route('shipping-policy') }}">Shipping Policy</a></li>
                    <li><a href="{{ route('returns-refunds') }}">Returns &amp; Refunds</a></li>
                    <li><a href="{{ route('track-order') }}">Track My Order</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('size-guide') }}">Size Guide</a></li>
                </ul>
            </div>

            {{-- Col 4: Contact --}}
            <div>
                <h4>Contact</h4>
                <ul>
                    <li>
                        <a href="mailto:americanbeautyshop1@gmail.com">
                            <i class="fas fa-envelope" style="color:var(--pink-lt);font-size:.75rem;width:13px"></i>
                            americanbeautyshop1@gmail.com
                        </a>
                    </li>
                    <li>
                        <a href="tel:+254722794265">
                            <i class="fas fa-phone" style="color:var(--pink-lt);font-size:.75rem;width:13px"></i>
                            +254 722 794 265
                        </a>
                    </li>
                    <li>
                        <a href="https://maps.app.goo.gl/zuS9GfSzqCzqEX139" target="_blank" rel="noopener">
                        <i class="fas fa-location-dot" style="color:var(--pink-lt);font-size:.75rem;width:13px"></i>
                        BAZAAR PLAZA MEZZANINE 1 RM 4, BIASHARA ST, Nairobi CBD
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} American Beauty. All rights reserved.</span>
            <div class="footer-bottom-links">
                <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                <a href="{{ route('terms-of-service') }}">Terms of Service</a>
                <a href="{{ route('cookie-policy') }}">Cookie Policy</a>
            </div>
            <div class="dev-credit">
                <span class="dev-dot"></span>
                Crafted by
                <a href="https://ngwasidaniel.vercel.app/#contact" target="_blank" rel="noopener">Dantechdevs Developers</a>
                <span class="dev-dot"></span>
            </div>
        </div>

    </footer>

    <script>
        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(r => r.json())
                .then(d => {
                    document.getElementById('cart-count').textContent = d.count;
                    document.getElementById('topbar-cart-count').textContent = d.count;
                });
        }

        function handleNewsletter(e) {
            e.preventDefault();
            var form = e.target;
            var btn = form.querySelector("button");
            var email = form.querySelector("input[name='email']").value;
            btn.disabled = true;
            btn.textContent = "Subscribing...";
            fetch(form.action, {
                method: "POST",
                headers: {"Content-Type":"application/json","X-CSRF-TOKEN":form.querySelector("[name=_token]").value,"Accept":"application/json"},
                body: JSON.stringify({email: email})
            }).then(function(r){return r.json();})
            .then(function(d){
                btn.textContent = "✓ Subscribed!";
                btn.style.background = "#16a34a";
                form.querySelector("input[name='email']").value = "";
                setTimeout(function(){btn.textContent="Subscribe";btn.style.background="";btn.disabled=false;},3000);
            }).catch(function(){
                btn.textContent = "Error. Try again.";
                btn.style.background = "#dc2626";
                setTimeout(function(){btn.textContent="Subscribe";btn.style.background="";btn.disabled=false;},3000);
            });
            return false;

        async function handleWhatsapp(e) {
            e.preventDefault();
            const phone = document.getElementById('whatsapp-phone').value.trim();

            try {
                const res = await fetch('/subscribers/whatsapp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ phone })
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('whatsapp-form').reset();
                    window.open('https://wa.me/254722794265?text=Hi!%20I%20just%20subscribed%20for%20updates.', '_blank');
                } else {
                    alert(data.message || 'Something went wrong.');
                }
            } catch(err) {
                alert('Error. Please try again.');
            }
        }
        }
    </script>
    @stack('scripts')
<script>document.getElementById("hamburger").addEventListener("click",function(){document.getElementById("nav-links").classList.toggle("open");});</script>
</body>
</html>