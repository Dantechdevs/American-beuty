@extends('layouts.admin')
@section('title', 'Edit Service')

@section('content')

{{-- Header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div style="display:flex;align-items:center;gap:.75rem">
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <div class="page-title"><i class="fas fa-pen-to-square" style="color:var(--purple)"></i> Edit Service</div>
            <div class="page-sub">{{ $service->name }}</div>
        </div>
    </div>
    <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
          onsubmit="return confirm('Permanently delete {{ addslashes($service->name) }}?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm" style="background:#fff1f2;color:#e11d48;border:1.5px solid #fecdd3;font-weight:600">
            <i class="fas fa-trash"></i> Delete Service
        </button>
    </form>
</div>

@if(session('success'))
<div class="flash success" style="margin-bottom:1.25rem">
    <i class="fas fa-circle-check"></i> {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.25rem;align-items:start">

    {{-- ── Left: Main Form ── --}}
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:.6rem">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-spa" style="color:#fff;font-size:.8rem"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.9rem;color:var(--text)">Service Details</div>
                    <div style="font-size:.72rem;color:var(--muted)">Update treatment information</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.services.update', $service) }}" method="POST" id="editForm">
                @csrf @method('PUT')

                {{-- Name + Category --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                    <div class="form-group">
                        <label>Service Name <span style="color:var(--pink)">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $service->name) }}" required
                            placeholder="e.g. Express HydraFacial">
                        @error('name')<div style="color:#e11d48;font-size:.75rem;margin-top:.25rem">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Category <span style="color:var(--pink)">*</span></label>
                        <select name="service_category_id" class="form-control" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $cat->id == $service->service_category_id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="form-group" style="margin-bottom:1.25rem">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"
                        placeholder="Brief description of this treatment..."
                        style="resize:vertical">{{ old('description', $service->description) }}</textarea>
                </div>

                {{-- Pricing divider --}}
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem">
                    <div style="height:1px;flex:1;background:linear-gradient(to right,var(--border),transparent)"></div>
                    <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--purple)">
                        <i class="fas fa-tag" style="margin-right:.3rem"></i>Pricing & Duration
                    </span>
                    <div style="height:1px;flex:1;background:linear-gradient(to left,var(--border),transparent)"></div>
                </div>

                {{-- Price fields --}}
                <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:1rem;margin-bottom:1.25rem">
                    <div class="form-group">
                        <label>Price Display</label>
                        <input type="text" name="price_display" class="form-control"
                            value="{{ old('price_display', $service->price_display) }}"
                            placeholder="Ksh 6,000 – 8,000">
                    </div>
                    <div class="form-group">
                        <label>From (Ksh)</label>
                        <input type="number" name="price_from" class="form-control"
                            value="{{ old('price_from', $service->price_from) }}"
                            placeholder="6000">
                    </div>
                    <div class="form-group">
                        <label>To (Ksh)</label>
                        <input type="number" name="price_to" class="form-control"
                            value="{{ old('price_to', $service->price_to) }}"
                            placeholder="8000">
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" name="duration" class="form-control"
                            value="{{ old('duration', $service->duration) }}"
                            placeholder="30–40 mins">
                    </div>
                </div>

                {{-- Sort Order --}}
                <div class="form-group" style="max-width:160px;margin-bottom:1.5rem">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control"
                        value="{{ old('sort_order', $service->sort_order) }}">
                </div>

                {{-- Submit --}}
                <div style="display:flex;gap:.75rem">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Service
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Right: Sidebar cards ── --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

        {{-- Status card --}}
        <div class="card">
            <div class="card-header">
                <span style="font-weight:700;font-size:.88rem;color:var(--text)">
                    <i class="fas fa-toggle-on" style="color:var(--purple);margin-right:.4rem"></i>Visibility
                </span>
            </div>
            <div class="card-body">
                <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer;padding:.85rem 1rem;border-radius:10px;border:1.5px solid var(--border);background:var(--pink-soft);transition:all .15s"
                       id="activeToggleLabel">
                    <input type="checkbox" name="is_active" id="is_active" form="editForm"
                        {{ $service->is_active ? 'checked' : '' }}
                        style="width:16px;height:16px;accent-color:var(--purple)"
                        onchange="updateToggle(this)">
                    <div>
                        <div style="font-size:.85rem;font-weight:700;color:var(--text)" id="toggleTitle">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </div>
                        <div style="font-size:.73rem;color:var(--muted)">
                            {{ $service->is_active ? 'Visible on public services page' : 'Hidden from public page' }}
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Meta card --}}
        <div class="card">
            <div class="card-header">
                <span style="font-weight:700;font-size:.88rem;color:var(--text)">
                    <i class="fas fa-circle-info" style="color:var(--purple);margin-right:.4rem"></i>Info
                </span>
            </div>
            <div class="card-body" style="padding:.75rem 1rem">
                <div style="display:flex;flex-direction:column;gap:.65rem">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem">
                        <span style="color:var(--muted);font-weight:500">ID</span>
                        <span style="font-weight:700;color:var(--text)">#{{ $service->id }}</span>
                    </div>
                    <div style="height:1px;background:var(--border)"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem">
                        <span style="color:var(--muted);font-weight:500">Category</span>
                        <span style="background:#f3e8ff;color:var(--purple);padding:.15rem .6rem;border-radius:20px;font-size:.72rem;font-weight:600">
                            {{ $service->category->icon ?? '' }} {{ $service->category->name ?? '—' }}
                        </span>
                    </div>
                    <div style="height:1px;background:var(--border)"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem">
                        <span style="color:var(--muted);font-weight:500">Created</span>
                        <span style="font-weight:600;color:var(--text)">{{ $service->created_at->format('d M Y') }}</span>
                    </div>
                    <div style="height:1px;background:var(--border)"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem">
                        <span style="color:var(--muted);font-weight:500">Updated</span>
                        <span style="font-weight:600;color:var(--text)">{{ $service->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Preview card --}}
        <div class="card">
            <div class="card-header">
                <span style="font-weight:700;font-size:.88rem;color:var(--text)">
                    <i class="fas fa-eye" style="color:var(--purple);margin-right:.4rem"></i>Preview
                </span>
            </div>
            <div class="card-body" style="padding:.85rem">
                <div style="border-radius:12px;border:1.5px solid #e9d5ff;padding:1rem;background:#faf5ff;position:relative;overflow:hidden">
                    <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--purple),var(--pink))"></div>
                    <div style="font-weight:700;font-size:.9rem;color:#1a0a2e;margin-bottom:.3rem">{{ $service->name }}</div>
                    <div style="font-size:.75rem;color:#8A7A9A;line-height:1.6;margin-bottom:.75rem">{{ Str::limit($service->description, 80) }}</div>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <div>
                            <div style="font-size:.8rem;font-weight:700;color:var(--purple)">{{ $service->price_display ?? 'No price set' }}</div>
                            @if($service->duration)
                            <div style="font-size:.68rem;color:#8A7A9A;margin-top:.1rem"><i class="fas fa-clock" style="margin-right:.25rem;color:var(--pink)"></i>{{ $service->duration }}</div>
                            @endif
                        </div>
                        <span style="font-size:.7rem;font-weight:600;background:#fce4f3;color:var(--pink);padding:.3rem .75rem;border-radius:20px;border:1px solid #f0c8e4">Book →</span>
                    </div>
                </div>
                <div style="margin-top:.75rem;text-align:center">
                    <a href="{{ route('services') }}" target="_blank" style="font-size:.75rem;color:var(--purple);text-decoration:none;font-weight:600">
                        <i class="fas fa-arrow-up-right-from-square" style="margin-right:.3rem"></i>View on public page
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function updateToggle(checkbox) {
    const label = document.getElementById('activeToggleLabel');
    const title = document.getElementById('toggleTitle');
    if (checkbox.checked) {
        title.textContent = 'Active';
        label.style.background = 'var(--pink-soft)';
        label.style.borderColor = 'var(--border)';
    } else {
        title.textContent = 'Inactive';
        label.style.background = '#f8fafc';
        label.style.borderColor = '#e2e8f0';
    }
}
</script>
@endpush
