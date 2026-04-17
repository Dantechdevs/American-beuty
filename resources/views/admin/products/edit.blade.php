@extends('layouts.admin')
@section('title','Edit Product')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.3rem;font-weight:700">Edit: {{ $product->name }}</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form action="{{ route('admin.products.update',$product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">
        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3>Basic Information</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="name" value="{{ old('name',$product->name) }}" required>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Category *</label>
                            <select name="category_id" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Brand</label>
                            <select name="brand_id">
                                <option value="">No brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id==$brand->id?'selected':'' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Short Description</label>
                        <textarea name="short_description" rows="2">{{ old('short_description',$product->short_description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Full Description</label>
                        <textarea name="description" rows="5">{{ old('description',$product->description) }}</textarea>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Skin Type</label>
                            <select name="skin_type">
                                @foreach(['','All Skin Types','Dry','Oily','Combination','Sensitive','Normal'] as $type)
                                    <option value="{{ $type }}" {{ $product->skin_type==$type?'selected':'' }}>{{ $type ?: 'Select...' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Concern</label>
                            <input type="text" name="concern" value="{{ old('concern',$product->concern) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ingredients</label>
                        <textarea name="ingredients" rows="2">{{ old('ingredients',$product->ingredients) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3>Pricing & Inventory</h3></div>
                <div class="card-body">
                    <div class="form-grid-3">
                        <div class="form-group">
                            <label>Regular Price (KSh) *</label>
                            <input type="number" name="price" value="{{ old('price',$product->price) }}" required min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Sale Price (KSh)</label>
                            <input type="number" name="sale_price" value="{{ old('sale_price',$product->sale_price) }}" min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="text" name="sku" value="{{ old('sku',$product->sku) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity *</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity',$product->stock_quantity) }}" required min="0">
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3>Thumbnail</h3></div>
                <div class="card-body">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/'.$product->thumbnail) }}" style="width:100%;border-radius:10px;margin-bottom:.8rem;object-fit:cover;height:180px">
                    @endif
                    <div class="form-group">
                        <label>Replace Image</label>
                        <input type="file" name="thumbnail" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3>Product Flags</h3></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:.9rem">
                    <label class="form-check"><input type="checkbox" name="is_active" value="1" {{ $product->is_active?'checked':'' }}> Active</label>
                    <label class="form-check"><input type="checkbox" name="is_featured" value="1" {{ $product->is_featured?'checked':'' }}> Featured</label>
                    <label class="form-check"><input type="checkbox" name="is_new_arrival" value="1" {{ $product->is_new_arrival?'checked':'' }}> New Arrival</label>
                    <label class="form-check"><input type="checkbox" name="is_best_seller" value="1" {{ $product->is_best_seller?'checked':'' }}> Best Seller</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1.2rem;padding:.9rem;justify-content:center"><i class="fas fa-save"></i> Update Product</button>
        </div>
    </div>
</form>
@endsection
