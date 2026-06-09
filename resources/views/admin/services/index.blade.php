@extends('layouts.admin')
@section('title', 'Services')

@section('content')

{{-- Header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title"><i class="fas fa-spa" style="color:var(--purple)"></i> Services</div>
        <div class="page-sub">Manage treatments displayed on the public services page</div>
    </div>
    <div style="display:flex;gap:.5rem">
        <a href="{{ route('admin.services.categories') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-tags"></i> Categories
        </a>
        <button onclick="openModal('createModal')" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Service
        </button>
    </div>
</div>

@if(session('success'))
<div class="flash success" style="margin-bottom:1rem">
    <i class="fas fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:1.25rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-spa"></i></div>
        <div>
            <div class="stat-value">{{ $services->count() }}</div>
            <div class="stat-label">Total Services</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value">{{ $services->where('is_active',true)->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-tags"></i></div>
        <div>
            <div class="stat-value">{{ $categories->count() }}</div>
            <div class="stat-label">Categories</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-eye-slash"></i></div>
        <div>
            <div class="stat-value">{{ $services->where('is_active',false)->count() }}</div>
            <div class="stat-label">Inactive</div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body" style="padding:0">
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr>
                    <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);background:linear-gradient(120deg,var(--pink-soft),#fff8fb);border-bottom:1.5px solid var(--border);text-align:left">Service</th>
                    <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);background:linear-gradient(120deg,var(--pink-soft),#fff8fb);border-bottom:1.5px solid var(--border);text-align:left">Category</th>
                    <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);background:linear-gradient(120deg,var(--pink-soft),#fff8fb);border-bottom:1.5px solid var(--border);text-align:left">Price</th>
                    <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);background:linear-gradient(120deg,var(--pink-soft),#fff8fb);border-bottom:1.5px solid var(--border);text-align:left">Duration</th>
                    <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);background:linear-gradient(120deg,var(--pink-soft),#fff8fb);border-bottom:1.5px solid var(--border);text-align:left">Status</th>
                    <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);background:linear-gradient(120deg,var(--pink-soft),#fff8fb);border-bottom:1.5px solid var(--border);text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $svc)
                <tr style="border-bottom:1px solid var(--border);transition:background .15s" onmouseover="this.style.background='#fff8fb'" onmouseout="this.style.background=''">
                    <td style="padding:.85rem 1rem;max-width:280px">
                        <div style="font-weight:600;font-size:.88rem;color:var(--text)">{{ $svc->name }}</div>
                        <div style="font-size:.75rem;color:var(--muted);margin-top:.1rem">{{ Str::limit($svc->description, 65) }}</div>
                    </td>
                    <td style="padding:.85rem 1rem">
                        <span style="background:#f3e8ff;color:var(--purple);border:1px solid #e9d5ff;padding:.2rem .75rem;border-radius:20px;font-size:.72rem;font-weight:600">
                            {{ $svc->category->icon ?? '' }} {{ $svc->category->name ?? '—' }}
                        </span>
                    </td>
                    <td style="padding:.85rem 1rem;font-size:.85rem;font-weight:600;color:var(--text);white-space:nowrap">
                        {{ $svc->price_display ?? '—' }}
                    </td>
                    <td style="padding:.85rem 1rem;font-size:.8rem;color:var(--muted);white-space:nowrap">
                        @if($svc->duration)
                            <i class="fas fa-clock" style="color:var(--pink);font-size:.7rem;margin-right:.3rem"></i>{{ $svc->duration }}
                        @else —
                        @endif
                    </td>
                    <td style="padding:.85rem 1rem">
                        @if($svc->is_active)
                            <span class="badge badge-success"><i class="fas fa-circle" style="font-size:.45rem;vertical-align:middle"></i> Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td style="padding:.85rem 1rem;text-align:right">
                        <a href="{{ route('admin.services.edit', $svc) }}" class="btn btn-outline btn-sm" style="margin-right:.3rem">
                            <i class="fas fa-pen-to-square"></i> Edit
                        </a>
                        <form action="{{ route('admin.services.destroy', $svc) }}" method="POST" style="display:inline"
                              onsubmit="return confirm('Delete {{ addslashes($svc->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:#fff1f2;color:#e11d48;border:1.5px solid #fecdd3">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:3rem 1rem;color:var(--muted)">
                        <i class="fas fa-spa" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.3"></i>
                        <div style="font-weight:600;margin-bottom:.25rem">No services yet</div>
                        <small>Click "Add Service" to get started</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══════════════════════════════════════
     CREATE MODAL
══════════════════════════════════════ --}}
<div id="createModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:640px;max-height:90vh;overflow-y:auto;box-shadow:0 24px 60px rgba(0,0,0,.18);margin:1rem">
        <form action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            {{-- Header --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1.5px solid var(--border);background:linear-gradient(120deg,#fff,var(--pink-soft))">
                <div style="display:flex;align-items:center;gap:.75rem">
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-spa" style="color:#fff;font-size:.85rem"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.95rem;color:var(--text)">Add New Service</div>
                        <div style="font-size:.75rem;color:var(--muted)">Fill in the treatment details</div>
                    </div>
                </div>
                <button type="button" onclick="closeModal('createModal')" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted)">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            {{-- Body --}}
            <div style="padding:1.5rem">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div style="grid-column:span 2">
                        <label class="form-group" style="display:block">
                            <span style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.38rem">Service Name <span style="color:var(--pink)">*</span></span>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Express HydraFacial" required>
                        </label>
                    </div>
                    <div style="grid-column:span 2">
                        <label class="form-group" style="display:block">
                            <span style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.38rem">Category <span style="color:var(--pink)">*</span></span>
                            <select name="service_category_id" class="form-control" required>
                                <option value="">Select category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div style="grid-column:span 2">
                        <label class="form-group" style="display:block">
                            <span style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.38rem">Description</span>
                            <textarea name="description" class="form-control" rows="3" placeholder="Brief description of this treatment..."></textarea>
                        </label>
                    </div>
                    {{-- Divider --}}
                    <div style="grid-column:span 2;display:flex;align-items:center;gap:.75rem;margin:.25rem 0">
                        <div style="height:1px;flex:1;background:linear-gradient(to right,var(--border),transparent)"></div>
                        <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--purple)"><i class="fas fa-tag" style="margin-right:.3rem"></i>Pricing & Duration</span>
                        <div style="height:1px;flex:1;background:linear-gradient(to left,var(--border),transparent)"></div>
                    </div>
                    <div style="grid-column:span 2">
                        <label class="form-group" style="display:block">
                            <span style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.38rem">Price Display</span>
                            <input type="text" name="price_display" class="form-control" placeholder="Ksh 6,000 – 8,000">
                        </label>
                    </div>
                    <div>
                        <label class="form-group" style="display:block">
                            <span style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.38rem">From (Ksh)</span>
                            <input type="number" name="price_from" class="form-control" placeholder="6000">
                        </label>
                    </div>
                    <div>
                        <label class="form-group" style="display:block">
                            <span style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.38rem">To (Ksh)</span>
                            <input type="number" name="price_to" class="form-control" placeholder="8000">
                        </label>
                    </div>
                    <div>
                        <label class="form-group" style="display:block">
                            <span style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.38rem">Duration</span>
                            <input type="text" name="duration" class="form-control" placeholder="30–40 mins">
                        </label>
                    </div>
                    <div>
                        <label class="form-group" style="display:block">
                            <span style="font-size:.78rem;font-weight:600;color:var(--muted);display:block;margin-bottom:.38rem">Sort Order</span>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </label>
                    </div>
                    <div style="grid-column:span 2">
                        <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;padding:.75rem 1rem;background:var(--pink-soft);border-radius:10px;border:1.5px solid var(--border)">
                            <input type="checkbox" name="is_active" checked style="width:16px;height:16px;accent-color:var(--purple)">
                            <span style="font-size:.85rem;font-weight:600;color:var(--text)">Active — visible on website</span>
                        </label>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div style="display:flex;justify-content:flex-end;gap:.75rem;padding:1rem 1.5rem;border-top:1.5px solid var(--border);background:#fafafa;border-radius:0 0 16px 16px">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save" style="margin-right:.35rem"></i> Save Service</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const m = document.getElementById(id);
    m.style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('createModal');
});
</script>
@endpush
