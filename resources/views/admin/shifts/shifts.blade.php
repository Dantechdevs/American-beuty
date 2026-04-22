@extends('layouts.admin')

@section('title', 'Shifts')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   SHIFTS — MANAGER
   ═══════════════════════════════════════════════════════════ */

.shifts-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 960px) { .shifts-grid { grid-template-columns: 1fr; } }

/* Shift cards */
.shift-cards { display: flex; flex-direction: column; gap: .85rem; }
.shift-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: box-shadow .18s, transform .18s;
}
.shift-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.shift-card.inactive { opacity: .6; }
.shift-card-body {
    padding: 1.1rem 1.25rem;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
}
.shift-icon {
    width: 50px; height: 50px; border-radius: 13px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--purple), var(--pink));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.1rem;
    box-shadow: 0 4px 14px rgba(124,58,237,.25);
}
.shift-icon.inactive { background: linear-gradient(135deg, #ccc, #aaa); box-shadow: none; }
.shift-info { flex: 1; min-width: 0; }
.shift-name {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: .25rem;
}
.shift-schedule {
    font-size: .82rem; color: var(--muted);
    display: flex; align-items: center; gap: .35rem; flex-wrap: wrap;
}
.shift-schedule i { font-size: .75rem; color: var(--purple); }
.shift-meta { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; margin-top: .5rem; }
.shift-pill {
    display: inline-flex; align-items: center; gap: .28rem;
    font-size: .7rem; font-weight: 600;
    padding: .2rem .6rem; border-radius: 20px; border: 1px solid transparent;
}
.shift-pill.duration { background: var(--purple-soft); color: var(--purple); border-color: #ddd6fe; }
.shift-pill.grace    { background: var(--gold-soft);   color: var(--gold);   border-color: #fde68a; }
.shift-pill.staff    { background: var(--green-soft);  color: #15803d;       border-color: #bbf7d0; }
.shift-pill.inactive { background: #f5f5f5; color: #888; border-color: #e5e5e5; }
.shift-actions { display: flex; gap: .4rem; align-items: center; flex-shrink: 0; }
.sh-btn {
    width: 32px; height: 32px; border-radius: 9px;
    border: 1.5px solid var(--border); background: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .78rem; color: var(--muted); transition: all .15s; text-decoration: none;
}
.sh-btn:hover        { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.sh-btn.danger:hover { border-color: var(--tango);  color: var(--tango);  background: var(--pink-soft); }

/* Empty state */
.shifts-empty {
    background: #fff; border: 1.5px dashed var(--border);
    border-radius: var(--r); padding: 3rem 1.5rem; text-align: center; color: var(--muted);
}
.shifts-empty i { font-size: 2.5rem; opacity: .15; color: var(--purple); display: block; margin-bottom: .75rem; }

/* Form card */
.form-card {
    background: #fff; border: 1.5px solid var(--border);
    border-radius: var(--r); box-shadow: var(--shadow);
    overflow: hidden; position: sticky; top: 80px;
}
.form-card-header {
    padding: .9rem 1.25rem; border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
}
.form-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.form-card-header h3 i { color: var(--purple); }
.form-card-body { padding: 1.25rem; }

.sf-field { display: flex; flex-direction: column; gap: .35rem; margin-bottom: .9rem; }
.sf-field:last-child { margin-bottom: 0; }
.sf-label {
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.sf-label span { color: var(--pink); margin-left: .1rem; }
.sf-input {
    padding: .6rem .9rem;
    border: 1.5px solid var(--border); border-radius: var(--r-sm);
    font-size: .86rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.sf-input:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.sf-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
.sf-toggle-wrap {
    display: flex; align-items: center; justify-content: space-between;
    padding: .7rem .9rem; border: 1.5px solid var(--border);
    border-radius: var(--r-sm); background: #faf7ff;
}
.sf-toggle-label { font-size: .85rem; font-weight: 500; color: var(--text); }
.sf-toggle {
    width: 40px; height: 22px; border-radius: 11px;
    background: var(--border); position: relative; cursor: pointer;
    transition: background .2s; flex-shrink: 0; border: none;
    appearance: none; -webkit-appearance: none;
}
.sf-toggle:checked { background: var(--purple); }
.sf-toggle::after {
    content: ''; position: absolute;
    width: 16px; height: 16px; border-radius: 50%;
    background: #fff; top: 3px; left: 3px;
    transition: transform .2s; box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.sf-toggle:checked::after { transform: translateX(18px); }

/* Edit banner */
.edit-banner {
    display: none; background: var(--purple-soft);
    border: 1.5px solid #ddd6fe; border-radius: var(--r-sm);
    padding: .65rem .9rem; margin-bottom: 1rem;
    font-size: .8rem; font-weight: 600; color: var(--purple);
    align-items: center; gap: .5rem; justify-content: space-between;
}
.edit-banner.show { display: flex; }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-clock" style="color:var(--purple)"></i> Shifts
        </h1>
        <p class="page-sub">Define work shifts, hours and late grace periods</p>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-id-badge"></i> Employees
        </a>
        <a href="{{ route('admin.attendance.terminal') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-user-clock"></i> Terminal
        </a>
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

<div class="shifts-grid">

    {{-- ════ LEFT: Shift list ════ --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.85rem">
            <span style="font-size:.82rem;color:var(--muted);font-weight:600">
                {{ $shifts->count() }} shift{{ $shifts->count() !== 1 ? 's' : '' }} defined
            </span>
            <span style="font-size:.78rem;color:var(--muted)">
                <i class="fas fa-circle" style="color:var(--green);font-size:.5rem"></i>
                {{ $shifts->where('is_active', true)->count() }} active
            </span>
        </div>

        @if($shifts->isEmpty())
            <div class="shifts-empty">
                <i class="fas fa-clock"></i>
                <p style="font-size:.88rem;margin:.5rem 0 1rem">No shifts defined yet.</p>
                <p style="font-size:.8rem">Use the form on the right to create your first shift.</p>
            </div>
        @else
            <div class="shift-cards">
                @foreach($shifts as $shift)
                <div class="shift-card {{ !$shift->is_active ? 'inactive' : '' }}">
                    <div class="shift-card-body">

                        <div class="shift-icon {{ !$shift->is_active ? 'inactive' : '' }}">
                            <i class="fas fa-clock"></i>
                        </div>

                        <div class="shift-info">
                            <div class="shift-name">{{ $shift->name }}</div>
                            <div class="shift-schedule">
                                <i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('g:i A') }}
                                <i class="fas fa-arrow-right" style="font-size:.6rem;color:var(--border)"></i>
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('g:i A') }}
                            </div>
                            <div class="shift-meta">
                                <span class="shift-pill duration">
                                    <i class="fas fa-hourglass-half"></i>
                                    {{ $shift->duration }}
                                </span>
                                <span class="shift-pill grace">
                                    <i class="fas fa-stopwatch"></i>
                                    {{ $shift->grace_minutes }}m grace
                                </span>
                                <span class="shift-pill staff">
                                    <i class="fas fa-users"></i>
                                    {{ $shift->employees_count }} staff
                                </span>
                                @if(!$shift->is_active)
                                    <span class="shift-pill inactive">
                                        <i class="fas fa-pause-circle"></i> Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="shift-actions">
                            <button type="button" class="sh-btn" title="Edit"
                                    onclick="loadEdit(
                                        {{ $shift->id }},
                                        '{{ addslashes($shift->name) }}',
                                        '{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}',
                                        '{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}',
                                        {{ $shift->grace_minutes }},
                                        {{ $shift->is_active ? 'true' : 'false' }}
                                    )">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                            <form method="POST"
                                  action="{{ route('admin.shifts.destroy', $shift->id) }}"
                                  onsubmit="return confirm('Delete shift \'{{ addslashes($shift->name) }}\'?')"
                                  style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="sh-btn danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ════ RIGHT: Create / Edit form ════ --}}
    <div>
        <div class="form-card">
            <div class="form-card-header">
                <h3>
                    <i class="fas fa-plus-circle"></i>
                    <span id="formTitleText">New Shift</span>
                </h3>
            </div>
            <div class="form-card-body">

                <div class="edit-banner" id="editBanner">
                    <span><i class="fas fa-pen-to-square"></i> Editing: <strong id="editingName"></strong></span>
                    <button type="button" onclick="resetForm()"
                            style="background:none;border:none;cursor:pointer;color:var(--purple);font-size:.8rem;font-weight:600">
                        <i class="fas fa-xmark"></i> Cancel
                    </button>
                </div>

                <form method="POST" id="shiftForm" action="{{ route('admin.shifts.store') }}">
                    @csrf
                    <span id="methodSpoof"></span>

                    <div class="sf-field">
                        <label class="sf-label">Shift Name <span>*</span></label>
                        <input type="text" name="name" id="sfName" class="sf-input"
                               placeholder="e.g. Morning Shift" required>
                    </div>

                    <div class="sf-grid-2">
                        <div class="sf-field">
                            <label class="sf-label">Start Time <span>*</span></label>
                            <input type="time" name="start_time" id="sfStart" class="sf-input" required>
                        </div>
                        <div class="sf-field">
                            <label class="sf-label">End Time <span>*</span></label>
                            <input type="time" name="end_time" id="sfEnd" class="sf-input" required>
                        </div>
                    </div>

                    <div class="sf-field">
                        <label class="sf-label">Grace Period (minutes)</label>
                        <input type="number" name="grace_minutes" id="sfGrace" class="sf-input"
                               value="15" min="0" max="120" placeholder="15">
                        <span style="font-size:.72rem;color:var(--muted)">
                            Staff arriving within this window are not marked late.
                        </span>
                    </div>

                    <div class="sf-field">
                        <div class="sf-toggle-wrap">
                            <span class="sf-toggle-label">Active</span>
                            <input type="checkbox" name="is_active" id="sfActive"
                                   class="sf-toggle" value="1" checked>
                        </div>
                    </div>

                    {{-- Live preview --}}
                    <div id="shiftPreview"
                         style="display:none;background:var(--purple-soft);border:1.5px solid #ddd6fe;border-radius:var(--r-sm);padding:.75rem 1rem;margin-bottom:1rem">
                        <div style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem">
                            Preview
                        </div>
                        <div style="font-size:.88rem;font-weight:600;color:var(--purple)" id="previewText">—</div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn"
                            style="width:100%;justify-content:center">
                        <i class="fas fa-floppy-disk" id="submitIcon"></i>
                        <span id="submitLabel">Create Shift</span>
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
const STORE_URL = '{{ route("admin.shifts.store") }}';

/* ── Live preview ── */
function updatePreview() {
    const start = document.getElementById('sfStart').value;
    const end   = document.getElementById('sfEnd').value;
    const grace = document.getElementById('sfGrace').value || 0;
    const prev  = document.getElementById('shiftPreview');
    const text  = document.getElementById('previewText');

    if (!start || !end) { prev.style.display = 'none'; return; }

    const fmt = t => {
        const [h, m] = t.split(':').map(Number);
        const ampm = h >= 12 ? 'PM' : 'AM';
        const h12  = h % 12 || 12;
        return `${h12}:${String(m).padStart(2,'0')} ${ampm}`;
    };

    const startMins = parseInt(start.split(':')[0]) * 60 + parseInt(start.split(':')[1]);
    const endMins   = parseInt(end.split(':')[0])   * 60 + parseInt(end.split(':')[1]);
    let diff = endMins - startMins;
    if (diff < 0) diff += 1440;
    const dh  = Math.floor(diff / 60);
    const dm  = diff % 60;
    const dur = dm > 0 ? `${dh}h ${dm}m` : `${dh}h`;

    text.textContent    = `${fmt(start)} – ${fmt(end)} · ${dur} · ${grace}m grace`;
    prev.style.display  = 'block';
}

['sfStart', 'sfEnd', 'sfGrace'].forEach(id => {
    document.getElementById(id).addEventListener('input', updatePreview);
});

/* ── Load shift into edit form ── */
function loadEdit(id, name, start, end, grace, isActive) {
    document.getElementById('sfName').value     = name;
    document.getElementById('sfStart').value    = start;
    document.getElementById('sfEnd').value      = end;
    document.getElementById('sfGrace').value    = grace;
    document.getElementById('sfActive').checked = isActive;

    document.getElementById('shiftForm').action     = '/admin/shifts/' + id;
    document.getElementById('methodSpoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';

    document.getElementById('editBanner').classList.add('show');
    document.getElementById('editingName').textContent    = name;
    document.getElementById('submitIcon').className       = 'fas fa-floppy-disk';
    document.getElementById('submitLabel').textContent    = 'Update Shift';
    document.getElementById('formTitleText').textContent  = 'Edit Shift';

    updatePreview();
    document.getElementById('sfName').focus();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ── Reset to create mode ── */
function resetForm() {
    document.getElementById('shiftForm').reset();
    document.getElementById('shiftForm').action          = STORE_URL;
    document.getElementById('methodSpoof').innerHTML     = '';
    document.getElementById('sfGrace').value             = '15';
    document.getElementById('sfActive').checked          = true;
    document.getElementById('editBanner').classList.remove('show');
    document.getElementById('submitLabel').textContent   = 'Create Shift';
    document.getElementById('formTitleText').textContent = 'New Shift';
    document.getElementById('shiftPreview').style.display = 'none';
}
</script>
@endpush