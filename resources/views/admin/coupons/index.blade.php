@extends('layouts.admin')
@section('title', 'Coupons')

@section('content')

{{-- Header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-ticket" style="color:var(--purple)"></i> Coupons
        </div>
        <div class="page-sub">Manage discount codes and promotions</div>
    </div>
    <button onclick="openModal('createModal')" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Coupon
    </button>
</div>

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:1.25rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-ticket"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Coupons</div>
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
        <div class="stat-icon pink"><i class="fas fa-fire"></i></div>
        <div>
            <div class="stat-value">{{ $stats['used'] }}</div>
            <div class="stat-label">Total Uses</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1rem">
    <div class="card-body" style="padding:.85rem 1.3rem">
        <form method="GET" style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search coupon code…"
                style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;width:220px;outline:none">

            <select name="type" style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none;background:#fff">
                <option value="">All Types</option>
                <option value="fixed"   {{ request('type')==='fixed'   ? 'selected':'' }}>Fixed (KES)</option>
                <option value="percent" {{ request('type')==='percent' ? 'selected':'' }}>Percent (%)</option>
            </select>

            <select name="status" style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none;background:#fff">
                <option value="">All Status</option>
                <option value="active"   {{ request('status')==='active'   ? 'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')==='inactive' ? 'selected':'' }}>Inactive</option>
                <option value="expired"  {{ request('status')==='expired'  ? 'selected':'' }}>Expired</option>
            </select>

            <button type="submit" class="btn btn-outline btn-sm">
                <i class="fas fa-search"></i> Filter
            </button>
            @if(request()->hasAny(['search','type','status']))
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline btn-sm">
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
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Order</th>
                    <th>Usage</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.6rem">
                            <div style="background:var(--purple-soft);border:1.5px dashed var(--purple);border-radius:7px;padding:.25rem .65rem;font-family:monospace;font-size:.85rem;font-weight:700;color:var(--purple);letter-spacing:.05em">
                                {{ $coupon->code }}
                            </div>
                            <button onclick="navigator.clipboard.writeText('{{ $coupon->code }}').then(()=>showToast('Copied!'))"
                                class="btn btn-outline btn-sm" title="Copy code"
                                style="padding:.25rem .5rem">
                                <i class="fas fa-copy" style="font-size:.7rem"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $coupon->type === 'percent' ? 'badge-purple' : 'badge-info' }}">
                            {{ $coupon->type === 'percent' ? '%' : 'KES' }} {{ $coupon->type === 'percent' ? 'Percent' : 'Fixed' }}
                        </span>
                    </td>
                    <td style="font-weight:700;color:var(--green)">
                        @if($coupon->type === 'percent')
                            {{ $coupon->value }}%
                        @else
                            KES {{ number_format($coupon->value, 2) }}
                        @endif
                    </td>
                    <td style="font-size:.84rem;color:var(--muted)">
                        {{ $coupon->minimum_order > 0 ? 'KES '.number_format($coupon->minimum_order,2) : '—' }}
                    </td>
                    <td>
                        <div style="font-size:.84rem">
                            <span style="font-weight:600;color:var(--text)">{{ $coupon->used_count }}</span>
                            <span style="color:var(--muted)">
                                / {{ $coupon->usage_limit ?? '∞' }}
                            </span>
                        </div>
                        @if($coupon->usage_limit)
                            @php $pct = min(100, ($coupon->used_count / $coupon->usage_limit) * 100) @endphp
                            <div style="height:4px;background:var(--border);border-radius:4px;margin-top:.3rem;width:80px">
                                <div style="height:4px;border-radius:4px;width:{{ $pct }}%;background:{{ $pct >= 100 ? 'var(--tango)' : 'var(--purple)' }}"></div>
                            </div>
                        @endif
                    </td>
                    <td style="font-size:.82rem">
                        @if($coupon->expires_at)
                            @if($coupon->expires_at->isPast())
                                <span style="color:var(--tango);font-weight:600">
                                    <i class="fas fa-circle-xmark" style="font-size:.7rem"></i>
                                    Expired {{ $coupon->expires_at->format('d M Y') }}
                                </span>
                            @else
                                <span style="color:var(--green)">
                                    <i class="fas fa-clock" style="font-size:.7rem"></i>
                                    {{ $coupon->expires_at->format('d M Y') }}
                                </span>
                            @endif
                        @else
                            <span style="color:var(--muted)">No expiry</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $coupon->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            {{-- Edit --}}
                            <button onclick="openEdit({{ $coupon->id }}, '{{ $coupon->code }}', '{{ $coupon->type }}', '{{ $coupon->value }}', '{{ $coupon->minimum_order }}', '{{ $coupon->usage_limit }}', '{{ $coupon->expires_at?->format('Y-m-d') }}', {{ $coupon->is_active ? 1 : 0 }})"
                                class="btn btn-outline btn-sm" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            {{-- Toggle --}}
                            <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" style="margin:0">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline btn-sm"
                                    title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $coupon->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            {{-- Delete --}}
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                  onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')"
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
                        <i class="fas fa-ticket" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                        No coupons found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $coupons->links() }}</div>
</div>

{{-- ══════════ CREATE MODAL ══════════ --}}
<div id="createModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:18px;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;margin:1rem">
        <div style="padding:1.2rem 1.5rem;border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(120deg,#fff 45%,var(--purple-soft) 100%)">
            <h3 style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:flex;align-items:center;gap:.5rem">
                <i class="fas fa-plus" style="color:var(--purple)"></i> Create Coupon
            </h3>
            <button onclick="closeModal('createModal')" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted)">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.coupons.store') }}" style="padding:1.5rem">
            @csrf

            <div style="margin-bottom:1rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">Coupon Code *</label>
                <div style="display:flex;gap:.5rem">
                    <input type="text" name="code" id="create_code" required
                        placeholder="e.g. SAVE20"
                        style="flex:1;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:monospace;text-transform:uppercase;outline:none">
                    <button type="button" onclick="generateCode()"
                        class="btn btn-outline btn-sm" style="white-space:nowrap">
                        <i class="fas fa-wand-magic-sparkles"></i> Generate
                    </button>
                </div>
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
                    <input type="number" name="value" step="0.01" min="0.01" required placeholder="e.g. 10">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Minimum Order (KES)</label>
                    <input type="number" name="minimum_order" step="0.01" min="0" placeholder="0 = no minimum">
                </div>
                <div class="form-group">
                    <label>Usage Limit</label>
                    <input type="number" name="usage_limit" min="1" placeholder="Leave blank = unlimited">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" name="expires_at" min="{{ today()->addDay()->toDateString() }}">
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.84rem;font-weight:500;color:var(--text);margin-bottom:0;padding-bottom:.5rem">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked style="width:auto;accent-color:var(--purple)">
                        Active immediately
                    </label>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:.5rem">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Create Coupon
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
    <div style="background:#fff;border-radius:18px;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;margin:1rem">
        <div style="padding:1.2rem 1.5rem;border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(120deg,#fff 45%,var(--purple-soft) 100%)">
            <h3 style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:flex;align-items:center;gap:.5rem">
                <i class="fas fa-pen" style="color:var(--purple)"></i> Edit Coupon
            </h3>
            <button onclick="closeModal('editModal')" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted)">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <form method="POST" id="editForm" style="padding:1.5rem">
            @csrf @method('PUT')

            <div style="margin-bottom:1rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">Coupon Code *</label>
                <input type="text" name="code" id="edit_code" required
                    style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:monospace;text-transform:uppercase;outline:none">
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

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Minimum Order (KES)</label>
                    <input type="number" name="minimum_order" id="edit_minimum_order" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Usage Limit</label>
                    <input type="number" name="usage_limit" id="edit_usage_limit" min="1" placeholder="Blank = unlimited">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" name="expires_at" id="edit_expires_at">
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.84rem;font-weight:500;color:var(--text);margin-bottom:0;padding-bottom:.5rem">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width:auto;accent-color:var(--purple)">
                        Active
                    </label>
                </div>
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
    Copied!
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    m.style.display = 'flex';
    setTimeout(() => m.querySelector('input,select') && m.querySelector('input,select').focus(), 100);
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

function openEdit(id, code, type, value, minOrder, usageLimit, expiresAt, isActive) {
    document.getElementById('editForm').action = '/admin/coupons/' + id;
    document.getElementById('edit_code').value          = code;
    document.getElementById('edit_type').value          = type;
    document.getElementById('edit_value').value         = value;
    document.getElementById('edit_minimum_order').value = minOrder;
    document.getElementById('edit_usage_limit').value   = usageLimit || '';
    document.getElementById('edit_expires_at').value    = expiresAt  || '';
    document.getElementById('edit_is_active').checked   = isActive == 1;
    updateValueLabel('edit');
    openModal('editModal');
}

function generateCode() {
    fetch('{{ route('admin.coupons.generate') }}')
        .then(r => r.json())
        .then(data => {
            document.getElementById('create_code').value = data.code;
        });
}

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.opacity = '1';
    setTimeout(() => t.style.opacity = '0', 2000);
}

// Auto-open create modal if validation errors on store
@if($errors->any() && old('code'))
    openModal('createModal');
@endif
</script>
@endpush