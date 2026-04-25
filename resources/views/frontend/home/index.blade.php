@extends('layouts.app')
@section('title','American Beauty — Glow Naturally')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap');

:root {
  --purple:    #7B2FBE;
  --purple-dk: #5A1F8A;
  --purple-lt: #EDE0F8;
  --magenta:   #C8359D;
  --magenta-lt:#F9E0F4;
  --green:     #3DB54A;
  --green-lt:  #E0F5E3;
  --white:     #FFFFFF;
  --off-white: #FAF8FC;
  --charcoal:  #1E1225;
  --gray:      #6B6478;
  --border:    rgba(123,47,190,.15);
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Poppins', sans-serif; color: var(--charcoal); background: var(--off-white); }

/* ── HERO ── */
.hero {
  min-height: 90vh;
  display: flex;
  align-items: center;
  padding: 5rem clamp(1.5rem,6vw,6rem);
  position: relative;
  overflow: hidden;
}

/* Background video — sharp, no blur */
.hero-video-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
}
.hero-video-bg video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  filter: none;
  opacity: 1;
  transform: translateZ(0);
  -webkit-transform: translateZ(0);
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  will-change: transform;
}

/* Text content sits above video */
.hero-content {
  position: relative;
  z-index: 1;
  max-width: 620px;
  background: linear-gradient(to right, rgba(10,5,20,0.70) 60%, rgba(10,5,20,0.0) 100%);
  padding: 2.5rem 4rem 2.5rem 2rem;
  border-radius: 16px;
}

.hero-eyebrow {
  display: inline-flex; align-items: center; gap: .5rem;
  font-size: .75rem; letter-spacing: .22em; text-transform: uppercase;
  color: #f9c8ef; font-weight: 600; margin-bottom: 1.2rem;
  background: rgba(200,53,157,.25);
  padding: .35rem 1rem; border-radius: 40px;
  border: 1px solid rgba(200,53,157,.4);
}

.hero-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.6rem,5vw,4.4rem);
  line-height: 1.1; font-weight: 700;
  color: #fff;
  margin-bottom: 1.4rem;
}
.hero-title .t-purple { color: #d89ef8; }
.hero-title .t-green  { color: #7de888; font-style: italic; }

.hero-sub {
  font-size: 1rem; color: rgba(255,255,255,.92);
  line-height: 1.75; max-width: 460px; margin-bottom: 2.4rem;
}

.hero-btns { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.8rem; }

.btn-primary {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; padding: .9rem 2.2rem;
  border-radius: 50px; font-size: .9rem; font-weight: 600;
  border: none; cursor: pointer; font-family: 'Poppins', sans-serif;
  transition: all .25s; display: inline-block; text-decoration: none;
  box-shadow: 0 6px 24px rgba(123,47,190,.35);
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(123,47,190,.45); }

.btn-outline {
  border: 2px solid #fff;
  color: #fff; padding: .88rem 2.2rem;
  border-radius: 50px; font-size: .9rem; font-weight: 600;
  cursor: pointer; transition: all .25s; display: inline-block; text-decoration: none;
  background: rgba(255,255,255,.1);
  backdrop-filter: blur(4px);
}
.btn-outline:hover { background: #fff; color: var(--purple); }

.btn-green {
  background: linear-gradient(135deg, var(--green), #28a035);
  color: #fff; padding: .9rem 2.2rem;
  border-radius: 50px; font-size: .9rem; font-weight: 600;
  border: none; cursor: pointer; font-family: 'Poppins', sans-serif;
  transition: all .25s; display: inline-block; text-decoration: none;
  box-shadow: 0 6px 24px rgba(61,181,74,.35);
}
.btn-green:hover { transform: translateY(-2px); }

.hero-stats {
  display: flex; gap: 2.5rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(255,255,255,.25);
}
.hero-stat strong {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem; font-weight: 700; color: #d89ef8;
  display: block;
}
.hero-stat span { font-size: .78rem; color: rgba(255,255,255,.80); margin-top: .1rem; }

/* ── FEATURES ── */
.features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
  gap: 0;
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  margin: 0; padding: 0;
}
.feature {
  text-align: center; padding: 2.2rem 1.5rem;
  border-right: 1px solid rgba(255,255,255,.15);
  transition: background .25s;
}
.feature:last-child { border-right: none; }
.feature:hover { background: rgba(255,255,255,.08); }
.feature-icon { font-size: 1.8rem; margin-bottom: .8rem; color: #fff; }
.feature h4 { font-weight: 600; margin-bottom: .3rem; font-size: .92rem; color: #fff; }
.feature p { font-size: .8rem; color: rgba(255,255,255,.72); line-height: 1.5; }

/* ── SECTION ── */
.section { max-width: 1280px; margin: 5rem auto; padding: 0 1.5rem; }
.section-header { text-align: center; margin-bottom: 3rem; }
.section-eyebrow {
  font-size: .75rem; letter-spacing: .22em; text-transform: uppercase;
  color: var(--magenta); font-weight: 600; margin-bottom: .6rem;
  display: inline-block;
}
.section-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem,3.5vw,2.8rem); font-weight: 700;
  color: var(--charcoal);
}
.section-title span { color: var(--purple); }

/* ── CATEGORIES ── */
.cat-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(150px,1fr)); gap: 1rem; }
.cat-card {
  background: #fff; border-radius: 20px; padding: 1.8rem 1rem;
  text-align: center; transition: all .25s; cursor: pointer;
  border: 1.5px solid var(--border); text-decoration: none; color: var(--charcoal);
  position: relative; overflow: hidden;
}
.cat-card::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(135deg, var(--purple-lt), var(--magenta-lt));
  opacity: 0; transition: opacity .25s;
}
.cat-card:hover { border-color: var(--purple); transform: translateY(-4px); box-shadow: 0 15px 40px rgba(123,47,190,.15); }
.cat-card:hover::before { opacity: 1; }
.cat-icon { font-size: 2.2rem; margin-bottom: .8rem; position: relative; }
.cat-name { font-size: .88rem; font-weight: 600; position: relative; }
.cat-count { font-size: .75rem; color: var(--magenta); margin-top: .2rem; position: relative; font-weight: 500; }

/* ── PRODUCTS ── */
.product-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(240px,1fr)); gap: 1.5rem; }
.product-card {
  background: #fff; border-radius: 20px; overflow: hidden;
  transition: all .25s; border: 1px solid var(--border);
}
.product-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(123,47,190,.12); }
.product-img {
  height: 240px;
  background: linear-gradient(135deg, var(--purple-lt), var(--magenta-lt));
  position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.product-img img { width: 100%; height: 100%; object-fit: cover; }
.product-img-placeholder { font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--purple); letter-spacing: .05em; }
.badge-sale {
  position: absolute; top: .8rem; left: .8rem;
  background: linear-gradient(135deg, var(--magenta), #a02070);
  color: #fff; font-size: .7rem; font-weight: 700;
  padding: .25rem .65rem; border-radius: 20px;
}
.badge-new {
  position: absolute; top: .8rem; left: .8rem;
  background: linear-gradient(135deg, var(--green), #28a035);
  color: #fff; font-size: .7rem; font-weight: 700;
  padding: .25rem .65rem; border-radius: 20px;
}
.product-wish {
  position: absolute; top: .8rem; right: .8rem;
  background: #fff; border: none; width: 32px; height: 32px;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: #ccc; transition: all .2s; font-size: .9rem;
  box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
.product-wish:hover { color: var(--magenta); transform: scale(1.1); }
.product-body { padding: 1.1rem; }
.product-category { font-size: .7rem; color: var(--purple); text-transform: uppercase; letter-spacing: .12em; margin-bottom: .3rem; font-weight: 600; }
.product-name { font-size: .95rem; font-weight: 500; line-height: 1.4; margin-bottom: .6rem; }
.product-name a { color: var(--charcoal); transition: color .2s; text-decoration: none; }
.product-name a:hover { color: var(--purple); }
.product-pricing { display: flex; align-items: center; gap: .6rem; margin-bottom: .9rem; }
.price-current { font-size: 1.05rem; font-weight: 700; color: var(--purple); }
.price-original { font-size: .85rem; color: #aaa; text-decoration: line-through; }
.stars { color: #f4b942; font-size: .75rem; display: flex; gap: .1rem; }
.btn-add-cart {
  width: 100%; border: none; padding: .7rem;
  border-radius: 12px; font-size: .85rem; cursor: pointer;
  font-family: 'Poppins', sans-serif; transition: all .2s; margin-top: .8rem;
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; font-weight: 600;
  box-shadow: 0 4px 14px rgba(123,47,190,.25);
}
.btn-add-cart:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(123,47,190,.35); }

/* ── TABS ── */
.tabs {
  display: flex; gap: 0; justify-content: center;
  background: var(--purple-lt);
  border-radius: 50px; padding: .4rem;
  width: fit-content; margin: 0 auto 2.5rem;
}
.tab {
  padding: .6rem 1.8rem; font-size: .88rem; font-weight: 600;
  cursor: pointer; border-radius: 50px; transition: all .2s;
  color: var(--purple); background: none; border: none;
  font-family: 'Poppins', sans-serif;
}
.tab.active { background: linear-gradient(135deg, var(--purple), var(--magenta)); color: #fff; box-shadow: 0 4px 14px rgba(123,47,190,.3); }
.tab:hover:not(.active) { background: rgba(123,47,190,.1); }

/* ── BANNER ── */
.banner-strip {
  background: linear-gradient(135deg, var(--charcoal) 0%, #2D1050 50%, #1A0C35 100%);
  padding: 5rem 1.5rem; text-align: center; margin: 4rem 0;
  position: relative; overflow: hidden;
}
.banner-strip::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(123,47,190,.15) 1px, transparent 1px),
    linear-gradient(90deg, rgba(123,47,190,.15) 1px, transparent 1px);
  background-size: 50px 50px;
}
.banner-strip::after {
  content: '';
  position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
  width: 600px; height: 600px; border-radius: 50%;
  background: radial-gradient(circle, rgba(200,53,157,.15) 0%, transparent 70%);
}
.banner-strip h2 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem,4vw,3rem); font-weight: 700;
  color: #fff; margin-bottom: 1rem; position: relative; z-index: 1;
}
.banner-strip h2 em { color: var(--green); font-style: italic; }
.banner-strip p { color: rgba(255,255,255,.65); margin-bottom: 2rem; font-size: .95rem; position: relative; z-index: 1; }
.banner-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

/* ── PROMO BAR ── */
.promo-bar {
  background: linear-gradient(135deg, var(--green), #28a035);
  padding: .7rem 1.5rem; text-align: center;
  font-size: .82rem; font-weight: 600; color: #fff; letter-spacing: .05em;
}
.promo-bar span { opacity: .8; margin: 0 1rem; }

@media(max-width:768px){
  .hero { min-height: 80vh; padding: 3rem 1.5rem; }
  .hero-content { padding: 2rem 1.5rem; background: rgba(10,5,20,0.65); border-radius: 12px; }
  .hero-btns { justify-content: flex-start; }
  .hero-stats { justify-content: flex-start; }
  .features { grid-template-columns: 1fr 1fr; }
  .feature { border-right: none; border-bottom: 1px solid rgba(255,255,255,.15); }
}
</style>
@endpush

@section('content')

{{-- Promo bar --}}
<div class="promo-bar">
  🌿 Free delivery on orders over KSh 3,000 &nbsp;<span>|</span>&nbsp; 100% Authentic Products &nbsp;<span>|</span>&nbsp; Pay with M-PESA ✦
</div>

<!-- HERO -->
<section class="hero">

  {{-- Full background video — sharp, no blur --}}
  <div class="hero-video-bg">
    <video id="hero-video" autoplay muted playsinline>
      <source src="{{ asset('videos/american.mp4') }}" type="video/mp4">
    </video>
  </div>

  {{-- Text content over video --}}
  <div class="hero-content">
    <p class="hero-eyebrow">✦ New Season Collection</p>
    <h1 class="hero-title">
      <span class="t-purple">Glow</span> Naturally,<br>
      Live <span class="t-green">Beautifully</span>
    </h1>
    <p class="hero-sub">Discover premium skincare, makeup & beauty essentials curated for every skin type. Authentic products. Pay easily with M-PESA.</p>
    <div class="hero-btns">
      <a href="{{ route('products.index') }}" class="btn-primary">Shop Now →</a>
      <a href="{{ route('products.index', ['filter'=>'new']) }}" class="btn-outline">New Arrivals</a>
      <a href="#book-appointment" class="btn-green">📅 Book Appointment</a>
    </div>
    <div class="hero-stats">
      <div class="hero-stat"><strong>500+</strong><span>Products</span></div>
      <div class="hero-stat"><strong>10K+</strong><span>Happy Clients</span></div>
      <div class="hero-stat"><strong>100%</strong><span>Authentic</span></div>
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
    <h2 class="section-title">Shop by <span>Category</span></h2>
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
    <h2 class="section-title">Featured <span>Products</span></h2>
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
    <a href="{{ route('products.index') }}" class="btn-outline" style="color:var(--purple);border-color:var(--purple);background:transparent;">View All Products →</a>
  </div>
</div>

<!-- BANNER -->
<div class="banner-strip">
  <h2>Ready for Your Skincare <em>Glow-Up?</em></h2>
  <p>Shop our bestselling serums, moisturizers & SPF — starting from KSh 1,500</p>
  <div class="banner-btns">
    <a href="{{ route('products.index', ['category'=>'skincare']) }}" class="btn-primary">Shop Skincare Now</a>
    <a href="{{ route('products.index') }}" class="btn-green">View All Products</a>
  </div>
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

// Video playlist — cycles american.mp4 → americanB.mp4 → repeat
const videos = [
  '{{ asset("videos/american.mp4") }}',
  '{{ asset("videos/americanB.mp4") }}'
];
let current = 0;
const vid = document.getElementById('hero-video');
vid.addEventListener('ended', function () {
  current = (current + 1) % videos.length;
  vid.src = videos[current];
  vid.play();
});

document.querySelectorAll('.btn-add-cart').forEach(btn => {
  btn.addEventListener('click', function() {
    const id = this.dataset.id;
    fetch('{{ route("cart.add") }}', {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
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