@extends('layouts.admin')
@section('title', 'Service Categories')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Service Categories</h4>
            <small class="text-muted">Manage categories shown on the services page</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-list me-1"></i> All Services
            </a>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus me-1"></i> Add Category
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Color Class</th>
                        <th>Services</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td style="font-size:1.4rem">{{ $cat->icon }}</td>
                        <td class="fw-semibold">{{ $cat->name }}</td>
                        <td><code>{{ $cat->slug }}</code></td>
                        <td><code>{{ $cat->color_class }}</code></td>
                        <td><span class="badge bg-primary">{{ $cat->services_count }}</span></td>
                        <td>{{ $cat->sort_order }}</td>
                        <td>
                            @if($cat->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal{{ $cat->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.services.categories.destroy', $cat) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this category and all its services?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Modal per category --}}
                    <div class="modal fade" id="editCategoryModal{{ $cat->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.services.categories.update', $cat) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Category</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body row g-3">
                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold">Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Icon (emoji)</label>
                                            <input type="text" name="icon" class="form-control" value="{{ $cat->icon }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold">Color Class</label>
                                            <input type="text" name="color_class" class="form-control" value="{{ $cat->color_class }}" placeholder="e.g. c-hydra">
                                            <small class="text-muted">Options: c-hydra, c-express, c-antiage, c-custom, c-micro, c-wax, c-skin, c-addon</small>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Sort Order</label>
                                            <input type="number" name="sort_order" class="form-control" value="{{ $cat->sort_order }}">
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" @checked($cat->is_active)>
                                                <label class="form-check-label">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.services.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Icon (emoji)</label>
                        <input type="text" name="icon" class="form-control" placeholder="💧">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Color Class</label>
                        <input type="text" name="color_class" class="form-control" placeholder="c-hydra">
                        <small class="text-muted">Options: c-hydra, c-express, c-antiage, c-custom, c-micro, c-wax, c-skin, c-addon</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
