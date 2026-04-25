@extends('layouts.admin')
@section('title', 'Customer Logs')

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-users-viewfinder" style="color:var(--pink)"></i> Customer Logs
        </div>
        <div class="page-sub">Login, logout and order activity for customers</div>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(5,1fr);margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-list"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Events</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-calendar-day"></i></div>
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

@include('admin.logs._filters', ['clearRoute' => route('admin.logs.customers'), 'actions' => ['login','logout','order_placed','order_cancelled']])

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users-viewfinder"></i> Customer Activity</h3>
        <span style="font-size:.78rem;color:var(--muted)">{{ $logs->total() }} records</span>
    </div>
    <div class="table-wrap">
        @include('admin.logs._table')
    </div>
    <div class="pagination-wrap">{{ $logs->withQueryString()->links() }}</div>
</div>
@endsection