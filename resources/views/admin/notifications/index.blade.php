@extends('layouts.admin')

@section('title', 'Push Notifications')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   NOTIFICATIONS PAGE
   ═══════════════════════════════════════════════════════════ */
.notif-tabs {
    display: flex; gap: 0;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    margin-bottom: 1.5rem;
    background: #fff;
    width: fit-content;
}
.notif-tab {
    padding: .6rem 1.4rem;
    font-size: .82rem; font-weight: 700;
    cursor: pointer; border: none;
    background: transparent; color: var(--muted);
    text-decoration: none; display: flex; align-items: center; gap: .4rem;
    transition: all .15s;
}
.notif-tab.active,
.notif-tab:hover {
    background: var(--purple); color: #fff;
}

.notif-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.notif-card-header {
    padding: .9rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; gap: .5rem;
}
.notif-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.notif-card-header h3 i { color: var(--purple); }
.notif-card-body { padding: 1.25rem; }

/* Form */
.nf-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
@media (max-width: 700px) { .nf-grid { grid-template-columns: 1fr; } }

.nf-group {
    display: flex; flex-direction: column; gap: .35rem;
}
.nf-group label {
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .06em;
}
.nf-group input,
.nf-group select,
.nf-group textarea {
    padding: .6rem .85rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .84rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.nf-group input:focus,
.nf-group select:focus,
.nf-group textarea:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.nf-group textarea { resize: vertical; min-height: 90px; }
.nf-span { grid-column: 1 / -1; }

/* Audience pill preview */
.audience-info {
    font-size: .75rem; color: var(--muted);
    margin-top: .25rem;
}

/* History table */
.nh-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
.nh-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.nh-table thead th {
    padding: .65rem 1rem; text-align: left;
    font-size: .69rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .07em;
}
.nh-table tbody tr { border-bottom: 1px solid #f3eeff; transition: background .13s; }
.nh-table tbody tr:last-child { border-bottom: none; }
.nh-table tbody tr:hover { background: #faf7ff; }
.nh-table td { padding: .75rem 1rem; vertical-align: middle; }

/* Status badge */
.sched-badge {
    display: inline-block; padding: .2rem .65rem;
    border-radius: 12px; font-size: .7rem; font-weight: 700;
    text-transform: capitalize;
}
.sched-pending   { background: #fef9c3; color: #ca8a04; }
.sched-sent      { background: #dcfce7; color: #16a34a; }
.sched-failed    { background: #fee2e2; color: #dc2626; }
.sched-cancelled { background: #f3f4f6; color: #6b7280; }

/* SMS toggle */
.sms-toggle { display: flex; align-items: center; gap: .5rem; font-size: .84rem; }
.sms-toggle input[type=checkbox] { width: 16px; height: 16px; accent-color: var(--purple); }
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-bell" style="color:var(--purple)"></i> Push Notifications
        </h1>
        <p class="page-sub">Send in-app notifications to users</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

{{-- Tabs --}}
<div class="notif-tabs">
    <a href="?tab=send"     class="notif-tab {{ $tab === 'send'     ? 'active' : '' }}">
        <i class="fas fa-paper-plane"></i> Send Now
    </a>
    <a href="?tab=schedule" class="notif-tab {{ $tab === 'schedule' ? 'active' : '' }}">
        <i class="fas fa-clock"></i> Schedule
    </a>
    <a href="?tab=history"  class="notif-tab {{ $tab === 'history'  ? 'active' : '' }}">
        <i class="fas fa-history"></i> History
    </a>
    <a href="?tab=scheduled_list" class="notif-tab {{ $tab === 'scheduled_list' ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i> Scheduled Queue
    </a>
</div>

{{-- ── Send Now ── --}}
@if($tab === 'send')
<div class="notif-card">
    <div class="notif-card-header">
        <h3><i class="fas fa-paper-plane"></i> Send Notification Now</h3>
    </div>
    <div class="notif-card-body">
        <form method="POST" action="{{ route('admin.notifications.send') }}">
            @csrf
            <div class="nf-grid">
                <div class="nf-group nf-span">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="e.g. Special offer just for you!" required value="{{ old('title') }}">
                </div>
                <div class="nf-group nf-span">
                    <label>Message</label>
                    <textarea name="body" placeholder="Write your notification message here..." required>{{ old('body') }}</textarea>
                </div>
                <div class="nf-group">
                    <label>Audience</label>
                    <select name="audience" id="audienceSelect" onchange="toggleSpecific(this.value)">
                        <option value="all">All Users</option>
                        <option value="customers">Customers Only</option>
                        <option value="admins">Admins Only</option>
                        <option value="managers">Managers Only</option>
                        <option value="pos_operators">POS Operators Only</option>
                        <option value="specific">Specific User</option>
                    </select>
                </div>
                <div class="nf-group" id="specificUserWrap" style="display:none">
                    <label>Select User</label>
                    <select name="specific_user_id">
                        <option value="">— Choose user —</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }}) — {{ $u->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="nf-group">
                    <label>Link URL (optional)</label>
                    <input type="text" name="url" placeholder="/orders/123" value="{{ old('url') }}">
                </div>
                <div class="nf-group" style="justify-content:flex-end;padding-bottom:.2rem">
                    <label>&nbsp;</label>
                    <div class="sms-toggle">
                        <input type="checkbox" name="send_sms" id="sendSms" value="1" disabled>
                        <label for="sendSms" style="text-transform:none;font-size:.83rem;font-weight:500;color:var(--muted)">
                            Also send SMS via Twilio
                            <span style="font-size:.7rem;background:#f3f4f6;padding:.1rem .4rem;border-radius:8px;margin-left:.3rem">Coming soon</span>
                        </label>
                    </div>
                </div>
            </div>
            <div style="margin-top:1.25rem">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Now
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ── Schedule ── --}}
@if($tab === 'schedule')
<div class="notif-card">
    <div class="notif-card-header">
        <h3><i class="fas fa-clock"></i> Schedule Notification</h3>
    </div>
    <div class="notif-card-body">
        <form method="POST" action="{{ route('admin.notifications.schedule') }}">
            @csrf
            <div class="nf-grid">
                <div class="nf-group nf-span">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="Notification title" required value="{{ old('title') }}">
                </div>
                <div class="nf-group nf-span">
                    <label>Message</label>
                    <textarea name="body" placeholder="Notification message..." required>{{ old('body') }}</textarea>
                </div>
                <div class="nf-group">
                    <label>Audience</label>
                    <select name="audience" id="audienceSelectSched" onchange="toggleSpecificSched(this.value)">
                        <option value="all">All Users</option>
                        <option value="customers">Customers Only</option>
                        <option value="admins">Admins Only</option>
                        <option value="managers">Managers Only</option>
                        <option value="pos_operators">POS Operators Only</option>
                        <option value="specific">Specific User</option>
                    </select>
                </div>
                <div class="nf-group" id="specificUserWrapSched" style="display:none">
                    <label>Select User</label>
                    <select name="specific_user_id">
                        <option value="">— Choose user —</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="nf-group">
                    <label>Schedule Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" required
                           min="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}">
                </div>
                <div class="nf-group">
                    <label>Link URL (optional)</label>
                    <input type="text" name="url" placeholder="/products">
                </div>
            </div>
            <div style="margin-top:1.25rem">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-clock"></i> Schedule Notification
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ── History ── --}}
@if($tab === 'history')
<div class="notif-card">
    <div class="notif-card-header">
        <h3><i class="fas fa-history"></i> Sent Notifications</h3>
        <span style="font-size:.75rem;color:var(--muted)">Manual sends only</span>
    </div>
    @if($sent->isNotEmpty())
        <div style="overflow-x:auto">
            <table class="nh-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Recipient</th>
                        <th>Type</th>
                        <th>Read</th>
                        <th>Sent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sent as $n)
                    <tr>
                        <td style="font-weight:600;max-width:220px">
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                <i class="{{ $n->icon }}" style="color:var(--purple);margin-right:.4rem;font-size:.8rem"></i>
                                {{ $n->title }}
                            </div>
                            <div style="font-size:.72rem;color:var(--muted);margin-top:.15rem">
                                {{ Str::limit($n->body, 60) }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:.82rem">{{ $n->user->name ?? '—' }}</div>
                            <div style="font-size:.72rem;color:var(--muted)">{{ $n->user->role ?? '' }}</div>
                        </td>
                        <td>
                            <span class="badge badge-purple" style="text-transform:capitalize">
                                {{ str_replace('_', ' ', $n->type) }}
                            </span>
                        </td>
                        <td>
                            @if($n->is_read)
                                <span style="color:var(--green)"><i class="fas fa-check-circle"></i> Read</span>
                            @else
                                <span style="color:var(--muted)"><i class="fas fa-circle" style="font-size:.5rem"></i> Unread</span>
                            @endif
                        </td>
                        <td style="font-size:.78rem;color:var(--muted);white-space:nowrap">
                            {{ $n->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1rem 1.25rem">
            {{ $sent->links() }}
        </div>
    @else
        <div style="padding:3rem;text-align:center;color:var(--muted)">
            <i class="fas fa-bell-slash" style="font-size:2rem;opacity:.15;display:block;margin-bottom:.5rem"></i>
            No notifications sent yet.
        </div>
    @endif
</div>
@endif

{{-- ── Scheduled Queue ── --}}
@if($tab === 'scheduled_list')
<div class="notif-card">
    <div class="notif-card-header">
        <h3><i class="fas fa-calendar-check"></i> Scheduled Queue</h3>
        <span style="font-size:.75rem;color:var(--muted)">
            Run <code>php artisan notifications:process</code> or add to scheduler
        </span>
    </div>
    @if($scheduled->isNotEmpty())
        <div style="overflow-x:auto">
            <table class="nh-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Audience</th>
                        <th>Scheduled</th>
                        <th>Status</th>
                        <th>Created by</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scheduled as $s)
                    <tr>
                        <td style="font-weight:600;max-width:200px">
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $s->title }}
                            </div>
                            <div style="font-size:.72rem;color:var(--muted)">
                                {{ Str::limit($s->body, 50) }}
                            </div>
                        </td>
                        <td style="font-size:.82rem">{{ $s->audienceLabel() }}</td>
                        <td style="font-size:.78rem;white-space:nowrap">
                            {{ $s->scheduled_at->format('d M Y H:i') }}
                        </td>
                        <td>
                            <span class="sched-badge sched-{{ $s->status }}">{{ $s->status }}</span>
                        </td>
                        <td style="font-size:.8rem;color:var(--muted)">{{ $s->creator->name ?? '—' }}</td>
                        <td style="white-space:nowrap">
                            @if($s->status === 'pending')
                                <form method="POST" action="{{ route('admin.notifications.scheduled.cancel', $s) }}"
                                      style="display:inline" onsubmit="return confirm('Cancel this notification?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-outline btn-sm">
                                        <i class="fas fa-xmark"></i> Cancel
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.notifications.scheduled.destroy', $s) }}"
                                  style="display:inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline btn-sm" style="color:var(--tango)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1rem 1.25rem">
            {{ $scheduled->links() }}
        </div>
    @else
        <div style="padding:3rem;text-align:center;color:var(--muted)">
            <i class="fas fa-calendar-xmark" style="font-size:2rem;opacity:.15;display:block;margin-bottom:.5rem"></i>
            No scheduled notifications.
        </div>
    @endif
</div>
@endif

@endsection

@push('scripts')
<script>
function toggleSpecific(val) {
    document.getElementById('specificUserWrap').style.display = val === 'specific' ? 'block' : 'none';
}
function toggleSpecificSched(val) {
    document.getElementById('specificUserWrapSched').style.display = val === 'specific' ? 'block' : 'none';
}
</script>
@endpush
