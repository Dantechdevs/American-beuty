@extends('layouts.admin')

@section('title', 'Attendance Report')

@push('styles')
<style>
.report-filters {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex; flex-wrap: wrap;
    gap: .75rem; align-items: flex-end;
}
.report-filters .fg {
    display: flex; flex-direction: column;
    gap: .3rem; flex: 1; min-width: 130px;
}
.report-filters label {
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.report-filters input,
.report-filters select {
    padding: .55rem .8rem;
    border: 1.5px solid var(--border); border-radius: var(--r-sm);
    font-size: .85rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.report-filters input:focus,
.report-filters select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}

.report-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 1.25rem;
}
@media (max-width: 900px) { .report-stats { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 500px) { .report-stats { grid-template-columns: repeat(2,1fr); } }

.report-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.report-card-header {
    padding: .95rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
}
.report-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .96rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.report-card-header h3 i { color: var(--purple); }

.report-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.report-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.report-table thead th {
    padding: .72rem 1rem; text-align: left;
    font-size: .7rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .07em;
    white-space: nowrap;
}
.report-table tbody tr { border-bottom: 1px solid #f3eeff; transition: background .13s; }
.report-table tbody tr:last-child { border-bottom: none; }
.report-table tbody tr:hover { background: #faf7ff; }
.report-table td { padding: .85rem 1rem; vertical-align: middle; }

.pct-bar {
    display: flex; align-items: center; gap: .5rem;
}
.pct-track {
    flex: 1; height: 7px; background: var(--border);
    border-radius: 10px; overflow: hidden;
}
.pct-fill { height: 100%; border-radius: 10px; transition: width .4s; }
.pct-fill.good  { background: linear-gradient(90deg, var(--green), var(--green-lt)); }
.pct-fill.warn  { background: linear-gradient(90deg, var(--gold), #fbbf24); }
.pct-fill.bad   { background: linear-gradient(90deg, var(--tango), var(--tango-lt)); }
.pct-label { font-size: .76rem; font-weight: 700; min-width: 36px; text-align: right; }
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-chart-bar" style="color:var(--purple)"></i>
            Attendance Report
        </h1>
        <p class="page-sub">
            {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
            –
            {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
        </p>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.attendance.today') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-calendar-day"></i> Today
        </a>
        <a href="{{ route('admin.attendance.export') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}"
           class="btn btn-outline btn-sm">
            <i class="fas fa-file-export"></i> Export CSV
        </a>
    </div>
</div>

{{-- Date filter --}}
<form method="GET" action="{{ route('admin.attendance.report') }}" class="report-filters">
    <div class="fg" style="max-width:160px">
        <label>From</label>
        <input type="date" name="date_from" value="{{ $dateFrom }}" required>
    </div>
    <div class="fg" style="max-width:160px">
        <label>To</label>
        <input type="date" name="date_to" value="{{ $dateTo }}" required>
    </div>
    <div class="fg" style="max-width:160px">
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
    <div style="display:flex;gap:.5rem;align-items:flex-end;padding-bottom:1px">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-chart-bar"></i> Generate
        </button>
    </div>
</form>

{{-- Summary stats --}}
<div class="report-stats">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div><div class="stat-value">{{ $summary['present'] }}</div><div class="stat-label">Total Present</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-circle-xmark"></i></div>
        <div><div class="stat-value">{{ $summary['absent'] }}</div><div class="stat-label">Total Absent</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value">{{ $summary['late'] }}</div><div class="stat-label">Total Late</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-hourglass-half"></i></div>
        <div><div class="stat-value">{{ number_format($summary['total_hours'],1) }}h</div><div class="stat-label">Total Hours</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-calendar-days"></i></div>
        <div><div class="stat-value">{{ $summary['days'] }}</div><div class="stat-label">Working Days</div></div>
    </div>
</div>

{{-- Per-employee breakdown --}}
<div class="report-card">
    <div class="report-card-header">
        <h3>
            <i class="fas fa-table"></i> Per-Employee Breakdown
        </h3>
        <span style="font-size:.78rem;color:var(--muted)">
            {{ $summary['days'] }} day period
        </span>
    </div>
    <div style="overflow-x:auto">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Role</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Early Out</th>
                    <th>Half Day</th>
                    <th>Hours</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report as $row)
                @php
                    $pct   = $row['percentage'];
                    $color = $pct >= 80 ? 'good' : ($pct >= 60 ? 'warn' : 'bad');
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.attendance.show', $row['employee']->id) }}"
                           style="font-weight:600;color:var(--purple);text-decoration:none">
                            {{ $row['employee']->name }}
                        </a>
                    </td>
                    <td>
                        <span class="badge badge-purple" style="text-transform:capitalize">
                            {{ $row['employee']->role }}
                        </span>
                    </td>
                    <td><span style="color:var(--green);font-weight:700">{{ $row['present'] }}</span></td>
                    <td><span style="color:var(--tango);font-weight:700">{{ $row['absent'] }}</span></td>
                    <td><span style="color:var(--gold);font-weight:700">{{ $row['late'] }}</span></td>
                    <td>{{ $row['early_out'] }}</td>
                    <td>{{ $row['half_day'] }}</td>
                    <td><span style="font-weight:700">{{ number_format($row['hours'],1) }}h</span></td>
                    <td>
                        <div class="pct-bar">
                            <div class="pct-track">
                                <div class="pct-fill {{ $color }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="pct-label" style="color:var(--{{ $color === 'good' ? 'green' : ($color === 'warn' ? 'gold' : 'tango') }})">
                                {{ $pct }}%
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div style="padding:3rem;text-align:center;color:var(--muted)">
                            <i class="fas fa-chart-bar" style="font-size:2rem;display:block;margin-bottom:.6rem;opacity:.18"></i>
                            No data for selected period.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
