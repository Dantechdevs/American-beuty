@extends('layouts.admin')

@section('title', 'Purchases')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PURCHASES — INDEX
   ═══════════════════════════════════════════════════════════ */

/* ── Stat cards ── */
.purchase-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 900px) { .purchase-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 500px) { .purchase-stats { grid-template-columns: 1fr; } }

/* ── Filter bar ── */
.purchase-filters {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    align-items: flex-end;
}
.purchase-filters .filter-group {
    display: flex;
    flex-direction: column;
    gap: .3rem;
    flex: 1;
    min-width: 140px;
}
.purchase-filters label {
    font-size: .72rem;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .05em;
}
.purchase-filters select,
.purchase-filters input[type="date"] {
    padding: .55rem .8rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .85rem;
    font-family: inherit;
    outline: none;
    background: #fff;
    color: var(--text);
    transition: border-color .18s;
    width: 100%;
}
.purchase-filters select:focus,
.purchase-filters input[type="date"]:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.filter-actions {
    display: flex;
    gap: .5rem;
    align-items: flex-end;
    padding-bottom: 1px;
}

/* ── Table card ── */
.purchase-table-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.purchase-table-header {
    padding: 1rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
}
.purchase-table-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.purchase-table-header h3 i { color: var(--purple); }

.purchase-table-wrap { overflow-x: auto; }
.purchase-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .84rem;
}
.purchase-table thead tr {
    background: #faf7ff;
    border-bottom: 1.5px solid var(--border);
}
.purchase-table thead th {
    padding: .75rem 1rem;
    text-align: left;
    font-size: .71rem;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
}
.purchase-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .15s;
}
.purchase-table tbody tr:last-child { border-bottom: none; }
.purchase-table tbody tr:hover { background: #faf7ff; }
.purchase-table td {
    padding: .85rem 1rem;
    color: var(--text);
    vertical-align: middle;
}

/* Invoice pill */
.invoice-pill {
    font-size: .75rem;
    font-weight: 700;
    color: var(--purple);
    background: var(--purple-soft);
    padding: .25rem .65rem;
    border-radius: 20px;
    letter-spacing: .03em;
    white-space: nowrap;
}

/* Supplier cell */
.supplier-cell {
    display: flex;
    align-items: center;
    gap: .55rem;
}
.supplier-avatar {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--purple-soft), var(--pink-soft));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .78rem;
    font-weight: 700;
    color: var(--purple);
    flex-shrink: 0;
}
.supplier-name { font-weight: 600; color: var(--text); }

/* Amount cells */
.amount-cell {
    font-weight: 600;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}
.amount-cell.total  { color: var(--text); }
.amount-cell.paid   { color: var(--green); }
.amount-cell.balance { color: var(--tango); }
.amount-cell.zero   { color: var(--muted); }

/* Payment status badges */
.badge-paid    { background: var(--green-soft); color: #15803d; border: 1px solid #bbf7d0; }
.badge-partial { background: #fef9c3;           color: #a16207; border: 1px solid #fde68a; }
.badge-unpaid  { background: var(--pink-soft);  color: var(--pink);  border: 1px solid #fecdd3; }

/* Action buttons */
.tbl-actions { display: flex; gap: .4rem; align-items: center; }
.tbl-btn {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .76rem;
    color: var(--muted);
    transition: all .15s;
    text-decoration: none;
}
.tbl-btn:hover          { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.tbl-btn.tbl-btn-danger:hover { border-color: var(--tango); color: var(--tango); background: var(--pink-soft); }

/* Empty state */
.purchase-empty {
    padding: 3.5rem 1rem;
    text-align: center;
    color: var(--muted);
}
.purchase-empty i { font-size: 2.5rem; opacity: .15; color: var(--purple); display: block; margin-bottom: .75rem; }
.purchase-empty p { font-size: .88rem; }

/* Pagination */
.purchase-pagination {
    padding: .85rem 1.25rem;
    border-top: 1.5px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: .82rem;
    color: var(--muted);
    background: #faf7ff;
    flex-wrap: wrap;
    gap: .5rem;
}
</style>
@endpush

@section('content')

{{-- ── Page header ── --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-truck" style="color:var(--purple)"></i>
            Purchases
        </h1>
        <p class="page-sub">Track stock purchases and supplier payments</p>
    </div>
    <a href="{{ route('admin.purchase.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Purchase
    </a>
</div>

{{-- ── Flash messages ── --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="flash flash-error" style="margin-bottom:1rem">
        <i class="fas fa-circle-exclamation"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

{{-- ── Stat cards ── --}}
<div class="purchase-stats">

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-truck"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Purchases</span>
            <span class="stat-value">{{ number_format($stats['total_purchases']) }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Paid</span>
            <span class="stat-value">KSh {{ number_format($stats['total_paid'], 2) }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon tango">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Unpaid</span>
            <span class="stat-value">KSh {{ number_format($stats['total_unpaid'], 2) }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon pink">
            <i class="fas fa-rotate-left"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">Returns</span>
            <span class="stat-value">{{ number_format($stats['total_returns']) }}</span>
        </div>
    </div>

</div>

{{-- ── Filters ── --}}
<form method="GET" action="{{ route('admin.purchase.index') }}" class="purchase-filters">

    <div class="filter-group">
        <label>Supplier</label>
        <select name="supplier_id">
            <option value="">All Suppliers</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label>Payment Status</label>
        <select name="payment_status">
            <option value="">All Statuses</option>
            <option value="paid"    {{ request('payment_status') === 'paid'    ? 'selected' : '' }}>Paid</option>
            <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="unpaid"  {{ request('payment_status') === 'unpaid'  ? 'selected' : '' }}>Unpaid</option>
        </select>
    </div>

    <div class="filter-group">
        <label>From Date</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}">
    </div>

    <div class="filter-group">
        <label>To Date</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}">
    </div>

    <div class="filter-actions">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['supplier_id','payment_status','date_from','date_to']))
            <a href="{{ route('admin.purchase.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-xmark"></i> Clear
            </a>
        @endif
    </div>

</form>

{{-- ── Table ── --}}
<div class="purchase-table-card">

    <div class="purchase-table-header">
        <h3>
            <i class="fas fa-list"></i>
            All Purchases
            <span style="font-size:.75rem;font-weight:600;color:var(--muted);font-family:inherit">
                ({{ $purchases->total() }})
            </span>
        </h3>
        <a href="{{ route('admin.purchase.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add
        </a>
    </div>

    <div class="purchase-table-wrap">
        <table class="purchase-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr>
                    {{-- Invoice --}}
                    <td>
                        <span class="invoice-pill">{{ $purchase->invoice_no }}</span>
                    </td>

                    {{-- Supplier --}}
                    <td>
                        <div class="supplier-cell">
                            <div class="supplier-avatar">
                                {{ strtoupper(substr($purchase->supplier->name, 0, 1)) }}
                            </div>
                            <span class="supplier-name">{{ $purchase->supplier->name }}</span>
                        </div>
                    </td>

                    {{-- Date --}}
                    <td style="color:var(--muted);white-space:nowrap">
                        <i class="fas fa-calendar-days" style="margin-right:.35rem;font-size:.75rem"></i>
                        {{ $purchase->purchase_date->format('d M Y') }}
                    </td>

                    {{-- Items count --}}
                    <td style="text-align:center">
                        <span style="
                            background:var(--purple-soft);color:var(--purple);
                            font-weight:700;font-size:.78rem;
                            padding:.2rem .55rem;border-radius:20px;">
                            {{ $purchase->items->count() }}
                        </span>
                    </td>

                    {{-- Total --}}
                    <td>
                        <span class="amount-cell total">
                            KSh {{ number_format($purchase->total_amount, 2) }}
                        </span>
                    </td>

                    {{-- Paid --}}
                    <td>
                        <span class="amount-cell paid">
                            KSh {{ number_format($purchase->paid_amount, 2) }}
                        </span>
                    </td>

                    {{-- Balance --}}
                    <td>
                        @php $balance = $purchase->total_amount - $purchase->paid_amount; @endphp
                        <span class="amount-cell {{ $balance > 0 ? 'balance' : 'zero' }}">
                            KSh {{ number_format($balance, 2) }}
                        </span>
                    </td>

                    {{-- Status badge --}}
                    <td>
                        <span class="badge badge-{{ $purchase->payment_status === 'paid' ? 'paid' : ($purchase->payment_status === 'partial' ? 'partial' : 'unpaid') }}">
                            @if($purchase->payment_status === 'paid')
                                <i class="fas fa-circle-check"></i> Paid
                            @elseif($purchase->payment_status === 'partial')
                                <i class="fas fa-circle-half-stroke"></i> Partial
                            @else
                                <i class="fas fa-clock"></i> Unpaid
                            @endif
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="tbl-actions">
                            <a href="{{ route('admin.purchase.show', $purchase->id) }}"
                               class="tbl-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.purchase.edit', $purchase->id) }}"
                               class="tbl-btn" title="Edit">
                                <i class="fas fa-pencil"></i>
                            </a>
                            <a href="{{ route('admin.purchase.return.form', $purchase->id) }}"
                               class="tbl-btn" title="Return" style="color:var(--tango)">
                                <i class="fas fa-rotate-left"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.purchase.destroy', $purchase->id) }}"
                                  onsubmit="return confirm('Delete this purchase?')"
                                  style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn tbl-btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="purchase-empty">
                            <i class="fas fa-truck"></i>
                            <p>No purchases found.
                                @if(request()->hasAny(['supplier_id','payment_status','date_from','date_to']))
                                    Try clearing your filters or
                                @endif
                                <a href="{{ route('admin.purchase.create') }}" style="color:var(--purple);font-weight:600">
                                    record a new purchase
                                </a>.
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($purchases->hasPages())
    <div class="purchase-pagination">
        <span>
            Showing {{ $purchases->firstItem() }}–{{ $purchases->lastItem() }}
            of {{ $purchases->total() }} purchases
        </span>
        {{ $purchases->withQueryString()->links() }}
    </div>
    @endif

</div>

@endsection