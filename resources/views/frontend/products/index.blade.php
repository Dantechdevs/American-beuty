@extends('layouts.app')
@section('title','Shop All Products')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

:root {
    --gold: #FFD700;
    --gold-dark: #C9A800;
    --pink: #FFB3D1;
    --pink-dark: #d4748f;
    --green: #4ADE80;
    --green-dark: #16a34a;
    --plum: #12002A;
    --violet: #1A0035;
    --violet2: #2D0060;
    --border: #3D0080;
}

.shop-wrap {
    max-width: 1280px; margin: 2.5rem auto;
    padding: 0 1.5rem;
    display: grid; grid-template-columns: 240px 1fr; gap: 2.5rem;
}

/* ── SIDEBAR ── */
.sidebar {
    background: var(--violet);
    border-radius: 16px; padding: 1.5rem;
    border: 1.5px solid var(--gold);
    align-self: start; position: sticky; top: 80px;
    font-family: 'Poppins', sans-serif;
}
.sidebar h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem; color: var(--gold); margin-bottom: 1.2rem;
    padding-bottom: .7rem;
    border-bottom: 1px solid rgba(255,215,0,.2);
}
.filter-group { margin-bottom: 1.6rem; }
.filter-group h4 {
    font-size: .7rem; letter-spacing: .14em;
    text-transform: uppercase; color: var(--pink);
    margin-bottom: .75rem; font-weight: 700;
}
.filter-group ul { list-style: none; display: flex; flex-direction: column; gap: .35rem; }
.filter-group ul li a {
    font-size: .85rem; color: #ccc;
    transition: color .2s, padding-left .2s;
    display: flex; justify-content: space-between; align-items: center;
    padding: .3rem 0;
}
.filter-group ul li a:hover { color: #fff; padding-left: 5px; }
.filter-group ul li a.active {
    color: var(--gold); font-weight: 700;
}
.filter-count {
    background: var(--pink);
    color: var(--plum);
    border-radius: 10px; padding: .05rem .45rem;
    font-size: .68rem; font-weight: 700;
}
.price-inputs { display: flex; gap: .5rem; align-items: center; margin-bottom: .8rem; }
.price-inputs input {
    width: 80px; padding: .45rem .6rem;
    background: var(--violet2);
    border: 1.5px solid var(--border);
    border-radius: 8px; font-size: .82rem;
    font-family: 'Poppins', sans-serif; color: #fff; outline: none;
    transition: border-color .2s;
}
.price-inputs input::placeholder { color: #888; }
.price-inputs input:focus { border-color: var(--gold); }
.price-inputs span { color: #fff; font-size: .85rem; font-weight: 600; }
.btn-filter {
    width: 100%; background: var(--gold); color: var(--plum);
    padding: .7rem; border: none; border-radius: 10px;
    cursor: pointer; font-family: 'Poppins', sans-serif;
    font-size: .85rem; font-weight: 700; letter-spacing: .04em;
    transition: background .2s, transform .15s;
}
.btn-filter:hover { background: var(--gold-dark); transform: translateY(-1px); }

/* ── TOPBAR ── */
.shop-topbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.5rem; flex-wrap: wrap; gap: .8rem;
    font-family: 'Poppins', sans-serif;
}
.shop-topbar p { font-size: .9rem; color: #ccc; }
.shop-topbar p span { color: var(--gold); font-weight: 700; font-size: 1rem; }
.sort-select {
    padding: .5rem .9rem;
    background: var(--violet);
    border: 1.5px solid var(--gold);
    border-radius: 10px; font-family: 'Poppins', sans-serif;
    font-size: .85rem; cursor: pointer; color: var(--gold);
    outline: none; transition: border-color .2s;
}
.sort-select:focus { border-color: var(--gold-dark); }
.sort-select option { background: var(--violet); color: #fff; }

/* ── PRODUCT GRID ── */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 1.3rem;
}
.product-card {
    background: var(--violet);
    border-radius: 16px; overflow: hidden;
    transition: transform .25s, box-shadow .25s, border-color .25s;
    border: 1.5px solid var(--border);
    font-family: 'Poppins', sans-serif;
}
.product-card:hover {
    transform: translateY(-6px);
    border-color: var(--gold);
    box-shadow: 0 16px 40px rgba(255,215,0,.18);
}
.product-img {
    height: 210px;
    background: linear-gradient(135deg, var(--violet2), var(--violet));
    position: relative; display: flex;
    align-items: center; justify-content: center; overflow: hidden;
}
.product-img img { width: 100%; height: 100%; object-fit: cover; }
.product-img-placeholder {
    font-family: 'Cormorant Garamond', serif;
    font-size: .9rem; color: #888;
    letter-spacing: .05em; text-align: center; padding: 1rem;
}

/* Badges */
.badge-sale {
    position: absolute; top: .7rem; left: .7rem;
    background: var(--pink); color: var(--plum);
    font-size: .68rem; font-weight: 700;
    padding: .25rem .65rem; border-radius: 20px;
    letter-spacing: .04em;
}
.badge-new {
    position: absolute; top: .7rem; left: .7rem;
    background: var(--green); color: var(--plum);
    font-size: .68rem; font-weight: 700;
    padding: .25rem .65rem; border-radius: 20px;
    letter-spacing: .04em;
}
.badge-best {
    position: absolute; top: .7rem; left: .7rem;
    background: var(--gold); color: var(--plum);
    font-size: .68rem; font-weight: 700;
    padding: .25rem .65rem; border-radius: 20px;
    letter-spacing: .04em;
}
.product-wish {
    position: absolute; top: .7rem; right: .7rem;
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.3);
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #fff;
    transition: all .2s; font-size: .85rem;
}
.product-wish:hover {
    color: var(--pink); background: #fff;
    border-color: #fff;
}

/* Card body */
.product-body { padding: 1rem; }
.product-category {
    font-size: .68rem; color: var(--pink);
    text-transform: uppercase; letter-spacing: .12em;
    margin-bottom: .25rem; font-weight: 600;
}
.product-name {
    font-size: .9rem; font-weight: 500;
    line-height: 1.4; margin-bottom: .5rem;
}
.product-name a { color: #fff; transition: color .2s; }
.product-name a:hover { color: var(--gold); }

.product-pricing {
    display: flex; align-items: center;
    gap: .5rem; margin-bottom: .5rem;
}
.price-current { font-size: 1.05rem; font-weight: 700; color: var(--gold); }
.price-original {
    font-size: .8rem; color: #888;
    text-decoration: line-through;
}
.price-saving {
    font-size: .66rem; font-weight: 700;
    background: var(--pink); color: var(--plum);
    border-radius: 6px; padding: .12rem .4rem;
}

.stars { color: var(--gold); font-size: .75rem; }
.stars span { color: #666; font-size: .72rem; margin-left: 3px; }

.btn-add-cart {
    width: 100%;
    background: var(--green);
    color: var(--plum);
    border: none;
    padding: .65rem; border-radius: 10px;
    font-size: .82rem; font-weight: 700;
    cursor: pointer; font-family: 'Poppins', sans-serif;
    transition: background .2s, transform .15s, color .2s;
    margin-top: .7rem; letter-spacing: .03em;
}
.btn-add-cart:hover { background: var(--green-dark); color: #fff; transform: translateY(-1px); }
.btn-add-cart.added {
    background: var(--green-dark) !important;
    color: #fff !important;
}

/* Empty state */
.empty-state {
    text-align: center; padding: 4rem 2rem;
    font-family: 'Poppins', sans-serif;
}
.empty-state-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: var(--violet2);
    border: 2px solid var(--gold);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.2rem; font-size: 1.8rem;
}
.empty-state h3 { color: #fff; font-size: 1.2rem; margin-bottom: .5rem; font-weight: 700; }
.empty-state p { color: #aaa; font-size: .9rem; }
.empty-state a { color: var(--pink); font-weight: 700; }
.empty-state a:hover { color: var(--gold); }

/* Pagination */
.pagination {
    display: flex; justify-content: center; gap: .4rem;
    margin-top: 2.5rem; flex-wrap: wrap;
    font-family: 'Poppins', sans-serif;
}
.pagination a, .pagination span {
    padding: .5rem .95rem; border-radius: 8px;
    font-size: .85rem;
    border: 1.5px solid var(--border);
    background: var(--violet); color: #ccc;
    transition: all .2s;
}
.pagination a:hover {
    border-color: var(--gold); color: var(--gold);
    background: rgba(255,215,0,.08);
}
.pagination .active span {
    background: var(--gold); color: var(--plum);
    border-color: var(--gold); font-weight: 700;
}
.pagination .next-btn a {
    background: var(--green); color: var(--plum);
    border-color: var(--green); font-weight: 700;
}
.pagination .next-btn a:hover {
    background: var(--green-dark); color: #fff;
    border-color: var(--green-dark);
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

    {{-- SIDEBAR --}}
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
                           ✦ New Arrivals
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
                <button type="submit" class="btn-filter">Apply Filters</button>
            </div>
        </form>
    </aside>

    {{-- MAIN --}}
    <div class="shop-main">
        <div class="shop-topbar">
            <p><span>{{ $products->total() }}</span> products found</p>
            <form method="GET" action="{{ route('products.index') }}">
                @foreach(request()->except('sort') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="latest"     {{ request('sort')=='latest'     ? 'selected':'' }}>Latest</option>
                    <option value="price_low"  {{ request('sort')=='price_low'  ? 'selected':'' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort')=='price_high' ? 'selected':'' }}>Price: High to Low</option>
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
        const originalText = this.textContent;
        this.textContent = 'Adding...';
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
                this.textContent = '✓ Added to Bag!';
                this.classList.add('added');
                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('added');
                    this.disabled = false;
                }, 2000);
            }
        });
    });
});
</script>
@endpush