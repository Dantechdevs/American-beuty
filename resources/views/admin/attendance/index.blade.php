@extends('layouts.admin')

@section('title', 'Attendance Records')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   ATTENDANCE — INDEX
   ═══════════════════════════════════════════════════════════ */

.att-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1000px) { .att-stats { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 600px)  { .att-stats { grid-template-columns: repeat(2,1fr); } }

/* ── Filters ── */
.att-filters {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    align-items: flex-end;
}
.att-filters .filter-group {
    display: flex; flex-direction: column;
    gap: .3rem; flex: 1; min-width: 140px;
}
.att-filters label {
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.att-filters input,
.att-filters select {
    padding: .55rem .8rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .85rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.att-filters input:focus,
.att-filters select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}

/* ── Table card ── */
.att-table-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.att-table-header {
    padding: 1rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
}
.att-table-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.att-table-header h3 i { color: var(--purple); }

.att-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.att-table thead tr {
    background: #faf7ff; border-bottom: 1.5px solid var(--border);
}
.att-table thead th {
    padding: .75rem 1rem; text-align: left;
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase;
    letter-spacing: .06em; white-space: nowrap;
}
.att-table tbody tr {
    border-bottom: 1px solid var(--border); transition: background .15s;
}
.att-table tbody tr:last-child { border-bottom: none; }
.att-table tbody tr:hover { background: #faf7ff; }
.att-table td { padding: .85rem 1rem; vertical-align: middle; }

/* Employee cell */
.emp-cell { display: flex; align-items: center; gap: .65rem; }
.emp-avatar {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--purple), var(--pink));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .78rem; font-weight: 700; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(124,58,237,.25);
}
.emp-name { font-weight: 600; color: var(--text); }
.emp-role { font-size: .71rem; color: var(--muted); margin-top: .1rem; }

/* Time cells */
.time-cell {
    font-variant-numeric: tabular-nums;
    font-weight: 600; white-space: nowrap;
}
.time-cell.in  { color: var(--green); }
.time-cell.out { color: var(--tango); }
.time-cell.none { color: var(--muted); font-weight: 400; }

/* Hours badge */
.hours-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .76rem; font-weight: 700;
    background: var(--purple-soft); color: var(--purple);
    border: 1px solid #ddd6fe; border-radius: 20px;
    padding: .2rem .6rem;
}

/* Status badges */
.badge-present   { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
.badge-late      { background: #fffbeb; color: #a16207; border-color: #fde68a; }
.badge-early_out { background: var(--tango-soft); color: var(--tango); border-color: #fed7aa; }
.badge-absent    { background: var(--pink-soft);  color: var(--pink);  border-color: #fecdd3; }
.badge-half_day  { background: var(--gold-soft);  color: var(--gold);  border-color: #fde68a; }

/* Action buttons */
.tbl-actions { display: flex; gap: .4rem; align-items: center; }
.tbl-btn {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1.5px solid var(--border); background: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .76rem; color: var(--muted);
    transition: all .15s; text-decoration: none;
}
.tbl-btn:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.tbl-btn.danger:hover { border-color: var(--tango); color: var(--tango); background: var(--pink-soft); }

/* Empty state */
.att-empty {
    padding: 3.5rem 1rem; text-align: center; color: var(--muted);
}
.att-empty i {
    font-size: 2.5rem; opacity: .15; color: var(--purple);
    display: block; margin-bottom: .75rem;
}

/* Pagination */
.att-pagination {
    padding: .85rem 1.25rem;
    border-top: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    font-size: .82rem; color: var(--muted);
    background: #faf7ff; flex-wrap: wrap; gap: .5rem;
}

/* Override modal */
.override-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,1,20,.55); backdrop-filter: blur(4px);
    z-index: 500; align-items: center; justify-content: center;
}
.override-modal-overlay.show { display: flex; }
.override-modal {
    background: #fff; border-radius: 18px;
    padding: 1.75rem; width: 420px; max-width: 95vw;
    box-shadow: 0 20px 60px rgba(124,58,237,.2);
    animation: modalIn .2s ease;
}
@keyframes modalIn { from { opacity:0; transform:scale(.94); } to { opacity:1; transform:scale(1); } }
.override-modal h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; margin-bottom: 1rem;
    display: flex; align-items: center; gap: .5rem;
}
.override-modal h3 i { color: var(--purple); }
.om-field { display: flex; flex-direction: column; gap: .35rem; margin-bottom: .85rem; }
.om-label {
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.om-input, .om-select {
    padding: .6rem .9rem;
    border: 1.5px solid var(--border); border-radius: var(--r-sm);
    font-size: .86rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.om-input:focus, .om-select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-clipboard-list" style="color:var(--purple)"></i>
            Attendance Records
        </h1>
        <p class="page-sub">Full attendance log for all employees</p>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.attendance.terminal') }}" class="btn btn-outline">
            <i class="fas fa-user-clock"></i> Terminal
        </a>
        <a href="{{ route('admin.attendance.report') }}" class="btn btn-outline">
            <i class="fas fa-chart-bar"></i> Report
        </a>
        <a href="{{ route('admin.attendance.export') }}" class="btn btn-outline">
            <i class="fas fa-file-export"></i> Export
        </a>
        <button type="button" class="btn btn-primary" onclick="openManualModal()">
            <i class="fas fa-plus"></i> Manual Entry
        </button>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i><div>{{ session('success') }}</div>
    </div>
@endif

{{-- Stat cards --}}
<div class="att-stats">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <span class="stat-label">Present Today</span>
            <span class="stat-value">{{ $todayStats['present'] }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <span class="stat-label">Late Today</span>
            <span class="stat-value">{{ $todayStats['late'] }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-circle-xmark"></i></div>
        <div class="stat-info">
            <span class="stat-label">Absent Today</span>
            <span class="stat-value">{{ $todayStats['absent'] }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <span class="stat-label">Total Staff</span>
            <span class="stat-value">{{ $todayStats['total'] }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-arrow-right-from-bracket"></i></div>
        <div class="stat-info">
            <span class="stat-label">Early Out</span>
            <span class="stat-value">{{ $todayStats['early_out'] }}</span>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.attendance.index') }}" class="att-filters">
    <div class="filter-group">
        <label>Employee</label>
        <select name="employee_id">
            <option value="">All Employees</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}"
                    {{ request('employee_id') == $emp->id ? 'selected':'' }}>
                    {{ $emp->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="filter-group" style="max-width:160px">
        <label>Status</label>
        <select name="status">
            <option value="">All Statuses</option>
            <option value="present"   {{ request('status') === 'present'   ? 'selected':'' }}>Present</option>
            <option value="late"      {{ request('status') === 'late'      ? 'selected':'' }}>Late</option>
            <option value="early_out" {{ request('status') === 'early_out' ? 'selected':'' }}>Early Out</option>
            <option value="absent"    {{ request('status') === 'absent'    ? 'selected':'' }}>Absent</option>
            <option value="half_day"  {{ request('status') === 'half_day'  ? 'selected':'' }}>Half Day</option>
        </select>
    </div>
    <div class="filter-group" style="max-width:160px">
        <label>Date</label>
        <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}">
    </div>
    <div class="filter-group" style="max-width:140px">
        <label>From</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}">
    </div>
    <div class="filter-group" style="max-width:140px">
        <label>To</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}">
    </div>
    <div style="display:flex;gap:.5rem;align-items:flex-end;padding-bottom:1px">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['employee_id','status','date','date_from','date_to']))
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-xmark"></i> Clear
            </a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="att-table-card">
    <div class="att-table-header">
        <h3>
            <i class="fas fa-list"></i> Records
            <span style="font-size:.75rem;font-weight:600;color:var(--muted);font-family:inherit">
                ({{ $attendances->total() }})
            </span>
        </h3>
        <span style="font-size:.78rem;color:var(--muted)">
            Showing {{ request('date', today()->toDateString()) }}
        </span>
    </div>

    <div style="overflow-x:auto">
        <table class="att-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Shift</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Hours</th>
                    <th style="text-align:center">Status</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    {{-- Employee --}}
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar">{{ $att->employee->initials }}</div>
                            <div>
                                <div class="emp-name">{{ $att->employee->name }}</div>
                                <div class="emp-role">{{ $att->employee->role_label }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Date --}}
                    <td style="color:var(--muted);white-space:nowrap;font-size:.82rem">
                        <i class="fas fa-calendar-days" style="margin-right:.3rem;font-size:.75rem"></i>
                        {{ $att->date->format('d M Y') }}
                    </td>

                    {{-- Shift --}}
                    <td>
                        @if($att->shift)
                            <span style="font-size:.78rem;font-weight:600;color:var(--purple)">
                                {{ $att->shift->name }}
                            </span>
                            <div style="font-size:.68rem;color:var(--muted)">
                                {{ $att->shift->formatted_hours }}
                            </div>
                        @else
                            <span style="color:var(--muted);font-size:.8rem">—</span>
                        @endif
                    </td>

                    {{-- Clock In --}}
                    <td>
                        @if($att->clock_in)
                            <span class="time-cell in">
                                <i class="fas fa-right-to-bracket" style="font-size:.72rem;margin-right:.25rem"></i>
                                {{ $att->clock_in->format('H:i') }}
                            </span>
                        @else
                            <span class="time-cell none">—</span>
                        @endif
                    </td>

                    {{-- Clock Out --}}
                    <td>
                        @if($att->clock_out)
                            <span class="time-cell out">
                                <i class="fas fa-right-from-bracket" style="font-size:.72rem;margin-right:.25rem"></i>
                                {{ $att->clock_out->format('H:i') }}
                            </span>
                        @else
                            @if($att->clock_in)
                                <span style="font-size:.75rem;color:var(--green);font-weight:600">
                                    ● Still In
                                </span>
                            @else
                                <span class="time-cell none">—</span>
                            @endif
                        @endif
                    </td>

                    {{-- Hours --}}
                    <td>
                        @if($att->hours_worked)
                            <span class="hours-badge">
                                <i class="fas fa-hourglass-half" style="font-size:.65rem"></i>
                                {{ $att->hours_worked_formatted }}
                            </span>
                        @else
                            <span style="color:var(--muted);font-size:.8rem">—</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td style="text-align:center">
                        <span class="badge badge-{{ $att->status }}">
                            <i class="fas {{ $att->status_icon }}"></i>
                            {{ $att->status_label }}
                        </span>
                        @if($att->admin_override)
                            <div style="font-size:.65rem;color:var(--muted);margin-top:.2rem">
                                <i class="fas fa-pen-to-square"></i> Overridden
                            </div>
                        @endif
                    </td>

                    {{-- Note --}}
                    <td style="color:var(--muted);font-size:.78rem;max-width:140px">
                        <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $att->note ?? '—' }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="tbl-actions">
                            <button type="button"
                                    class="tbl-btn"
                                    title="Override"
                                    onclick="openOverrideModal(
                                        {{ $att->id }},
                                        '{{ addslashes($att->employee->name) }}',
                                        '{{ $att->status }}',
                                        '{{ $att->clock_in?->format('H:i') }}',
                                        '{{ $att->clock_out?->format('H:i') }}',
                                        '{{ addslashes($att->note ?? '') }}'
                                    )">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                            <a href="{{ route('admin.attendance.show', $att->employee_id) }}"
                               class="tbl-btn" title="View Employee">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.attendance.destroy', $att->id) }}"
                                  onsubmit="return confirm('Delete this record?')"
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
                    <td colspan="9">
                        <div class="att-empty">
                            <i class="fas fa-clipboard-list"></i>
                            <p>No attendance records found for this filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($attendances->hasPages())
    <div class="att-pagination">
        <span>
            Showing {{ $attendances->firstItem() }}–{{ $attendances->lastItem() }}
            of {{ $attendances->total() }} records
        </span>
        {{ $attendances->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Override Modal --}}
<div class="override-modal-overlay" id="overrideModal">
    <div class="override-modal">
        <h3><i class="fas fa-pen-to-square"></i> Override Attendance</h3>
        <p style="font-size:.82rem;color:var(--muted);margin-bottom:1rem">
            Editing record for <strong id="omEmpName"></strong>
        </p>
        <form method="POST" id="overrideForm">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                <div class="om-field">
                    <label class="om-label">Clock In</label>
                    <input type="time" name="clock_in" id="omClockIn" class="om-input">
                </div>
                <div class="om-field">
                    <label class="om-label">Clock Out</label>
                    <input type="time" name="clock_out" id="omClockOut" class="om-input">
                </div>
            </div>

            <div class="om-field">
                <label class="om-label">Status</label>
                <select name="status" id="omStatus" class="om-select">
                    <option value="present">Present</option>
                    <option value="late">Late</option>
                    <option value="early_out">Early Out</option>
                    <option value="absent">Absent</option>
                    <option value="half_day">Half Day</option>
                </select>
            </div>

            <div class="om-field">
                <label class="om-label">Note / Reason</label>
                <input type="text" name="note" id="omNote" class="om-input"
                       placeholder="Reason for override…">
            </div>

            <div style="display:flex;gap:.65rem;margin-top:1.25rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Save Override
                </button>
                <button type="button" class="btn btn-outline" onclick="closeOverrideModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Manual Entry Modal --}}
<div class="override-modal-overlay" id="manualModal">
    <div class="override-modal">
        <h3><i class="fas fa-plus"></i> Manual Attendance Entry</h3>
        <form method="POST" action="{{ route('admin.attendance.manual') }}">
            @csrf

            <div class="om-field">
                <label class="om-label">Employee <span style="color:var(--pink)">*</span></label>
                <select name="employee_id" class="om-select" required>
                    <option value="">— Select employee —</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="om-field">
                <label class="om-label">Date <span style="color:var(--pink)">*</span></label>
                <input type="date" name="date" class="om-input"
                       value="{{ today()->toDateString() }}" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                <div class="om-field">
                    <label class="om-label">Clock In</label>
                    <input type="time" name="clock_in" class="om-input">
                </div>
                <div class="om-field">
                    <label class="om-label">Clock Out</label>
                    <input type="time" name="clock_out" class="om-input">
                </div>
            </div>

            <div class="om-field">
                <label class="om-label">Status <span style="color:var(--pink)">*</span></label>
                <select name="status" class="om-select" required>
                    <option value="present">Present</option>
                    <option value="late">Late</option>
                    <option value="early_out">Early Out</option>
                    <option value="absent">Absent</option>
                    <option value="half_day">Half Day</option>
                </select>
            </div>

            <div class="om-field">
                <label class="om-label">Note</label>
                <input type="text" name="note" class="om-input"
                       placeholder="Optional note…">
            </div>

            <div style="display:flex;gap:.65rem;margin-top:1.25rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Save Entry
                </button>
                <button type="button" class="btn btn-outline" onclick="closeManualModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Override Modal ──
function openOverrideModal(id, name, status, clockIn, clockOut, note) {
    document.getElementById('omEmpName').textContent  = name;
    document.getElementById('omClockIn').value        = clockIn || '';
    document.getElementById('omClockOut').value       = clockOut || '';
    document.getElementById('omStatus').value         = status;
    document.getElementById('omNote').value           = note;
    document.getElementById('overrideForm').action    =
        '/admin/attendance/' + id + '/override';
    document.getElementById('overrideModal').classList.add('show');
}
function closeOverrideModal() {
    document.getElementById('overrideModal').classList.remove('show');
}

// ── Manual Modal ──
function openManualModal() {
    document.getElementById('manualModal').classList.add('show');
}
function closeManualModal() {
    document.getElementById('manualModal').classList.remove('show');
}

// Close on backdrop click
['overrideModal','manualModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});
</script>
@endpush