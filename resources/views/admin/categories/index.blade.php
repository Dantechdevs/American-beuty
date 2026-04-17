@extends('layouts.admin')
@section('title','Categories')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.3rem;font-weight:700">Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Parent</th><th>Products</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td><strong>{{ $cat->name }}</strong><br><span style="font-size:.75rem;color:#888">{{ $cat->slug }}</span></td>
                    <td style="font-size:.85rem;color:#666">{{ $cat->parent->name ?? '—' }}</td>
                    <td><span class="badge badge-info">{{ $cat->products_count }}</span></td>
                    <td><span class="badge {{ $cat->is_active?'badge-success':'badge-secondary' }}">{{ $cat->is_active?'Active':'Inactive' }}</span></td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            <a href="{{ route('admin.categories.edit',$cat) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.categories.destroy',$cat) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#aaa;padding:3rem">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $categories->links() }}</div>
</div>
@endsection
