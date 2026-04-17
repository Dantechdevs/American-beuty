<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; background:var(--bg-deep); color:#fff; }
        a { text-decoration:none; color:inherit; }

        /* ── TOPBAR ── */
        .topbar {
            background: linear-gradient(90deg, #1A0035 0%, #2a0050 50%, #1A0035 100%);
            border-bottom: 1px solid rgba(255,10,108,.3);
            color: rgba(255,255,255,.9);
            font-size: .8rem;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            padding: .5rem 1.5rem;
            display: flex; align-items: center; justify-content: center;
            gap: 1.2rem; flex-wrap: wrap;
        }
        .topbar-item {
            display: flex; align-items: center; gap: .4rem;
            color: rgba(255,255,255,.85);
            font-weight: 500;
        }
        .topbar-item i { color: var(--pink-lt); font-size: .8rem; }
        .topbar-sep { color: rgba(255,10,108,.5); font-size: .9rem; }
        .topbar-cart-btn {
            display: flex; align-items: center; gap: .45rem;
            background: var(--pink);
            border: none; color: #fff;
            padding: .28rem .9rem; border-radius: 20px;
            font-size: .76rem; font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .15s;
            font-family: 'Poppins', sans-serif;
            text-decoration: none; letter-spacing: .02em;
        }
        .topbar-cart-btn:hover { background: var(--pink-dk); transform: translateY(-1px); color: #fff; }
        .topbar-cart-icon { position: relative; display: flex; align-items: center; }
        .topbar-cart-icon i { font-size: .85rem; }
        .topbar-cart-mini-badge {
            position: absolute; top: -6px; right: -8px;
            background: #fff; color: var(--pink);
            font-size: .55rem; width: 13px; height: 13px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; border: 1.5px solid var(--pink);
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
            background: #07000F;
            border-top: 1px solid rgba(255,10,108,.2);
            color: rgba(255,255,255,.55);
            padding: 0;
            margin-top: 5rem;
            font-family: 'Poppins', sans-serif;
        }

        /* Newsletter strip */
        .footer-newsletter-strip {
            border-bottom: 1px solid rgba(255,255,255,.06);
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
            font-size: .78rem; color: rgba(255,255,255,.4);
            line-height: 1.4; margin: 0;
        }
        .newsletter-form {
            display: flex; flex: 0 0 340px;
            border: 1px solid rgba(255,10,108,.35);
            border-radius: 10px; overflow: hidden;
            transition: border-color .2s;
        }
        .newsletter-form:focus-within {
            border-color: var(--pink);
            box-shadow: 0 0 0 3px rgba(255,10,108,.1);
        }
        .newsletter-form input {
            flex: 1; padding: .6rem .9rem;
            background: rgba(255,255,255,.05);
            border: none; outline: none;
            font-size: .82rem; font-family: 'Poppins', sans-serif; color: #fff;
        }
        .newsletter-form input::placeholder { color: rgba(255,255,255,.25); }
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
        .footer-brand-name span { color: var(--pink); }
        .footer-tagline {
            font-size: .68rem; color: var(--pink-lt);
            letter-spacing: .12em; text-transform: uppercase;
            margin-bottom: .65rem; font-weight: 600;
        }
        .footer-desc {
            font-size: .8rem; line-height: 1.7;
            color: rgba(255,255,255,.45); margin-bottom: 1rem;
        }
        .footer h4 {
            color: #fff; margin-bottom: .9rem;
            font-size: .7rem; letter-spacing: .14em;
            text-transform: uppercase; font-weight: 700;
        }
        .footer ul { list-style: none; display: flex; flex-direction: column; gap: .45rem; }
        .footer ul li a {
            color: rgba(255,255,255,.5); font-size: .82rem;
            transition: color .2s, padding-left .2s;
            display: inline-flex; align-items: center; gap: .35rem;
        }
        .footer ul li a:hover { color: #fff; padding-left: 4px; }

        /* Social */
        .social-links { display: flex; gap: 7px; margin-bottom: .9rem; }
        .social-links a {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.5); font-size: .82rem;
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
            font-size: .68rem; color: rgba(255,255,255,.3);
            text-transform: uppercase; letter-spacing: .1em;
            margin-right: 2px; white-space: nowrap;
        }
        .pay-pill {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 5px; padding: .18rem .55rem;
            font-size: .7rem; color: rgba(255,255,255,.6);
            font-weight: 600; letter-spacing: .03em;
        }

        /* Footer bottom */
        .footer-bottom {
            max-width: 1280px; margin: 0 auto;
            border-top: 1px solid rgba(255,255,255,.06);
            padding: 1rem 1.5rem;
            font-size: .74rem; color: rgba(255,255,255,.28);
            display: flex; justify-content: space-between;
            align-items: center; flex-wrap: wrap; gap: .6rem;
        }
        .footer-bottom-links { display: flex; gap: 1.2rem; }
        .footer-bottom-links a {
            color: rgba(255,255,255,.28); font-size: .74rem; transition: color .2s;
        }
        .footer-bottom-links a:hover { color: var(--pink-lt); }
        .dev-credit {
            display: flex; align-items: center; gap: .35rem;
            font-size: .72rem; color: rgba(255,255,255,.22);
        }
        .dev-credit a { color: var(--pink-lt); font-weight: 600; transition: color .2s; }
        .dev-credit a:hover { color: #fff; }
        .dev-dot {
            width: 3px; height: 3px; border-radius: 50%;
            background: rgba(255,10,108,.4); display: inline-block;
        }

        @media(max-width:900px){
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 1.6rem; }
            .footer-newsletter-inner { flex-direction: column; align-items: flex-start; }
            .newsletter-form { flex: 0 0 100%; width: 100%; }
        }
        @media(max-width:768px){
            .nav-links { display: none; }
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
            <div class="nav-links">
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
                <form class="newsletter-form" onsubmit="handleNewsletter(event)">
                    @csrf
                    <input type="email" name="newsletter_email" placeholder="your@email.com" required>
                    <button type="submit">Subscribe</button>
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
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
                <div class="pay-row">
                    <span class="pay-label-inline">Pay via</span>
                    <span class="pay-pill">M-PESA</span>
                    <span class="pay-pill">Visa</span>
                    <span class="pay-pill">Mastercard</span>
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
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Returns & Refunds</a></li>
                    <li><a href="#">Track My Order</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Size Guide</a></li>
                </ul>
            </div>

            {{-- Col 4: Contact --}}
            <div>
                <h4>Contact</h4>
                <ul>
                    <li>
                        <a href="mailto:info@americanbeauty.com">
                            <i class="fas fa-envelope" style="color:var(--pink-lt);font-size:.75rem;width:13px"></i>
                            info@americanbeauty.com
                        </a>
                    </li>
                    <li>
                        <a href="tel:+254700000000">
                            <i class="fas fa-phone" style="color:var(--pink-lt);font-size:.75rem;width:13px"></i>
                            +254 700 000 000
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-location-dot" style="color:var(--pink-lt);font-size:.75rem;width:13px"></i>
                            Nairobi, Kenya
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} American Beauty. All rights reserved.</span>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
            <div class="dev-credit">
                <span class="dev-dot"></span>
                Crafted by
                <a href="https://ngwasidaniel.vercel.app/#contact" target="_blank" rel="noopener">DanTech Developers</a>
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
            var btn = e.target.querySelector('button');
            btn.textContent = '✓ Subscribed!';
            btn.style.background = '#16a34a';
            e.target.querySelector('input').value = '';
            setTimeout(function() {
                btn.textContent = 'Subscribe';
                btn.style.background = '';
            }, 3000);
        }
    </script>
    @stack('scripts')
</body>
</html>