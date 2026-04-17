@extends('layouts.admin')
@section('title', isset($category) ? 'Edit Category' : 'Add Category')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.3rem;font-weight:700">{{ isset($category) ? 'Edit: '.$category->name : 'Add Category' }}</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="max-width:600px">
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($category) ? route('admin.categories.update',$category) : route('admin.categories.store') }}" method="POST">
                @csrf
                @if(isset($category)) @method('PUT') @endif

                <div class="form-group">
                    <label>Category Name *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required placeholder="e.g. Serums">
                    @error('name')<p style="color:#e74c3c;font-size:.78rem;margin-top:.2rem">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label>Parent Category</label>
                    <select name="parent_id">
                        <option value="">None (Top Level)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ (old('parent_id', $category->parent_id ?? ''))==$parent->id?'selected':'' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Brief category description...">{{ old('description', $category->description ?? '') }}</textarea>
                </div>

                <label class="form-check" style="margin-bottom:1.5rem">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                    <span>Active (visible in store)</span>
                </label>

                <div style="display:flex;gap:.8rem">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ isset($category) ? 'Update' : 'Create' }} Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
