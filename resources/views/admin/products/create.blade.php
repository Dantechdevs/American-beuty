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
                            <div style="display:flex;gap:.5rem;align-items:center;">
                                <select name="brand_id" id="brand_id" style="flex:1;">
                                    <option value="">No brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id?'selected':'' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="document.getElementById('brand-modal').style.display='flex'" style="background:var(--pink,#FF0A6C);color:#fff;border:none;border-radius:8px;padding:.45rem .9rem;font-size:.8rem;font-weight:700;cursor:pointer;white-space:nowrap;">+ Brand</button>
                            </div>
                        </div>

{{-- Quick Add Brand Modal --}}
<div id="brand-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:2rem;width:100%;max-width:400px;box-shadow:0 8px 32px rgba(0,0,0,.2);">
        <h3 style="margin:0 0 1rem;font-size:1.1rem;">Add New Brand</h3>
        <input type="text" id="new-brand-name" placeholder="Brand name" style="width:100%;padding:.6rem .9rem;border:1px solid #ddd;border-radius:8px;font-size:.9rem;margin-bottom:.8rem;box-sizing:border-box;">
        <p id="brand-modal-msg" style="font-size:.8rem;min-height:1rem;margin-bottom:.5rem;"></p>
        <div style="display:flex;gap:.7rem;justify-content:flex-end;">
            <button type="button" onclick="document.getElementById('brand-modal').style.display='none'" style="padding:.5rem 1.2rem;border:1px solid #ddd;border-radius:8px;background:#fff;cursor:pointer;">Cancel</button>
            <button type="button" onclick="quickAddBrand()" style="padding:.5rem 1.2rem;border:none;border-radius:8px;background:var(--pink,#FF0A6C);color:#fff;font-weight:700;cursor:pointer;">Save Brand</button>
        </div>
    </div>
</div>
<script>
function quickAddBrand() {
    var name = document.getElementById('new-brand-name').value.trim();
    var msg  = document.getElementById('brand-modal-msg');
    fetch('{{ route('admin.brands.quick-add') }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
        body: JSON.stringify({name: name})
    }).then(function(r){ return r.json(); }).then(function(d) {
        if (d.id) {
            var select = document.getElementById('brand_id');
            var option = new Option(d.name, d.id, true, true);
            select.add(option);
            document.getElementById('brand-modal').style.display = 'none';
            document.getElementById('new-brand-name').value = '';
            msg.textContent = '';
        } else {
            msg.style.color='#dc2626';
            msg.textContent = d.message || 'Error saving brand.';
        }
    }).catch(function() {
        msg.style.color='#dc2626'; msg.textContent='Network error.';
    });
}
</script>
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
                            <label>Weight / Volume</label>
                            <div style="display:flex;gap:.5rem;">
                                <input type="number" name="weight" value="{{ old('weight') }}" min="0" step="0.01" placeholder="e.g. 250" style="flex:1;">
                                <select name="unit" style="width:90px;">
                                    <option value="">Unit</option>
                                    <option value="ml" {{ old('unit')=='ml'?'selected':'' }}>ml</option>
                                    <option value="L" {{ old('unit')=='L'?'selected':'' }}>L</option>
                                    <option value="g" {{ old('unit')=='g'?'selected':'' }}>g</option>
                                    <option value="kg" {{ old('unit')=='kg'?'selected':'' }}>kg</option>
                                    <option value="oz" {{ old('unit')=='oz'?'selected':'' }}>oz</option>
                                    <option value="fl oz" {{ old('unit')=='fl oz'?'selected':'' }}>fl oz</option>
                                    <option value="pcs" {{ old('unit')=='pcs'?'selected':'' }}>pcs</option>
                                    <option value="set" {{ old('unit')=='set'?'selected':'' }}>set</option>
                                </select>
                            </div>
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
