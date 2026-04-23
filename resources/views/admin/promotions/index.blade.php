@extends('layouts.admin')
@section('title', 'Promotions')

@section('content')

{{-- Header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-bullhorn" style="color:var(--purple)"></i> Promotions
        </div>
        <div class="page-sub">Manage automatic discounts and promotional campaigns</div>
    </div>
    <button onclick="openModal('createModal')" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Promotion
    </button>
</div>

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:1.25rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-bullhorn"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Promotions</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value">{{ $stats['active'] }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $stats['expired'] }}</div>
            <div class="stat-label">Expired</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-calendar-day"></i></div>
        <div>
            <div class="stat-value">{{ $stats['scheduled'] }}</div>
            <div class="stat-label">Scheduled</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1rem">
    <div class="card-body" style="padding:.85rem 1.3rem">
        <form method="GET" style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search promotion name…"
                style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;width:220px;outline:none">

            <select name="type" style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none;background:#fff">
                <option value="">All Types</option>
                <option value="fixed"   {{ request('type')==='fixed'   ? 'selected':'' }}>Fixed (KES)</option>
                <option value="percent" {{ request('type')==='percent' ? 'selected':'' }}>Percent (%)</option>
            </select>

            <select name="status" style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none;background:#fff">
                <option value="">All Status</option>
                <option value="active"    {{ request('status')==='active'    ? 'selected':'' }}>Active</option>
                <option value="inactive"  {{ request('status')==='inactive'  ? 'selected':'' }}>Inactive</option>
                <option value="scheduled" {{ request('status')==='scheduled' ? 'selected':'' }}>Scheduled</option>
                <option value="expired"   {{ request('status')==='expired'   ? 'selected':'' }}>Expired</option>
            </select>

            <button type="submit" class="btn btn-outline btn-sm">
                <i class="fas fa-search"></i> Filter
            </button>
            @if(request()->hasAny(['search','type','status']))
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-xmark"></i> Clear
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Order</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $promotion)
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--text);font-size:.88rem">{{ $promotion->name }}</div>
                        @if($promotion->description)
                            <div style="font-size:.78rem;color:var(--muted);margin-top:.15rem">{{ Str::limit($promotion->description, 55) }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $promotion->type === 'percent' ? 'badge-purple' : 'badge-info' }}">
                            {{ $promotion->type === 'percent' ? '%' : 'KES' }} {{ $promotion->type === 'percent' ? 'Percent' : 'Fixed' }}
                        </span>
                    </td>
                    <td style="font-weight:700;color:var(--green)">
                        @if($promotion->type === 'percent')
                            {{ $promotion->value }}%
                        @else
                            KES {{ number_format($promotion->value, 2) }}
                        @endif
                    </td>
                    <td style="font-size:.84rem;color:var(--muted)">
                        {{ $promotion->minimum_order > 0 ? 'KES '.number_format($promotion->minimum_order, 2) : '—' }}
                    </td>
                    <td style="font-size:.82rem">
                        @if($promotion->starts_at)
                            @if($promotion->starts_at->isFuture())
                                <span style="color:var(--purple)">
                                    <i class="fas fa-calendar-day" style="font-size:.7rem"></i>
                                    {{ $promotion->starts_at->format('d M Y') }}
                                </span>
                            @else
                                <span style="color:var(--muted)">
                                    {{ $promotion->starts_at->format('d M Y') }}
                                </span>
                            @endif
                        @else
                            <span style="color:var(--muted)">Immediate</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem">
                        @if($promotion->ends_at)
                            @if($promotion->ends_at->isPast())
                                <span style="color:var(--tango);font-weight:600">
                                    <i class="fas fa-circle-xmark" style="font-size:.7rem"></i>
                                    Expired {{ $promotion->ends_at->format('d M Y') }}
                                </span>
                            @else
                                <span style="color:var(--green)">
                                    <i class="fas fa-clock" style="font-size:.7rem"></i>
                                    {{ $promotion->ends_at->format('d M Y') }}
                                </span>
                            @endif
                        @else
                            <span style="color:var(--muted)">No expiry</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $now = now();
                            $isScheduled = $promotion->starts_at && $promotion->starts_at->isFuture();
                            $isExpired   = $promotion->ends_at   && $promotion->ends_at->isPast();
                        @endphp
                        @if($isExpired)
                            <span class="badge badge-danger">Expired</span>
                        @elseif($isScheduled)
                            <span class="badge badge-purple">Scheduled</span>
                        @elseif($promotion->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            {{-- Edit --}}
                            <button onclick="openEdit(
                                    {{ $promotion->id }},
                                    '{{ addslashes($promotion->name) }}',
                                    '{{ addslashes($promotion->description ?? '') }}',
                                    '{{ $promotion->type }}',
                                    '{{ $promotion->value }}',
                                    '{{ $promotion->minimum_order }}',
                                    '{{ $promotion->starts_at?->format('Y-m-d') }}',
                                    '{{ $promotion->ends_at?->format('Y-m-d') }}',
                                    {{ $promotion->is_active ? 1 : 0 }}
                                )"
                                class="btn btn-outline btn-sm" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            {{-- Toggle --}}
                            <form action="{{ route('admin.promotions.toggle', $promotion) }}" method="POST" style="margin:0">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline btn-sm"
                                    title="{{ $promotion->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $promotion->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            {{-- Delete --}}
                            <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST"
                                  onsubmit="return confirm('Delete promotion \'{{ addslashes($promotion->name) }}\'?')"
                                  style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:var(--muted)">
                        <i class="fas fa-bullhorn" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                        No promotions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $promotions->links() }}</div>
</div>

{{-- ══════════ CREATE MODAL ══════════ --}}
<div id="createModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:18px;width:100%;max-width:560px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;margin:1rem">
        <div style="padding:1.2rem 1.5rem;border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(120deg,#fff 45%,var(--purple-soft) 100%)">
            <h3 style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:flex;align-items:center;gap:.5rem">
                <i class="fas fa-plus" style="color:var(--purple)"></i> Create Promotion
            </h3>
            <button onclick="closeModal('createModal')" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted)">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.promotions.store') }}" style="padding:1.5rem;max-height:80vh;overflow-y:auto">
            @csrf

            <div class="form-group" style="margin-bottom:1rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">Promotion Name *</label>
                <input type="text" name="name" required
                    placeholder="e.g. Summer Sale, Flash Deal"
                    value="{{ old('name') }}"
                    style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;outline:none">
            </div>

            <div class="form-group" style="margin-bottom:1rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">Description</label>
                <textarea name="description" rows="2"
                    placeholder="Optional — shown to customers"
                    style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;outline:none;resize:vertical">{{ old('description') }}</textarea>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Type *</label>
                    <select name="type" id="create_type" onchange="updateValueLabel('create')">
                        <option value="percent">Percent (%)</option>
                        <option value="fixed">Fixed (KES)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label id="create_value_label">Discount Value * (%)</label>
                    <input type="number" name="value" step="0.01" min="0.01" required
                        placeholder="e.g. 15" value="{{ old('value') }}">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:1rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">Minimum Order (KES)</label>
                <input type="number" name="minimum_order" step="0.01" min="0"
                    placeholder="0 = no minimum" value="{{ old('minimum_order') }}"
                    style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;outline:none">
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at') }}">
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="ends_at" value="{{ old('ends_at') }}"
                        min="{{ today()->addDay()->toDateString() }}">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:1rem">
                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.84rem;font-weight:500;color:var(--text);margin-bottom:0;padding-bottom:.5rem">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked style="width:auto;accent-color:var(--purple)">
                    Active immediately
                </label>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Create Promotion
                </button>
                <button type="button" onclick="closeModal('createModal')" class="btn btn-outline">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ EDIT MODAL ══════════ --}}
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:18px;width:100%;max-width:560px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;margin:1rem">
        <div style="padding:1.2rem 1.5rem;border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(120deg,#fff 45%,var(--purple-soft) 100%)">
            <h3 style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:flex;align-items:center;gap:.5rem">
                <i class="fas fa-pen" style="color:var(--purple)"></i> Edit Promotion
            </h3>
            <button onclick="closeModal('editModal')" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted)">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <form method="POST" id="editForm" style="padding:1.5rem;max-height:80vh;overflow-y:auto">
            @csrf @method('PUT')

            <div class="form-group" style="margin-bottom:1rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">Promotion Name *</label>
                <input type="text" name="name" id="edit_name" required
                    style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;outline:none">
            </div>

            <div class="form-group" style="margin-bottom:1rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">Description</label>
                <textarea name="description" id="edit_description" rows="2"
                    placeholder="Optional — shown to customers"
                    style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;outline:none;resize:vertical"></textarea>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Type *</label>
                    <select name="type" id="edit_type" onchange="updateValueLabel('edit')">
                        <option value="percent">Percent (%)</option>
                        <option value="fixed">Fixed (KES)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label id="edit_value_label">Discount Value *</label>
                    <input type="number" name="value" id="edit_value" step="0.01" min="0.01" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:1rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">Minimum Order (KES)</label>
                <input type="number" name="minimum_order" id="edit_minimum_order" step="0.01" min="0"
                    style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;outline:none">
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="starts_at" id="edit_starts_at">
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="ends_at" id="edit_ends_at">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:1rem">
                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.84rem;font-weight:500;color:var(--text);margin-bottom:0;padding-bottom:.5rem">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width:auto;accent-color:var(--purple)">
                    Active
                </label>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Save Changes
                </button>
                <button type="button" onclick="closeModal('editModal')" class="btn btn-outline">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast --}}
<div id="toast" style="position:fixed;bottom:1.5rem;right:1.5rem;background:#1a0a2e;color:#fff;padding:.7rem 1.2rem;border-radius:10px;font-size:.83rem;font-weight:600;opacity:0;transition:opacity .3s;z-index:999;pointer-events:none">
    Saved!
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    m.style.display = 'flex';
    setTimeout(() => m.querySelector('input,select,textarea') && m.querySelector('input,select,textarea').focus(), 100);
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Close on backdrop click
['createModal','editModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModal(id);
    });
});

// Close on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeModal('createModal');
        closeModal('editModal');
    }
});

function updateValueLabel(prefix) {
    const type  = document.getElementById(prefix + '_type').value;
    const label = document.getElementById(prefix + '_value_label');
    label.textContent = type === 'percent' ? 'Discount Value * (%)' : 'Discount Value * (KES)';
}

function openEdit(id, name, description, type, value, minOrder, startsAt, endsAt, isActive) {
    document.getElementById('editForm').action = '/admin/promotions/' + id;
    document.getElementById('edit_name').value          = name;
    document.getElementById('edit_description').value   = description;
    document.getElementById('edit_type').value          = type;
    document.getElementById('edit_value').value         = value;
    document.getElementById('edit_minimum_order').value = minOrder;
    document.getElementById('edit_starts_at').value     = startsAt || '';
    document.getElementById('edit_ends_at').value       = endsAt   || '';
    document.getElementById('edit_is_active').checked   = isActive == 1;
    updateValueLabel('edit');
    openModal('editModal');
}

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.opacity = '1';
    setTimeout(() => t.style.opacity = '0', 2000);
}

// Auto-open create modal if validation errors on store
@if($errors->any() && old('name'))
    openModal('createModal');
@endif
</script>
@endpush