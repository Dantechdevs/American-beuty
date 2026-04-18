@extends('layouts.app')
@section('title','Shop All Products')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

/* ── SHOP LAYOUT ── */
.shop-wrap {
    max-width: 1280px; margin: 2.5rem auto;
    padding: 0 1.5rem;
    display: grid; grid-template-columns: 240px 1fr; gap: 2.5rem;
}

/* ── SIDEBAR ── */
.sidebar {
    background: #12002A;
    border-radius: 16px; padding: 1.5rem;
    border: 1px solid rgba(255,10,108,.2);
    align-self: start; position: sticky; top: 80px;
    font-family: 'Poppins', sans-serif;
}
.sidebar h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem; color: #fff; margin-bottom: 1.2rem;
    padding-bottom: .7rem;
    border-bottom: 1px solid rgba(255,255,255,.08);
}
.filter-group { margin-bottom: 1.6rem; }
.filter-group h4 {
    font-size: .68rem; letter-spacing: .14em;
    text-transform: uppercase; color: rgba(255,255,255,.4);
    margin-bottom: .75rem; font-weight: 700;
}
.filter-group ul { list-style: none; display: flex; flex-direction: column; gap: .4rem; }
.filter-group ul li a {
    font-size: .83rem; color: rgba(255,255,255,.6);
    transition: color .2s, padding-left .2s;
    display: flex; justify-content: space-between; align-items: center;
    padding: .3rem 0;
}
.filter-group ul li a:hover { color: #FF6FB0; padding-left: 4px; }
.filter-group ul li a.active {
    color: #FF0A6C; font-weight: 600;
}
.filter-group ul li a.active::before {
    content: ''; display: inline-block;
    width: 3px; height: 3px; border-radius: 50%;
    background: #FF0A6C; margin-right: 6px;
}
.filter-count {
    background: rgba(255,255,255,.08);
    border-radius: 10px; padding: .05rem .4rem;
    font-size: .68rem; color: rgba(255,255,255,.35);
}

.price-inputs { display: flex; gap: .5rem; align-items: center; margin-bottom: .8rem; }
.price-inputs input {
    width: 80px; padding: .45rem .6rem;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 8px; font-size: .82rem;
    font-family: 'Poppins', sans-serif; color: #fff; outline: none;
    transition: border-color .2s;
}
.price-inputs input::placeholder { color: rgba(255,255,255,.25); }
.price-inputs input:focus { border-color: #FF0A6C; }
.price-inputs span { color: rgba(255,255,255,.3); font-size: .85rem; }
.btn-filter {
    width: 100%; background: #FF0A6C; color: #fff;
    padding: .65rem; border: none; border-radius: 10px;
    cursor: pointer; font-family: 'Poppins', sans-serif;
    font-size: .83rem; font-weight: 600; letter-spacing: .03em;
    transition: background .2s, transform .15s;
}
.btn-filter:hover { background: #d6005a; transform: translateY(-1px); }

/* ── SHOP TOPBAR ── */
.shop-topbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.5rem; flex-wrap: wrap; gap: .8rem;
    font-family: 'Poppins', sans-serif;
}
.shop-topbar p { font-size: .88rem; color: rgba(255,255,255,.5); }
.shop-topbar p span { color: #FF6FB0; font-weight: 600; }
.sort-select {
    padding: .5rem .9rem;
    background: #12002A;
    border: 1px solid rgba(255,10,108,.25);
    border-radius: 10px; font-family: 'Poppins', sans-serif;
    font-size: .83rem; cursor: pointer; color: rgba(255,255,255,.8);
    outline: none; transition: border-color .2s;
}
.sort-select:focus { border-color: #FF0A6C; }
.sort-select option { background: #12002A; }

/* ── PRODUCT GRID ── */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 1.2rem;
}
.product-card {
    background: #12002A;
    border-radius: 16px; overflow: hidden;
    transition: transform .25s, border-color .25s, box-shadow .25s;
    border: 1px solid rgba(255,10,108,.15);
    font-family: 'Poppins', sans-serif;
}
.product-card:hover {
    transform: translateY(-5px);
    border-color: rgba(255,10,108,.4);
    box-shadow: 0 16px 40px rgba(255,10,108,.12);
}
.product-img {
    height: 210px;
    background: linear-gradient(135deg, #1A0035, #2a0050);
    position: relative; display: flex;
    align-items: center; justify-content: center; overflow: hidden;
}
.product-img img { width: 100%; height: 100%; object-fit: cover; }
.product-img-placeholder {
    font-family: 'Cormorant Garamond', serif;
    font-size: .9rem; color: rgba(255,255,255,.25);
    letter-spacing: .05em; text-align: center; padding: 1rem;
}

/* Badges */
.badge-sale {
    position: absolute; top: .7rem; left: .7rem;
    background: #FF0A6C; color: #fff;
    font-size: .65rem; font-weight: 700;
    padding: .22rem .6rem; border-radius: 20px;
    letter-spacing: .04em; font-family: 'Poppins', sans-serif;
}
.badge-new {
    position: absolute; top: .7rem; left: .7rem;
    background: #7C3AED; color: #fff;
    font-size: .65rem; font-weight: 700;
    padding: .22rem .6rem; border-radius: 20px;
    letter-spacing: .04em; font-family: 'Poppins', sans-serif;
}
.badge-best {
    position: absolute; top: .7rem; left: .7rem;
    background: #FFD700; color: #1a0035;
    font-size: .65rem; font-weight: 700;
    padding: .22rem .6rem; border-radius: 20px;
    letter-spacing: .04em; font-family: 'Poppins', sans-serif;
}
.product-wish {
    position: absolute; top: .7rem; right: .7rem;
    background: rgba(255,255,255,.1);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.2);
    width: 30px; height: 30px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: rgba(255,255,255,.5);
    transition: color .2s, background .2s; font-size: .85rem;
}
.product-wish:hover { color: #FF0A6C; background: rgba(255,10,108,.15); border-color: rgba(255,10,108,.4); }

/* Card body */
.product-body { padding: 1rem; }
.product-category {
    font-size: .65rem; color: #FF6FB0;
    text-transform: uppercase; letter-spacing: .12em; margin-bottom: .25rem;
    font-weight: 600;
}
.product-name { font-size: .88rem; font-weight: 500; line-height: 1.4; margin-bottom: .5rem; }
.product-name a { color: rgba(255,255,255,.9); transition: color .2s; }
.product-name a:hover { color: #FF6FB0; }

.product-pricing { display: flex; align-items: center; gap: .5rem; margin-bottom: .5rem; }
.price-current { font-size: 1rem; font-weight: 700; color: #fff; }
.price-original { font-size: .78rem; color: rgba(255,255,255,.3); text-decoration: line-through; }
.price-saving {
    font-size: .65rem; font-weight: 600;
    background: rgba(255,10,108,.15);
    color: #FF6FB0; border-radius: 6px;
    padding: .1rem .35rem;
}

.stars { color: #FFD700; font-size: .72rem; }
.stars span { color: rgba(255,255,255,.3); font-size: .72rem; margin-left: 2px; }

.btn-add-cart {
    width: 100%; background: rgba(255,10,108,.12);
    color: #FF6FB0;
    border: 1px solid rgba(255,10,108,.3);
    padding: .6rem; border-radius: 10px;
    font-size: .8rem; font-weight: 600;
    cursor: pointer; font-family: 'Poppins', sans-serif;
    transition: background .2s, color .2s, border-color .2s;
    margin-top: .7rem; letter-spacing: .03em;
}
.btn-add-cart:hover {
    background: #FF0A6C; color: #fff;
    border-color: #FF0A6C;
}

/* Empty state */
.empty-state {
    text-align: center; padding: 4rem 2rem;
    font-family: 'Poppins', sans-serif;
}
.empty-state-icon {
    width: 64px; height: 64px; border-radius: 50%;
    background: rgba(255,10,108,.1);
    border: 1px solid rgba(255,10,108,.2);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.2rem; font-size: 1.6rem;
}
.empty-state h3 { color: #fff; font-size: 1.1rem; margin-bottom: .5rem; }
.empty-state p { color: rgba(255,255,255,.45); font-size: .88rem; }
.empty-state a { color: #FF6FB0; font-weight: 600; }

/* Pagination */
.pagination {
    display: flex; justify-content: center; gap: .4rem;
    margin-top: 2.5rem; flex-wrap: wrap;
    font-family: 'Poppins', sans-serif;
}
.pagination a, .pagination span {
    padding: .5rem .9rem; border-radius: 8px;
    font-size: .83rem;
    border: 1px solid rgba(255,10,108,.2);
    background: #12002A; color: rgba(255,255,255,.6);
    transition: all .2s;
}
.pagination a:hover { border-color: #FF0A6C; color: #FF6FB0; }
.pagination .active span {
    background: #FF0A6C; color: #fff;
    border-color: #FF0A6C;
}

@media(max-width:768px){
    .shop-wrap { grid-template-columns: 1fr; }
    .sidebar { position: static; }
    .product-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
}
</style>
@endpush

@section('content')
<div class="shop-wrap">

    {{-- SIDEBAR FILTERS --}}
    <aside class="sidebar">
        <h3>Filters</h3>
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
                                    <span class="filter-count">+{{ $cat->children->count() }}</span>
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
                           class="{{ !request('filter') ? 'active' : '' }}">All</a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['filter'=>'new']) }}"
                           class="{{ request('filter')=='new' ? 'active' : '' }}">New Arrivals</a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['filter'=>'sale']) }}"
                           class="{{ request('filter')=='sale' ? 'active' : '' }}">On Sale</a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['filter'=>'featured']) }}"
                           class="{{ request('filter')=='featured' ? 'active' : '' }}">Featured</a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['filter'=>'bestseller']) }}"
                           class="{{ request('filter')=='bestseller' ? 'active' : '' }}">Best Sellers</a>
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
                <button type="submit" class="btn-filter">Apply Filters</button>
            </div>
        </form>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="shop-main">
        <div class="shop-topbar">
            <p><span>{{ $products->total() }}</span> products found</p>
            <form method="GET" action="{{ route('products.index') }}">
                @foreach(request()->except('sort') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="latest"     {{ request('sort')=='latest'     ? 'selected' : '' }}>Latest</option>
                    <option value="price_low"  {{ request('sort')=='price_low'  ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort')=='price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name"       {{ request('sort')=='name'       ? 'selected' : '' }}>Name A–Z</option>
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
                this.textContent = '✓ Added!';
                this.style.background = 'rgba(34,197,94,.2)';
                this.style.color = '#6ee7a0';
                this.style.borderColor = 'rgba(34,197,94,.3)';
                setTimeout(() => {
                    this.textContent = 'Add to Cart';
                    this.style.background = '';
                    this.style.color = '';
                    this.style.borderColor = '';
                }, 2000);
            }
        });
    });
});
</script>
@endpush