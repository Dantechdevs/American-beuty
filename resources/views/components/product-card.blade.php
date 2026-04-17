<div class="product-card">
    <div class="product-img">
        @if($product->thumbnail)
            <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="{{ $product->name }}">
        @else
            <div class="product-img-placeholder">✦ {{ strtoupper(substr($product->name,0,12)) }}</div>
        @endif
        @if($product->sale_price)
            <span class="badge-sale">-{{ $product->getDiscountPercent() }}%</span>
        @elseif($product->is_new_arrival)
            <span class="badge-new">NEW</span>
        @endif
        <button class="product-wish"><i class="far fa-heart"></i></button>
    </div>
    <div class="product-body">
        <div class="product-category">{{ $product->category->name ?? '' }}</div>
        <div class="product-name"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></div>
        <div class="product-pricing">
            <span class="price-current">KSh {{ number_format($product->getCurrentPrice(), 0) }}</span>
            @if($product->sale_price)
                <span class="price-original">KSh {{ number_format($product->price, 0) }}</span>
            @endif
        </div>
        <div class="stars">
            @for($i=1;$i<=5;$i++)
                <i class="{{ $i <= $product->getAverageRating() ? 'fas' : 'far' }} fa-star"></i>
            @endfor
        </div>
        @if($product->isInStock())
            <button class="btn-add-cart" data-id="{{ $product->id }}">Add to Cart</button>
        @else
            <button class="btn-add-cart" disabled style="background:#ccc;cursor:not-allowed">Out of Stock</button>
        @endif
    </div>
</div>
