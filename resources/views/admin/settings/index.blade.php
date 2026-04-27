@extends('layouts.admin')
@section('title','Settings')

@section('content')
<h2 style="font-size:1.3rem;font-weight:700;margin-bottom:1.5rem">Store Settings</h2>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start">
    <!-- GENERAL SETTINGS -->
    <div class="card">
        <div class="card-header"><h3>General Settings</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Store Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'American Beauty' }}">
                </div>
                <div class="form-group">
                    <label>Tagline</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Contact Email</label>
                    <input type="email" name="site_email" value="{{ $settings['site_email'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Contact Phone</label>
                    <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="site_address" value="{{ $settings['site_address'] ?? '' }}">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label>Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? 'KSh' }}">
                    </div>
                    <div class="form-group">
                        <label>Currency Code</label>
                        <input type="text" name="currency_code" value="{{ $settings['currency_code'] ?? 'KES' }}">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label>Shipping Fee (KSh)</label>
                        <input type="number" name="shipping_fee" value="{{ $settings['shipping_fee'] ?? 200 }}">
                    </div>
                    <div class="form-group">
                        <label>Free Shipping Minimum (KSh)</label>
                        <input type="number" name="free_shipping_min" value="{{ $settings['free_shipping_min'] ?? 3000 }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>VAT / Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] ?? 16 }}" step="0.01">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
            </form>
        </div>
    </div>

    <!-- PAYMENT GATEWAYS -->
    <div class="card">
        <div class="card-header"><h3>Payment Gateways</h3></div>
        <div class="card-body">
            @foreach($gateways as $gateway)
            <div style="border:1.5px solid var(--border);border-radius:12px;padding:1.2rem;margin-bottom:1rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.8rem">
                    <div style="font-weight:700;font-size:.95rem">
                        @if($gateway->slug=='mpesa') 📱 @elseif($gateway->slug=='stripe') 💳 @else 💵 @endif
                        {{ $gateway->name }}
                    </div>
                    <span class="badge {{ $gateway->is_active?'badge-success':'badge-secondary' }}">
                        {{ $gateway->is_active?'Active':'Inactive' }}
                    </span>
                </div>
                <form action="{{ route('admin.settings.gateway',$gateway) }}" method="POST">
                    @csrf @method('PATCH')
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;margin-bottom:.8rem">
                        <div class="form-group" style="margin:0">
                            <label>Status</label>
                            <select name="is_active">
                                <option value="1" {{ $gateway->is_active?'selected':'' }}>Active</option>
                                <option value="0" {{ !$gateway->is_active?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0">
                            <label>Mode</label>
                            <select name="mode">
                                <option value="sandbox" {{ $gateway->mode=='sandbox'?'selected':'' }}>Sandbox</option>
                                <option value="live" {{ $gateway->mode=='live'?'selected':'' }}>Live</option>
                            </select>
                        </div>
                    </div>
                    @if($gateway->slug === 'mpesa')
                    <p style="font-size:.78rem;color:#888;background:#f8f8f8;padding:.6rem .8rem;border-radius:8px;margin-bottom:.8rem;line-height:1.6">
                        M-PESA credentials are set via <strong>.env</strong> file:<br>
                        <code>MPESA_CONSUMER_KEY, MPESA_CONSUMER_SECRET,<br>MPESA_SHORTCODE, MPESA_PASSKEY, MPESA_CALLBACK_URL</code>
                    </p>
                    @endif
                    <button type="submit" class="btn btn-outline btn-sm">Save</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ACCESS CONTROL
══════════════════════════════════════════════════════════ --}}
<h2 style="font-size:1.3rem;font-weight:700;margin:2rem 0 1.25rem;display:flex;align-items:center;gap:.6rem">
    <i class="fas fa-shield-halved" style="color:var(--purple)"></i> Access Control
</h2>

{{-- ── Tabs ── --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap">
    <button onclick="showTab('tab-roles')" id="btn-tab-roles"
        class="ac-tab ac-tab-active">
        <i class="fas fa-shield-halved"></i> Role Permissions
    </button>
    <button onclick="showTab('tab-users')" id="btn-tab-users"
        class="ac-tab">
        <i class="fas fa-users"></i> User Roles
    </button>
</div>

<style>
.ac-tab{display:inline-flex;align-items:center;gap:.4rem;padding:.48rem 1rem;border-radius:9px;font-size:.82rem;font-weight:600;cursor:pointer;border:1.5px solid var(--border);background:#fff;color:var(--muted);transition:all .15s;font-family:inherit;}
.ac-tab:hover{border-color:var(--purple);color:var(--purple);background:var(--purple-soft);}
.ac-tab-active{background:var(--purple-soft)!important;border-color:var(--purple)!important;color:var(--purple)!important;}
.perm-group{margin-bottom:1.2rem;}
.perm-group-title{font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;}
.perm-group-title::after{content:'';flex:1;height:1px;background:var(--border);}
.perm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.4rem;}
.perm-item{display:flex;align-items:center;gap:.5rem;padding:.38rem .6rem;border-radius:8px;border:1.5px solid var(--border);background:#fff;cursor:pointer;transition:all .13s;font-size:.8rem;}
.perm-item:hover{border-color:var(--purple);background:var(--purple-soft);}
.perm-item input[type=checkbox]{accent-color:var(--purple);width:14px;height:14px;flex-shrink:0;cursor:pointer;}
.perm-item.checked{border-color:var(--purple);background:var(--purple-soft);}
.role-card{border:1.5px solid var(--border);border-radius:var(--r);overflow:hidden;margin-bottom:1.25rem;}
.role-card-header{padding:.85rem 1.1rem;display:flex;align-items:center;justify-content:space-between;gap:.75rem;cursor:pointer;user-select:none;background:#fff;}
.role-card-header:hover{background:var(--purple-soft);}
.role-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.role-card-body{padding:1.1rem;border-top:1.5px solid var(--border);background:#faf9ff;display:none;}
.role-card-body.open{display:block;}
.user-row{display:flex;align-items:center;gap:.75rem;padding:.7rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);background:#fff;margin-bottom:.6rem;}
.user-av{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.82rem;font-weight:700;flex-shrink:0;}
.role-chk{display:flex;align-items:center;gap:.35rem;padding:.28rem .6rem;border-radius:7px;border:1.5px solid var(--border);background:#fff;font-size:.77rem;font-weight:500;color:var(--text);cursor:pointer;transition:all .13s;}
.role-chk:hover{border-color:var(--purple);background:var(--purple-soft);}
.role-chk input{accent-color:var(--purple);width:13px;height:13px;cursor:pointer;}
.role-chk.checked{border-color:var(--purple);background:var(--purple-soft);color:var(--purple);font-weight:600;}
</style>

{{-- ══ TAB: ROLE PERMISSIONS ══ --}}
<div id="tab-roles">
    @foreach($roles as $role)
    @if($role->name !== 'super-admin')
    <div class="role-card">
        <div class="role-card-header" onclick="toggleRole('role-{{ $role->id }}')">
            <div style="display:flex;align-items:center;gap:.65rem;flex:1">
                <span class="role-dot" style="background:{{ $role->color ?? '#6366f1' }}"></span>
                <div>
                    <div style="font-weight:700;font-size:.9rem;color:var(--text)">{{ $role->display_name }}</div>
                    @if($role->description)
                    <div style="font-size:.73rem;color:var(--muted);margin-top:.1rem">{{ $role->description }}</div>
                    @endif
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:.75rem">
                <span class="badge badge-purple">{{ $role->permissions->count() }} permissions</span>
                <i class="fas fa-chevron-down" id="chev-role-{{ $role->id }}" style="color:var(--muted);font-size:.75rem;transition:transform .2s"></i>
            </div>
        </div>
        <div class="role-card-body" id="role-{{ $role->id }}">
            <form method="POST" action="{{ route('admin.settings.role-permissions', $role) }}">
                @csrf @method('PATCH')

                {{-- Select All / Deselect All --}}
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;flex-wrap:wrap">
                    <span style="font-size:.78rem;font-weight:600;color:var(--muted)">Quick select:</span>
                    <button type="button" onclick="selectAll('role-form-{{ $role->id }}')"
                        style="font-size:.74rem;color:var(--purple);background:none;border:none;cursor:pointer;font-weight:600;padding:0">
                        <i class="fas fa-check-double"></i> All
                    </button>
                    <button type="button" onclick="deselectAll('role-form-{{ $role->id }}')"
                        style="font-size:.74rem;color:var(--muted);background:none;border:none;cursor:pointer;font-weight:600;padding:0">
                        <i class="fas fa-xmark"></i> None
                    </button>
                </div>

                <div id="role-form-{{ $role->id }}">
                @foreach($permissions as $group => $groupPerms)
                <div class="perm-group">
                    <div class="perm-group-title">{{ $group }}</div>
                    <div class="perm-grid">
                        @foreach($groupPerms as $perm)
                        @php $checked = $role->permissions->contains('name', $perm->name); @endphp
                        <label class="perm-item {{ $checked ? 'checked' : '' }}" id="lbl-{{ $role->id }}-{{ $perm->id }}">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $perm->name }}"
                                   {{ $checked ? 'checked' : '' }}
                                   onchange="toggleLabel(this, 'lbl-{{ $role->id }}-{{ $perm->id }}')">
                            {{ $perm->display_name }}
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
                </div>

                <div style="margin-top:1.1rem;padding-top:.9rem;border-top:1px solid var(--border)">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Save {{ $role->display_name }} Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endforeach
</div>

{{-- ══ TAB: USER ROLES ══ --}}
<div id="tab-users" style="display:none">
    @forelse($staff as $user)
    <div class="user-row">
        @if($user->avatar)
            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                 style="width:34px;height:34px;border-radius:9px;object-fit:cover;flex-shrink:0">
        @else
            <div class="user-av">{{ strtoupper(substr($user->name,0,1)) }}</div>
        @endif
        <div style="flex:1;min-width:0">
            <div style="font-weight:600;font-size:.85rem">{{ $user->name }}</div>
            <div style="font-size:.72rem;color:var(--muted)">{{ $user->email }}</div>
        </div>
        <form method="POST" action="{{ route('admin.settings.user-roles', $user) }}"
              style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap">
            @csrf @method('PATCH')
            @foreach($roles as $role)
            @php $hasRole = $user->roles->contains('name', $role->name); @endphp
            <label class="role-chk {{ $hasRole ? 'checked' : '' }}" id="ulbl-{{ $user->id }}-{{ $role->id }}">
                <input type="checkbox"
                       name="roles[]"
                       value="{{ $role->name }}"
                       {{ $hasRole ? 'checked' : '' }}
                       onchange="toggleLabel(this, 'ulbl-{{ $user->id }}-{{ $role->id }}')">
                {{ $role->display_name }}
            </label>
            @endforeach
            <button type="submit" class="btn btn-outline btn-sm" style="margin-left:.25rem">
                <i class="fas fa-save"></i>
            </button>
        </form>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <p>No staff users found.</p>
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
// ── Tabs ──
function showTab(id) {
    ['tab-roles','tab-users'].forEach(t => {
        document.getElementById(t).style.display = t === id ? 'block' : 'none';
    });
    ['btn-tab-roles','btn-tab-users'].forEach(b => {
        document.getElementById(b).classList.toggle('ac-tab-active',
            b === 'btn-' + id);
    });
}

// ── Role accordion ──
function toggleRole(id) {
    const body = document.getElementById(id);
    const chev = document.getElementById('chev-' + id);
    const open = body.classList.contains('open');
    body.classList.toggle('open', !open);
    if (chev) chev.style.transform = open ? '' : 'rotate(180deg)';
}

// ── Checkbox label highlight ──
function toggleLabel(checkbox, labelId) {
    document.getElementById(labelId).classList.toggle('checked', checkbox.checked);
}

// ── Select / Deselect all in a role form ──
function selectAll(formId) {
    document.querySelectorAll('#' + formId + ' input[type=checkbox]').forEach(cb => {
        cb.checked = true;
        const lbl = cb.closest('label');
        if (lbl) lbl.classList.add('checked');
    });
}
function deselectAll(formId) {
    document.querySelectorAll('#' + formId + ' input[type=checkbox]').forEach(cb => {
        cb.checked = false;
        const lbl = cb.closest('label');
        if (lbl) lbl.classList.remove('checked');
    });
}
</script>
@endpush