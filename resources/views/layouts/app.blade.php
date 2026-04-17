<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'American Beauty') — Glow Naturally</title>
    <meta name="description" content="@yield('meta_description', 'Premium skincare, cosmetics & beauty products. Delivered across Kenya.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            background: var(--bg-panel);
            border-bottom: 1px solid rgba(255,10,108,.2);
            color: rgba(255,255,255,.65);
            font-size: .78rem;
            text-align: center;
            padding: .45rem 1.5rem;
            display: flex; align-items: center; justify-content: center;
            gap: 1.2rem; flex-wrap: wrap;
        }
        .topbar-sep { color: rgba(255,10,108,.35); }
        .topbar-cart-btn {
            display: flex; align-items: center; gap: .45rem;
            background: rgba(255,10,108,.15);
            border: 1px solid rgba(255,10,108,.35);
            color: var(--pink-lt);
            padding: .22rem .75rem;
            border-radius: 20px;
            font-size: .76rem; font-weight: 600;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            font-family: inherit;
            text-decoration: none;
        }
        .topbar-cart-btn:hover {
            background: rgba(255,10,108,.28);
            border-color: rgba(255,10,108,.6);
            color: #fff;
        }
        .topbar-cart-icon { position: relative; display: flex; align-items: center; }
        .topbar-cart-icon i { font-size: .85rem; }
        .topbar-cart-mini-badge {
            position: absolute; top: -6px; right: -8px;
            background: var(--pink); color: #fff;
            font-size: .55rem; width: 13px; height: 13px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            border: 1.5px solid var(--bg-panel);
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
        .nav-links a { color: rgba(255,255,255,.6); transition: color .2s; }
        .nav-links a:hover { color: var(--pink-lt); }
        .nav-actions { display: flex; align-items: center; gap: 1.2rem; }
        .nav-icon {
            color: rgba(255,255,255,.6); font-size: 1.05rem;
            position: relative; cursor: pointer;
            transition: color .2s;
            display: flex; align-items: center;
        }
        .nav-icon:hover { color: var(--pink-lt); }

        /* Enhanced cart icon */
        .cart-icon-wrap {
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,10,108,.12);
            border: 1px solid rgba(255,10,108,.3);
            padding: 7px 11px;
            border-radius: 12px;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            color: var(--pink-lt);
            position: relative;
        }
        .cart-icon-wrap:hover {
            background: rgba(255,10,108,.22);
            border-color: rgba(255,10,108,.55);
            color: #fff;
        }
        .cart-badge {
            position: absolute; top: -7px; right: -7px;
            background: var(--pink); color: #fff;
            font-size: .6rem; width: 17px; height: 17px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            border: 2px solid var(--bg-dark);
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
        .flash-error   { background: rgba(255,10,108,.12); color: #FF6FB0;  border-bottom: 1px solid rgba(255,10,108,.2); }

        /* ── FOOTER ── */
        footer {
            background: #0A0018;
            border-top: 1px solid rgba(255,10,108,.15);
            color: rgba(255,255,255,.4);
            padding: 3rem 1.5rem 1.5rem;
            margin-top: 5rem;
        }
        .footer-grid {
            max-width: 1280px; margin: auto;
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2.5rem;
        }
        .footer-brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem; color: #fff; margin-bottom: .7rem;
        }
        .footer-brand-name span { color: var(--pink); }
        .footer-desc { font-size: .875rem; line-height: 1.7; color: rgba(255,255,255,.38); }
        .footer h4 {
            color: rgba(255,255,255,.8); margin-bottom: 1rem;
            font-size: .8rem; letter-spacing: .12em; text-transform: uppercase;
        }
        .footer ul { list-style: none; display: flex; flex-direction: column; gap: .5rem; }
        .footer ul li a { color: rgba(255,255,255,.38); font-size: .875rem; transition: color .2s; }
        .footer ul li a:hover { color: var(--pink-lt); }
        .social-links { display: flex; gap: 8px; margin-top: .8rem; }
        .social-links a {
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(255,10,108,.1);
            border: 1px solid rgba(255,10,108,.2);
            color: rgba(255,255,255,.45); font-size: .9rem;
            display: flex; align-items: center; justify-content: center;
            transition: background .2s, color .2s, border-color .2s;
        }
        .social-links a:hover {
            background: rgba(255,10,108,.25);
            border-color: rgba(255,10,108,.5);
            color: var(--pink-lt);
        }
        .pay-label { margin-top: 1rem; font-size: .75rem; color: rgba(255,255,255,.25); }
        .pay-methods { margin-top: .4rem; font-size: .85rem; color: var(--pink-lt); font-weight: 600; }
        .footer-bottom {
            max-width: 1280px; margin: .5rem auto 0;
            border-top: 1px solid rgba(255,10,108,.1);
            padding-top: 1.2rem; font-size: .8rem;
            color: rgba(255,255,255,.25);
            display: flex; justify-content: space-between; flex-wrap: wrap; gap: .5rem;
        }

        @media(max-width:768px){
            .nav-links { display: none; }
            .topbar { gap: .6rem; font-size: .72rem; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media(max-width:480px){
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- TOPBAR with enhanced cart shortcut --}}
    <div class="topbar">
        <span>✨ Free shipping on orders over KSh 3,000</span>
        <span class="topbar-sep">|</span>
        <span>Pay with M-PESA</span>
        <span class="topbar-sep">|</span>
        <span>100% Authentic Products</span>
        <span class="topbar-sep">|</span>
        <a href="{{ route('cart') }}" class="topbar-cart-btn">
            <span class="topbar-cart-icon">
                <i class="fas fa-shopping-bag"></i>
                <span class="topbar-cart-mini-badge" id="topbar-cart-count">
                    {{ app(\App\Services\CartService::class)->count() }}
                </span>
            </span>
            View Cart
        </a>
    </div>

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
                    <i class="fas fa-shopping-bag"></i>
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

    <footer>
        <div class="footer-grid">
            <div>
                <div class="footer-brand-name">American<span>Beauty</span></div>
                <p class="footer-desc">Premium skincare & beauty products delivered across Kenya. Authentic, curated, affordable.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div>
                <h4>Shop</h4>
                <ul>
                    <li><a href="{{ route('products.index', ['category'=>'skincare']) }}">Skincare</a></li>
                    <li><a href="{{ route('products.index', ['category'=>'makeup']) }}">Makeup</a></li>
                    <li><a href="{{ route('products.index', ['category'=>'haircare']) }}">Haircare</a></li>
                    <li><a href="{{ route('products.index', ['filter'=>'new']) }}">New Arrivals</a></li>
                    <li><a href="{{ route('products.index', ['filter'=>'sale']) }}">Sale</a></li>
                </ul>
            </div>
            <div>
                <h4>Help</h4>
                <ul>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Track Order</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h4>Contact</h4>
                <ul>
                    <li><a href="mailto:info@americanbeauty.com">info@americanbeauty.com</a></li>
                    <li><a href="tel:+254700000000">+254 700 000 000</a></li>
                    <li><a href="#">Nairobi, Kenya</a></li>
                </ul>
                <div class="pay-label">Pay securely with</div>
                <div class="pay-methods">M-PESA &nbsp;|&nbsp; Visa &nbsp;|&nbsp; Mastercard</div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} American Beauty. All rights reserved.</span>
            <span>Privacy Policy &nbsp;|&nbsp; Terms of Service</span>
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
    </script>
    @stack('scripts')
</body>
</html>