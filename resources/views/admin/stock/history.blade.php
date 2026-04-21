@extends('layouts.admin')

@section('title', 'Stock History')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   STOCK — HISTORY
   ═══════════════════════════════════════════════════════════ */

.hist-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 900px) { .hist-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 500px) { .hist-stats { grid-template-columns: 1fr; } }

.hist-filters {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    align-items: flex-end;
}
.hist-filters .fg {
    display: flex; flex-direction: column;
    gap: .3rem; flex: 1; min-width: 130px;
}
.hist-filters label {
    font-size: .71rem; font-weight: 700;
    color: var(--text-muted); text-transform: uppercase; letter-spacing: .06em;
}
.hist-filters input,
.hist-filters select {
    padding: .55rem .8rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: .84rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.hist-filters input:focus,
.hist-filters select:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 3px rgba(247,37,133,.08);
}

.hist-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.hist-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--pink-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.hist-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .98rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.hist-card-header h3 i { color: var(--pink); }

.hist-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.hist-table thead tr { background: var(--pink-soft); border-bottom: 1.5px solid var(--border); }
.hist-table thead th {
    padding: .72rem 1rem; text-align: left;
    font-size: .7rem; font-weight: 700;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: .07em; white-space: nowrap;
}
.hist-table tbody tr { border-bottom: 1px solid #faf0f7; transition: background .13s; }
.hist-table tbody tr:last-child { border-bottom: none; }
.hist-table tbody tr:hover { background: #fff8fc; }
.hist-table td { padding: .82rem 1rem; vertical-align: middle; }

.dir-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .7rem; font-weight: 700; white-space: nowrap;
    border: 1px solid transparent;
}
.dir-pill.in  { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.dir-pill.out { background: #fff1f2; color: var(--tango); border-color: #fecdd3; }

.prod-cell { display: flex; align-items: center; gap: .6rem; }
.prod-thumb {
    width: 34px; height: 34px; border-radius: 8px;
    object-fit: cover; flex-shrink: 0;
}
.prod-thumb-ph {
    width: 34px; height: 34px; border-radius: 8px;
    background: var(--pink-soft);
    display: flex; align-items: center; justify-content: center;
    color: var(--pink); font-size: .8rem; flex-shrink: 0;
}
.prod-name { font-weight: 600; font-size: .82rem; color: var(--text); line-height: 1.3; }
.prod-sku  { font-size: .69rem; color: var(--text-muted); }

.stock-flow {
    display: flex; align-items: center; gap: .4rem;
    font-size: .8rem; font-variant-numeric: tabular-nums;
}
.stock-flow .before { color: var(--text-muted); }
.stock-flow .arrow  { color: var(--border); font-size: .7rem; }
.stock-flow .after  { font-weight: 700; }
.stock-flow .after.up   { color: #16a34a; }
.stock-flow .after.down { color: var(--tango); }

.qty-badge { font-size: .82rem; font-weight: 700; font-variant-numeric: tabular-nums; }
.qty-badge.in  { color: #16a34a; }
.qty-badge.out { color: var(--tango); }

.hist-empty {
    padding: 3.5rem 1rem; text-align: center; color: var(--text-muted);
}
.hist-empty i { font-size: 2.5rem; opacity: .15; display: block; margin-bottom: .75rem; color: var(--pink); }

.hist-pagination {
    padding: .85rem 1.25rem;
    border-top: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    font-size: .82rem; color: var(--text-muted);
    background: var(--pink-soft); flex-wrap: wrap; gap: .5rem;
}
</style>
@endpush

@section('content')

{{-- Page header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:700;display:flex;align-items:center;gap:.6rem;margin:0 0 .25rem">
            <i class="fas fa-clock-rotate-left" style="color:var(--pink)"></i> Stock History
        </h1>
        <p style="font-size:.82rem;color:var(--text-muted);margin:0">Full audit log of all stock movements</p>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.stock.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Stock
        </a>
        <a href="{{ route('admin.stock.damaged') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-box-archive" style="color:var(--tango)"></i> Damaged / Expired
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="hist-stats">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-arrow-trend-up"></i></div>
        <div>
            <div class="stat-label">Total Stock In</div>
            <div class="stat-value">{{ number_format($stats['total_in']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-arrow-trend-down"></i></div>
        <div>
            <div class="stat-label">Total Stock Out</div>
            <div class="stat-value">{{ number_format($stats['total_out']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--tango-soft);color:var(--tango)">
            <i class="fas fa-box-archive"></i>
        </div>
        <div>
            <div class="stat-label">Damaged Units</div>
            <div class="stat-value">{{ number_format($stats['damaged']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f9f9f9;color:#888">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="stat-label">Expired Units</div>
            <div class="stat-value">{{ number_format($stats['expired']) }}</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.stock.history') }}" class="hist-filters">
    <div class="fg">
        <label>Product</label>
        <select name="product_id">
            <option value="">All Products</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="fg" style="max-width:155px">
        <label>Type</label>
        <select name="type">
            <option value="">All Types</option>
            <option value="purchase"      {{ request('type') === 'purchase'       ? 'selected' : '' }}>Purchase</option>
            <option value="pos_sale"      {{ request('type') === 'pos_sale'       ? 'selected' : '' }}>POS Sale</option>
            <option value="online_sale"   {{ request('type') === 'online_sale'    ? 'selected' : '' }}>Online Sale</option>
            <option value="manual_add"    {{ request('type') === 'manual_add'     ? 'selected' : '' }}>Manual Add</option>
            <option value="manual_deduct" {{ request('type') === 'manual_deduct'  ? 'selected' : '' }}>Manual Deduct</option>
            <option value="damaged"       {{ request('type') === 'damaged'        ? 'selected' : '' }}>Damaged</option>
            <option value="expired"       {{ request('type') === 'expired'        ? 'selected' : '' }}>Expired</option>
        </select>
    </div>
    <div class="fg" style="max-width:130px">
        <label>Direction</label>
        <select name="direction">
            <option value="">All</option>
            <option value="in"  {{ request('direction') === 'in'  ? 'selected' : '' }}>Stock In</option>
            <option value="out" {{ request('direction') === 'out' ? 'selected' : '' }}>Stock Out</option>
        </select>
    </div>
    <div class="fg" style="max-width:145px">
        <label>Date From</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}">
    </div>
    <div class="fg" style="max-width:145px">
        <label>Date To</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}">
    </div>
    <div style="display:flex;gap:.5rem;align-items:flex-end;padding-bottom:1px">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['product_id','type','direction','date_from','date_to']))
            <a href="{{ route('admin.stock.history') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-xmark"></i> Clear
            </a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="hist-card">
    <div class="hist-card-header">
        <h3>
            <i class="fas fa-list-check"></i> Audit Log
            <span style="font-size:.75rem;font-weight:600;color:var(--text-muted);font-family:inherit">
                ({{ $adjustments->total() }} records)
            </span>
        </h3>
        <span style="font-size:.78rem;color:var(--text-muted)">Most recent first</span>
    </div>

    <div style="overflow-x:auto">
        <table class="hist-table">
            <thead>
                <tr>
                    <th>Date / Time</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th style="text-align:center">Direction</th>
                    <th style="text-align:center">Qty</th>
                    <th>Stock Flow</th>
                    <th>Note</th>
                    <th>By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adjustments as $adj)
                <tr>
                    {{-- Date --}}
                    <td style="white-space:nowrap;font-size:.8rem">
                        <div style="font-weight:600;color:var(--text)">
                            {{ $adj->created_at->format('d M Y') }}
                        </div>
                        <div style="color:var(--text-muted)">
                            {{ $adj->created_at->format('H:i:s') }}
                        </div>
                    </td>

                    {{-- Product --}}
                    <td>
                        <div class="prod-cell">
                            @if($adj->product?->thumbnail)
                                <img src="{{ asset('storage/'.$adj->product->thumbnail) }}"
                                     alt="{{ $adj->product->name }}" class="prod-thumb">
                            @else
                                <div class="prod-thumb-ph"><i class="fas fa-spa"></i></div>
                            @endif
                            <div>
                                <div class="prod-name">{{ $adj->product?->name ?? '—' }}</div>
                                @if($adj->product?->sku)
                                    <div class="prod-sku">{{ $adj->product->sku }}</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Type — uses model helpers --}}
                    <td>
                        <span class="badge {{ $adj->getTypeBadgeClass() }}">
                            <i class="fas {{ $adj->getTypeIconClass() }}"></i>
                            {{ $adj->getTypeLabel() }}
                        </span>
                    </td>

                    {{-- Direction --}}
                    <td style="text-align:center">
                        <span class="dir-pill {{ $adj->direction }}">
                            @if($adj->direction === 'in')
                                <i class="fas fa-arrow-up"></i> IN
                            @else
                                <i class="fas fa-arrow-down"></i> OUT
                            @endif
                        </span>
                    </td>

                    {{-- Qty --}}
                    <td style="text-align:center">
                        <span class="qty-badge {{ $adj->direction }}">
                            {{ $adj->direction === 'in' ? '+' : '-' }}{{ number_format($adj->quantity) }}
                        </span>
                    </td>

                    {{-- Stock flow --}}
                    <td>
                        <div class="stock-flow">
                            <span class="before">{{ number_format($adj->stock_before) }}</span>
                            <span class="arrow"><i class="fas fa-arrow-right"></i></span>
                            <span class="after {{ $adj->direction === 'in' ? 'up' : 'down' }}">
                                {{ number_format($adj->stock_after) }}
                            </span>
                        </div>
                    </td>

                    {{-- Note --}}
                    <td style="max-width:180px;font-size:.8rem;color:var(--text-muted)">
                        {{ $adj->note ? \Illuminate\Support\Str::limit($adj->note, 50) : '—' }}
                    </td>

                    {{-- By --}}
                    <td style="font-size:.8rem;white-space:nowrap">
                        <div style="font-weight:600;color:var(--text)">
                            {{ $adj->createdBy?->name ?? 'System' }}
                        </div>
                        <div style="color:var(--text-muted);font-size:.72rem">
                            {{ $adj->created_at->diffForHumans() }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="hist-empty">
                            <i class="fas fa-clock-rotate-left"></i>
                            <p style="font-size:.88rem;margin:.5rem 0 0">No stock movements found.</p>
                            @if(request()->hasAny(['product_id','type','direction','date_from','date_to']))
                                <a href="{{ route('admin.stock.history') }}"
                                   class="btn btn-outline btn-sm" style="margin-top:.75rem">
                                    <i class="fas fa-xmark"></i> Clear Filters
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($adjustments->hasPages())
    <div class="hist-pagination">
        <span>
            Showing {{ $adjustments->firstItem() }}–{{ $adjustments->lastItem() }}
            of {{ $adjustments->total() }} records
        </span>
        {{ $adjustments->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection