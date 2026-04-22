@extends('layouts.admin')

@section('title', 'Shifts')

@push('styles')
<style>
.shifts-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 900px) { .shifts-grid { grid-template-columns: 1fr; } }

.shifts-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.shifts-header {
    padding: 1rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
}
.shifts-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .96rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.shifts-header h3 i { color: var(--purple); }

.shifts-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.shifts-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.shifts-table thead th {
    padding: .72rem 1rem; text-align: left;
    font-size: .7rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .07em;
    white-space: nowrap;
}
.shifts-table tbody tr { border-bottom: 1px solid #f3eeff; transition: background .13s; }
.shifts-table tbody tr:last-child { border-bottom: none; }
.shifts-table tbody tr:hover { background: #faf7ff; }
.shifts-table td { padding: .85rem 1rem; vertical-align: middle; }

/* Shift time block */
.shift-time {
    display: inline-flex; align-items: center; gap: .3rem;
    font-weight: 700; font-size: .86rem;
    color: var(--purple); font-variant-numeric: tabular-nums;
}
.shift-sep { color: var(--muted); font-weight: 400; }

/* Duration pill */
.dur-pill {
    display: inline-flex; align-items: center; gap: .28rem;
    background: var(--purple-soft); color: var(--purple);
    border: 1px solid #ddd6fe; border-radius: 20px;
    font-size: .72rem; font-weight: 700;
    padding: .2rem .6rem;
}

/* Grace pill */
.grace-pill {
    display: inline-flex; align-items: center; gap: .28rem;
    background: #fffbeb; color: var(--gold);
    border: 1px solid #fde68a; border-radius: 20px;
    font-size: .72rem; font-weight: 700;
    padding: .2rem .6rem;
}

/* Action buttons */
.tbl-actions { display: flex; gap: .4rem; }
.tbl-btn {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1.5px solid var(--border); background: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .76rem; color: var(--muted); transition: all .15s;
}
.tbl-btn:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.tbl-btn.danger:hover { border-color: var(--tango); color: var(--tango); background: var(--pink-soft); }

/* Add/Edit form card */
.form-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.form-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
}
.form-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .96rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.form-card-header h3 i { color: var(--purple); }
.form-card-body { padding: 1.25rem; }

.sf-field { display: flex; flex-direction: column; gap: .32rem; margin-bottom: .85rem; }
.sf-label {
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.sf-input, .sf-select {
    padding: .6rem .9rem;
    border: 1.5px solid var(--border); border-radius: var(--r-sm);
    font-size: .86rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.sf-input:focus, .sf-select:focus {
    border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.sf-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }

/* Edit modal */
.em-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,1,20,.55); backdrop-filter: blur(4px);
    z-index: 500; align-items: center; justify-content: center;
}
.em-overlay.show { display: flex; }
.em-modal {
    background: #fff; border-radius: 20px;
    padding: 1.75rem; width: 460px; max-width: 95vw;
    box-shadow: 0 20px 60px rgba(124,58,237,.2);
    animation: modalIn .2s ease;
}
@keyframes modalIn{from{opacity:0;transform:scale(.94);}to{opacity:1;transform:scale(1);}}
.em-modal h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; margin-bottom: 1.2rem;
    display: flex; align-items: center; gap: .5rem;
    padding-bottom: .8rem; border-bottom: 1.5px solid var(--border);
}
.em-modal h3 i { color: var(--purple); }
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-clock" style="color:var(--purple)"></i> Work Shifts
        </h1>
        <p class="page-sub">Define shift schedules and grace periods for attendance tracking</p>
    </div>
    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Employees
    </a>
</div>

@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div class="shifts-grid">

    {{-- Shifts list --}}
    <div class="shifts-card">
        <div class="shifts-header">
            <h3><i class="fas fa-list"></i> All Shifts ({{ $shifts->count() }})</h3>
        </div>
        <div style="overflow-x:auto">
            <table class="shifts-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Schedule</th>
                        <th>Duration</th>
                        <th>Grace</th>
                        <th>Staff</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                    <tr>
                        <td style="font-weight:600">{{ $shift->name }}</td>
                        <td>
                            <span class="shift-time">
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                <span class="shift-sep">–</span>
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                            </span>
                        </td>
                        <td>
                            <span class="dur-pill">
                                <i class="fas fa-hourglass-half" style="font-size:.6rem"></i>
                                {{ $shift->duration }}
                            </span>
                        </td>
                        <td>
                            <span class="grace-pill">
                                <i class="fas fa-clock" style="font-size:.6rem"></i>
                                {{ $shift->grace_minutes ?? 0 }}min
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:600;color:var(--purple)">
                                {{ $shift->employees_count }}
                            </span>
                            <span style="font-size:.75rem;color:var(--muted)"> staff</span>
                        </td>
                        <td>
                            <span class="badge {{ $shift->is_active ? 'badge-success' : 'badge-muted' }}">
                                {{ $shift->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="tbl-actions">
                                <button type="button" class="tbl-btn" title="Edit"
                                        onclick="openEdit(
                                            {{ $shift->id }},
                                            '{{ addslashes($shift->name) }}',
                                            '{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}',
                                            '{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}',
                                            {{ $shift->grace_minutes ?? 0 }},
                                            {{ $shift->is_active ? 1 : 0 }}
                                        )">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                <form method="POST"
                                      action="{{ route('admin.shifts.destroy', $shift) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($shift->name) }}?')"
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
                        <td colspan="7">
                            <div style="padding:3rem;text-align:center;color:var(--muted)">
                                <i class="fas fa-clock" style="font-size:2rem;display:block;margin-bottom:.6rem;opacity:.18"></i>
                                <p style="font-size:.86rem">No shifts yet. Create one →</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add shift form --}}
    <div class="form-card">
        <div class="form-card-header">
            <h3><i class="fas fa-plus"></i> Add New Shift</h3>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('admin.shifts.store') }}">
                @csrf
                <div class="sf-field">
                    <label class="sf-label">Shift Name <span style="color:var(--pink)">*</span></label>
                    <input type="text" name="name" class="sf-input"
                           placeholder="e.g. Morning Shift" required
                           value="{{ old('name') }}">
                </div>
                <div class="sf-grid-2">
                    <div class="sf-field">
                        <label class="sf-label">Start Time <span style="color:var(--pink)">*</span></label>
                        <input type="time" name="start_time" class="sf-input"
                               value="{{ old('start_time','08:00') }}" required>
                    </div>
                    <div class="sf-field">
                        <label class="sf-label">End Time <span style="color:var(--pink)">*</span></label>
                        <input type="time" name="end_time" class="sf-input"
                               value="{{ old('end_time','17:00') }}" required>
                    </div>
                </div>
                <div class="sf-field">
                    <label class="sf-label">Grace Period (minutes)</label>
                    <input type="number" name="grace_minutes" class="sf-input"
                           value="{{ old('grace_minutes',15) }}" min="0" max="60"
                           placeholder="15">
                    <span style="font-size:.72rem;color:var(--muted);margin-top:.2rem">
                        Allowed late arrival before marking as Late
                    </span>
                </div>
                <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem">
                    <input type="checkbox" name="is_active" id="is_active"
                           value="1" checked style="width:auto;accent-color:var(--purple)">
                    <label for="is_active"
                           style="font-size:.84rem;cursor:pointer;color:var(--text);margin:0">
                        Active shift
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">
                    <i class="fas fa-floppy-disk"></i> Save Shift
                </button>
            </form>
        </div>
    </div>

</div>

{{-- Edit modal --}}
<div class="em-overlay" id="editModal">
    <div class="em-modal">
        <h3><i class="fas fa-pen-to-square"></i> Edit Shift</h3>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="sf-field">
                <label class="sf-label">Shift Name <span style="color:var(--pink)">*</span></label>
                <input type="text" name="name" id="editName" class="sf-input" required>
            </div>
            <div class="sf-grid-2">
                <div class="sf-field">
                    <label class="sf-label">Start Time</label>
                    <input type="time" name="start_time" id="editStart" class="sf-input">
                </div>
                <div class="sf-field">
                    <label class="sf-label">End Time</label>
                    <input type="time" name="end_time" id="editEnd" class="sf-input">
                </div>
            </div>
            <div class="sf-field">
                <label class="sf-label">Grace Period (minutes)</label>
                <input type="number" name="grace_minutes" id="editGrace"
                       class="sf-input" min="0" max="60">
            </div>
            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem">
                <input type="checkbox" name="is_active" id="editActive"
                       value="1" style="width:auto;accent-color:var(--purple)">
                <label for="editActive"
                       style="font-size:.84rem;cursor:pointer;color:var(--text);margin:0">
                    Active shift
                </label>
            </div>
            <div style="display:flex;gap:.65rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Update Shift
                </button>
                <button type="button" class="btn btn-outline" onclick="closeEdit()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEdit(id, name, start, end, grace, active) {
    document.getElementById('editForm').action  = `/admin/shifts/${id}`;
    document.getElementById('editName').value   = name;
    document.getElementById('editStart').value  = start;
    document.getElementById('editEnd').value    = end;
    document.getElementById('editGrace').value  = grace;
    document.getElementById('editActive').checked = active == 1;
    document.getElementById('editModal').classList.add('show');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('show');
}
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('show');
});
</script>
@endpush
