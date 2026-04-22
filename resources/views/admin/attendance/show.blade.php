@extends('layouts.admin')

@section('title', $employee->name . ' — Attendance')

@push('styles')
<style>
.show-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 900px) { .show-grid { grid-template-columns: 1fr; } }

.profile-mini {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.profile-mini-hero {
    background: linear-gradient(135deg, var(--purple), var(--pink));
    padding: 1.5rem 1.25rem;
    text-align: center;
    position: relative;
}
.profile-mini-hero::after {
    content: ''; position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 18px; background: #fff;
    border-radius: 20px 20px 0 0;
}
.mini-av {
    width: 64px; height: 64px; border-radius: 16px;
    background: rgba(255,255,255,.25);
    border: 2.5px solid rgba(255,255,255,.5);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .65rem;
    color: #fff; font-size: 1.25rem; font-weight: 700;
    box-shadow: 0 6px 20px rgba(0,0,0,.18);
}
.mini-av img {
    width: 100%; height: 100%;
    border-radius: 14px; object-fit: cover;
}
.mini-name {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700;
    color: #fff; margin-bottom: .3rem;
}
.mini-role {
    font-size: .72rem; font-weight: 600;
    background: rgba(255,255,255,.2); color: #fff;
    border-radius: 20px; padding: .18rem .65rem;
    display: inline-block; text-transform: capitalize;
}
.mini-info { padding: .85rem 1.1rem; }
.mini-row {
    display: flex; align-items: center; gap: .65rem;
    padding: .5rem 0; border-bottom: 1px solid var(--border);
    font-size: .82rem;
}
.mini-row:last-child { border-bottom: none; }
.mini-icon {
    width: 26px; height: 26px; border-radius: 7px;
    background: var(--purple-soft); color: var(--purple);
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; flex-shrink: 0;
}
.mini-lbl { font-size: .7rem; color: var(--muted); }
.mini-val { font-weight: 600; color: var(--text); font-size: .81rem; }

.month-stats {
    display: grid; grid-template-columns: repeat(2,1fr);
    gap: .65rem; padding: .85rem 1.1rem;
    border-top: 1.5px solid var(--border); background: #faf7ff;
}
.ms-item { text-align: center; }
.ms-val { font-size: 1.2rem; font-weight: 700; color: var(--text); line-height: 1; }
.ms-lbl { font-size: .7rem; color: var(--muted); margin-top: .2rem; }

.hist-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.hist-header {
    padding: .95rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
}
.hist-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.hist-header h3 i { color: var(--purple); }
.hist-filter {
    padding: .75rem 1.25rem; background: #faf7ff;
    border-bottom: 1px solid var(--border);
    display: flex; gap: .5rem; flex-wrap: wrap; align-items: center;
}
.hist-filter input, .hist-filter select {
    padding: .45rem .75rem;
    border: 1.5px solid var(--border); border-radius: var(--r-sm);
    font-size: .82rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s;
}
.hist-filter input:focus, .hist-filter select:focus {
    border-color: var(--purple);
}
.hist-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.hist-table thead tr { background: #f5f3ff; border-bottom: 1.5px solid var(--border); }
.hist-table thead th {
    padding: .68rem 1rem; text-align: left;
    font-size: .69rem; font-weight: 700;
    color: var(--purple); text-transform: uppercase; letter-spacing: .07em;
}
.hist-table tbody tr { border-bottom: 1px solid #f3eeff; transition: background .13s; }
.hist-table tbody tr:last-child { border-bottom: none; }
.hist-table tbody tr:hover { background: #faf7ff; }
.hist-table td { padding: .82rem 1rem; vertical-align: middle; }

.today-pill {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,var(--purple),var(--pink));
    color: #fff; font-size: .6rem; font-weight: 700;
    padding: .12rem .45rem; border-radius: 20px;
    letter-spacing: .04em; text-transform: uppercase;
    vertical-align: middle; margin-left: .35rem;
}
.live-in {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 700; color: var(--green);
}
.live-in::before {
    content: ''; width: 6px; height: 6px; border-radius: 50%;
    background: var(--green); box-shadow: 0 0 0 2px rgba(45,198,83,.25);
    animation: blink 2s infinite;
}
@keyframes blink {
    0%,100%{box-shadow:0 0 0 2px rgba(45,198,83,.25);}
    50%{box-shadow:0 0 0 5px rgba(45,198,83,.06);}
}
.tbl-btn {
    width: 28px; height: 28px; border-radius: 7px;
    border: 1.5px solid var(--border); background: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .74rem; color: var(--muted);
    transition: all .15s;
}
.tbl-btn:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }

/* Override modal */
.ov-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,1,20,.55); backdrop-filter: blur(4px);
    z-index: 500; align-items: center; justify-content: center;
}
.ov-overlay.show { display: flex; }
.ov-modal {
    background: #fff; border-radius: 18px;
    padding: 1.75rem; width: 420px; max-width: 95vw;
    box-shadow: 0 20px 60px rgba(124,58,237,.2);
    animation: modalIn .2s ease;
}
@keyframes modalIn{from{opacity:0;transform:scale(.94);}to{opacity:1;transform:scale(1);}}
.ov-modal h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; margin-bottom: 1rem;
    display: flex; align-items: center; gap: .5rem;
    padding-bottom: .75rem; border-bottom: 1.5px solid var(--border);
}
.ov-modal h3 i { color: var(--purple); }
.ov-field { display: flex; flex-direction: column; gap: .32rem; margin-bottom: .82rem; }
.ov-label { font-size: .71rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
.ov-input, .ov-select {
    padding: .6rem .9rem; border: 1.5px solid var(--border);
    border-radius: var(--r-sm); font-size: .86rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.ov-input:focus, .ov-select:focus {
    border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div style="display:flex;align-items:center;gap:.65rem;margin-bottom:1.25rem;flex-wrap:wrap">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Employees
    </a>
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-list"></i> All Records
    </a>
    <a href="{{ route('admin.attendance.export') }}?employee_id={{ $employee->id }}"
       class="btn btn-outline btn-sm">
        <i class="fas fa-file-export"></i> Export CSV
    </a>
</div>

@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div class="show-grid">

    {{-- Profile card --}}
    <div>
        <div class="profile-mini">
            <div class="profile-mini-hero">
                <div class="mini-av">
                    @if($employee->photo)
                        <img src="{{ asset('storage/'.$employee->photo) }}" alt="{{ $employee->name }}">
                    @else
                        {{ strtoupper(substr($employee->name,0,2)) }}
                    @endif
                </div>
                <div class="mini-name">{{ $employee->name }}</div>
                <span class="mini-role">{{ $employee->role }}</span>
            </div>
            <div class="mini-info">
                <div class="mini-row">
                    <div class="mini-icon"><i class="fas fa-circle-dot"></i></div>
                    <div>
                        <div class="mini-lbl">Status</div>
                        <div class="mini-val">
                            @if($employee->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-muted">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($employee->shift)
                <div class="mini-row">
                    <div class="mini-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="mini-lbl">Shift</div>
                        <div class="mini-val">
                            {{ $employee->shift->name }}
                            <span style="font-weight:400;font-size:.73rem;color:var(--muted)">
                                · {{ \Carbon\Carbon::parse($employee->shift->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($employee->shift->end_time)->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                @if($employee->phone)
                <div class="mini-row">
                    <div class="mini-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="mini-lbl">Phone</div>
                        <div class="mini-val">{{ $employee->phone }}</div>
                    </div>
                </div>
                @endif
                <div class="mini-row">
                    <div class="mini-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <div class="mini-lbl">Joined</div>
                        <div class="mini-val">
                            {{ $employee->joined_date?->format('d M Y') ?? $employee->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Month stats --}}
            <div class="month-stats">
                <div class="ms-item">
                    <div class="ms-val" style="color:var(--green)">{{ $stats['present'] }}</div>
                    <div class="ms-lbl">Present</div>
                </div>
                <div class="ms-item">
                    <div class="ms-val" style="color:var(--tango)">{{ $stats['absent'] }}</div>
                    <div class="ms-lbl">Absent</div>
                </div>
                <div class="ms-item">
                    <div class="ms-val" style="color:var(--gold)">{{ $stats['late'] }}</div>
                    <div class="ms-lbl">Late</div>
                </div>
                <div class="ms-item">
                    <div class="ms-val" style="color:var(--purple)">{{ $stats['hours'] }}h</div>
                    <div class="ms-lbl">Hours</div>
                </div>
            </div>

            {{-- Today --}}
            <div style="padding:.85rem 1.1rem;border-top:1.5px solid var(--border);background:var(--purple-soft)">
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.6rem">
                    Today
                </div>
                @if($today)
                    <div style="display:flex;flex-direction:column;gap:.4rem;font-size:.82rem">
                        <div style="display:flex;justify-content:space-between">
                            <span style="color:var(--muted)">Status</span>
                            <span class="badge badge-{{ $today->status_badge }}">{{ $today->status_label }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="color:var(--muted)">In</span>
                            <strong style="color:var(--green)">{{ $today->clock_in?->format('H:i') ?? '—' }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="color:var(--muted)">Out</span>
                            <strong>{{ $today->clock_out?->format('H:i') ?? '—' }}</strong>
                        </div>
                        @if($today->isClockedIn())
                            <div class="live-in" style="justify-content:flex-end">Currently in</div>
                        @endif
                    </div>
                @else
                    <div style="text-align:center;color:var(--muted);font-size:.82rem;padding:.4rem 0">
                        <i class="fas fa-door-closed" style="display:block;font-size:1.4rem;opacity:.18;margin-bottom:.35rem"></i>
                        Not clocked in
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- History --}}
    <div class="hist-card">
        <div class="hist-header">
            <h3><i class="fas fa-clock-rotate-left"></i> Attendance History</h3>
            <a href="{{ route('admin.attendance.report') }}?employee_id={{ $employee->id }}"
               class="btn btn-outline btn-sm">
                <i class="fas fa-chart-bar"></i> Full Report
            </a>
        </div>

        <form method="GET" class="hist-filter"
              action="{{ route('admin.attendance.show', $employee) }}">
            <label style="font-size:.75rem;font-weight:600;color:var(--muted)">From</label>
            <input type="date" name="date_from"
                   value="{{ request('date_from', now()->startOfMonth()->toDateString()) }}">
            <label style="font-size:.75rem;font-weight:600;color:var(--muted)">To</label>
            <input type="date" name="date_to"
                   value="{{ request('date_to', now()->toDateString()) }}">
            <select name="status">
                <option value="">All Statuses</option>
                <option value="present"   {{ request('status')==='present'  ?'selected':'' }}>Present</option>
                <option value="late"      {{ request('status')==='late'     ?'selected':'' }}>Late</option>
                <option value="early_out" {{ request('status')==='early_out'?'selected':'' }}>Early Out</option>
                <option value="absent"    {{ request('status')==='absent'   ?'selected':'' }}>Absent</option>
                <option value="half_day"  {{ request('status')==='half_day' ?'selected':'' }}>Half Day</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>

        <div style="overflow-x:auto">
            <table class="hist-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Shift</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $a)
                    <tr>
                        <td>
                            <span style="font-weight:600">{{ $a->date->format('d M Y') }}</span>
                            @if($a->date->isToday())
                                <span class="today-pill">Today</span>
                            @endif
                        </td>
                        <td style="color:var(--muted);font-size:.78rem">{{ $a->date->format('D') }}</td>
                        <td style="font-size:.78rem;color:var(--muted)">{{ $a->shift?->name ?? '—' }}</td>
                        <td>
                            @if($a->clock_in)
                                <span style="font-weight:600;color:var(--green)">{{ $a->clock_in->format('H:i') }}</span>
                            @else
                                <span style="color:var(--muted)">—</span>
                            @endif
                        </td>
                        <td>
                            @if($a->clock_out)
                                <span style="font-weight:600">{{ $a->clock_out->format('H:i') }}</span>
                            @elseif($a->clock_in)
                                <span class="live-in">Live</span>
                            @else
                                <span style="color:var(--muted)">—</span>
                            @endif
                        </td>
                        <td style="font-weight:600">{{ $a->hours_formatted }}</td>
                        <td>
                            <span class="badge badge-{{ $a->status_badge }}">{{ $a->status_label }}</span>
                            @if($a->admin_override)
                                <span class="badge badge-purple" style="margin-left:.2rem;font-size:.6rem" title="Overridden">
                                    <i class="fas fa-pen"></i>
                                </span>
                            @endif
                        </td>
                        <td style="font-size:.76rem;color:var(--muted);max-width:120px">
                            <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $a->note ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <button class="tbl-btn" title="Override"
                                    onclick="openOv({{ $a->id }},'{{ $a->status }}','{{ $a->clock_in?->format('H:i') }}','{{ $a->clock_out?->format('H:i') }}','{{ addslashes($a->note ?? '') }}')">
                                <i class="fas fa-pen"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div style="padding:2.5rem;text-align:center;color:var(--muted)">
                                <i class="fas fa-clock" style="font-size:2rem;display:block;margin-bottom:.6rem;opacity:.18"></i>
                                <span style="font-size:.86rem">No records for this period.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
        <div style="padding:.8rem 1.25rem;border-top:1.5px solid var(--border);background:#faf7ff">
            {{ $attendances->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

{{-- Override modal --}}
<div class="ov-overlay" id="ovModal">
    <div class="ov-modal">
        <h3><i class="fas fa-pen-to-square"></i> Override Record</h3>
        <form id="ovForm" method="POST">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                <div class="ov-field">
                    <label class="ov-label">Clock In</label>
                    <input type="time" name="clock_in" id="ov_in" class="ov-input">
                </div>
                <div class="ov-field">
                    <label class="ov-label">Clock Out</label>
                    <input type="time" name="clock_out" id="ov_out" class="ov-input">
                </div>
            </div>
            <div class="ov-field">
                <label class="ov-label">Status</label>
                <select name="status" id="ov_status" class="ov-select">
                    <option value="present">Present</option>
                    <option value="late">Late</option>
                    <option value="early_out">Early Out</option>
                    <option value="absent">Absent</option>
                    <option value="half_day">Half Day</option>
                </select>
            </div>
            <div class="ov-field">
                <label class="ov-label">Note</label>
                <input type="text" name="note" id="ov_note" class="ov-input" placeholder="Reason…">
            </div>
            <div style="display:flex;gap:.65rem;margin-top:1.1rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Save
                </button>
                <button type="button" class="btn btn-outline" onclick="closeOv()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openOv(id, status, cin, cout, note) {
    document.getElementById('ovForm').action = `/admin/attendance/${id}/override`;
    document.getElementById('ov_status').value = status;
    document.getElementById('ov_in').value     = cin  || '';
    document.getElementById('ov_out').value    = cout || '';
    document.getElementById('ov_note').value   = note || '';
    document.getElementById('ovModal').classList.add('show');
}
function closeOv() { document.getElementById('ovModal').classList.remove('show'); }
document.getElementById('ovModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('show');
});
</script>
@endpush
