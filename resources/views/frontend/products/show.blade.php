@extends('layouts.app')
@section('title', $product->name)

@push('styles')
<style>
:root{--rose:#c8847a;--rose-dk:#a05e56;--cream:#faf7f4;--sand:#f0e8df;--charcoal:#2c2c2c;--border:#e8ddd6;}
.product-detail{max-width:1200px;margin:2.5rem auto;padding:0 1.5rem;}
.breadcrumb{font-size:.82rem;color:#888;margin-bottom:2rem;display:flex;gap:.4rem;align-items:center;flex-wrap:wrap;}
.breadcrumb a{color:#888;transition:color .2s;}.breadcrumb a:hover{color:var(--rose);}
.breadcrumb span{color:#ccc;}
.product-grid{display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:start;}
/* GALLERY */
.gallery-main{aspect-ratio:1;background:linear-gradient(135deg,var(--sand),#e8d5cc);border-radius:24px;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;}
.gallery-main img{width:100%;height:100%;object-fit:cover;}
.gallery-placeholder{font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:#b09080;letter-spacing:.1em;}
.gallery-thumbs{display:flex;gap:.6rem;}
.gallery-thumb{width:72px;height:72px;background:var(--sand);border-radius:12px;overflow:hidden;cursor:pointer;border:2px solid transparent;transition:border-color .2s;flex-shrink:0;}
.gallery-thumb:hover,.gallery-thumb.active{border-color:var(--rose);}
.gallery-thumb img{width:100%;height:100%;object-fit:cover;}
/* INFO */
.product-info{}
.product-info .category-tag{font-size:.75rem;color:var(--rose);text-transform:uppercase;letter-spacing:.15em;font-weight:600;margin-bottom:.6rem;}
.product-info h1{font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:400;line-height:1.2;margin-bottom:.8rem;}
.rating-row{display:flex;align-items:center;gap:.8rem;margin-bottom:1.2rem;}
.stars{color:#f4b942;font-size:.9rem;}
.rating-count{font-size:.83rem;color:#888;}
.price-row{display:flex;align-items:baseline;gap:.8rem;margin-bottom:1.5rem;}
.price-main{font-size:2rem;font-weight:700;color:var(--charcoal);}
.price-old{font-size:1.1rem;color:#aaa;text-decoration:line-through;}
.badge-discount{background:var(--rose);color:#fff;font-size:.75rem;font-weight:700;padding:.25rem .7rem;border-radius:20px;}
.product-desc{color:#666;font-size:.93rem;line-height:1.8;margin-bottom:1.5rem;}
.product-meta{display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.8rem;font-size:.87rem;}
.product-meta span{color:#555;}.product-meta strong{color:var(--charcoal);}
.stock-badge{display:inline-flex;align-items:center;gap:.4rem;font-size:.82rem;font-weight:600;padding:.3rem .8rem;border-radius:20px;margin-bottom:1.2rem;}
.in-stock{background:#d4edda;color:#155724;}
.out-stock{background:#f8d7da;color:#721c24;}
.qty-row{display:flex;align-items:center;gap:1rem;margin-bottom:1.2rem;}
.qty-control{display:flex;align-items:center;border:1.5px solid var(--border);border-radius:12px;overflow:hidden;}
.qty-btn{width:40px;height:44px;background:none;border:none;font-size:1.2rem;cursor:pointer;color:#555;transition:background .2s;}
.qty-btn:hover{background:var(--sand);}
.qty-value{width:50px;text-align:center;font-size:1rem;font-weight:600;border:none;background:none;font-family:inherit;}
.btn-add-cart{flex:1;background:var(--charcoal);color:#fff;border:none;padding:.85rem 1.5rem;border-radius:12px;font-size:.95rem;font-weight:600;cursor:pointer;font-family:inherit;transition:background .2s;}
.btn-add-cart:hover{background:var(--rose);}
.btn-wishlist{width:48px;height:48px;border:1.5px solid var(--border);background:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.2rem;color:#aaa;transition:all .2s;}
.btn-wishlist:hover{border-color:var(--rose);color:var(--rose);}
.divider{border:none;border-top:1px solid var(--border);margin:2rem 0;}
/* TABS */
.tabs{display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:2rem;}
.tab-btn{padding:.7rem 1.5rem;font-size:.9rem;font-weight:500;cursor:pointer;border-bottom:2px solid transparent;transition:all .2s;color:#888;background:none;border-top:none;border-left:none;border-right:none;font-family:inherit;}
.tab-btn.active,.tab-btn:hover{color:var(--rose);border-bottom-color:var(--rose);}
.tab-content{display:none;font-size:.92rem;color:#555;line-height:1.9;}
.tab-content.active{display:block;}
/* REVIEWS */
.review-card{background:var(--sand);border-radius:14px;padding:1.2rem 1.5rem;margin-bottom:1rem;}
.review-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem;}
.reviewer{font-weight:600;font-size:.9rem;}
.review-date{font-size:.78rem;color:#aaa;}
/* RELATED */
.related{max-width:1200px;margin:4rem auto;padding:0 1.5rem;}
.related h2{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:400;margin-bottom:2rem;}
.product-grid-related{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem;}
.product-card{background:#fff;border-radius:16px;overflow:hidden;transition:all .25s;border:1px solid var(--border);}
.product-card:hover{transform:translateY(-4px);box-shadow:0 15px 40px rgba(0,0,0,.08);}
.product-img{height:200px;background:linear-gradient(135deg,var(--sand),#e8d5cc);display:flex;align-items:center;justify-content:center;}
.product-img img{width:100%;height:100%;object-fit:cover;}
.product-img-placeholder{font-family:'Cormorant Garamond',serif;font-size:.85rem;color:#a08070;letter-spacing:.05em;}
.product-body{padding:.9rem;}
.product-name{font-size:.88rem;font-weight:500;margin-bottom:.4rem;}
.product-name a{color:var(--charcoal);}
.price-current{font-size:.95rem;font-weight:600;}
.btn-add-cart-sm{width:100%;background:var(--charcoal);color:#fff;border:none;padding:.55rem;border-radius:8px;font-size:.82rem;cursor:pointer;font-family:inherit;transition:background .2s;margin-top:.6rem;}
.btn-add-cart-sm:hover{background:var(--rose);}
@media(max-width:768px){.product-grid{grid-template-columns:1fr;gap:2rem;}}
</style>
@endpush

@section('content')
<div class="product-detail">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a><span>/</span>
        <a href="{{ route('products.index') }}">Shop</a><span>/</span>
        @if($product->category)
            <a href="{{ route('products.index', ['category'=>$product->category->slug]) }}">{{ $product->category->name }}</a><span>/</span>
        @endif
        <span style="color:#555">{{ $product->name }}</span>
    </div>

    <div class="product-grid">
        <!-- GALLERY -->
        <div>
            <div class="gallery-main" id="main-img">
                @if($product->thumbnail)
                    <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="{{ $product->name }}" id="main-img-el">
                @else
                    <div class="gallery-placeholder">✦ AMERICAN BEAUTY</div>
                @endif
            </div>
            @if($product->images->count())
            <div class="gallery-thumbs">
                @foreach($product->images as $img)
                    <div class="gallery-thumb" onclick="setImage('{{ asset('storage/'.$img->image) }}', this)">
                        <img src="{{ asset('storage/'.$img->image) }}" alt="">
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- PRODUCT INFO -->
        <div class="product-info">
            <div class="category-tag">{{ $product->category->name ?? 'Beauty' }}</div>
            <h1>{{ $product->name }}</h1>

            <div class="rating-row">
                <div class="stars">
                    @for($i=1;$i<=5;$i++)
                        <i class="{{ $i <= $product->getAverageRating() ? 'fas' : 'far' }} fa-star"></i>
                    @endfor
                </div>
                <span class="rating-count">{{ $product->reviews->count() }} reviews</span>
            </div>

            <div class="price-row">
                <span class="price-main">KSh {{ number_format($product->getCurrentPrice(), 0) }}</span>
                @if($product->sale_price)
                    <span class="price-old">KSh {{ number_format($product->price, 0) }}</span>
                    <span class="badge-discount">Save {{ $product->getDiscountPercent() }}%</span>
                @endif
            </div>

            <p class="product-desc">{{ $product->short_description }}</p>

            <div class="product-meta">
                @if($product->brand) <span><strong>Brand:</strong> {{ $product->brand->name }}</span> @endif
                @if($product->skin_type) <span><strong>Skin Type:</strong> {{ $product->skin_type }}</span> @endif
                @if($product->concern) <span><strong>Concern:</strong> {{ $product->concern }}</span> @endif
                @if($product->sku) <span><strong>SKU:</strong> {{ $product->sku }}</span> @endif
            </div>

            <div class="stock-badge {{ $product->isInStock() ? 'in-stock' : 'out-stock' }}">
                <i class="fas fa-{{ $product->isInStock() ? 'check-circle' : 'times-circle' }}"></i>
                {{ $product->isInStock() ? 'In Stock' : 'Out of Stock' }}
                @if($product->isInStock() && $product->stock_quantity <= 10)
                    &nbsp;— Only {{ $product->stock_quantity }} left!
                @endif
            </div>

            @if($product->isInStock())
            <div class="qty-row">
                <div class="qty-control">
                    <button class="qty-btn" onclick="changeQty(-1)">−</button>
                    <input class="qty-value" id="qty" type="number" value="1" min="1" max="{{ $product->stock_quantity }}">
                    <button class="qty-btn" onclick="changeQty(1)">+</button>
                </div>
                <button class="btn-add-cart" id="add-cart-btn" data-id="{{ $product->id }}">
                    <i class="fas fa-shopping-bag"></i> Add to Cart
                </button>
                <button class="btn-wishlist" title="Add to Wishlist"><i class="far fa-heart"></i></button>
            </div>
            @else
                <p style="color:#e74c3c;font-weight:600;margin-bottom:1.2rem">This product is currently out of stock.</p>
            @endif

            <div style="font-size:.82rem;color:#888;display:flex;flex-direction:column;gap:.3rem">
                <span>🚚 Free delivery on orders over KSh 3,000</span>
                <span>📱 Pay easily with M-PESA STK Push</span>
                <span>↩️ 7-day hassle-free returns</span>
            </div>
        </div>
    </div>

    <!-- TABS: Description, Ingredients, Reviews -->
    <hr class="divider">
    <div class="tabs">
        <button class="tab-btn active" onclick="showTab('desc',this)">Description</button>
        <button class="tab-btn" onclick="showTab('ingredients',this)">Ingredients</button>
        <button class="tab-btn" onclick="showTab('reviews',this)">Reviews ({{ $product->reviews->count() }})</button>
    </div>

    <div id="tab-desc" class="tab-content active">
        {!! nl2br(e($product->description ?? $product->short_description)) !!}
    </div>
    <div id="tab-ingredients" class="tab-content">
        @if($product->ingredients)
            <p>{{ $product->ingredients }}</p>
        @else
            <p style="color:#aaa">Ingredient information not available for this product.</p>
        @endif
    </div>
    <div id="tab-reviews" class="tab-content">
        @forelse($product->reviews as $review)
        <div class="review-card">
            <div class="review-header">
                <span class="reviewer">{{ $review->user->name }}</span>
                <span class="review-date">{{ $review->created_at->format('d M Y') }}</span>
            </div>
            <div class="stars" style="margin-bottom:.5rem">
                @for($i=1;$i<=5;$i++)<i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star" style="font-size:.8rem"></i>@endfor
            </div>
            @if($review->title)<strong style="font-size:.9rem">{{ $review->title }}</strong><br>@endif
            <p style="font-size:.88rem;margin-top:.3rem">{{ $review->body }}</p>
        </div>
        @empty
            <p style="color:#aaa">No reviews yet. Be the first to review this product!</p>
        @endforelse
    </div>
</div>

<!-- RELATED PRODUCTS -->
@if($related->count())
<div class="related">
    <h2>You May Also Like</h2>
    <div class="product-grid-related">
        @foreach($related as $p)
        <div class="product-card">
            <div class="product-img">
                @if($p->thumbnail)
                    <img src="{{ asset('storage/'.$p->thumbnail) }}" alt="{{ $p->name }}">
                @else
                    <div class="product-img-placeholder">✦ {{ substr($p->name,0,10) }}</div>
                @endif
            </div>
            <div class="product-body">
                <div class="product-name"><a href="{{ route('products.show', $p->slug) }}">{{ $p->name }}</a></div>
                <div class="price-current">KSh {{ number_format($p->getCurrentPrice(),0) }}</div>
                <button class="btn-add-cart-sm" data-id="{{ $p->id }}">Add to Cart</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    input.value = Math.max(1, parseInt(input.value) + delta);
}
function showTab(name, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-'+name).classList.add('active');
    btn.classList.add('active');
}
function setImage(src, thumb) {
    document.getElementById('main-img-el').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
document.querySelectorAll('[data-id]').forEach(btn => {
    btn.addEventListener('click', function() {
        const id  = this.dataset.id;
        const qty = parseInt(document.getElementById('qty')?.value || 1);
        fetch('{{ route("cart.add") }}', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({product_id: id, quantity: qty})
        }).then(r=>r.json()).then(d=>{
            if(d.success){
                document.getElementById('cart-count').textContent = d.count;
                const orig = this.innerHTML;
                this.innerHTML = '✓ Added to Cart!';
                setTimeout(()=>{ this.innerHTML = orig; }, 2000);
            }
        });
    });
});
</script>
@endpush
