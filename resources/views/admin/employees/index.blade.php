@extends('layouts.admin')

@section('title', 'Employees')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   EMPLOYEES — MANAGER
   ═══════════════════════════════════════════════════════════ */

/* Stats */
.emp-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 900px) { .emp-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 500px) { .emp-stats { grid-template-columns: 1fr; } }

/* Filters */
.emp-filters {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex; flex-wrap: wrap;
    gap: .75rem; align-items: flex-end;
}
.emp-filters .fg {
    display: flex; flex-direction: column;
    gap: .3rem; flex: 1; min-width: 130px;
}
.emp-filters label {
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .06em;
}
.emp-filters input,
.emp-filters select {
    padding: .55rem .8rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .84rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.emp-filters input:focus,
.emp-filters select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}

/* Table card */
.emp-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.emp-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
}
.emp-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .98rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.emp-card-header h3 i { color: var(--purple); }

.emp-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.emp-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.emp-table thead th {
    padding: .72rem 1rem; text-align: left;
    font-size: .7rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase;
    letter-spacing: .07em; white-space: nowrap;
}
.emp-table tbody tr { border-bottom: 1px solid #f3eeff; transition: background .13s; }
.emp-table tbody tr:last-child { border-bottom: none; }
.emp-table tbody tr:hover { background: #faf7ff; }
.emp-table td { padding: .85rem 1rem; vertical-align: middle; }

/* Employee cell */
.emp-cell { display: flex; align-items: center; gap: .75rem; }
.emp-photo {
    width: 40px; height: 40px; border-radius: 11px;
    object-fit: cover; flex-shrink: 0;
    border: 2px solid var(--border);
}
.emp-avatar {
    width: 40px; height: 40px; border-radius: 11px;
    background: linear-gradient(135deg, var(--purple), var(--pink));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .85rem; font-weight: 700;
    flex-shrink: 0; box-shadow: 0 2px 8px rgba(124,58,237,.25);
}
.emp-name { font-weight: 600; color: var(--text); font-size: .85rem; }
.emp-email { font-size: .71rem; color: var(--muted); margin-top: .1rem; }

/* PIN badge */
.pin-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 700;
    background: var(--purple-soft); color: var(--purple);
    border: 1px solid #ddd6fe; border-radius: 8px;
    padding: .2rem .55rem; letter-spacing: .1em;
    font-variant-numeric: tabular-nums;
    cursor: pointer; transition: all .15s;
}
.pin-badge:hover { background: var(--purple); color: #fff; }

/* Shift pill */
.shift-pill {
    display: inline-flex; align-items: center; gap: .28rem;
    font-size: .72rem; font-weight: 600;
    background: #eff6ff; color: #2563eb;
    border: 1px solid #bfdbfe; border-radius: 20px;
    padding: .2rem .6rem; white-space: nowrap;
}

/* Action buttons */
.tbl-actions { display: flex; gap: .4rem; align-items: center; }
.tbl-btn {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1.5px solid var(--border); background: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .76rem; color: var(--muted);
    transition: all .15s; text-decoration: none;
}
.tbl-btn:hover         { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.tbl-btn.green:hover   { border-color: var(--green);  color: var(--green);  background: var(--green-soft); }
.tbl-btn.danger:hover  { border-color: var(--tango);  color: var(--tango);  background: var(--pink-soft); }

/* Empty */
.emp-empty { padding: 3.5rem 1rem; text-align: center; color: var(--muted); }
.emp-empty i { font-size: 2.5rem; opacity: .15; color: var(--purple); display: block; margin-bottom: .75rem; }

/* Pagination */
.emp-pagination {
    padding: .85rem 1.25rem;
    border-top: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    font-size: .82rem; color: var(--muted);
    background: #faf7ff; flex-wrap: wrap; gap: .5rem;
}

/* ── Modal shared styles ── */
.em-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,1,20,.55); backdrop-filter: blur(4px);
    z-index: 500; align-items: center; justify-content: center;
}
.em-overlay.show { display: flex; }
.em-modal {
    background: #fff; border-radius: 20px;
    padding: 1.75rem; width: 500px; max-width: 95vw;
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 20px 60px rgba(124,58,237,.2);
    animation: modalIn .2s ease;
}
@keyframes modalIn { from { opacity:0; transform:scale(.94); } to { opacity:1; transform:scale(1); } }
.em-modal h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: .5rem;
    padding-bottom: .85rem; border-bottom: 1.5px solid var(--border);
}
.em-modal h3 i { color: var(--purple); }
.em-field { display: flex; flex-direction: column; gap: .35rem; margin-bottom: .9rem; }
.em-label {
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.em-label span { color: var(--pink); margin-left: .15rem; }
.em-input, .em-select {
    padding: .62rem .9rem;
    border: 1.5px solid var(--border); border-radius: var(--r-sm);
    font-size: .86rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.em-input:focus, .em-select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.em-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-id-badge" style="color:var(--purple)"></i> Employees
        </h1>
        <p class="page-sub">Manage staff profiles, roles, PINs and shift assignments</p>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.attendance.terminal') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-user-clock"></i> Terminal
        </a>
        <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-clock"></i> Shifts
        </a>
        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
            <i class="fas fa-plus"></i> Add Employee
        </button>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flash flash-error" style="margin-bottom:1rem">
        <i class="fas fa-circle-xmark"></i> {{ session('error') }}
    </div>
@endif

{{-- Stat cards --}}
<div class="emp-stats">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-label">Total Employees</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-label">Active</div>
            <div class="stat-value">{{ $stats['active'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-circle-pause"></i></div>
        <div>
            <div class="stat-label">Inactive</div>
            <div class="stat-value">{{ $stats['inactive'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-dot"></i></div>
        <div>
            <div class="stat-label">Clocked In Now</div>
            <div class="stat-value">{{ $stats['clocked_in'] }}</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.employees.index') }}" class="emp-filters">
    <div class="fg">
        <label>Search</label>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Name, email or phone…">
    </div>
    <div class="fg" style="max-width:145px">
        <label>Role</label>
        <select name="role">
            <option value="">All Roles</option>
            <option value="cashier"       {{ request('role') === 'cashier'       ? 'selected':'' }}>Cashier</option>
            <option value="beautician"    {{ request('role') === 'beautician'    ? 'selected':'' }}>Beautician</option>
            <option value="receptionist"  {{ request('role') === 'receptionist'  ? 'selected':'' }}>Receptionist</option>
            <option value="manager"       {{ request('role') === 'manager'       ? 'selected':'' }}>Manager</option>
            <option value="cleaner"       {{ request('role') === 'cleaner'       ? 'selected':'' }}>Cleaner</option>
        </select>
    </div>
    <div class="fg" style="max-width:145px">
        <label>Shift</label>
        <select name="shift_id">
            <option value="">All Shifts</option>
            @foreach($shifts as $shift)
                <option value="{{ $shift->id }}"
                    {{ request('shift_id') == $shift->id ? 'selected':'' }}>
                    {{ $shift->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="fg" style="max-width:130px">
        <label>Status</label>
        <select name="status">
            <option value="">All</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected':'' }}>Inactive</option>
        </select>
    </div>
    <div style="display:flex;gap:.5rem;align-items:flex-end;padding-bottom:1px">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['search','role','shift_id','status']))
            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-xmark"></i> Clear
            </a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="emp-card">
    <div class="emp-card-header">
        <h3>
            <i class="fas fa-list"></i> All Employees
            <span style="font-size:.75rem;font-weight:600;color:var(--muted);font-family:inherit">
                ({{ $employees->total() }})
            </span>
        </h3>
        <span style="font-size:.78rem;color:var(--muted)">
            Sorted by newest first
        </span>
    </div>

    <div style="overflow-x:auto">
        <table class="emp-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Shift</th>
                    <th>PIN</th>
                    <th>Joined</th>
                    <th style="text-align:center">Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    {{-- Employee --}}
                    <td>
                        <div class="emp-cell">
                            @if($emp->photo)
                                <img src="{{ asset('storage/'.$emp->photo) }}"
                                     alt="{{ $emp->name }}" class="emp-photo">
                            @else
                                <div class="emp-avatar">
                                    {{ strtoupper(substr($emp->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <div class="emp-name">{{ $emp->name }}</div>
                                @if($emp->email)
                                    <div class="emp-email">{{ $emp->email }}</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Phone --}}
                    <td style="font-size:.82rem;color:var(--muted)">
                        {{ $emp->phone ?? '—' }}
                    </td>

                    {{-- Role --}}
                    <td>
                        <span class="badge badge-purple" style="text-transform:capitalize">
                            {{ $emp->role_label }}
                        </span>
                    </td>

                    {{-- Shift --}}
                    <td>
                        @if($emp->shift)
                            <span class="shift-pill">
                                <i class="fas fa-clock" style="font-size:.65rem"></i>
                                {{ $emp->shift->name }}
                            </span>
                            <div style="font-size:.68rem;color:var(--muted);margin-top:.2rem">
                                {{ \Carbon\Carbon::parse($emp->shift->start_time)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($emp->shift->end_time)->format('H:i') }}
                            </div>
                        @else
                            <span style="color:var(--muted);font-size:.8rem">—</span>
                        @endif
                    </td>

                    {{-- PIN --}}
                    <td>
                        @if($emp->pin)
                            <span class="pin-badge" title="Click to copy"
                                  onclick="copyPin('{{ $emp->pin }}', this)">
                                <i class="fas fa-key" style="font-size:.65rem"></i>
                                {{ $emp->pin }}
                            </span>
                        @else
                            <span style="color:var(--muted);font-size:.8rem">Not set</span>
                        @endif
                    </td>

                    {{-- Joined --}}
                    <td style="font-size:.8rem;color:var(--muted);white-space:nowrap">
                        {{ optional($emp->joined_date ?? $emp->created_at)->format('d M Y') }}
                    </td>

                    {{-- Status --}}
                    <td style="text-align:center">
                        @if($emp->is_active)
                            <span class="badge badge-success">
                                <i class="fas fa-circle" style="font-size:.45rem"></i> Active
                            </span>
                        @else
                            <span class="badge badge-muted">
                                <i class="fas fa-circle" style="font-size:.45rem"></i> Inactive
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="tbl-actions">
                            <a href="{{ route('admin.attendance.show', $emp->id) }}"
                               class="tbl-btn green" title="View Attendance">
                                <i class="fas fa-calendar-days"></i>
                            </a>
                            <button type="button" class="tbl-btn" title="Edit"
                                    onclick="openEditModal(
                                        {{ $emp->id }},
                                        '{{ addslashes($emp->name) }}',
                                        '{{ $emp->email }}',
                                        '{{ $emp->phone }}',
                                        '{{ $emp->role }}',
                                        '{{ $emp->shift_id }}',
                                        '{{ $emp->joined_date?->format('Y-m-d') }}'
                                    )">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                            <form method="POST"
                                  action="{{ route('admin.employees.toggle', $emp->id) }}"
                                  style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="tbl-btn"
                                        title="{{ $emp->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $emp->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"
                                       style="color:{{ $emp->is_active ? 'var(--green)' : 'var(--muted)' }}"></i>
                                </button>
                            </form>
                            <form method="POST"
                                  action="{{ route('admin.employees.destroy', $emp->id) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($emp->name) }}? This cannot be undone.')"
                                  style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="emp-empty">
                            <i class="fas fa-id-badge"></i>
                            <p style="font-size:.88rem;margin:.5rem 0 1rem">No employees found.</p>
                            <button type="button" class="btn btn-primary btn-sm" onclick="openCreateModal()">
                                <i class="fas fa-plus"></i> Add First Employee
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($employees->hasPages())
    <div class="emp-pagination">
        <span>
            Showing {{ $employees->firstItem() }}–{{ $employees->lastItem() }}
            of {{ $employees->total() }} employees
        </span>
        {{ $employees->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- ── Create Modal ── --}}
<div class="em-overlay" id="createModal">
    <div class="em-modal">
        <h3><i class="fas fa-plus"></i> Add New Employee</h3>
        <form method="POST" action="{{ route('admin.employees.store') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="em-field">
                <label class="em-label">Full Name <span>*</span></label>
                <input type="text" name="name" class="em-input"
                       placeholder="e.g. Jane Wanjiru" required>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Email</label>
                    <input type="email" name="email" class="em-input"
                           placeholder="jane@example.com">
                </div>
                <div class="em-field">
                    <label class="em-label">Phone</label>
                    <input type="text" name="phone" class="em-input"
                           placeholder="0712 345 678">
                </div>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Role <span>*</span></label>
                    <select name="role" class="em-select" required>
                        <option value="cashier">Cashier</option>
                        <option value="beautician">Beautician</option>
                        <option value="receptionist">Receptionist</option>
                        <option value="manager">Manager</option>
                        <option value="cleaner">Cleaner</option>
                    </select>
                </div>
                <div class="em-field">
                    <label class="em-label">PIN (4–10 digits) <span>*</span></label>
                    <input type="text" name="pin" class="em-input"
                           placeholder="e.g. 1234" maxlength="10"
                           pattern="\d{4,10}" required>
                </div>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Shift</label>
                    <select name="shift_id" class="em-select">
                        <option value="">— No shift —</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">
                                {{ $shift->name }}
                                ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                – {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="em-field">
                    <label class="em-label">Joined Date</label>
                    <input type="date" name="joined_date" class="em-input"
                           value="{{ today()->toDateString() }}">
                </div>
            </div>

            <div class="em-field">
                <label class="em-label">Photo</label>
                <input type="file" name="photo" class="em-input"
                       accept="image/jpeg,image/png,image/webp">
            </div>

            <div style="display:flex;gap:.65rem;margin-top:1.25rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Save Employee
                </button>
                <button type="button" class="btn btn-outline" onclick="closeCreateModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Edit Modal ── --}}
<div class="em-overlay" id="editModal">
    <div class="em-modal">
        <h3><i class="fas fa-pen-to-square"></i> Edit Employee</h3>
        <form method="POST" id="editForm" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="em-field">
                <label class="em-label">Full Name <span>*</span></label>
                <input type="text" name="name" id="editName" class="em-input" required>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Email</label>
                    <input type="email" name="email" id="editEmail" class="em-input">
                </div>
                <div class="em-field">
                    <label class="em-label">Phone</label>
                    <input type="text" name="phone" id="editPhone" class="em-input">
                </div>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Role <span>*</span></label>
                    <select name="role" id="editRole" class="em-select" required>
                        <option value="cashier">Cashier</option>
                        <option value="beautician">Beautician</option>
                        <option value="receptionist">Receptionist</option>
                        <option value="manager">Manager</option>
                        <option value="cleaner">Cleaner</option>
                    </select>
                </div>
                <div class="em-field">
                    <label class="em-label">PIN (4–10 digits)</label>
                    <input type="text" name="pin" id="editPin" class="em-input"
                           placeholder="Leave blank to keep current"
                           maxlength="10" pattern="\d{4,10}">
                </div>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Shift</label>
                    <select name="shift_id" id="editShift" class="em-select">
                        <option value="">— No shift —</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">
                                {{ $shift->name }}
                                ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                – {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="em-field">
                    <label class="em-label">Joined Date</label>
                    <input type="date" name="joined_date" id="editJoined" class="em-input">
                </div>
            </div>

            <div class="em-field">
                <label class="em-label">Photo (leave blank to keep current)</label>
                <input type="file" name="photo" class="em-input"
                       accept="image/jpeg,image/png,image/webp">
            </div>

            <div style="display:flex;gap:.65rem;margin-top:1.25rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Update Employee
                </button>
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ── Create modal ── */
function openCreateModal() {
    document.getElementById('createModal').classList.add('show');
}
function closeCreateModal() {
    document.getElementById('createModal').classList.remove('show');
}

/* ── Edit modal ── */
function openEditModal(id, name, email, phone, role, shiftId, joinedDate) {
    document.getElementById('editName').value   = name;
    document.getElementById('editEmail').value  = email  || '';
    document.getElementById('editPhone').value  = phone  || '';
    document.getElementById('editPin').value    = '';        // never pre-fill PIN
    document.getElementById('editRole').value   = role;
    document.getElementById('editShift').value  = shiftId || '';
    document.getElementById('editJoined').value = joinedDate || '';
    document.getElementById('editForm').action  = '/admin/employees/' + id;
    document.getElementById('editModal').classList.add('show');
}
function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
}

/* ── Copy PIN ── */
function copyPin(pin, el) {
    navigator.clipboard.writeText(pin).then(() => {
        const orig = el.innerHTML;
        el.innerHTML = '<i class="fas fa-check" style="font-size:.65rem"></i> Copied';
        el.style.background = 'var(--green)';
        el.style.color = '#fff';
        setTimeout(() => {
            el.innerHTML = orig;
            el.style.background = '';
            el.style.color = '';
        }, 1500);
    });
}

/* ── Close on backdrop click ── */
['createModal', 'editModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});
</script>
@endpush