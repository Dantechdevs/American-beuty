@extends('layouts.admin')
@section('title', 'Manager Logs')

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-user-tie" style="color:var(--gold)"></i> Manager Logs
        </div>
        <div class="page-sub">Login and action history for managers</div>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(5,1fr);margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-list"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Events</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-calendar-day"></i></div>
        <div><div class="stat-value">{{ $stats['today'] }}</div><div class="stat-label">Today</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-right-to-bracket"></i></div>
        <div><div class="stat-value">{{ $stats['logins'] }}</div><div class="stat-label">Logins</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon muted"><i class="fas fa-right-from-bracket"></i></div>
        <div><div class="stat-value">{{ $stats['logouts'] }}</div><div class="stat-label">Logouts</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-bolt"></i></div>
        <div><div class="stat-value">{{ $stats['actions'] }}</div><div class="stat-label">Actions</div></div>
    </div>
</div>

@include('admin.logs._filters', ['clearRoute' => route('admin.logs.managers'), 'actions' => ['login','logout','status_changed','override']])

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-tie"></i> Manager Activity</h3>
        <span style="font-size:.78rem;color:var(--muted)">{{ $logs->total() }} records</span>
    </div>
    <div class="table-wrap">
        @include('admin.logs._table')
    </div>
    <div class="pagination-wrap">{{ $logs->withQueryString()->links() }}</div>
</div>
@endsection