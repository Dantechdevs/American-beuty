@extends('layouts.admin')
@section('title','Products')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <h2 style="font-size:1.3rem;font-weight:700">All Products</h2>
        <p style="color:#888;font-size:.85rem;margin-top:.2rem">{{ $products->total() }} products total</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" style="display:flex;gap:.8rem;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." style="padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;width:220px">
            <select name="category" style="padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i> Filter</button>
            @if(request()->hasAny(['search','category']))<a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">Clear</a>@endif
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Flags</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.8rem">
                            <div style="width:44px;height:44px;background:var(--sand);border-radius:8px;overflow:hidden;flex-shrink:0">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/'.$product->thumbnail) }}" style="width:100%;height:100%;object-fit:cover">
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:.88rem">{{ $product->name }}</div>
                                <div style="font-size:.75rem;color:#888">{{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.85rem">{{ $product->category->name ?? '—' }}</td>
                    <td>
                        <strong>KSh {{ number_format($product->getCurrentPrice(),0) }}</strong>
                        @if($product->sale_price)<br><span style="font-size:.75rem;color:#aaa;text-decoration:line-through">KSh {{ number_format($product->price,0) }}</span>@endif
                    </td>
                    <td>
                        <span class="{{ $product->stock_quantity <= 5 ? 'badge badge-danger' : 'badge badge-success' }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </td>
                    <td style="font-size:.75rem">
                        @if($product->is_featured)<span class="badge badge-info">Featured</span> @endif
                        @if($product->is_new_arrival)<span class="badge badge-primary">New</span> @endif
                        @if($product->is_best_seller)<span class="badge badge-warning">Best Seller</span> @endif
                    </td>
                    <td>
                        <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            <a href="{{ route('admin.products.edit',$product) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.products.destroy',$product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:#aaa;padding:3rem">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $products->links() }}</div>
</div>
@endsection
