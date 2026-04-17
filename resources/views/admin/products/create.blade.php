@extends('layouts.admin')
@section('title','Add Product')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.3rem;font-weight:700">Add New Product</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">
        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3>Basic Information</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Hydrating Facial Serum">
                        @error('name')<p style="color:#e74c3c;font-size:.78rem;margin-top:.2rem">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Category *</label>
                            <select name="category_id" required>
                                <option value="">Select category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Brand</label>
                            <select name="brand_id">
                                <option value="">No brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id?'selected':'' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Short Description</label>
                        <textarea name="short_description" rows="2" placeholder="Brief product summary...">{{ old('short_description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Full Description</label>
                        <textarea name="description" rows="5" placeholder="Detailed product description...">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Skin Type</label>
                            <select name="skin_type">
                                <option value="">Select...</option>
                                <option>All Skin Types</option><option>Dry</option><option>Oily</option>
                                <option>Combination</option><option>Sensitive</option><option>Normal</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Concern</label>
                            <input type="text" name="concern" value="{{ old('concern') }}" placeholder="e.g. Anti-aging, Brightening">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ingredients</label>
                        <textarea name="ingredients" rows="2" placeholder="List key ingredients...">{{ old('ingredients') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3>Pricing & Inventory</h3></div>
                <div class="card-body">
                    <div class="form-grid-3">
                        <div class="form-group">
                            <label>Regular Price (KSh) *</label>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Sale Price (KSh)</label>
                            <input type="number" name="sale_price" value="{{ old('sale_price') }}" min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" placeholder="Auto-generated if blank">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Stock Quantity *</label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity',0) }}" required min="0">
                        </div>
                        <div class="form-group">
                            <label>Weight (g)</label>
                            <input type="number" name="weight" value="{{ old('weight') }}" min="0" step="0.01">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header"><h3>Thumbnail</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="thumbnail" accept="image/*">
                        <p style="font-size:.75rem;color:#aaa;margin-top:.3rem">JPG, PNG. Max 2MB.</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3>Product Flags</h3></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:.9rem">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active',1)?'checked':'' }}>
                        <span>Active (visible in store)</span>
                    </label>
                    <label class="form-check">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured')?'checked':'' }}>
                        <span>Featured on homepage</span>
                    </label>
                    <label class="form-check">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival')?'checked':'' }}>
                        <span>New Arrival</span>
                    </label>
                    <label class="form-check">
                        <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller')?'checked':'' }}>
                        <span>Best Seller</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1.2rem;padding:.9rem;font-size:.95rem;justify-content:center">
                <i class="fas fa-save"></i> Save Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="width:100%;margin-top:.5rem;justify-content:center">Cancel</a>
        </div>
    </div>
</form>
@endsection
