@extends('layouts.app')
@section('title','American Beauty — Glow Naturally')

@push('styles')
<style>
:root{--rose:#c8847a;--rose-dk:#a05e56;--cream:#faf7f4;--sand:#f0e8df;--charcoal:#2c2c2c;--border:#e8ddd6;}
/* HERO */
.hero{background:linear-gradient(135deg,#fdf0ec 0%,#f5e6de 50%,#ede0d8 100%);min-height:88vh;display:grid;grid-template-columns:1fr 1fr;align-items:center;padding:4rem clamp(1.5rem,5vw,5rem);gap:3rem;overflow:hidden;position:relative;}
.hero::before{content:'';position:absolute;top:-100px;right:-100px;width:500px;height:500px;background:radial-gradient(circle,rgba(200,132,122,.12) 0%,transparent 70%);border-radius:50%;}
.hero-eyebrow{font-size:.8rem;letter-spacing:.25em;text-transform:uppercase;color:var(--rose);font-weight:500;margin-bottom:1rem;}
.hero-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2.8rem,5vw,4.5rem);line-height:1.1;font-weight:400;color:var(--charcoal);margin-bottom:1.5rem;}
.hero-title em{font-style:italic;color:var(--rose);}
.hero-sub{font-size:1.05rem;color:#666;line-height:1.7;max-width:480px;margin-bottom:2.5rem;}
.hero-btns{display:flex;gap:1rem;flex-wrap:wrap;}
.btn-primary{background:var(--rose);color:#fff;padding:.85rem 2rem;border-radius:40px;font-size:.9rem;font-weight:500;border:none;cursor:pointer;transition:all .25s;display:inline-block;font-family:'DM Sans',sans-serif;}
.btn-primary:hover{background:var(--rose-dk);transform:translateY(-1px);box-shadow:0 8px 20px rgba(200,132,122,.35);}
.btn-outline{border:1.5px solid var(--charcoal);color:var(--charcoal);padding:.83rem 2rem;border-radius:40px;font-size:.9rem;font-weight:500;cursor:pointer;transition:all .25s;display:inline-block;}
.btn-outline:hover{border-color:var(--rose);color:var(--rose);}
.hero-stats{display:flex;gap:2.5rem;margin-top:2.5rem;}
.hero-stat strong{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:600;color:var(--charcoal);}
.hero-stat span{display:block;font-size:.78rem;color:#888;margin-top:.1rem;}
.hero-image{position:relative;display:flex;justify-content:center;align-items:center;}
.hero-img-box{width:clamp(300px,40vw,480px);height:clamp(380px,50vw,560px);background:linear-gradient(145deg,#e8d5cc,#d4b8ae);border-radius:40% 60% 60% 40%/50% 40% 60% 50%;display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:1.2rem;color:#fff;letter-spacing:.1em;box-shadow:0 30px 80px rgba(200,132,122,.25);}
.hero-badge{position:absolute;bottom:40px;left:-20px;background:#fff;border-radius:16px;padding:.8rem 1.2rem;box-shadow:0 10px 30px rgba(0,0,0,.1);font-size:.82rem;}
.hero-badge strong{display:block;color:var(--rose);font-size:1rem;}

/* SECTION */
.section{max-width:1280px;margin:5rem auto;padding:0 1.5rem;}
.section-header{text-align:center;margin-bottom:3rem;}
.section-eyebrow{font-size:.78rem;letter-spacing:.25em;text-transform:uppercase;color:var(--rose);font-weight:500;margin-bottom:.6rem;}
.section-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,3.5vw,3rem);font-weight:400;}

/* CATEGORIES */
.cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;}
.cat-card{background:#fff;border-radius:20px;padding:2rem 1rem;text-align:center;transition:all .25s;cursor:pointer;border:1.5px solid transparent;text-decoration:none;color:var(--charcoal);}
.cat-card:hover{border-color:var(--rose);transform:translateY(-4px);box-shadow:0 15px 40px rgba(200,132,122,.15);}
.cat-icon{font-size:2.2rem;margin-bottom:.8rem;}
.cat-name{font-size:.9rem;font-weight:500;}
.cat-count{font-size:.78rem;color:#888;margin-top:.2rem;}

/* PRODUCTS */
.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.5rem;}
.product-card{background:#fff;border-radius:20px;overflow:hidden;transition:all .25s;border:1px solid var(--border);}
.product-card:hover{transform:translateY(-5px);box-shadow:0 20px 50px rgba(0,0,0,.09);}
.product-img{height:240px;background:linear-gradient(135deg,var(--sand),#e8d5cc);position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;}
.product-img img{width:100%;height:100%;object-fit:cover;}
.product-img-placeholder{font-family:'Cormorant Garamond',serif;font-size:1rem;color:#a08070;letter-spacing:.05em;}
.badge-sale{position:absolute;top:.8rem;left:.8rem;background:var(--rose);color:#fff;font-size:.7rem;font-weight:600;padding:.25rem .6rem;border-radius:20px;}
.badge-new{position:absolute;top:.8rem;left:.8rem;background:var(--charcoal);color:#fff;font-size:.7rem;font-weight:600;padding:.25rem .6rem;border-radius:20px;}
.product-wish{position:absolute;top:.8rem;right:.8rem;background:#fff;border:none;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#ccc;transition:color .2s;font-size:.9rem;}
.product-wish:hover{color:var(--rose);}
.product-body{padding:1.1rem;}
.product-category{font-size:.72rem;color:var(--rose);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.3rem;}
.product-name{font-size:.95rem;font-weight:500;line-height:1.4;margin-bottom:.6rem;}
.product-name a{color:var(--charcoal);transition:color .2s;}
.product-name a:hover{color:var(--rose);}
.product-pricing{display:flex;align-items:center;gap:.6rem;margin-bottom:.9rem;}
.price-current{font-size:1.05rem;font-weight:600;color:var(--charcoal);}
.price-original{font-size:.85rem;color:#aaa;text-decoration:line-through;}
.stars{color:#f4b942;font-size:.75rem;display:flex;gap:.1rem;}
.btn-add-cart{width:100%;background:var(--charcoal);color:#fff;border:none;padding:.65rem;border-radius:12px;font-size:.85rem;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;margin-top:.8rem;}
.btn-add-cart:hover{background:var(--rose);}

/* TABS */
.tabs{display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:2.5rem;justify-content:center;}
.tab{padding:.7rem 1.8rem;font-size:.9rem;font-weight:500;cursor:pointer;border-bottom:2px solid transparent;transition:all .2s;color:#888;background:none;border-top:none;border-left:none;border-right:none;font-family:inherit;}
.tab.active,.tab:hover{color:var(--rose);border-bottom-color:var(--rose);}

/* BANNER STRIP */
.banner-strip{background:var(--sand);padding:3.5rem 1.5rem;text-align:center;margin:4rem 0;}
.banner-strip h2{font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,3vw,2.8rem);font-weight:400;margin-bottom:1rem;}
.banner-strip p{color:#666;margin-bottom:1.8rem;font-size:.95rem;}

/* FEATURES */
.features{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:2rem;max-width:1280px;margin:0 auto 5rem;padding:0 1.5rem;}
.feature{text-align:center;padding:2rem 1rem;}
.feature-icon{font-size:2rem;color:var(--rose);margin-bottom:1rem;}
.feature h4{font-weight:600;margin-bottom:.4rem;font-size:.95rem;}
.feature p{font-size:.83rem;color:#888;line-height:1.6;}

@media(max-width:768px){
    .hero{grid-template-columns:1fr;min-height:auto;padding:3rem 1.5rem;text-align:center;}
    .hero-image{display:none;}
    .hero-btns{justify-content:center;}
    .hero-stats{justify-content:center;}
}
</style>
@endpush

@section('content')
<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <p class="hero-eyebrow">✦ New Season Collection</p>
        <h1 class="hero-title">Glow <em>Naturally,</em><br>Live Beautifully</h1>
        <p class="hero-sub">Discover premium skincare, makeup & beauty essentials curated for every skin type. Authentic products. Pay easily with M-PESA.</p>
        <div class="hero-btns">
            <a href="{{ route('products.index') }}" class="btn-primary">Shop Now</a>
            <a href="{{ route('products.index', ['filter'=>'new']) }}" class="btn-outline">New Arrivals</a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat"><strong>500+</strong><span>Products</span></div>
            <div class="hero-stat"><strong>10K+</strong><span>Happy Clients</span></div>
            <div class="hero-stat"><strong>100%</strong><span>Authentic</span></div>
        </div>
    </div>
    <div class="hero-image">
        <div class="hero-img-box">✦ AMERICAN BEAUTY</div>
        <div class="hero-badge">
            <strong>⭐ 4.9/5</strong>
            <span>Trusted by thousands</span>
        </div>
    </div>
</section>

<!-- FEATURES -->
<div class="features">
    <div class="feature"><div class="feature-icon"><i class="fas fa-truck"></i></div><h4>Free Delivery</h4><p>On orders over KSh 3,000 across Kenya</p></div>
    <div class="feature"><div class="feature-icon"><i class="fas fa-mobile-alt"></i></div><h4>Pay with M-PESA</h4><p>Seamless STK Push payment at checkout</p></div>
    <div class="feature"><div class="feature-icon"><i class="fas fa-certificate"></i></div><h4>100% Authentic</h4><p>All products are genuine & verified</p></div>
    <div class="feature"><div class="feature-icon"><i class="fas fa-undo"></i></div><h4>Easy Returns</h4><p>7-day hassle-free return policy</p></div>
</div>

<!-- CATEGORIES -->
<div class="section">
    <div class="section-header">
        <p class="section-eyebrow">Browse by</p>
        <h2 class="section-title">Shop by Category</h2>
    </div>
    <div class="cat-grid">
        @foreach($categories as $cat)
        <a href="{{ route('products.index', ['category'=>$cat->slug]) }}" class="cat-card">
            <div class="cat-icon">
                @php
                $icons = ['skincare'=>'🧴','makeup'=>'💄','haircare'=>'💇','fragrance'=>'🌸','body-care'=>'🛁','tools'=>'🪞','moisturizers'=>'✨','serums'=>'💧'];
                echo $icons[$cat->slug] ?? '✦';
                @endphp
            </div>
            <div class="cat-name">{{ $cat->name }}</div>
            <div class="cat-count">{{ $cat->products_count }} products</div>
        </a>
        @endforeach
    </div>
</div>

<!-- FEATURED PRODUCTS -->
<div class="section">
    <div class="section-header">
        <p class="section-eyebrow">Handpicked for you</p>
        <h2 class="section-title">Featured Products</h2>
    </div>
    <div class="tabs">
        <button class="tab active" onclick="showTab('featured',this)">Featured</button>
        <button class="tab" onclick="showTab('new',this)">New Arrivals</button>
        <button class="tab" onclick="showTab('best',this)">Best Sellers</button>
    </div>

    <div id="tab-featured" class="product-grid">
        @foreach($featured as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
    <div id="tab-new" class="product-grid" style="display:none">
        @foreach($newArrivals as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
    <div id="tab-best" class="product-grid" style="display:none">
        @foreach($bestSellers as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>

    <div style="text-align:center;margin-top:3rem">
        <a href="{{ route('products.index') }}" class="btn-outline">View All Products</a>
    </div>
</div>

<!-- BANNER -->
<div class="banner-strip">
    <h2>Ready for Your Skincare Glow-Up?</h2>
    <p>Shop our bestselling serums, moisturizers & SPF — starting from KSh 1,500</p>
    <a href="{{ route('products.index', ['category'=>'skincare']) }}" class="btn-primary">Shop Skincare Now</a>
</div>
@endsection

@push('scripts')
<script>
function showTab(name, btn) {
    ['featured','new','best'].forEach(t => {
        document.getElementById('tab-'+t).style.display = 'none';
    });
    document.querySelectorAll('.tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-'+name).style.display = 'grid';
    btn.classList.add('active');
}
document.querySelectorAll('.btn-add-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        fetch('{{ route("cart.add") }}', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({product_id: id, quantity: 1})
        }).then(r=>r.json()).then(d=>{
            if(d.success){
                document.getElementById('cart-count').textContent = d.count;
                this.textContent = '✓ Added!';
                setTimeout(()=>{ this.textContent = 'Add to Cart'; }, 2000);
            }
        });
    });
});
</script>
@endpush
