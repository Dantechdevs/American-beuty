@extends('layouts.app')
@section('title', 'Our Services — American Beauty')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Jost:wght@300;400;500;600&display=swap"rel="stylesheet">
<style>
:root {
    --rose:    #C8359D;
    --plum:    #7B2FBE;
    --deep:    #1A0030;
    --blush:   #F7E8F3;
    --gold:    #D4AF7A;
    --cream:   #FDF8F5;
    --ink:     #1E0E2C;
    --muted:   #8A7A9A;
    --card-r:  16px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--cream); font-family: 'Jost', sans-serif; color: var(--ink); }
.sv-hero {
    position: relative; overflow: hidden;
    background: var(--deep); padding: 5rem 1.5rem 4rem; text-align: center;
}
.sv-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 70% 60% at 20% 50%, rgba(123,47,190,.55) 0%, transparent 70%),
        radial-gradient(ellipse 60% 80% at 80% 30%, rgba(200,53,157,.4) 0%, transparent 65%);
    pointer-events: none;
}
.sv-hero-grain {
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    opacity: .35; pointer-events: none;
}
.sv-hero-inner { position: relative; z-index: 1; max-width: 640px; margin: 0 auto; }
.sv-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 10px;
    font-size: .65rem; letter-spacing: .22em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 1.2rem;
}
.sv-hero-eyebrow::before,
.sv-hero-eyebrow::after { content: ''; flex: 1; width: 28px; height: 1px; background: var(--gold); opacity: .6; }
.sv-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.8rem, 7vw, 5rem); font-weight: 900;
    line-height: 1.08; color: #fff; margin-bottom: 1rem;
}
.sv-hero h1 em { font-style: italic; color: transparent; -webkit-text-stroke: 1.5px rgba(255,255,255,.7); }
.sv-hero-sub {
    font-size: .95rem; font-weight: 300; color: rgba(255,255,255,.65);
    line-height: 1.7; margin-bottom: 2rem; max-width: 440px; margin-left: auto; margin-right: auto;
}
.sv-hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.sv-btn {
    font-family: 'Jost', sans-serif; font-size: .82rem; font-weight: 500;
    letter-spacing: .06em; text-transform: uppercase;
    padding: .75rem 2rem; border-radius: 99px; cursor: pointer;
    text-decoration: none; transition: all .22s; border: none; display: inline-block;
}
.sv-btn-primary { background: var(--gold); color: var(--deep); }
.sv-btn-primary:hover { background: #e2c28a; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(212,175,122,.35); }
.sv-btn-outline { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.4); }
.sv-btn-outline:hover { background: rgba(255,255,255,.1); }
.sv-stats {
    display: flex; justify-content: center; flex-wrap: wrap; gap: 0;
    background: #fff; border-bottom: 1px solid #EEE3F5;
    box-shadow: 0 2px 20px rgba(123,47,190,.07);
}
.sv-stat {
    padding: 1.1rem 2.5rem; text-align: center;
    border-right: 1px solid #EEE3F5; flex: 1; min-width: 120px;
}
.sv-stat:last-child { border-right: none; }
.sv-stat-num { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: var(--plum); line-height: 1; }
.sv-stat-lbl { font-size: .68rem; letter-spacing: .1em; text-transform: uppercase; color: var(--muted); margin-top: .2rem; }
.sv-filters-wrap {
    position: sticky; top: 0; z-index: 100;
    background: rgba(253,248,245,.92); backdrop-filter: blur(12px);
    border-bottom: 1px solid #EEE3F5; padding: .85rem 1.5rem;
}
.sv-filters { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; max-width: 960px; margin: 0 auto; }
.sv-filter {
    font-family: 'Jost', sans-serif; font-size: .72rem; font-weight: 500;
    letter-spacing: .05em; text-transform: uppercase;
    padding: .4rem 1.1rem; border-radius: 99px;
    border: 1px solid #DCCAEE; background: #fff; color: var(--plum);
    cursor: pointer; transition: all .18s;
}
.sv-filter:hover { background: #F3E8FF; border-color: var(--plum); }
.sv-filter.active { background: var(--plum); border-color: var(--plum); color: #fff; }
.sv-body { padding: 3rem 1.5rem 4rem; background: var(--cream); }
.sv-inner { max-width: 1120px; margin: 0 auto; }
.sv-section { margin-bottom: 3.5rem; }
.sv-cat-label { display: flex; align-items: center; gap: 14px; margin-bottom: 1.5rem; }
.sv-cat-label::after { content: ''; flex: 1; height: 1px; background: linear-gradient(to right, #DCCAEE, transparent); }
.sv-cat-icon { font-size: 1rem; }
.sv-cat-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--plum); white-space: nowrap; letter-spacing: .02em; }
.sv-cat-count { font-size: .65rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #fff; background: var(--rose); padding: .15rem .55rem; border-radius: 99px; }
.sv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 16px; }
.sv-card {
    background: #fff; border-radius: var(--card-r); border: 1px solid #EEE3F5;
    padding: 1.4rem 1.5rem 1.2rem; transition: transform .2s, box-shadow .2s;
    position: relative; overflow: hidden;
}
.sv-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    border-radius: var(--card-r) var(--card-r) 0 0;
}
.sv-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(123,47,190,.1); }
.sv-card.c-hydra::before   { background: linear-gradient(90deg,#7B2FBE,#C8359D); }
.sv-card.c-express::before { background: linear-gradient(90deg,#3B82F6,#06B6D4); }
.sv-card.c-antiage::before { background: linear-gradient(90deg,#D4AF7A,#E8834A); }
.sv-card.c-custom::before  { background: linear-gradient(90deg,#10B981,#3B82F6); }
.sv-card.c-micro::before   { background: linear-gradient(90deg,#C8359D,#E879A8); }
.sv-card.c-wax::before     { background: linear-gradient(90deg,#F59E0B,#EF4444); }
.sv-card.c-skin::before    { background: linear-gradient(90deg,#06B6D4,#7B2FBE); }
.sv-card.c-addon::before   { background: linear-gradient(90deg,#8B5CF6,#C8359D); }
.sv-card-name { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--ink); line-height: 1.3; margin-bottom: .5rem; }
.sv-card-desc { font-size: .78rem; color: var(--muted); line-height: 1.65; margin-bottom: 1rem; }
.sv-card-footer { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
.sv-card-meta { display: flex; flex-direction: column; gap: 2px; }
.sv-card-price { font-size: .82rem; font-weight: 600; color: var(--plum); }
.sv-card-duration { font-size: .68rem; color: var(--muted); display: flex; align-items: center; gap: 4px; }
.sv-book-btn {
    font-family: 'Jost', sans-serif; font-size: .72rem; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase;
    padding: .45rem 1.1rem; border-radius: 99px;
    background: var(--blush); color: var(--rose); border: 1px solid #F0C8E4;
    cursor: pointer; transition: all .18s; text-decoration: none; white-space: nowrap;
}
.sv-book-btn:hover { background: var(--rose); color: #fff; border-color: var(--rose); }
.sv-footer-cta {
    position: relative; overflow: hidden;
    background: var(--deep); padding: 4rem 1.5rem; text-align: center;
}
.sv-footer-cta::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 80% 80% at 50% 120%, rgba(200,53,157,.5) 0%, transparent 65%),
        radial-gradient(ellipse 50% 60% at 10% 0%, rgba(123,47,190,.4) 0%, transparent 70%);
    pointer-events: none;
}
.sv-footer-inner { position: relative; z-index: 1; max-width: 480px; margin: 0 auto; }
.sv-footer-cta h2 { font-family: 'Playfair Display', serif; font-size: clamp(2rem,5vw,3rem); font-weight: 900; color: #fff; margin-bottom: .6rem; }
.sv-footer-cta h2 em { font-style: italic; color: var(--gold); }
.sv-footer-cta p { font-size: .9rem; color: rgba(255,255,255,.65); margin-bottom: 1.8rem; line-height: 1.7; }
.sv-footer-badges { display: flex; justify-content: center; flex-wrap: wrap; gap: 8px; margin-bottom: 1.8rem; }
.sv-badge { font-size: .7rem; font-weight: 500; letter-spacing: .06em; text-transform: uppercase; padding: .3rem .8rem; border-radius: 99px; border: 1px solid rgba(255,255,255,.25); color: rgba(255,255,255,.8); }
@media (max-width: 600px) {
    .sv-stat { padding: .8rem 1rem; }
    .sv-grid { grid-template-columns: 1fr; }
    .sv-filter { font-size: .68rem; padding: .35rem .85rem; }
}
@keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
.sv-section { animation: fadeUp .4s ease both; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="sv-hero">
    <div class="sv-hero-grain"></div>
    <div class="sv-hero-inner">
        <div class="sv-hero-eyebrow">American Beauty · Nairobi CBD</div>
        <h1>Beauty <em>Elevated</em><br>Services</h1>
        <p class="sv-hero-sub">Premium skincare & beauty treatments tailored for every skin type. Walk in or book online — The Bazaar Plaza, Moi Avenue.</p>
        <div class="sv-hero-btns">
            <a href="{{ route('book.index') }}" class="sv-btn sv-btn-primary">Book Appointment</a>
            <a href="{{ route('contact') }}" class="sv-btn sv-btn-outline">Contact Us</a>
        </div>
    </div>
</div>

{{-- STATS STRIP --}}
<div class="sv-stats">
    <div class="sv-stat">
        <div class="sv-stat-num">{{ $categories->count() }}+</div>
        <div class="sv-stat-lbl">Service Categories</div>
    </div>
    <div class="sv-stat">
        <div class="sv-stat-num">{{ $totalServices }}</div>
        <div class="sv-stat-lbl">Treatments</div>
    </div>
    <div class="sv-stat">
        <div class="sv-stat-num">5★</div>
        <div class="sv-stat-lbl">Client Rated</div>
    </div>
    <div class="sv-stat">
        <div class="sv-stat-num">CBD</div>
        <div class="sv-stat-lbl">Nairobi Location</div>
    </div>
</div>

{{-- FILTERS --}}
<div class="sv-filters-wrap">
    <div class="sv-filters" id="sv-filters">
        <button class="sv-filter active" data-cat="all">✦ All Services</button>
        @foreach($categories as $cat)
        <button class="sv-filter" data-cat="{{ $cat->slug }}">{{ $cat->icon }} {{ $cat->name }}</button>
        @endforeach
    </div>
</div>

{{-- SERVICE CARDS --}}
<div class="sv-body">
    <div class="sv-inner" id="sv-body">
        @foreach($categories as $i => $cat)
        <div class="sv-section" data-cat="{{ $cat->slug }}" style="animation-delay:{{ $i * 0.06 }}s">
            <div class="sv-cat-label">
                <span class="sv-cat-icon">{{ $cat->icon }}</span>
                <span class="sv-cat-title">{{ $cat->name }}</span>
                <span class="sv-cat-count">{{ $cat->services->count() }}</span>
            </div>
            <div class="sv-grid">
                @foreach($cat->services as $svc)
                <div class="sv-card {{ $cat->color_class }}">
                    <div class="sv-card-name">{{ $svc->name }}</div>
                    <div class="sv-card-desc">{{ $svc->description }}</div>
                    <div class="sv-card-footer">
                        <div class="sv-card-meta">
                            <div class="sv-card-price">{{ $svc->price_display }}</div>
                            @if($svc->duration)
                            <div class="sv-card-duration"><i class="fas fa-clock"></i> {{ $svc->duration }}</div>
                            @endif
                        </div>
                        <a href="{{ route('book.index') }}" class="sv-book-btn">Book →</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- FOOTER CTA --}}
<div class="sv-footer-cta">
    <div class="sv-footer-inner">
        <h2>Ready to <em>Glow?</em></h2>
        <p>Book your next appointment in minutes. No app needed, no fuss — just beautiful skin.</p>
        <div class="sv-footer-badges">
            <span class="sv-badge">✓ Easy Booking</span>
            <span class="sv-badge">✓ Expert Therapists</span>
            <span class="sv-badge">✓ Premium Products</span>
        </div>
        <a href="{{ route('book.index') }}" class="sv-btn sv-btn-primary">Book Now →</a>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('sv-filters').addEventListener('click', function(e) {
    const btn = e.target.closest('.sv-filter');
    if (!btn) return;
    document.querySelectorAll('.sv-filter').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const cat = btn.dataset.cat;
    document.querySelectorAll('#sv-body .sv-section').forEach(sec => {
        sec.style.display = (cat === 'all' || sec.dataset.cat === cat) ? '' : 'none';
    });
});
</script>
@endpush
