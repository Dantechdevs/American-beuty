@extends('layouts.admin')

@section('title', 'Products Report')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PRODUCTS REPORT  — reuses sales report CSS vars
   ═══════════════════════════════════════════════════════════ */

.rpt-filters {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    display: flex; flex-wrap: wrap;
    gap: .75rem; align-items: flex-end;
}
.rpt-filters .fg {
    display: flex; flex-direction: column;
    gap: .3rem; flex: 1; min-width: 130px;
}
.rpt-filters label {
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .06em;
}
.rpt-filters input,
.rpt-filters select {
    padding: .55rem .8rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .84rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.rpt-filters input:focus,
.rpt-filters select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}

.period-pills { display: flex; gap: .4rem; flex-wrap: wrap; }
.period-pill {
    padding: .38rem .85rem;
    border: 1.5px solid var(--border);
    border-radius: 20px; font-size: .78rem; font-weight: 600;
    cursor: pointer; background: #fff; color: var(--muted);
    text-decoration: none; transition: all .15s;
}
.period-pill.active,
.period-pill:hover { background: var(--purple); color: #fff; border-color: var(--purple); }

/* Stats */
.rpt-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem; margin-bottom: 1.5rem;
}
@media (max-width: 900px) { .rpt-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 500px) { .rpt-stats { grid-template-columns: 1fr; } }

/* Cards */
.rpt-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.rpt-card-header {
    padding: .9rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.rpt-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.rpt-card-header h3 i { color: var(--purple); }
.rpt-card-body { padding: 1.25rem; }

.chart-wrap { position: relative; height: 240px; }

.rpt-grid-2 {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 1.25rem; margin-bottom: 1.25rem;
}
@media (max-width: 800px) { .rpt-grid-2 { grid-template-columns: 1fr; } }

/* Table */
.rpt-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
.rpt-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.rpt-table thead th {
    padding: .65rem 1rem; text-align: left;
    font-size: .69rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .07em;
}
.rpt-table tbody tr { border-bottom: 1px solid #f3eeff; transition: background .13s; }
.rpt-table tbody tr:last-child { border-bottom: none; }
.rpt-table tbody tr:hover { background: #faf7ff; }
.rpt-table td { padding: .7rem 1rem; vertical-align: middle; }

/* Progress bar */
.prog-bar {
    height: 6px; border-radius: 4px;
    background: var(--purple-soft); overflow: hidden; min-width: 80px;
}
.prog-bar-fill {
    height: 100%; border-radius: 4px;
    background: linear-gradient(90deg, var(--purple), var(--pink));
    transition: width .4s ease;
}

/* Stars */
.stars { color: #f59e0b; font-size: .8rem; letter-spacing: 1px; }

/* Stock pill */
.stock-pill {
    display: inline-block; padding: .2rem .6rem;
    border-radius: 12px; font-size: .72rem; font-weight: 700;
}
.stock-ok     { background: #dcfce7; color: #16a34a; }
.stock-low    { background: #fef9c3; color: #ca8a04; }
.stock-out    { background: #fee2e2; color: #dc2626; }

/* Movement type badge */
.mv-badge {
    display: inline-block; padding: .18rem .55rem;
    border-radius: 10px; font-size: .7rem; font-weight: 700;
    text-transform: capitalize;
}
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-chart-bar" style="color:var(--purple)"></i> Products Report
        </h1>
        <p class="page-sub">
            {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
            –
            {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
        </p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.reports.products') }}" class="rpt-filters" id="reportForm">
    <div class="fg" style="flex:0 0 auto">
        <label>Period</label>
        <div class="period-pills">
            @foreach(['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'custom' => 'Custom'] as $val => $label)
                <a href="#"
                   class="period-pill {{ $period === $val ? 'active' : '' }}"
                   onclick="setPeriod('{{ $val }}'); return false;">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <input type="hidden" name="period" id="periodInput" value="{{ $period }}">
    </div>

    <div class="fg" id="customRange"
         style="display:{{ $period === 'custom' ? 'flex' : 'none' }};flex-direction:row;gap:.5rem;align-items:flex-end">
        <div style="display:flex;flex-direction:column;gap:.3rem">
            <label>From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div style="display:flex;flex-direction:column;gap:.3rem">
            <label>To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}">
        </div>
    </div>

    <div style="display:flex;gap:.5rem;align-items:flex-end;padding-bottom:1px">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Apply
        </button>
        <a href="{{ route('admin.reports.products') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-rotate-left"></i> Reset
        </a>
    </div>
</form>

{{-- Stats --}}
<div class="rpt-stats">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-box"></i></div>
        <div>
            <div class="stat-label">Total Products</div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-label">Active Products</div>
            <div class="stat-value">{{ number_format($stats['active_products']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
            <div class="stat-label">Low Stock</div>
            <div class="stat-value">{{ number_format($stats['low_stock']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-ban"></i></div>
        <div>
            <div class="stat-label">Out of Stock</div>
            <div class="stat-value">{{ number_format($stats['out_of_stock']) }}</div>
        </div>
    </div>
</div>

{{-- Second stats row --}}
<div class="rpt-stats" style="margin-top:-.5rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-label">Total Stock Value</div>
            <div class="stat-value">KES {{ number_format($stats['total_stock_value'], 2) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-star"></i></div>
        <div>
            <div class="stat-label">Featured</div>
            <div class="stat-value">{{ number_format($stats['featured']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-sparkles"></i></div>
        <div>
            <div class="stat-label">New Arrivals</div>
            <div class="stat-value">{{ number_format($stats['new_arrivals']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-fire"></i></div>
        <div>
            <div class="stat-label">Best Sellers</div>
            <div class="stat-value">{{ number_format($stats['best_sellers']) }}</div>
        </div>
    </div>
</div>

{{-- Stock Movement Chart --}}
<div class="rpt-card">
    <div class="rpt-card-header">
        <h3><i class="fas fa-chart-area"></i> Stock Movement</h3>
        <div style="display:flex;gap:1rem;font-size:.78rem;color:var(--muted)">
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#7c3aed;margin-right:4px"></span>Stock In</span>
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#ec4899;margin-right:4px"></span>Stock Out</span>
        </div>
    </div>
    <div class="rpt-card-body">
        <div class="chart-wrap">
            <canvas id="stockChart"></canvas>
        </div>
    </div>
</div>

{{-- Top Selling + Revenue by Category --}}
<div class="rpt-grid-2">

    {{-- Top Selling --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-trophy"></i> Top Selling Products</h3>
            <span style="font-size:.75rem;color:var(--muted)">by revenue · {{ \Carbon\Carbon::parse($from)->format('d M') }} – {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</span>
        </div>
        @if($topSelling->isNotEmpty())
            @php $maxRev = $topSelling->max('revenue'); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Units</th>
                        <th>Revenue</th>
                        <th style="min-width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSelling as $i => $p)
                    <tr>
                        <td style="color:var(--muted);font-weight:700">{{ $i + 1 }}</td>
                        <td style="font-weight:600;max-width:160px">
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $p->product_name }}
                            </div>
                        </td>
                        <td style="color:var(--muted)">{{ number_format($p->units_sold) }}</td>
                        <td style="font-weight:700;white-space:nowrap">KES {{ number_format($p->revenue, 2) }}</td>
                        <td>
                            <div class="prog-bar">
                                <div class="prog-bar-fill"
                                     style="width:{{ $maxRev > 0 ? round(($p->revenue / $maxRev) * 100) : 0 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:2.5rem;text-align:center;color:var(--muted);font-size:.85rem">
                <i class="fas fa-box-open" style="font-size:2rem;opacity:.15;display:block;margin-bottom:.5rem"></i>
                No sales data for this period.
            </div>
        @endif
    </div>

    {{-- Revenue by Category --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-layer-group"></i> Revenue by Category</h3>
            <span style="font-size:.75rem;color:var(--muted)">paid orders only</span>
        </div>
        @if($revenueByCategory->isNotEmpty())
            @php $maxCat = $revenueByCategory->max('revenue'); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Units</th>
                        <th>Revenue</th>
                        <th style="min-width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueByCategory as $c)
                    <tr>
                        <td style="font-weight:600">{{ $c->category_name }}</td>
                        <td style="color:var(--muted)">{{ number_format($c->units_sold) }}</td>
                        <td style="font-weight:700;white-space:nowrap">KES {{ number_format($c->revenue, 2) }}</td>
                        <td>
                            <div class="prog-bar">
                                <div class="prog-bar-fill"
                                     style="width:{{ $maxCat > 0 ? round(($c->revenue / $maxCat) * 100) : 0 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:2.5rem;text-align:center;color:var(--muted);font-size:.85rem">No data for this period.</div>
        @endif
    </div>
</div>

{{-- Stock Value by Category + Stock Movement Types --}}
<div class="rpt-grid-2">

    {{-- Stock Value by Category --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-warehouse"></i> Stock Value by Category</h3>
        </div>
        @if($stockByCategory->isNotEmpty())
            @php $maxVal = $stockByCategory->max('stock_value'); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Products</th>
                        <th>Units</th>
                        <th>Value</th>
                        <th style="min-width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockByCategory as $s)
                    <tr>
                        <td style="font-weight:600">{{ $s->category_name }}</td>
                        <td style="color:var(--muted)">{{ $s->product_count }}</td>
                        <td style="color:var(--muted)">{{ number_format($s->total_units) }}</td>
                        <td style="font-weight:700;white-space:nowrap">KES {{ number_format($s->stock_value, 2) }}</td>
                        <td>
                            <div class="prog-bar">
                                <div class="prog-bar-fill"
                                     style="width:{{ $maxVal > 0 ? round(($s->stock_value / $maxVal) * 100) : 0 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:2.5rem;text-align:center;color:var(--muted);font-size:.85rem">No stock data.</div>
        @endif
    </div>

    {{-- Stock Movement Types --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-arrows-rotate"></i> Stock Movement Types</h3>
            <span style="font-size:.75rem;color:var(--muted)">this period</span>
        </div>
        @php
            $mvColors = [
                'purchase'      => ['bg'=>'#ede9fe','color'=>'#7c3aed'],
                'online_sale'   => ['bg'=>'#dcfce7','color'=>'#16a34a'],
                'pos_sale'      => ['bg'=>'#d1fae5','color'=>'#059669'],
                'manual_add'    => ['bg'=>'#dbeafe','color'=>'#2563eb'],
                'manual_deduct' => ['bg'=>'#fef9c3','color'=>'#ca8a04'],
                'damaged'       => ['bg'=>'#fee2e2','color'=>'#dc2626'],
                'expired'       => ['bg'=>'#fce7f3','color'=>'#db2777'],
            ];
        @endphp
        @if($stockMovement->isNotEmpty())
            @php $maxMv = $stockMovement->max('total_qty'); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Adjustments</th>
                        <th>Total Qty</th>
                        <th style="min-width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockMovement as $mv)
                    @php $col = $mvColors[$mv->type] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280']; @endphp
                    <tr>
                        <td>
                            <span class="mv-badge"
                                  style="background:{{ $col['bg'] }};color:{{ $col['color'] }}">
                                {{ str_replace('_', ' ', $mv->type) }}
                            </span>
                        </td>
                        <td style="color:var(--muted)">{{ number_format($mv->count) }}</td>
                        <td style="font-weight:700">{{ number_format($mv->total_qty) }}</td>
                        <td>
                            <div class="prog-bar">
                                <div class="prog-bar-fill"
                                     style="width:{{ $maxMv > 0 ? round(($mv->total_qty / $maxMv) * 100) : 0 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:2.5rem;text-align:center;color:var(--muted);font-size:.85rem">No stock movements in this period.</div>
        @endif
    </div>
</div>

{{-- Low Stock + Out of Stock --}}
<div class="rpt-grid-2">

    {{-- Low Stock --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-triangle-exclamation" style="color:#ca8a04"></i> Low Stock (≤10)</h3>
            <a href="{{ route('admin.stock.low') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-external-link"></i> View All
            </a>
        </div>
        @if($lowStock->isNotEmpty())
            <table class="rpt-table">
                <thead>
                    <tr><th>Product</th><th>Category</th><th>Stock</th></tr>
                </thead>
                <tbody>
                    @foreach($lowStock as $p)
                    <tr>
                        <td style="font-weight:600;max-width:160px">
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $p->name }}
                            </div>
                        </td>
                        <td style="color:var(--muted);font-size:.78rem">{{ $p->category->name ?? '—' }}</td>
                        <td>
                            <span class="stock-pill stock-low">{{ $p->stock_quantity }} left</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:2.5rem;text-align:center;color:var(--muted);font-size:.85rem">
                <i class="fas fa-check-circle" style="font-size:2rem;color:#16a34a;opacity:.4;display:block;margin-bottom:.5rem"></i>
                No low stock products.
            </div>
        @endif
    </div>

    {{-- Out of Stock --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-ban" style="color:#dc2626"></i> Out of Stock</h3>
            <a href="{{ route('admin.stock.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-external-link"></i> Manage Stock
            </a>
        </div>
        @if($outOfStock->isNotEmpty())
            <table class="rpt-table">
                <thead>
                    <tr><th>Product</th><th>Category</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($outOfStock as $p)
                    <tr>
                        <td style="font-weight:600;max-width:160px">
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $p->name }}
                            </div>
                        </td>
                        <td style="color:var(--muted);font-size:.78rem">{{ $p->category->name ?? '—' }}</td>
                        <td><span class="stock-pill stock-out">Out of stock</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:2.5rem;text-align:center;color:var(--muted);font-size:.85rem">
                <i class="fas fa-check-circle" style="font-size:2rem;color:#16a34a;opacity:.4;display:block;margin-bottom:.5rem"></i>
                No out of stock products.
            </div>
        @endif
    </div>
</div>

{{-- Damaged / Expired --}}
@if($damaged->isNotEmpty())
<div class="rpt-card" style="margin-top:1.25rem">
    <div class="rpt-card-header">
        <h3><i class="fas fa-skull-crossbones" style="color:#dc2626"></i> Damaged & Expired (This Period)</h3>
    </div>
    <table class="rpt-table">
        <thead>
            <tr><th>Product</th><th>Type</th><th>Qty Lost</th></tr>
        </thead>
        <tbody>
            @foreach($damaged as $d)
            @php $col = $mvColors[$d->type] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280']; @endphp
            <tr>
                <td style="font-weight:600">{{ $d->product_name }}</td>
                <td>
                    <span class="mv-badge" style="background:{{ $col['bg'] }};color:{{ $col['color'] }}">
                        {{ ucfirst($d->type) }}
                    </span>
                </td>
                <td style="font-weight:700;color:#dc2626">{{ number_format($d->total_qty) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Product Performance Table --}}
<div class="rpt-card" style="margin-top:1.25rem">
    <div class="rpt-card-header">
        <h3><i class="fas fa-table-list"></i> Product Performance</h3>
        <span style="font-size:.75rem;color:var(--muted)">Top 20 active products · sales all-time + reviews</span>
    </div>
    <div style="overflow-x:auto">
        <table class="rpt-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                    <th>Rating</th>
                    <th>Reviews</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productPerformance as $i => $p)
                <tr>
                    <td style="color:var(--muted);font-weight:700">{{ $i + 1 }}</td>
                    <td style="font-weight:600;max-width:200px">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $p->name }}
                        </div>
                    </td>
                    <td style="white-space:nowrap;font-size:.8rem">
                        @if($p->sale_price)
                            <span style="color:var(--purple);font-weight:700">KES {{ number_format($p->sale_price, 2) }}</span>
                            <span style="color:var(--muted);text-decoration:line-through;font-size:.72rem">{{ number_format($p->price, 2) }}</span>
                        @else
                            KES {{ number_format($p->price, 2) }}
                        @endif
                    </td>
                    <td>
                        @if($p->stock_quantity == 0)
                            <span class="stock-pill stock-out">0</span>
                        @elseif($p->stock_quantity <= 10)
                            <span class="stock-pill stock-low">{{ $p->stock_quantity }}</span>
                        @else
                            <span class="stock-pill stock-ok">{{ number_format($p->stock_quantity) }}</span>
                        @endif
                    </td>
                    <td style="font-weight:600">{{ number_format($p->units_sold ?? 0) }}</td>
                    <td style="font-weight:700;white-space:nowrap">
                        KES {{ number_format($p->revenue ?? 0, 2) }}
                    </td>
                    <td>
                        @if($p->avg_rating)
                            <div class="stars">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="fas fa-star{{ $s <= round($p->avg_rating) ? '' : '-o' }}"
                                       style="{{ $s <= round($p->avg_rating) ? '' : 'color:#d1d5db' }}"></i>
                                @endfor
                            </div>
                            <div style="font-size:.7rem;color:var(--muted)">{{ number_format($p->avg_rating, 1) }}</div>
                        @else
                            <span style="color:var(--muted);font-size:.78rem">No ratings</span>
                        @endif
                    </td>
                    <td style="color:var(--muted)">{{ number_format($p->review_count) }}</td>
                </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--muted)">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartData = @json($chartData);

new Chart(document.getElementById('stockChart'), {
    type: 'bar',
    data: {
        labels: chartData.labels,
        datasets: [
            {
                label: 'Stock In',
                data: chartData.stock_in,
                backgroundColor: 'rgba(124,58,237,.75)',
                borderRadius: 4,
            },
            {
                label: 'Stock Out',
                data: chartData.stock_out,
                backgroundColor: 'rgba(236,72,153,.65)',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { labels: { font: { size: 12 }, boxWidth: 14 } }
        },
        scales: {
            x: { grid: { color: '#f3eeff' }, ticks: { font: { size: 11 } } },
            y: {
                grid: { color: '#f3eeff' },
                ticks: { font: { size: 11 }, callback: v => Number(v).toLocaleString() }
            }
        }
    }
});

function setPeriod(val) {
    document.getElementById('periodInput').value = val;
    document.getElementById('customRange').style.display = val === 'custom' ? 'flex' : 'none';
    document.querySelectorAll('.period-pill').forEach(el => {
        el.classList.toggle('active', el.getAttribute('onclick').includes("'" + val + "'"));
    });
    if (val !== 'custom') document.getElementById('reportForm').submit();
}
</script>
@endpush