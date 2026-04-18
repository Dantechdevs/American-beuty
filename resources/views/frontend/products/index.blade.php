@extends('layouts.app')
@section('title','Shop All Products')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,600;0,700;1,400&display=swap');

:root {
    --purple:      #7C3AED;
    --purple-dark: #5B21B6;
    --purple-light:#EDE9FE;
    --purple-pale: #F5F3FF;
    --green:       #4ADE80;
    --green-dark:  #16a34a;
    --green-light: #DCFCE7;
    --pink:        #FFB3D1;
    --pink-light:  #FFF0F7;
    --gold:        #FFD700;
    --navy:        #12002A;
    --text:        #1e1e2e;
    --muted:       #6b7280;
    --border:      #e5e7eb;
    --bg:          #faf7ff;
    --card-bg:     #ffffff;
}

body { background: var(--bg); }

/* ── PAGE HEADER BANNER ── */
.shop-banner {
    background: linear-gradient(135deg, #7C3AED 0%, #9D4EDD 50%, #C77DFF 100%);
    padding: 2.2rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.shop-banner::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 20% 50%, rgba(255,179,209,.25) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 30%, rgba(74,222,128,.15) 0%, transparent 50%);
    pointer-events: none;
}
.shop-banner-inner { position: relative; z-index: 1; }
.shop-banner h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.4rem; font-weight: 700; color: #fff;
    letter-spacing: -.01em; margin-bottom: .3rem;
}
.shop-banner h1 em { color: var(--gold); font-style: italic; }
.shop-banner p { color: rgba(255,255,255,.8); font-size: .9rem; font-family: 'Poppins', sans-serif; }

/* ── QUICK FILTER CHIPS ── */
.filter-chips {
    max-width: 1280px; margin: 1.5rem auto .5rem;
    padding: 0 1.5rem;
    display: flex; gap: .55rem; flex-wrap: wrap;
    font-family: 'Poppins', sans-serif;
}
.chip {
    padding: .38rem .95rem; border-radius: 20px;
    font-size: .78rem; font-weight: 600;
    border: 1.5px solid var(--border);
    background: #fff; color: var(--muted);
    cursor: pointer; transition: all .2s;
    text-decoration: none; display: inline-flex; align-items: center; gap: .3rem;
}
.chip:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-pale); }
.chip.chip-active { background: var(--purple); color: #fff; border-color: var(--purple); }
.chip-green { border-color: var(--green-dark); color: var(--green-dark); background: var(--green-light); }
.chip-green:hover { background: var(--green-dark); color: #fff; }
.chip-pink { border-color: #d4748f; color: #d4748f; background: var(--pink-light); }
.chip-pink:hover { background: #d4748f; color: #fff; }
.chip-gold { border-color: #b8860b; color: #b8860b; background: #fffbeb; }
.chip-gold:hover { background: #b8860b; color: #fff; }

/* ── WRAP ── */
.shop-wrap {
    max-width: 1280px; margin: 0 auto 3rem;
    padding: 0 1.5rem;
    display: grid; grid-template-columns: 248px 1fr; gap: 2rem;
}

/* ── SIDEBAR ── */
.sidebar {
    background: #fff;
    border-radius: 18px; padding: 1.5rem;
    border: 1.5px solid var(--purple-light);
    box-shadow: 0 4px 24px rgba(124,58,237,.08);
    align-self: start; position: sticky; top: 80px;
    font-family: 'Poppins', sans-serif;
}
.sidebar h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem; color: var(--navy);
    margin-bottom: 1.2rem; padding-bottom: .7rem;
    border-bottom: 2px solid var(--purple-light);
    display: flex; align-items: center; gap: .5rem;
}
.filter-group { margin-bottom: 1.5rem; }
.filter-group h4 {
    font-size: .65rem; letter-spacing: .15em;
    text-transform: uppercase; color: var(--purple);
    margin-bottom: .65rem; font-weight: 700;
}
.filter-group ul { list-style: none; display: flex; flex-direction: column; gap: .15rem; }
.filter-group ul li a {
    font-size: .84rem; color: var(--muted);
    display: flex; justify-content: space-between; align-items: center;
    padding: .4rem .65rem; border-radius: 9px;
    transition: all .2s;
}
.filter-group ul li a:hover { color: var(--purple); background: var(--purple-pale); padding-left: .95rem; }
.filter-group ul li a.active {
    color: var(--purple); font-weight: 700;
    background: var(--purple-light);
}
.filter-count {
    background: var(--purple); color: #fff;
    border-radius: 10px; padding: .05rem .45rem;
    font-size: .62rem; font-weight: 700;
}
.price-inputs { display: flex; gap: .5rem; align-items: center; margin-bottom: .8rem; }
.price-inputs input {
    width: 80px; padding: .45rem .6rem;
    background: #f9fafb; border: 1.5px solid var(--border);
    border-radius: 8px; font-size: .82rem;
    font-family: 'Poppins', sans-serif; color: var(--text); outline: none;
    transition: border-color .2s;
}
.price-inputs input:focus { border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.08); }
.price-inputs span { color: var(--muted); font-size: .85rem; }
.btn-filter {
    width: 100%; background: var(--purple); color: #fff;
    padding: .72rem; border: none; border-radius: 10px;
    cursor: pointer; font-family: 'Poppins', sans-serif;
    font-size: .85rem; font-weight: 700; letter-spacing: .04em;
    transition: background .2s, transform .15s;
    display: flex; align-items: center; justify-content: center; gap: .4rem;
}
.btn-filter:hover { background: var(--purple-dark); transform: translateY(-1px); }

/* ── SHOP TOPBAR ── */
.shop-topbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.4rem; flex-wrap: wrap; gap: .8rem;
    font-family: 'Poppins', sans-serif;
    background: #fff; border-radius: 12px;
    padding: .8rem 1.1rem;
    border: 1.5px solid var(--border);
    box-shadow: 0 1px 6px rgba(0,0,0,.04);
}
.shop-topbar p { font-size: .88rem; color: var(--muted); }
.shop-topbar p span { color: var(--purple); font-weight: 700; font-size: .95rem; }
.sort-select {
    padding: .48rem .9rem;
    background: var(--purple-pale);
    border: 1.5px solid var(--purple-light);
    border-radius: 10px; font-family: 'Poppins', sans-serif;
    font-size: .84rem; cursor: pointer; color: var(--purple);
    outline: none; font-weight: 600; transition: border-color .2s;
}
.sort-select:focus { border-color: var(--purple); }

/* ── PRODUCT GRID ── */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 1.4rem;
}

/* ── PRODUCT CARD ── */
.product-card {
    background: var(--card-bg);
    border-radius: 18px; overflow: hidden;
    border: 1.5px solid var(--border);
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    transition: transform .28s cubic-bezier(.16,1,.3,1),
                box-shadow .28s, border-color .28s;
    font-family: 'Poppins', sans-serif;
    position: relative;
}
.product-card:hover {
    transform: translateY(-7px);
    border-color: var(--purple);
    box-shadow: 0 20px 48px rgba(124,58,237,.16);
}

/* Image area */
.product-img {
    height: 215px;
    background: linear-gradient(135deg, #f3e8ff 0%, #fce7f3 60%, #ede9fe 100%);
    position: relative; display: flex;
    align-items: center; justify-content: center; overflow: hidden;
}
.product-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .4s ease;
}
.product-card:hover .product-img img { transform: scale(1.06); }
.product-img-placeholder {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1rem; color: var(--purple);
    text-align: center; padding: 1rem; line-height: 1.6;
    opacity: .75;
}

/* Quick actions overlay */
.card-actions-overlay {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(124,58,237,.85), transparent);
    padding: .8rem;
    display: flex; gap: .5rem; justify-content: center;
    opacity: 0; transform: translateY(8px);
    transition: opacity .25s, transform .25s;
}
.product-card:hover .card-actions-overlay { opacity: 1; transform: translateY(0); }
.overlay-btn {
    background: rgba(255,255,255,.95); color: var(--purple);
    border: none; border-radius: 8px;
    padding: .38rem .7rem; font-size: .72rem; font-weight: 700;
    cursor: pointer; font-family: 'Poppins', sans-serif;
    display: flex; align-items: center; gap: .3rem;
    transition: background .18s, color .18s;
}
.overlay-btn:hover { background: var(--purple); color: #fff; }

/* Badges */
.badge {
    position: absolute; top: .75rem; left: .75rem;
    font-size: .63rem; font-weight: 700;
    padding: .28rem .7rem; border-radius: 20px;
    letter-spacing: .05em; z-index: 2;
}
.badge-sale  { background: var(--pink);  color: var(--navy); }
.badge-new   { background: var(--green); color: var(--navy); }
.badge-best  { background: var(--gold);  color: var(--navy); }
.badge-hot   { background: #FF4136; color: #fff; }

.product-wish {
    position: absolute; top: .75rem; right: .75rem; z-index: 2;
    background: rgba(255,255,255,.9);
    border: 1.5px solid var(--border);
    width: 33px; height: 33px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--muted);
    transition: all .2s; font-size: .82rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
}
.product-wish:hover { color: #e91e8c; background: #fff; border-color: var(--pink); transform: scale(1.1); }
.product-wish.wished { color: #e91e8c; border-color: var(--pink); }

/* Card body */
.product-body { padding: 1rem 1.1rem 1.1rem; }
.product-category {
    font-size: .62rem; color: var(--purple);
    text-transform: uppercase; letter-spacing: .13em;
    margin-bottom: .2rem; font-weight: 700;
}
.product-name {
    font-size: .9rem; font-weight: 600; color: var(--text);
    line-height: 1.4; margin-bottom: .45rem;
}
.product-name a { color: var(--text); transition: color .2s; }
.product-name a:hover { color: var(--purple); }

.product-pricing {
    display: flex; align-items: center;
    gap: .45rem; margin-bottom: .4rem; flex-wrap: wrap;
}
.price-current { font-size: 1.08rem; font-weight: 700; color: var(--purple); }
.price-original { font-size: .78rem; color: var(--muted); text-decoration: line-through; }
.price-saving {
    font-size: .6rem; font-weight: 700;
    background: var(--green-light); color: var(--green-dark);
    border-radius: 5px; padding: .08rem .38rem;
}

.stars { color: var(--gold); font-size: .73rem; letter-spacing: .05em; }
.stars span { color: var(--muted); font-size: .68rem; margin-left: 3px; }

/* Stock indicator */
.stock-bar-wrap {
    margin: .5rem 0 .4rem;
    display: flex; align-items: center; gap: .5rem;
}
.stock-bar {
    flex: 1; height: 4px; background: #f3f4f6; border-radius: 99px; overflow: hidden;
}
.stock-bar-fill { height: 100%; border-radius: 99px; background: var(--green); }
.stock-label { font-size: .62rem; color: var(--muted); white-space: nowrap; }

.btn-add-cart {
    width: 100%;
    background: var(--purple);
    color: #fff;
    border: none; padding: .68rem; border-radius: 11px;
    font-size: .82rem; font-weight: 700;
    cursor: pointer; font-family: 'Poppins', sans-serif;
    transition: background .2s, transform .15s, box-shadow .2s;
    margin-top: .65rem; letter-spacing: .03em;
    display: flex; align-items: center; justify-content: center; gap: .4rem;
}
.btn-add-cart:hover {
    background: var(--purple-dark); transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(124,58,237,.3);
}
.btn-add-cart.added { background: var(--green-dark) !important; }

/* ── EMPTY STATE ── */
.empty-state {
    text-align: center; padding: 5rem 2rem;
    font-family: 'Poppins', sans-serif;
    grid-column: 1/-1;
}
.empty-state-icon {
    width: 80px; height: 80px; border-radius: 50%;
    background: var(--purple-light);
    border: 2px solid var(--purple);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.2rem; font-size: 2rem;
}
.empty-state h3 { color: var(--text); font-size: 1.25rem; margin-bottom: .5rem; font-weight: 700; }
.empty-state p { color: var(--muted); font-size: .9rem; }
.empty-state a { color: var(--purple); font-weight: 700; }

/* ── PAGINATION ── */
.pagination {
    display: flex; justify-content: center; gap: .4rem;
    margin-top: 2.5rem; flex-wrap: wrap;
    font-family: 'Poppins', sans-serif;
}
.pagination a, .pagination span {
    padding: .5rem .95rem; border-radius: 9px;
    font-size: .85rem;
    border: 1.5px solid var(--border);
    background: #fff; color: var(--muted);
    transition: all .2s;
}
.pagination a:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-pale); }
.pagination .active span {
    background: var(--purple); color: #fff;
    border-color: var(--purple); font-weight: 700;
}
.pagination .next-btn a {
    background: var(--green); color: var(--navy);
    border-color: var(--green); font-weight: 700;
}
.pagination .next-btn a:hover { background: var(--green-dark); color: #fff; border-color: var(--green-dark); }

@media(max-width:900px){
    .shop-wrap { grid-template-columns: 1fr; }
    .sidebar { position: static; }
}
@media(max-width:600px){
    .product-grid { grid-template-columns: repeat(2, 1fr); gap: .9rem; }
    .shop-banner h1 { font-size: 1.7rem; }
    .filter-chips { gap: .4rem; }
}
</style>
@endpush

@section('content')

{{-- PAGE BANNER --}}
<div class="shop-banner">
    <div class="shop-banner-inner">
        <h1>Shop <em>All</em> Products</h1>
        <p>Premium skincare, makeup & beauty essentials — curated for every skin type</p>
    </div>
</div>

{{-- QUICK FILTER CHIPS --}}
<div class="filter-chips">
    <a href="{{ route('products.index') }}"
       class="chip {{ !request('filter') && !request('category') ? 'chip-active' : '' }}">
        ✦ All
    </a>
    <a href="{{ route('products.index', ['filter'=>'new']) }}"
       class="chip chip-green {{ request('filter')=='new' ? 'chip-active' : '' }}">
        🌿 New Arrivals
    </a>
    <a href="{{ route('products.index', ['filter'=>'sale']) }}"
       class="chip chip-pink {{ request('filter')=='sale' ? 'chip-active' : '' }}">
        🔥 On Sale
    </a>
    <a href="{{ route('products.index', ['filter'=>'bestseller']) }}"
       class="chip chip-gold {{ request('filter')=='bestseller' ? 'chip-active' : '' }}">
        👑 Best Sellers
    </a>
    <a href="{{ route('products.index', ['filter'=>'featured']) }}"
       class="chip {{ request('filter')=='featured' ? 'chip-active' : '' }}">
        ★ Featured
    </a>
    @foreach($categories->take(5) as $cat)
    <a href="{{ route('products.index', ['category'=>$cat->slug]) }}"
       class="chip {{ request('category')==$cat->slug ? 'chip-active' : '' }}">
        {{ $cat->name }}
    </a>
    @endforeach
</div>

<div class="shop-wrap">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <h3>✦ Filters</h3>
        <form method="GET" action="{{ route('products.index') }}">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <div class="filter-group">
                <h4>Categories</h4>
                <ul>
                    <li>
                        <a href="{{ route('products.index') }}"
                           class="{{ !request('category') ? 'active' : '' }}">
                            All Categories
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('products.index', ['category'=>$cat->slug]) }}"
                               class="{{ request('category')==$cat->slug ? 'active' : '' }}">
                                {{ $cat->name }}
                                @if($cat->children->count())
                                    <span class="filter-count">{{ $cat->children->count() }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="filter-group">
                <h4>Show</h4>
                <ul>
                    <li>
                        <a href="{{ route('products.index', array_merge(request()->except('filter'), [])) }}"
                           class="{{ !request('filter') ? 'active' : '' }}">All Products</a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['filter'=>'new']) }}"
                           class="{{ request('filter')=='new' ? 'active' : '' }}">
                           🌿 New Arrivals
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['filter'=>'sale']) }}"
                           class="{{ request('filter')=='sale' ? 'active' : '' }}">
                           🔥 On Sale
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['filter'=>'featured']) }}"
                           class="{{ request('filter')=='featured' ? 'active' : '' }}">
                           ★ Featured
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['filter'=>'bestseller']) }}"
                           class="{{ request('filter')=='bestseller' ? 'active' : '' }}">
                           👑 Best Sellers
                        </a>
                    </li>
                </ul>
            </div>

            <div class="filter-group">
                <h4>Price Range (KSh)</h4>
                <div class="price-inputs">
                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                    <span>—</span>
                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                </div>
                <button type="submit" class="btn-filter">
                    <span>🔍</span> Apply Filters
                </button>
            </div>
        </form>
    </aside>

    {{-- MAIN --}}
    <div class="shop-main">

        {{-- TOPBAR --}}
        <div class="shop-topbar">
            <p>Showing <span>{{ $products->total() }}</span> products
                @if(request('category')) in <strong style="color:var(--purple)">{{ request('category') }}</strong>@endif
                @if(request('search')) for "<strong style="color:var(--purple)">{{ request('search') }}</strong>"@endif
            </p>
            <form method="GET" action="{{ route('products.index') }}">
                @foreach(request()->except('sort') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="latest"     {{ request('sort')=='latest'     ? 'selected':'' }}>✦ Latest</option>
                    <option value="price_low"  {{ request('sort')=='price_low'  ? 'selected':'' }}>Price: Low → High</option>
                    <option value="price_high" {{ request('sort')=='price_high' ? 'selected':'' }}>Price: High → Low</option>
                    <option value="name"       {{ request('sort')=='name'       ? 'selected':'' }}>Name A–Z</option>
                </select>
            </form>
        </div>

        @if($products->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">🔍</div>
                <h3>No products found</h3>
                <p>Try different filters or <a href="{{ route('products.index') }}">view all products</a>.</p>
            </div>
        @else
            <div class="product-grid">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="pagination">{{ $products->links() }}</div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-add-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const originalText = this.innerHTML;
        this.innerHTML = '<span>⏳</span> Adding...';
        this.disabled = true;

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: id, quantity: 1 })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                document.getElementById('cart-count').textContent = d.count;
                document.getElementById('topbar-cart-count').textContent = d.count;
                this.innerHTML = '<span>✓</span> Added to Bag!';
                this.classList.add('added');
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('added');
                    this.disabled = false;
                }, 2000);
            }
        });
    });
});

// Wishlist toggle
document.querySelectorAll('.product-wish').forEach(btn => {
    btn.addEventListener('click', function() {
        this.classList.toggle('wished');
        this.textContent = this.classList.contains('wished') ? '♥' : '♡';
    });
});
</script>
@endpush