@extends('layouts.admin')

@section('title', 'Damaged & Expired Stock')

@push('styles')
<style>
.de-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.de-stat-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: 1rem 1.25rem;
    text-align: center;
    box-shadow: var(--shadow);
}
.de-stat-num  { font-size: 1.9rem; font-weight: 800; line-height: 1; }
.de-stat-label{ font-size: .75rem; color: var(--muted); margin-top: .3rem; }

.filter-bar {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: .85rem 1.25rem;
    display: flex; gap: .75rem; flex-wrap: wrap;
    align-items: center;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow);
}
.filter-bar select, .filter-bar input[type=date] {
    padding: .45rem .75rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .83rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
}
.filter-bar select:focus, .filter-bar input[type=date]:focus {
    border-color: var(--purple);
}

.sa-card { background:#fff; border:1.5px solid var(--border); border-radius:var(--r); box-shadow:var(--shadow); overflow:hidden; }
.sa-card-header {
    padding:.9rem 1.25rem; border-bottom:1.5px solid var(--border);
    background:linear-gradient(120deg,#fff 55%,var(--purple-soft) 100%);
    display:flex; align-items:center; justify-content:space-between;
}
.sa-card-header h3 { font-family:'Playfair Display',serif; font-size:.95rem; font-weight:700; margin:0; display:flex; align-items:center; gap:.5rem; }
.sa-card-header h3 i { color:var(--purple); }

.badge-danger  { background:var(--pink-soft);  color:var(--tango);  font-size:.7rem; padding:.2rem .55rem; border-radius:20px; font-weight:700; }
.badge-warning { background:#fef9c3;            color:#a16207;       font-size:.7rem; padding:.2rem .55rem; border-radius:20px; font-weight:700; }
.badge-purple  { background:var(--purple-soft); color:var(--purple); font-size:.7rem; padding:.2rem .55rem; border-radius:20px; font-weight:700; }
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom:1.25rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-box-archive" style="color:var(--tango)"></i> Damaged & Expired Stock
        </h1>
        <p class="page-sub">All stock written off as damaged or expired</p>
    </div>
    <a href="{{ route('admin.stock.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Stock
    </a>
</div>

{{-- Stats --}}
<div class="de-stat-grid">
    <div class="de-stat-card">
        <div class="de-stat-num" style="color:var(--tango)">{{ number_format($stats['damaged_qty'] ?? 0) }}</div>
        <div class="de-stat-label">Damaged Units</div>
    </div>
    <div class="de-stat-card">
        <div class="de-stat-num" style="color:var(--purple)">{{ number_format($stats['expired_qty'] ?? 0) }}</div>
        <div class="de-stat-label">Expired Units</div>
    </div>
    <div class="de-stat-card">
        <div class="de-stat-num" style="color:var(--gold)">{{ number_format($stats['damaged_count'] ?? 0) }}</div>
        <div class="de-stat-label">Damaged Records</div>
    </div>
    <div class="de-stat-card">
        <div class="de-stat-num" style="color:var(--gold)">{{ number_format($stats['expired_count'] ?? 0) }}</div>
        <div class="de-stat-label">Expired Records</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.stock.damaged') }}">
    <div class="filter-bar">
        <select name="type" onchange="this.form.submit()">
            <option value="">All Types</option>
            <option value="damaged"  {{ request('type') === 'damaged'  ? 'selected' : '' }}>Damaged</option>
            <option value="expired"  {{ request('type') === 'expired'  ? 'selected' : '' }}>Expired</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               placeholder="From" onchange="this.form.submit()">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               placeholder="To" onchange="this.form.submit()">
        @if(request()->hasAny(['type','date_from','date_to']))
            <a href="{{ route('admin.stock.damaged') }}" class="btn btn-outline" style="font-size:.8rem">
                <i class="fas fa-xmark"></i> Clear
            </a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="sa-card">
    <div class="sa-card-header">
        <h3><i class="fas fa-list"></i> Records</h3>
        <span style="font-size:.78rem;color:var(--muted)">{{ $adjustments->total() }} total</span>
    </div>
    <div style="overflow-x:auto">
        <table class="table" style="margin:0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>Before</th>
                    <th>After</th>
                    <th>Note</th>
                    <th>By</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adjustments as $log)
                <tr>
                    <td style="font-weight:600;font-size:.85rem">
                        {{ $log->product?->name ?? '—' }}
                    </td>
                    <td>
                        @if($log->type === 'damaged')
                            <span class="badge-danger"><i class="fas fa-box-archive"></i> Damaged</span>
                        @else
                            <span class="badge-purple"><i class="fas fa-clock"></i> Expired</span>
                        @endif
                    </td>
                    <td style="font-weight:700;color:var(--tango)">-{{ $log->quantity }}</td>
                    <td style="font-size:.8rem;color:var(--muted)">{{ $log->stock_before }}</td>
                    <td style="font-size:.8rem;color:var(--muted)">{{ $log->stock_after }}</td>
                    <td style="font-size:.8rem;color:var(--muted);max-width:180px">
                        {{ $log->note ? Str::limit($log->note, 50) : '—' }}
                    </td>
                    <td style="font-size:.8rem">{{ $log->createdBy?->name ?? 'System' }}</td>
                    <td style="font-size:.8rem;color:var(--muted);white-space:nowrap">
                        {{ $log->created_at->format('d M Y, H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--muted)">
                        <i class="fas fa-box-archive" style="font-size:1.5rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                        No damaged or expired records found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($adjustments->hasPages())
        <div style="padding:1rem 1.25rem;border-top:1.5px solid var(--border)">
            {{ $adjustments->links() }}
        </div>
    @endif
</div>

@endsection