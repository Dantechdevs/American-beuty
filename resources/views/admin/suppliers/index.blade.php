@extends('layouts.admin')

@section('title', 'Suppliers')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   SUPPLIERS — INDEX
   ═══════════════════════════════════════════════════════════ */
.supplier-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 600px) { .supplier-stats { grid-template-columns: 1fr; } }

.sup-filters {
    background: #fff; border: 1.5px solid var(--border);
    border-radius: var(--r); padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex; flex-wrap: wrap; gap: .75rem; align-items: flex-end;
}
.sup-filters .filter-group {
    display: flex; flex-direction: column; gap: .3rem;
    flex: 1; min-width: 160px;
}
.sup-filters label {
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.sup-filters input,
.sup-filters select {
    padding: .55rem .8rem;
    border: 1.5px solid var(--border); border-radius: var(--r-sm);
    font-size: .85rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.sup-filters input:focus,
.sup-filters select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}

.sup-table-card {
    background: #fff; border: 1.5px solid var(--border);
    border-radius: var(--r); box-shadow: var(--shadow); overflow: hidden;
}
.sup-table-header {
    padding: 1rem 1.25rem; border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
}
.sup-table-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.sup-table-header h3 i { color: var(--purple); }

.sup-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.sup-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.sup-table thead th {
    padding: .75rem 1rem; text-align: left;
    font-size: .71rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .06em; white-space: nowrap;
}
.sup-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
.sup-table tbody tr:last-child { border-bottom: none; }
.sup-table tbody tr:hover { background: #faf7ff; }
.sup-table td { padding: .85rem 1rem; vertical-align: middle; }

.sup-avatar {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, var(--purple-soft), var(--pink-soft));
    display: flex; align-items: center; justify-content: center;
    font-size: .88rem; font-weight: 700; color: var(--purple); flex-shrink: 0;
}
.sup-name-cell { display: flex; align-items: center; gap: .65rem; }
.sup-name { font-weight: 600; color: var(--text); }
.sup-email { font-size: .72rem; color: var(--muted); }

.tbl-actions { display: flex; gap: .4rem; align-items: center; }
.tbl-btn {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1.5px solid var(--border); background: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .76rem; color: var(--muted); transition: all .15s; text-decoration: none;
}
.tbl-btn:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.tbl-btn.danger:hover { border-color: var(--tango); color: var(--tango); background: var(--pink-soft); }

.sup-empty {
    padding: 3.5rem 1rem; text-align: center; color: var(--muted);
}
.sup-empty i { font-size: 2.5rem; opacity: .15; color: var(--purple); display: block; margin-bottom: .75rem; }

.sup-pagination {
    padding: .85rem 1.25rem; border-top: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    font-size: .82rem; color: var(--muted); background: #faf7ff;
    flex-wrap: wrap; gap: .5rem;
}
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-building" style="color:var(--purple)"></i> Suppliers
        </h1>
        <p class="page-sub">Manage your product suppliers</p>
    </div>
    <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Supplier
    </a>
</div>

@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i><div>{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="flash flash-error" style="margin-bottom:1rem">
        <i class="fas fa-circle-exclamation"></i><div>{{ session('error') }}</div>
    </div>
@endif

{{-- Stats --}}
<div class="supplier-stats">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-building"></i></div>
        <div class="stat-info">
            <span class="stat-label">Total Suppliers</span>
            <span class="stat-value">{{ number_format($stats['total']) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <span class="stat-label">Active</span>
            <span class="stat-value">{{ number_format($stats['active']) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-circle-xmark"></i></div>
        <div class="stat-info">
            <span class="stat-label">Inactive</span>
            <span class="stat-value">{{ number_format($stats['inactive']) }}</span>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.supplier.index') }}" class="sup-filters">
    <div class="filter-group">
        <label>Search</label>
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Name, phone or email…">
    </div>
    <div class="filter-group" style="max-width:160px">
        <label>Status</label>
        <select name="status">
            <option value="">All</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected':'' }}>Inactive</option>
        </select>
    </div>
    <div style="display:flex;gap:.5rem;align-items:flex-end;padding-bottom:1px">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.supplier.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-xmark"></i> Clear
            </a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="sup-table-card">
    <div class="sup-table-header">
        <h3>
            <i class="fas fa-list"></i> All Suppliers
            <span style="font-size:.75rem;font-weight:600;color:var(--muted);font-family:inherit">
                ({{ $suppliers->total() }})
            </span>
        </h3>
        <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add
        </a>
    </div>

    <div style="overflow-x:auto">
        <table class="sup-table">
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th style="text-align:center">Purchases</th>
                    <th style="text-align:center">Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td>
                        <div class="sup-name-cell">
                            <div class="sup-avatar">
                                {{ strtoupper(substr($supplier->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="sup-name">{{ $supplier->name }}</div>
                                <div class="sup-email">Added {{ $supplier->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--muted)">{{ $supplier->phone ?? '—' }}</td>
                    <td style="color:var(--muted)">{{ $supplier->email ?? '—' }}</td>
                    <td style="color:var(--muted);max-width:160px">
                        <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $supplier->address ?? '—' }}
                        </span>
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('admin.purchase.index', ['supplier_id' => $supplier->id]) }}"
                           style="
                               background:var(--purple-soft);color:var(--purple);
                               font-weight:700;font-size:.78rem;
                               padding:.2rem .65rem;border-radius:20px;
                               text-decoration:none;">
                            {{ $supplier->purchases_count }}
                        </a>
                    </td>
                    <td style="text-align:center">
                        <form method="POST"
                              action="{{ route('admin.supplier.toggle', $supplier->id) }}"
                              style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="badge {{ $supplier->is_active ? 'badge-paid' : 'badge-unpaid' }}"
                                    style="border:none;cursor:pointer;font-family:inherit">
                                {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="tbl-actions">
                            <a href="{{ route('admin.supplier.edit', $supplier->id) }}"
                               class="tbl-btn" title="Edit">
                                <i class="fas fa-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.supplier.destroy', $supplier->id) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($supplier->name) }}?')"
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
                    <td colspan="7">
                        <div class="sup-empty">
                            <i class="fas fa-building"></i>
                            <p>No suppliers found.
                                <a href="{{ route('admin.supplier.create') }}"
                                   style="color:var(--purple);font-weight:600">Add your first supplier</a>.
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
    <div class="sup-pagination">
        <span>Showing {{ $suppliers->firstItem() }}–{{ $suppliers->lastItem() }} of {{ $suppliers->total() }}</span>
        {{ $suppliers->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection