@extends('layouts.admin')

@section('title', "Today's Attendance")

@push('styles')
<style>
.today-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 1000px) { .today-grid { grid-template-columns: 1fr; } }

.stats-today {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 1.25rem;
}
@media (max-width: 900px)  { .stats-today { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 500px)  { .stats-today { grid-template-columns: repeat(2,1fr); } }

/* Board card */
.board-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.board-header {
    padding: 1rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
}
.board-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .98rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.board-header h3 i { color: var(--purple); }

.board-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.board-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.board-table thead th {
    padding: .7rem 1rem; text-align: left;
    font-size: .7rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .07em;
}
.board-table tbody tr { border-bottom: 1px solid #f3eeff; transition: background .13s; }
.board-table tbody tr:last-child { border-bottom: none; }
.board-table tbody tr:hover { background: #faf7ff; }
.board-table td { padding: .8rem 1rem; vertical-align: middle; }

.emp-cell { display: flex; align-items: center; gap: .65rem; }
.emp-av {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--purple), var(--pink));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .78rem; font-weight: 700; flex-shrink: 0;
}
.emp-nm { font-weight: 600; font-size: .84rem; }
.emp-rl { font-size: .7rem; color: var(--muted); }

/* Live dot */
.live-dot {
    display: inline-block; width: 7px; height: 7px;
    border-radius: 50%; background: var(--green);
    box-shadow: 0 0 0 2px rgba(45,198,83,.25);
    animation: blink 2s infinite; margin-right: .3rem;
}
@keyframes blink {
    0%,100% { box-shadow: 0 0 0 2px rgba(45,198,83,.25); }
    50%      { box-shadow: 0 0 0 5px rgba(45,198,83,.06); }
}

/* PIN Terminal */
.terminal-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.terminal-header {
    background: linear-gradient(135deg, var(--purple), var(--pink));
    padding: 1.25rem;
    text-align: center;
    color: #fff;
}
.terminal-clock {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem; font-weight: 700;
    letter-spacing: .05em; line-height: 1;
    margin-bottom: .2rem;
}
.terminal-date {
    font-size: .78rem; opacity: .8;
}
.terminal-body { padding: 1.25rem; }
.t-field { display: flex; flex-direction: column; gap: .35rem; margin-bottom: .85rem; }
.t-label {
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.t-input, .t-select {
    padding: .6rem .9rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .86rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.t-input:focus, .t-select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.t-pin { text-align: center; font-size: 1.4rem; letter-spacing: .4em; }

/* Manual modal */
.em-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,1,20,.55); backdrop-filter: blur(4px);
    z-index: 500; align-items: center; justify-content: center;
}
.em-overlay.show { display: flex; }
.em-modal {
    background: #fff; border-radius: 20px;
    padding: 1.75rem; width: 460px; max-width: 95vw;
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 20px 60px rgba(124,58,237,.2);
    animation: modalIn .2s ease;
}
@keyframes modalIn { from{opacity:0;transform:scale(.94);}to{opacity:1;transform:scale(1);} }
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

<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-calendar-day" style="color:var(--purple)"></i>
            Today's Attendance
        </h1>
        <p class="page-sub">{{ now()->format('l, d F Y') }} &nbsp;·&nbsp; Live board</p>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-list"></i> All Records
        </a>
        <a href="{{ route('admin.attendance.report') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-chart-bar"></i> Report
        </a>
        <button type="button" class="btn btn-primary btn-sm" onclick="openManual()">
            <i class="fas fa-plus"></i> Manual Entry
        </button>
    </div>
</div>

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

{{-- Stats --}}
<div class="stats-today">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Staff</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div><div class="stat-value">{{ $stats['present'] }}</div><div class="stat-label">Present</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value">{{ $stats['late'] }}</div><div class="stat-label">Late</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-circle-xmark"></i></div>
        <div><div class="stat-value">{{ $stats['absent'] }}</div><div class="stat-label">Absent</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-arrow-right-from-bracket"></i></div>
        <div><div class="stat-value">{{ $stats['clocked_out'] }}</div><div class="stat-label">Clocked Out</div></div>
    </div>
</div>

<div class="today-grid">

    {{-- Board --}}
    <div class="board-card">
        <div class="board-header">
            <h3><i class="fas fa-table-list"></i> Live Board</h3>
            <span style="font-size:.75rem;color:var(--muted)">
                Auto-refreshes every 60s
            </span>
        </div>
        <div style="overflow-x:auto">
            <table class="board-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Shift</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                    @php $att = $emp->todayAttendance; @endphp
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-av">{{ strtoupper(substr($emp->name,0,2)) }}</div>
                                <div>
                                    <div class="emp-nm">{{ $emp->name }}</div>
                                    <div class="emp-rl">{{ $emp->role }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.78rem;color:var(--muted)">
                            {{ $emp->shift?->name ?? '—' }}
                        </td>
                        <td>
                            @if($att?->clock_in)
                                <span style="font-weight:600;color:var(--green)">
                                    {{ $att->clock_in->format('H:i') }}
                                </span>
                            @else
                                <span style="color:var(--muted)">—</span>
                            @endif
                        </td>
                        <td>
                            @if($att?->clock_out)
                                <span style="font-weight:600">{{ $att->clock_out->format('H:i') }}</span>
                            @elseif($att?->clock_in)
                                <span style="font-size:.75rem;font-weight:700;color:var(--green)">
                                    <span class="live-dot"></span>Live
                                </span>
                            @else
                                <span style="color:var(--muted)">—</span>
                            @endif
                        </td>
                        <td>
                            @if($att)
                                <span class="badge badge-{{ $att->status_badge }}">
                                    {{ $att->status_label }}
                                </span>
                            @else
                                <span class="badge badge-danger">Absent</span>
                            @endif
                        </td>
                        <td>
                            @if(!$att || !$att->clock_in)
                                <form method="POST" action="{{ route('admin.attendance.clock-in') }}" style="margin:0">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-sign-in-alt"></i> In
                                    </button>
                                </form>
                            @elseif($att->clock_in && !$att->clock_out)
                                <form method="POST" action="{{ route('admin.attendance.clock-out') }}" style="margin:0">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                    <button type="submit" class="btn btn-outline btn-sm">
                                        <i class="fas fa-sign-out-alt"></i> Out
                                    </button>
                                </form>
                            @else
                                <span style="font-size:.75rem;color:var(--muted)">Done</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- PIN Terminal --}}
    <div>
        <div class="terminal-card">
            <div class="terminal-header">
                <div class="terminal-clock" id="terminalClock">--:--:--</div>
                <div class="terminal-date">{{ now()->format('l, d F Y') }}</div>
            </div>
            <div class="terminal-body">
                <p style="font-size:.8rem;color:var(--muted);text-align:center;margin-bottom:1rem">
                    Select employee and enter PIN to clock in/out
                </p>

                <form method="POST" action="{{ route('admin.attendance.clock-in') }}">
                    @csrf
                    <div class="t-field">
                        <label class="t-label">Employee</label>
                        <select name="employee_id" class="t-select" required>
                            <option value="">— Select —</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="t-field">
                        <label class="t-label">PIN</label>
                        <input type="password" name="pin" class="t-input t-pin"
                               placeholder="• • • •" maxlength="10" autocomplete="off" required>
                    </div>
                    <button type="submit" class="btn btn-success" style="width:100%;margin-bottom:.5rem">
                        <i class="fas fa-sign-in-alt"></i> Clock In
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.attendance.clock-out') }}">
                    @csrf
                    <div class="t-field">
                        <select name="employee_id" class="t-select" required>
                            <option value="">— Select employee —</option>
                            @foreach($employees->filter(fn($e) => $e->isCurrentlyClockedIn()) as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline" style="width:100%">
                        <i class="fas fa-sign-out-alt"></i> Clock Out
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Manual Entry Modal --}}
<div class="em-overlay" id="manualModal">
    <div class="em-modal">
        <h3><i class="fas fa-plus"></i> Manual Attendance Entry</h3>
        <form method="POST" action="{{ route('admin.attendance.manual') }}">
            @csrf
            <div class="em-field">
                <label class="em-label">Employee <span style="color:var(--pink)">*</span></label>
                <select name="employee_id" class="em-select" required>
                    <option value="">— Select employee —</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Date <span style="color:var(--pink)">*</span></label>
                    <input type="date" name="date" class="em-input"
                           value="{{ today()->toDateString() }}" required>
                </div>
                <div class="em-field">
                    <label class="em-label">Status <span style="color:var(--pink)">*</span></label>
                    <select name="status" class="em-select" required>
                        <option value="present">Present</option>
                        <option value="late">Late</option>
                        <option value="early_out">Early Out</option>
                        <option value="absent">Absent</option>
                        <option value="half_day">Half Day</option>
                    </select>
                </div>
            </div>
            <div class="em-grid-2">
                <div class="em-field">
                    <label class="em-label">Clock In</label>
                    <input type="time" name="clock_in" class="em-input">
                </div>
                <div class="em-field">
                    <label class="em-label">Clock Out</label>
                    <input type="time" name="clock_out" class="em-input">
                </div>
            </div>
            <div class="em-field">
                <label class="em-label">Note</label>
                <input type="text" name="note" class="em-input" placeholder="Optional note…">
            </div>
            <div style="display:flex;gap:.65rem;margin-top:1.2rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Save Entry
                </button>
                <button type="button" class="btn btn-outline" onclick="closeManual()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function updateClock() {
    const n = new Date();
    const h = String(n.getHours()).padStart(2,'0');
    const m = String(n.getMinutes()).padStart(2,'0');
    const s = String(n.getSeconds()).padStart(2,'0');
    document.getElementById('terminalClock').textContent = `${h}:${m}:${s}`;
}
setInterval(updateClock, 1000);
updateClock();

// Auto-refresh board every 60s
setTimeout(() => location.reload(), 60000);

function openManual()  { document.getElementById('manualModal').classList.add('show'); }
function closeManual() { document.getElementById('manualModal').classList.remove('show'); }
document.getElementById('manualModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('show');
});
</script>
@endpush
