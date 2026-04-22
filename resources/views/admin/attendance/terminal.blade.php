@extends('layouts.admin')

@section('title', 'Attendance Terminal')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   ATTENDANCE — CLOCK TERMINAL
   ═══════════════════════════════════════════════════════════ */

.terminal-wrap {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 960px) { .terminal-wrap { grid-template-columns: 1fr; } }

/* ── Who's In panel ── */
.whosin-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.whosin-header {
    padding: .9rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
}
.whosin-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.whosin-header h3 i { color: var(--green); }

.whosin-grid {
    padding: 1rem;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: .75rem;
}
.whosin-item {
    background: var(--green-soft);
    border: 1.5px solid #bbf7d0;
    border-radius: var(--r-sm);
    padding: .85rem .75rem;
    text-align: center;
    transition: all .18s;
}
.whosin-item:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(45,198,83,.15); }
.whosin-avatar {
    width: 44px; height: 44px; border-radius: 12px;
    background: linear-gradient(135deg, var(--green), var(--green-lt));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .95rem; font-weight: 700;
    margin: 0 auto .5rem;
    box-shadow: 0 3px 10px rgba(45,198,83,.3);
}
.whosin-name {
    font-size: .78rem; font-weight: 600; color: var(--text);
    margin-bottom: .2rem; line-height: 1.3;
}
.whosin-time {
    font-size: .68rem; color: var(--green); font-weight: 600;
}
.whosin-role {
    font-size: .65rem; color: var(--muted); margin-top: .15rem;
}

.whosin-empty {
    padding: 2.5rem 1rem; text-align: center; color: var(--muted);
}
.whosin-empty i {
    font-size: 2rem; opacity: .15; color: var(--green);
    display: block; margin-bottom: .5rem;
}

/* ── PIN Terminal ── */
.pin-terminal {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    position: sticky;
    top: 80px;
}
.pin-terminal-header {
    padding: 1.25rem;
    background: linear-gradient(135deg, #5B21B6 0%, #7C3AED 55%, #F72585 100%);
    color: #fff; text-align: center;
}
.pin-clock {
    font-size: 2.2rem; font-weight: 800;
    font-variant-numeric: tabular-nums;
    letter-spacing: .02em; line-height: 1;
    margin-bottom: .2rem;
}
.pin-date {
    font-size: .78rem; opacity: .8;
}
.pin-terminal-body { padding: 1.25rem; }

/* Employee preview */
.emp-preview {
    display: none;
    background: var(--purple-soft);
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: .85rem 1rem;
    margin-bottom: 1rem;
    align-items: center;
    gap: .85rem;
}
.emp-preview.show { display: flex; }
.emp-preview-avatar {
    width: 44px; height: 44px; border-radius: 12px;
    background: linear-gradient(135deg, var(--purple), var(--pink));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .95rem; font-weight: 700;
    flex-shrink: 0; box-shadow: 0 3px 10px rgba(124,58,237,.3);
}
.emp-preview-name { font-weight: 700; font-size: .88rem; color: var(--text); }
.emp-preview-role { font-size: .72rem; color: var(--muted); margin-top: .1rem; }
.emp-preview-status {
    margin-left: auto; font-size: .72rem; font-weight: 700;
    padding: .2rem .6rem; border-radius: 20px;
}
.emp-preview-status.in  { background: var(--green-soft); color: var(--green); }
.emp-preview-status.out { background: var(--pink-soft);  color: var(--pink); }

/* PIN display */
.pin-display {
    background: #faf7ff;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: .85rem 1rem;
    text-align: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    letter-spacing: .5rem;
    font-weight: 700;
    color: var(--purple);
    min-height: 58px;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
}
.pin-display.error {
    border-color: var(--tango);
    background: var(--pink-soft);
    color: var(--tango);
    animation: shake .3s ease;
}
.pin-display.success {
    border-color: var(--green);
    background: var(--green-soft);
    color: var(--green);
}
@keyframes shake {
    0%,100% { transform: translateX(0); }
    25%      { transform: translateX(-6px); }
    75%      { transform: translateX(6px); }
}

/* PIN pad */
.pin-pad {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .5rem;
    margin-bottom: .75rem;
}
.pin-key {
    aspect-ratio: 1;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    background: #fff;
    font-size: 1.15rem;
    font-weight: 700;
    font-family: inherit;
    color: var(--text);
    cursor: pointer;
    transition: all .15s;
    display: flex; align-items: center; justify-content: center;
}
.pin-key:hover {
    background: var(--purple-soft);
    border-color: var(--purple);
    color: var(--purple);
    transform: scale(1.04);
}
.pin-key:active { transform: scale(.96); }
.pin-key.del {
    background: var(--pink-soft);
    border-color: #fecdd3;
    color: var(--tango);
    font-size: .9rem;
}
.pin-key.del:hover {
    background: var(--tango);
    border-color: var(--tango);
    color: #fff;
}
.pin-key.zero {
    grid-column: 2;
}

/* Action buttons */
.pin-actions { display: grid; grid-template-columns: 1fr 1fr; gap: .65rem; }
.pin-action-btn {
    padding: .85rem .5rem;
    border: none; border-radius: var(--r-sm);
    font-size: .85rem; font-weight: 700;
    font-family: inherit; cursor: pointer;
    transition: all .18s;
    display: flex; align-items: center;
    justify-content: center; gap: .45rem;
}
.pin-action-btn.clock-in {
    background: linear-gradient(135deg, var(--green), var(--green-lt));
    color: #fff; box-shadow: 0 4px 14px rgba(45,198,83,.3);
}
.pin-action-btn.clock-in:hover {
    box-shadow: 0 6px 20px rgba(45,198,83,.42);
    transform: translateY(-1px);
}
.pin-action-btn.clock-out {
    background: linear-gradient(135deg, var(--tango), var(--pink));
    color: #fff; box-shadow: 0 4px 14px rgba(247,37,133,.3);
}
.pin-action-btn.clock-out:hover {
    box-shadow: 0 6px 20px rgba(247,37,133,.42);
    transform: translateY(-1px);
}
.pin-action-btn:disabled {
    opacity: .4; cursor: not-allowed;
    transform: none; box-shadow: none;
}

/* Success toast */
.att-toast {
    display: none;
    position: fixed; bottom: 2rem; right: 2rem;
    background: #fff; border-radius: 14px;
    padding: 1rem 1.25rem;
    box-shadow: 0 8px 32px rgba(124,58,237,.2);
    border: 1.5px solid var(--border);
    z-index: 9999; min-width: 280px;
    animation: toastIn .25s ease;
    align-items: center; gap: .85rem;
}
.att-toast.show { display: flex; }
@keyframes toastIn {
    from { opacity:0; transform: translateY(16px) scale(.96); }
    to   { opacity:1; transform: translateY(0) scale(1); }
}
.att-toast-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.att-toast-icon.in  { background: var(--green-soft); color: var(--green); }
.att-toast-icon.out { background: var(--pink-soft);  color: var(--tango); }
.att-toast-title { font-weight: 700; font-size: .88rem; color: var(--text); }
.att-toast-sub   { font-size: .74rem; color: var(--muted); margin-top: .1rem; }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-user-clock" style="color:var(--purple)"></i>
            Attendance Terminal
        </h1>
        <p class="page-sub">Staff clock in and clock out using their PIN</p>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline">
            <i class="fas fa-list"></i> Records
        </a>
        <a href="{{ route('admin.attendance.report') }}" class="btn btn-primary">
            <i class="fas fa-chart-bar"></i> Report
        </a>
    </div>
</div>

<div class="terminal-wrap">

    {{-- ════ LEFT — Who's In ════ --}}
    <div>
        <div class="whosin-card">
            <div class="whosin-header">
                <h3>
                    <i class="fas fa-circle-check"></i>
                    Currently In
                </h3>
                <span style="font-size:.78rem;color:var(--muted);font-weight:600">
                    {{ $currentlyIn->count() }} of {{ $totalEmployees }} staff
                </span>
            </div>

            @if($currentlyIn->isEmpty())
                <div class="whosin-empty">
                    <i class="fas fa-users"></i>
                    <p style="font-size:.83rem">No staff clocked in yet today</p>
                </div>
            @else
                <div class="whosin-grid">
                    @foreach($currentlyIn as $att)
                    <div class="whosin-item">
                        <div class="whosin-avatar">
                            {{ $att->employee->initials }}
                        </div>
                        <div class="whosin-name">{{ $att->employee->name }}</div>
                        <div class="whosin-time">
                            <i class="fas fa-clock"></i>
                            {{ $att->clock_in->format('H:i') }}
                        </div>
                        <div class="whosin-role">{{ $att->employee->role_label }}</div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Today summary stats --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-top:1rem">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Present</span>
                    <span class="stat-value">{{ $todayStats['present'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Late</span>
                    <span class="stat-value">{{ $todayStats['late'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon tango"><i class="fas fa-circle-xmark"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Absent</span>
                    <span class="stat-value">{{ $todayStats['absent'] }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Total</span>
                    <span class="stat-value">{{ $totalEmployees }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ════ RIGHT — PIN Terminal ════ --}}
    <div>
        <div class="pin-terminal">

            {{-- Header with clock --}}
            <div class="pin-terminal-header">
                <div class="pin-clock" id="termClock">--:--:--</div>
                <div class="pin-date" id="termDate"></div>
            </div>

            <div class="pin-terminal-body">

                {{-- Employee preview --}}
                <div class="emp-preview" id="empPreview">
                    <div class="emp-preview-avatar" id="empAvatar">--</div>
                    <div>
                        <div class="emp-preview-name" id="empName">—</div>
                        <div class="emp-preview-role" id="empRole">—</div>
                    </div>
                    <span class="emp-preview-status" id="empStatus">—</span>
                </div>

                {{-- PIN display --}}
                <div class="pin-display" id="pinDisplay">
                    <span id="pinDots">_ _ _ _</span>
                </div>

                {{-- Number pad --}}
                <div class="pin-pad">
                    @foreach([1,2,3,4,5,6,7,8,9] as $n)
                        <button type="button" class="pin-key" onclick="pressKey('{{ $n }}')">
                            {{ $n }}
                        </button>
                    @endforeach
                    <button type="button" class="pin-key del" onclick="deleteLast()">
                        <i class="fas fa-delete-left"></i>
                    </button>
                    <button type="button" class="pin-key zero" onclick="pressKey('0')">0</button>
                    <button type="button" class="pin-key del" onclick="clearPin()">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>

                {{-- Clock In / Clock Out --}}
                <div class="pin-actions">
                    <button type="button"
                            class="pin-action-btn clock-in"
                            id="btnClockIn"
                            onclick="submitAction('in')"
                            disabled>
                        <i class="fas fa-right-to-bracket"></i> Clock In
                    </button>
                    <button type="button"
                            class="pin-action-btn clock-out"
                            id="btnClockOut"
                            onclick="submitAction('out')"
                            disabled>
                        <i class="fas fa-right-from-bracket"></i> Clock Out
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- Toast notification --}}
<div class="att-toast" id="attToast">
    <div class="att-toast-icon" id="toastIcon">
        <i class="fas fa-circle-check"></i>
    </div>
    <div>
        <div class="att-toast-title" id="toastTitle">Clocked In</div>
        <div class="att-toast-sub"   id="toastSub">Welcome!</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
const ROUTES = {
    lookup  : '{{ route("admin.attendance.pin.lookup") }}',
    clockIn : '{{ route("admin.attendance.clock-in") }}',
    clockOut: '{{ route("admin.attendance.clock-out") }}',
};

let pin        = '';
let currentEmp = null;

/* ── Clock ── */
function updateClock() {
    const now = new Date();
    document.getElementById('termClock').textContent =
        now.toLocaleTimeString('en-KE', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    document.getElementById('termDate').textContent =
        now.toLocaleDateString('en-KE', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
}
updateClock();
setInterval(updateClock, 1000);

/* ── PIN input ── */
function pressKey(key) {
    if (pin.length >= 4) return;
    pin += key;
    updatePinDisplay();
    if (pin.length === 4) lookupEmployee();
}

function deleteLast() {
    pin = pin.slice(0, -1);
    updatePinDisplay();
    resetEmployee();
}

function clearPin() {
    pin = '';
    updatePinDisplay();
    resetEmployee();
}

function updatePinDisplay() {
    let display = '';
    for (let i = 0; i < 4; i++) {
        display += i < pin.length ? '●' : '_';
        if (i < 3) display += ' ';
    }
    document.getElementById('pinDots').textContent = display;
}

/* ── Lookup employee by PIN ── */
function lookupEmployee() {
    fetch(ROUTES.lookup + '?pin=' + pin, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.found) {
            currentEmp = data;
            showEmployee(data);
        } else {
            showError();
        }
    })
    .catch(() => showError());
}

function showEmployee(data) {
    document.getElementById('empAvatar').textContent = data.initials;
    document.getElementById('empName').textContent   = data.name;
    document.getElementById('empRole').textContent   = data.role;

    const statusEl = document.getElementById('empStatus');
    if (data.is_clocked_in) {
        statusEl.textContent = '● Clocked In';
        statusEl.className   = 'emp-preview-status in';
        document.getElementById('btnClockIn').disabled  = true;
        document.getElementById('btnClockOut').disabled = false;
    } else {
        statusEl.textContent = '○ Not In';
        statusEl.className   = 'emp-preview-status out';
        document.getElementById('btnClockIn').disabled  = false;
        document.getElementById('btnClockOut').disabled = true;
    }

    document.getElementById('empPreview').classList.add('show');
    document.getElementById('pinDisplay').classList.remove('error');
    document.getElementById('pinDisplay').classList.add('success');
}

function showError() {
    currentEmp = null;
    document.getElementById('pinDisplay').classList.add('error');
    document.getElementById('pinDisplay').classList.remove('success');
    document.getElementById('empPreview').classList.remove('show');
    document.getElementById('btnClockIn').disabled  = true;
    document.getElementById('btnClockOut').disabled = true;
    setTimeout(() => {
        clearPin();
        document.getElementById('pinDisplay').classList.remove('error');
    }, 1200);
}

function resetEmployee() {
    currentEmp = null;
    document.getElementById('empPreview').classList.remove('show');
    document.getElementById('pinDisplay').classList.remove('error', 'success');
    document.getElementById('btnClockIn').disabled  = true;
    document.getElementById('btnClockOut').disabled = true;
}

/* ── Submit clock in/out ── */
function submitAction(action) {
    if (!currentEmp || pin.length !== 4) return;

    const url = action === 'in' ? ROUTES.clockIn : ROUTES.clockOut;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type'    : 'application/json',
            'X-CSRF-TOKEN'    : CSRF,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ pin }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(action, data.name, data.time);
            clearPin();
            setTimeout(() => location.reload(), 2000);
        } else {
            showError();
        }
    })
    .catch(() => showError());
}

/* ── Toast ── */
function showToast(action, name, time) {
    const toast = document.getElementById('attToast');
    const icon  = document.getElementById('toastIcon');

    icon.className = 'att-toast-icon ' + action;
    icon.innerHTML = action === 'in'
        ? '<i class="fas fa-right-to-bracket"></i>'
        : '<i class="fas fa-right-from-bracket"></i>';

    document.getElementById('toastTitle').textContent =
        action === 'in' ? '✅ Clocked In' : '👋 Clocked Out';
    document.getElementById('toastSub').textContent = name + ' · ' + time;

    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

/* ── Keyboard support ── */
document.addEventListener('keydown', function(e) {
    if (e.key >= '0' && e.key <= '9') pressKey(e.key);
    if (e.key === 'Backspace') deleteLast();
    if (e.key === 'Escape') clearPin();
    if (e.key === 'Enter' && currentEmp) {
        submitAction(currentEmp.is_clocked_in ? 'out' : 'in');
    }
});
</script>
@endpush