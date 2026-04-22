@extends('layouts.admin')

@section('title', $employee->name)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════
   EMPLOYEE PROFILE
   ═══════════════════════════════════════════════════════ */
.profile-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 900px) {
    .profile-grid { grid-template-columns: 1fr; }
}

/* Profile card */
.profile-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.profile-card-hero {
    background: linear-gradient(135deg, var(--purple) 0%, var(--pink) 100%);
    padding: 2rem 1.5rem 1.25rem;
    text-align: center;
    position: relative;
}
.profile-card-hero::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 20px;
    background: #fff;
    border-radius: 20px 20px 0 0;
}
.profile-photo {
    width: 80px; height: 80px;
    border-radius: 20px;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.4);
    margin: 0 auto .75rem;
    display: block;
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
}
.profile-initials {
    width: 80px; height: 80px;
    border-radius: 20px;
    background: rgba(255,255,255,.2);
    border: 3px solid rgba(255,255,255,.4);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .75rem;
    color: #fff; font-size: 1.5rem; font-weight: 700;
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
}
.profile-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; font-weight: 700;
    color: #fff; margin-bottom: .3rem;
}
.profile-role {
    font-size: .75rem; font-weight: 600;
    background: rgba(255,255,255,.2);
    color: #fff; border-radius: 20px;
    padding: .22rem .75rem;
    display: inline-block;
    letter-spacing: .04em; text-transform: capitalize;
}

/* Profile info rows */
.profile-info { padding: 1rem 1.25rem; }
.profile-row {
    display: flex; align-items: center; gap: .75rem;
    padding: .6rem 0;
    border-bottom: 1px solid var(--border);
    font-size: .83rem;
}
.profile-row:last-child { border-bottom: none; }
.profile-row-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: var(--purple-soft);
    display: flex; align-items: center; justify-content: center;
    color: var(--purple); font-size: .72rem; flex-shrink: 0;
}
.profile-row-label { color: var(--muted); font-size: .72rem; }
.profile-row-val   { font-weight: 600; color: var(--text); margin-top: .05rem; }

/* Quick stats row */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.25rem;
}
@media (max-width: 700px) { .quick-stats { grid-template-columns: repeat(2,1fr); } }

/* Attendance table card */
.att-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.att-card-header {
    padding: .95rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
}
.att-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.att-card-header h3 i { color: var(--purple); }
.att-filter {
    display: flex; gap: .5rem; align-items: center; flex-wrap: wrap;
    padding: .8rem 1.25rem;
    border-bottom: 1px solid var(--border);
    background: #faf7ff;
}
.att-filter input, .att-filter select {
    padding: .42rem .75rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .8rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s;
}
.att-filter input:focus, .att-filter select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.07);
}
.att-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
.att-table thead tr { background: #f5f3ff; border-bottom: 1.5px solid var(--border); }
.att-table thead th {
    padding: .68rem 1rem; text-align: left;
    font-size: .68rem; font-weight: 700;
    color: var(--purple); text-transform: uppercase; letter-spacing: .07em;
    white-space: nowrap;
}
.att-table tbody tr { border-bottom: 1px solid #f3eeff; transition: background .12s; }
.att-table tbody tr:last-child { border-bottom: none; }
.att-table tbody tr:hover { background: #faf7ff; }
.att-table td { padding: .8rem 1rem; vertical-align: middle; }

/* Today badge */
.today-badge {
    display: inline-flex; align-items: center; gap: .28rem;
    background: linear-gradient(135deg, var(--purple), var(--pink));
    color: #fff; font-size: .65rem; font-weight: 700;
    padding: .15rem .5rem; border-radius: 20px;
    letter-spacing: .04em; text-transform: uppercase;
    vertical-align: middle; margin-left: .4rem;
}

/* Clock status */
.clock-live {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 700; color: var(--green);
}
.clock-live::before {
    content: ''; width: 6px; height: 6px; border-radius: 50%;
    background: var(--green);
    box-shadow: 0 0 0 2px rgba(45,198,83,.25);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%,100% { box-shadow: 0 0 0 2px rgba(45,198,83,.25); }
    50%      { box-shadow: 0 0 0 5px rgba(45,198,83,.06); }
}

/* Action modal */
.em-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,1,20,.55); backdrop-filter: blur(4px);
    z-index: 500; align-items: center; justify-content: center;
}
.em-overlay.show { display: flex; }
.em-modal {
    background: #fff; border-radius: 20px;
    padding: 1.75rem; width: 480px; max-width: 95vw;
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 20px 60px rgba(124,58,237,.2);
    animation: modalIn .2s ease;
}
@keyframes modalIn { from { opacity:0; transform:scale(.94); } to { opacity:1; transform:scale(1); } }
.em-modal h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; margin-bottom: 1.2rem;
    display: flex; align-items: center; gap: .5rem;
    padding-bottom: .8rem; border-bottom: 1.5px solid var(--border);
}
.em-modal h3 i { color: var(--purple); }
.em-field { display: flex; flex-direction: column; gap: .3rem; margin-bottom: .85rem; }
.em-label {
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.em-input, .em-select {
    padding: .6rem .9rem;
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

{{-- Back nav --}}
<div style="display:flex;align-items:center;gap:.65rem;margin-bottom:1.25rem;flex-wrap:wrap">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Employees
    </a>
    <a href="{{ route('admin.attendance.today') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-calendar-day"></i> Today's Board
    </a>
    <button onclick="openEditModal()" class="btn btn-primary btn-sm">
        <i class="fas fa-pen-to-square"></i> Edit Profile
    </button>
    <form method="POST" action="{{ route('admin.employees.toggle', $employee) }}"
          style="margin:0">
        @csrf @method('PATCH')
        <button type="submit"
                class="btn btn-sm {{ $employee->is_active ? 'btn-outline' : 'btn-success' }}">
            <i class="fas {{ $employee->is_active ? 'fa-ban' : 'fa-check' }}"></i>
            {{ $employee->is_active ? 'Deactivate' : 'Activate' }}
        </button>
    </form>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

{{-- Quick stats --}}
<div class="quick-stats">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value">{{ $stats['present'] }}</div>
            <div class="stat-label">Present This Month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-circle-xmark"></i></div>
        <div>
            <div class="stat-value">{{ $stats['absent'] }}</div>
            <div class="stat-label">Absent This Month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $stats['late'] }}</div>
            <div class="stat-label">Late This Month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <div class="stat-value">{{ $stats['hours'] }}h</div>
            <div class="stat-label">Hours This Month</div>
        </div>
    </div>
</div>

{{-- Main grid --}}
<div class="profile-grid">

    {{-- ── Left: Profile card ── --}}
    <div>
        <div class="profile-card">
            {{-- Hero --}}
            <div class="profile-card-hero">
                @if($employee->photo)
                    <img src="{{ asset('storage/'.$employee->photo) }}"
                         alt="{{ $employee->name }}" class="profile-photo">
                @else
                    <div class="profile-initials">
                        {{ strtoupper(substr($employee->name,0,2)) }}
                    </div>
                @endif
                <div class="profile-name">{{ $employee->name }}</div>
                <span class="profile-role">{{ $employee->role }}</span>
            </div>

            {{-- Info rows --}}
            <div class="profile-info">
                <div class="profile-row">
                    <div class="profile-row-icon"><i class="fas fa-circle-dot"></i></div>
                    <div>
                        <div class="profile-row-label">Status</div>
                        <div class="profile-row-val">
                            @if($employee->is_active)
                                <span class="badge badge-success">
                                    <i class="fas fa-circle" style="font-size:.45rem"></i> Active
                                </span>
                            @else
                                <span class="badge badge-muted">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($employee->email)
                <div class="profile-row">
                    <div class="profile-row-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="profile-row-label">Email</div>
                        <div class="profile-row-val">{{ $employee->email }}</div>
                    </div>
                </div>
                @endif

                @if($employee->phone)
                <div class="profile-row">
                    <div class="profile-row-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="profile-row-label">Phone</div>
                        <div class="profile-row-val">{{ $employee->phone }}</div>
                    </div>
                </div>
                @endif

                <div class="profile-row">
                    <div class="profile-row-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="profile-row-label">Shift</div>
                        <div class="profile-row-val">
                            @if($employee->shift)
                                {{ $employee->shift->name }}
                                <span style="font-size:.72rem;color:var(--muted);font-weight:400">
                                    · {{ \Carbon\Carbon::parse($employee->shift->start_time)->format('H:i') }}
                                    – {{ \Carbon\Carbon::parse($employee->shift->end_time)->format('H:i') }}
                                </span>
                            @else
                                <span style="color:var(--muted);font-weight:400">Not assigned</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="profile-row">
                    <div class="profile-row-icon"><i class="fas fa-key"></i></div>
                    <div>
                        <div class="profile-row-label">PIN</div>
                        <div class="profile-row-val">
                            @if($employee->pin)
                                <span style="font-family:monospace;letter-spacing:.15em">
                                    {{ $employee->pin }}
                                </span>
                            @else
                                <span style="color:var(--muted);font-weight:400">Not set</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="profile-row">
                    <div class="profile-row-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <div class="profile-row-label">Joined</div>
                        <div class="profile-row-val">
                            {{ $employee->joined_date
                                ? $employee->joined_date->format('d M Y')
                                : $employee->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today status --}}
            <div style="padding:.9rem 1.25rem;border-top:1.5px solid var(--border);background:#faf7ff">
                <div style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.65rem">
                    Today's Status
                </div>
                @if($today)
                    <div style="display:flex;flex-direction:column;gap:.5rem;font-size:.82rem">
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="color:var(--muted)">Status</span>
                            <span class="badge badge-{{ $today->status_badge }}">
                                {{ $today->status_label }}
                            </span>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="color:var(--muted)">Clock In</span>
                            <strong style="color:var(--green)">
                                {{ $today->clock_in?->format('h:i A') ?? '—' }}
                            </strong>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="color:var(--muted)">Clock Out</span>
                            <strong>{{ $today->clock_out?->format('h:i A') ?? '—' }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between">
                            <span style="color:var(--muted)">Duration</span>
                            <strong>{{ $today->hours_formatted }}</strong>
                        </div>
                        @if($today->isClockedIn())
                            <div class="clock-live" style="justify-content:flex-end">
                                Currently clocked in
                            </div>
                        @endif
                    </div>
                @else
                    <div style="text-align:center;padding:.75rem 0;color:var(--muted);font-size:.83rem">
                        <i class="fas fa-door-closed"
                           style="display:block;font-size:1.5rem;opacity:.2;margin-bottom:.4rem"></i>
                        Not clocked in today
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Right: Attendance history ── --}}
    <div>
        <div class="att-card">
            <div class="att-card-header">
                <h3>
                    <i class="fas fa-clock-rotate-left"></i> Attendance History
                </h3>
                <div style="display:flex;gap:.5rem">
                    <a href="{{ route('admin.attendance.export') }}?employee_id={{ $employee->id }}"
                       class="btn btn-outline btn-sm">
                        <i class="fas fa-download"></i> Export
                    </a>
                    <a href="{{ route('admin.attendance.report') }}?employee_id={{ $employee->id }}"
                       class="btn btn-outline btn-sm">
                        <i class="fas fa-chart-bar"></i> Full Report
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <form method="GET" class="att-filter"
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

            {{-- Table --}}
            <div style="overflow-x:auto">
                <table class="att-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
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
                                <span style="font-weight:600">
                                    {{ $a->date->format('d M Y') }}
                                </span>
                                @if($a->date->isToday())
                                    <span class="today-badge">Today</span>
                                @endif
                            </td>
                            <td style="color:var(--muted);font-size:.8rem">
                                {{ $a->date->format('l') }}
                            </td>
                            <td>
                                @if($a->clock_in)
                                    <span style="font-weight:600;color:var(--green)">
                                        {{ $a->clock_in->format('h:i A') }}
                                    </span>
                                @else
                                    <span style="color:var(--muted)">—</span>
                                @endif
                            </td>
                            <td>
                                @if($a->clock_out)
                                    <span style="font-weight:600">
                                        {{ $a->clock_out->format('h:i A') }}
                                    </span>
                                @elseif($a->clock_in && !$a->clock_out)
                                    <span class="clock-live">Live</span>
                                @else
                                    <span style="color:var(--muted)">—</span>
                                @endif
                            </td>
                            <td style="font-weight:600">
                                {{ $a->hours_formatted }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $a->status_badge }}">
                                    {{ $a->status_label }}
                                </span>
                                @if($a->admin_override)
                                    <span class="badge badge-purple" style="margin-left:.25rem"
                                          title="Overridden by {{ $a->overriddenBy?->name }}">
                                        <i class="fas fa-pen" style="font-size:.55rem"></i>
                                    </span>
                                @endif
                            </td>
                            <td style="font-size:.77rem;color:var(--muted);max-width:130px">
                                {{ Str::limit($a->note ?? '—', 35) }}
                            </td>
                            <td>
                                <button onclick="openOverride({{ $a->id }}, '{{ $a->status }}', '{{ addslashes($a->note ?? '') }}')"
                                        class="btn btn-outline btn-sm" title="Override">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div style="padding:2.5rem;text-align:center;color:var(--muted)">
                                    <i class="fas fa-clock"
                                       style="font-size:2rem;display:block;margin-bottom:.6rem;opacity:.18"></i>
                                    <span style="font-size:.86rem">No records for selected period.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
            <div style="padding:.85rem 1.25rem;border-top:1.5px solid var(--border);background:#faf7ff">
                {{ $attendances->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ── Edit Employee Modal ── --}}
<div class="em-overlay" id="editModal">
    <div class="em-modal">
        <h3><i class="fas fa-pen-to-square"></i> Edit {{ $employee->name }}</h3>
        <form method="POST" action="{{ route('admin.employees.update', $employee) }}"
              enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="em-field">
                <label class="em-label">Full Name <span style="color:var(--pink)">*</span></label>
                <input type="text" name="name" class="em-input"
                       value="{{ $employee->name }}" required>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Email</label>
                    <input type="email" name="email" class="em-input"
                           value="{{ $employee->email }}">
                </div>
                <div class="em-field">
                    <label class="em-label">Phone</label>
                    <input type="text" name="phone" class="em-input"
                           value="{{ $employee->phone }}">
                </div>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Role <span style="color:var(--pink)">*</span></label>
                    <select name="role" class="em-select" required>
                        @foreach(['cashier','manager','supervisor','attendant','driver','cleaner'] as $r)
                            <option value="{{ $r }}"
                                {{ $employee->role === $r ? 'selected' : '' }}>
                                {{ ucfirst($r) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="em-field">
                    <label class="em-label">New PIN (blank = keep)</label>
                    <input type="text" name="pin" class="em-input"
                           placeholder="Leave blank to keep current"
                           maxlength="10" pattern="\d{4,10}">
                </div>
            </div>

            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Shift</label>
                    <select name="shift_id" class="em-select">
                        <option value="">— No shift —</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}"
                                {{ $employee->shift_id == $shift->id ? 'selected' : '' }}>
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
                           value="{{ $employee->joined_date?->toDateString() }}">
                </div>
            </div>

            <div class="em-field">
                <label class="em-label">Photo (leave blank to keep current)</label>
                @if($employee->photo)
                    <img src="{{ asset('storage/'.$employee->photo) }}"
                         style="width:48px;height:48px;border-radius:10px;object-fit:cover;margin-bottom:.4rem;display:block">
                @endif
                <input type="file" name="photo" class="em-input"
                       accept="image/jpeg,image/png,image/webp">
            </div>

            <div style="display:flex;gap:.65rem;margin-top:1.2rem">
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

{{-- ── Override Modal ── --}}
<div class="em-overlay" id="overrideModal">
    <div class="em-modal" style="width:400px">
        <h3><i class="fas fa-pen"></i> Override Attendance Record</h3>
        <form id="overrideForm" method="POST">
            @csrf @method('PUT')
            <div class="em-field">
                <label class="em-label">Status</label>
                <select name="status" id="ov_status" class="em-select">
                    <option value="present">Present</option>
                    <option value="late">Late</option>
                    <option value="early_out">Early Out</option>
                    <option value="absent">Absent</option>
                    <option value="half_day">Half Day</option>
                </select>
            </div>
            <div class="em-field">
                <label class="em-label">Admin Note</label>
                <textarea name="note" id="ov_note" class="em-input"
                          rows="3" placeholder="Reason for override…"
                          style="resize:vertical"></textarea>
            </div>
            <div style="display:flex;gap:.65rem;margin-top:1rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Save Override
                </button>
                <button type="button" class="btn btn-outline" onclick="closeOverride()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* Edit modal */
function openEditModal() {
    document.getElementById('editModal').classList.add('show');
}
function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
}

/* Override modal */
function openOverride(id, status, note) {
    document.getElementById('overrideForm').action = `/admin/attendance/${id}/override`;
    document.getElementById('ov_status').value = status;
    document.getElementById('ov_note').value   = note || '';
    document.getElementById('overrideModal').classList.add('show');
}
function closeOverride() {
    document.getElementById('overrideModal').classList.remove('show');
}

/* Close on backdrop click */
['editModal','overrideModal'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});
</script>
@endpush