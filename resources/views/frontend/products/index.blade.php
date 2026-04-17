@extends('layouts.app')
@section('title','Shop All Products')

@push('styles')
<style>
:root{--rose:#c8847a;--cream:#faf7f4;--sand:#f0e8df;--charcoal:#2c2c2c;--border:#e8ddd6;}
.shop-wrap{max-width:1280px;margin:2.5rem auto;padding:0 1.5rem;display:grid;grid-template-columns:240px 1fr;gap:2.5rem;}
.sidebar{background:#fff;border-radius:16px;padding:1.5rem;border:1px solid var(--border);align-self:start;position:sticky;top:80px;}
.sidebar h3{font-family:'Cormorant Garamond',serif;font-size:1.3rem;margin-bottom:1.2rem;}
.filter-group{margin-bottom:1.8rem;}
.filter-group h4{font-size:.8rem;letter-spacing:.12em;text-transform:uppercase;color:#888;margin-bottom:.8rem;font-weight:600;}
.filter-group ul{list-style:none;display:flex;flex-direction:column;gap:.5rem;}
.filter-group ul li a{font-size:.88rem;color:var(--charcoal);transition:color .2s;display:flex;justify-content:space-between;}
.filter-group ul li a:hover{color:var(--rose);}
.filter-group ul li a.active{color:var(--rose);font-weight:600;}
.price-inputs{display:flex;gap:.5rem;align-items:center;}
.price-inputs input{width:80px;padding:.4rem .6rem;border:1px solid var(--border);border-radius:8px;font-size:.83rem;font-family:inherit;}
.btn-filter{width:100%;background:var(--charcoal);color:#fff;padding:.65rem;border:none;border-radius:10px;cursor:pointer;font-family:inherit;font-size:.88rem;transition:background .2s;}
.btn-filter:hover{background:var(--rose);}

.shop-main{}
.shop-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:.8rem;}
.shop-topbar p{font-size:.9rem;color:#666;}
.sort-select{padding:.5rem .9rem;border:1px solid var(--border);border-radius:10px;font-family:inherit;font-size:.88rem;cursor:pointer;background:#fff;}
.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem;}
.product-card{background:#fff;border-radius:16px;overflow:hidden;transition:all .25s;border:1px solid var(--border);}
.product-card:hover{transform:translateY(-4px);box-shadow:0 15px 40px rgba(0,0,0,.08);}
.product-img{height:210px;background:linear-gradient(135deg,var(--sand),#e8d5cc);position:relative;display:flex;align-items:center;justify-content:center;}
.product-img img{width:100%;height:100%;object-fit:cover;}
.product-img-placeholder{font-family:'Cormorant Garamond',serif;font-size:.9rem;color:#a08070;letter-spacing:.05em;text-align:center;padding:1rem;}
.badge-sale{position:absolute;top:.7rem;left:.7rem;background:var(--rose);color:#fff;font-size:.68rem;font-weight:600;padding:.2rem .55rem;border-radius:20px;}
.badge-new{position:absolute;top:.7rem;left:.7rem;background:var(--charcoal);color:#fff;font-size:.68rem;font-weight:600;padding:.2rem .55rem;border-radius:20px;}
.product-wish{position:absolute;top:.7rem;right:.7rem;background:#fff;border:none;width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#ccc;transition:color .2s;font-size:.85rem;}
.product-wish:hover{color:var(--rose);}
.product-body{padding:1rem;}
.product-category{font-size:.7rem;color:var(--rose);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.25rem;}
.product-name{font-size:.9rem;font-weight:500;line-height:1.35;margin-bottom:.5rem;}
.product-name a{color:var(--charcoal);transition:color .2s;}
.product-name a:hover{color:var(--rose);}
.product-pricing{display:flex;align-items:center;gap:.5rem;margin-bottom:.7rem;}
.price-current{font-size:1rem;font-weight:600;}
.price-original{font-size:.8rem;color:#aaa;text-decoration:line-through;}
.stars{color:#f4b942;font-size:.72rem;}
.btn-add-cart{width:100%;background:var(--charcoal);color:#fff;border:none;padding:.6rem;border-radius:10px;font-size:.83rem;cursor:pointer;font-family:inherit;transition:background .2s;margin-top:.7rem;}
.btn-add-cart:hover{background:var(--rose);}
.pagination{display:flex;justify-content:center;gap:.4rem;margin-top:2.5rem;flex-wrap:wrap;}
.pagination a,.pagination span{padding:.5rem .9rem;border-radius:8px;font-size:.88rem;border:1px solid var(--border);background:#fff;color:var(--charcoal);transition:all .2s;}
.pagination a:hover{border-color:var(--rose);color:var(--rose);}
.pagination .active span{background:var(--rose);color:#fff;border-color:var(--rose);}
@media(max-width:768px){.shop-wrap{grid-template-columns:1fr;} .sidebar{position:static;}}
</style>
@endpush

@section('content')
<div class="shop-wrap">
    <!-- SIDEBAR FILTERS -->
    <aside class="sidebar">
        <h3>Filters</h3>
        <form method="GET" action="{{ route('products.index') }}">
            @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif

            <div class="filter-group">
                <h4>Categories</h4>
                <ul>
                    <li><a href="{{ route('products.index') }}" class="{{ !request('category') ? 'active' : '' }}">All Categories</a></li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('products.index', ['category'=>$cat->slug]) }}" class="{{ request('category')==$cat->slug ? 'active' : '' }}">
                                {{ $cat->name }}
                                @if($cat->children->count())<span>+{{ $cat->children->count() }}</span>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="filter-group">
                <h4>Show</h4>
                <ul>
                    <li><a href="{{ route('products.index', array_merge(request()->except('filter'), [])) }}" class="{{ !request('filter') ? 'active':'' }}">All</a></li>
                    <li><a href="{{ route('products.index', ['filter'=>'new']) }}" class="{{ request('filter')=='new'?'active':'' }}">New Arrivals</a></li>
                    <li><a href="{{ route('products.index', ['filter'=>'sale']) }}" class="{{ request('filter')=='sale'?'active':'' }}">On Sale</a></li>
                    <li><a href="{{ route('products.index', ['filter'=>'featured']) }}" class="{{ request('filter')=='featured'?'active':'' }}">Featured</a></li>
                    <li><a href="{{ route('products.index', ['filter'=>'bestseller']) }}" class="{{ request('filter')=='bestseller'?'active':'' }}">Best Sellers</a></li>
                </ul>
            </div>

            <div class="filter-group">
                <h4>Price Range (KSh)</h4>
                <div class="price-inputs">
                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                    <span>—</span>
                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                </div>
                <br>
                <button type="submit" class="btn-filter">Apply Filters</button>
            </div>
        </form>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="shop-main">
        <div class="shop-topbar">
            <p>{{ $products->total() }} products found</p>
            <form method="GET" action="{{ route('products.index') }}">
                @foreach(request()->except('sort') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>Latest</option>
                    <option value="price_low" {{ request('sort')=='price_low'?'selected':'' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort')=='price_high'?'selected':'' }}>Price: High to Low</option>
                    <option value="name" {{ request('sort')=='name'?'selected':'' }}>Name A–Z</option>
                </select>
            </form>
        </div>

        @if($products->isEmpty())
            <div style="text-align:center;padding:4rem;color:#888">
                <div style="font-size:3rem;margin-bottom:1rem">🔍</div>
                <h3>No products found</h3>
                <p>Try different filters or <a href="{{ route('products.index') }}" style="color:var(--rose)">view all products</a>.</p>
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
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({product_id: id, quantity: 1})
        }).then(r=>r.json()).then(d=>{
            if(d.success){
                document.getElementById('cart-count').textContent = d.count;
                this.textContent='✓ Added!';
                setTimeout(()=>{ this.textContent='Add to Cart'; },2000);
            }
        });
    });
});
</script>
@endpush
