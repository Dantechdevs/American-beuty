@extends('layouts.admin')

@section('title', 'Sales Report')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   SALES REPORT
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

/* Period pills */
.period-pills {
    display: flex; gap: .4rem; flex-wrap: wrap;
}
.period-pill {
    padding: .38rem .85rem;
    border: 1.5px solid var(--border);
    border-radius: 20px; font-size: .78rem; font-weight: 600;
    cursor: pointer; background: #fff; color: var(--muted);
    text-decoration: none; transition: all .15s;
}
.period-pill.active,
.period-pill:hover {
    background: var(--purple); color: #fff; border-color: var(--purple);
}

/* Stats grid */
.rpt-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem; margin-bottom: 1.5rem;
}
@media (max-width: 900px) { .rpt-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 500px) { .rpt-stats { grid-template-columns: 1fr; } }

/* Card */
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

/* Chart wrapper */
.chart-wrap {
    position: relative; height: 260px;
}

/* Two-col grid */
.rpt-grid-2 {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 1.25rem; margin-bottom: 1.25rem;
}
@media (max-width: 800px) { .rpt-grid-2 { grid-template-columns: 1fr; } }

/* Table inside card */
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
.rpt-table td { padding: .75rem 1rem; vertical-align: middle; }

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

/* Donut legend */
.legend-row {
    display: flex; align-items: center; gap: .6rem;
    font-size: .8rem; padding: .3rem 0;
}
.legend-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}

/* Txn badges row */
.txn-badges {
    display: flex; gap: .75rem; flex-wrap: wrap; padding: 1rem 1.25rem;
}
.txn-badge {
    flex: 1; min-width: 100px;
    background: var(--purple-soft);
    border: 1.5px solid #ddd6fe;
    border-radius: var(--r);
    padding: .85rem 1rem;
    text-align: center;
}
.txn-badge .tb-val {
    font-size: 1.5rem; font-weight: 800; color: var(--purple);
    font-variant-numeric: tabular-nums;
}
.txn-badge .tb-lbl {
    font-size: .7rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .05em; margin-top: .15rem;
}
.txn-badge.green  { background: var(--green-soft);  border-color: #bbf7d0; }
.txn-badge.green  .tb-val { color: var(--green); }
.txn-badge.yellow { background: #fefce8; border-color: #fde68a; }
.txn-badge.yellow .tb-val { color: #ca8a04; }
.txn-badge.red    { background: var(--pink-soft);   border-color: #fecaca; }
.txn-badge.red    .tb-val { color: var(--tango); }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-chart-line" style="color:var(--purple)"></i> Sales Report
        </h1>
        <p class="page-sub">
            {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
            –
            {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
        </p>
    </div>
    <a href="{{ route('admin.reports.sales.export', request()->query()) }}"
       class="btn btn-outline btn-sm">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.reports.sales') }}" class="rpt-filters" id="reportForm">

    {{-- Period pills --}}
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

    {{-- Custom range --}}
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
        <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-rotate-left"></i> Reset
        </a>
    </div>
</form>

{{-- Stats --}}
<div class="rpt-stats">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">KES {{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-bag-shopping"></i></div>
        <div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-calculator"></i></div>
        <div>
            <div class="stat-label">Avg Order Value</div>
            <div class="stat-value">KES {{ number_format($stats['avg_order'], 2) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-tag"></i></div>
        <div>
            <div class="stat-label">Total Discounts</div>
            <div class="stat-value">KES {{ number_format($stats['total_discount'], 2) }}</div>
        </div>
    </div>
</div>

{{-- Revenue chart --}}
<div class="rpt-card" style="margin-bottom:1.25rem">
    <div class="rpt-card-header">
        <h3><i class="fas fa-chart-area"></i> Revenue Over Time</h3>
        <div style="display:flex;gap:1rem;font-size:.78rem;color:var(--muted)">
            <span>
                <i class="fas fa-globe" style="color:var(--purple)"></i>
                Online: <strong>KES {{ number_format($stats['online_revenue'], 2) }}</strong>
            </span>
            <span>
                <i class="fas fa-cash-register" style="color:var(--pink)"></i>
                POS: <strong>KES {{ number_format($stats['pos_revenue'], 2) }}</strong>
            </span>
        </div>
    </div>
    <div class="rpt-card-body">
        <div class="chart-wrap">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

{{-- Top Products + Categories --}}
<div class="rpt-grid-2">

    {{-- Top Products --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-trophy"></i> Top Products</h3>
            <span style="font-size:.75rem;color:var(--muted)">by revenue</span>
        </div>
        @if($topProducts->isNotEmpty())
            @php $maxRev = $topProducts->max('revenue'); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Units</th>
                        <th>Revenue</th>
                        <th style="min-width:90px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $i => $p)
                    <tr>
                        <td style="color:var(--muted);font-weight:700">{{ $i + 1 }}</td>
                        <td style="font-weight:600;max-width:160px">
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $p->product_name }}
                            </div>
                        </td>
                        <td style="color:var(--muted)">{{ number_format($p->units_sold) }}</td>
                        <td style="font-weight:700;white-space:nowrap">
                            KES {{ number_format($p->revenue, 2) }}
                        </td>
                        <td>
                            <div class="prog-bar">
                                <div class="prog-bar-fill"
                                     style="width:{{ $maxRev > 0 ? round(($p->revenue / $maxRev) * 100) : 0 }}%">
                                </div>
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

    {{-- Top Categories --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-layer-group"></i> Sales by Category</h3>
            <span style="font-size:.75rem;color:var(--muted)">by revenue</span>
        </div>
        @if($topCategories->isNotEmpty())
            @php $maxCat = $topCategories->max('revenue'); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Units</th>
                        <th>Revenue</th>
                        <th style="min-width:90px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCategories as $c)
                    <tr>
                        <td style="font-weight:600">{{ $c->category_name }}</td>
                        <td style="color:var(--muted)">{{ number_format($c->units_sold) }}</td>
                        <td style="font-weight:700;white-space:nowrap">
                            KES {{ number_format($c->revenue, 2) }}
                        </td>
                        <td>
                            <div class="prog-bar">
                                <div class="prog-bar-fill"
                                     style="width:{{ $maxCat > 0 ? round(($c->revenue / $maxCat) * 100) : 0 }}%">
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:2.5rem;text-align:center;color:var(--muted);font-size:.85rem">
                <i class="fas fa-layer-group" style="font-size:2rem;opacity:.15;display:block;margin-bottom:.5rem"></i>
                No category data for this period.
            </div>
        @endif
    </div>
</div>

{{-- Gateway + Order Status --}}
<div class="rpt-grid-2">

    {{-- Payment Gateway --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-credit-card"></i> Sales by Payment Method</h3>
        </div>
        <div class="rpt-card-body" style="display:flex;gap:1.5rem;align-items:center;flex-wrap:wrap">
            <div style="flex:0 0 160px;height:160px">
                <canvas id="gatewayChart"></canvas>
            </div>
            <div style="flex:1;min-width:130px">
                @php
                    $gwColors = ['#7c3aed','#ec4899','#2563eb','#16a34a','#ea580c','#94a3b8'];
                    $gwTotal  = $gatewayBreakdown->sum('revenue');
                @endphp
                @foreach($gatewayBreakdown as $i => $gw)
                <div class="legend-row">
                    <div class="legend-dot" style="background:{{ $gwColors[$i % count($gwColors)] }}"></div>
                    <div style="flex:1">
                        <div style="font-weight:600;font-size:.82rem;text-transform:capitalize">
                            {{ $gw->payment_method ?? 'Unknown' }}
                        </div>
                        <div style="font-size:.72rem;color:var(--muted)">
                            {{ $gw->count }} orders ·
                            {{ $gwTotal > 0 ? round(($gw->revenue / $gwTotal) * 100) : 0 }}%
                        </div>
                    </div>
                    <div style="font-weight:700;font-size:.8rem;white-space:nowrap">
                        KES {{ number_format($gw->revenue, 2) }}
                    </div>
                </div>
                @endforeach
                @if($gatewayBreakdown->isEmpty())
                    <p style="color:var(--muted);font-size:.82rem">No data.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Order Status --}}
    <div class="rpt-card" style="margin-bottom:0">
        <div class="rpt-card-header">
            <h3><i class="fas fa-list-check"></i> Order Status Breakdown</h3>
        </div>
        @php
            $statusColors = [
                'completed' => 'badge-success',
                'delivered' => 'badge-success',
                'pending'   => 'badge-warning',
                'processing'=> 'badge-purple',
                'shipped'   => 'badge-info',
                'cancelled' => 'badge-muted',
                'refunded'  => 'badge-tango',
            ];
        @endphp
        @if($statusBreakdown->isNotEmpty())
            @php $maxStatus = $statusBreakdown->max('count'); @endphp
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                        <th style="min-width:90px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statusBreakdown as $s)
                    <tr>
                        <td>
                            <span class="badge {{ $statusColors[$s->status] ?? 'badge-muted' }}"
                                  style="text-transform:capitalize">
                                {{ $s->status }}
                            </span>
                        </td>
                        <td style="font-weight:700">{{ number_format($s->count) }}</td>
                        <td style="font-size:.8rem;color:var(--muted);white-space:nowrap">
                            KES {{ number_format($s->total, 2) }}
                        </td>
                        <td>
                            <div class="prog-bar">
                                <div class="prog-bar-fill"
                                     style="width:{{ $maxStatus > 0 ? round(($s->count / $maxStatus) * 100) : 0 }}%">
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:2.5rem;text-align:center;color:var(--muted);font-size:.85rem">
                No orders in this period.
            </div>
        @endif
    </div>
</div>

{{-- Transaction summary --}}
<div class="rpt-card" style="margin-top:1.25rem">
    <div class="rpt-card-header">
        <h3><i class="fas fa-arrow-right-arrow-left"></i> Transaction Summary</h3>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-external-link"></i> View All
        </a>
    </div>
    <div class="txn-badges">
        <div class="txn-badge green">
            <div class="tb-val">{{ number_format($txnStats['success']) }}</div>
            <div class="tb-lbl">Successful</div>
        </div>
        <div class="txn-badge yellow">
            <div class="tb-val">{{ number_format($txnStats['pending']) }}</div>
            <div class="tb-lbl">Pending</div>
        </div>
        <div class="txn-badge red">
            <div class="tb-val">{{ number_format($txnStats['failed']) }}</div>
            <div class="tb-lbl">Failed</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* ── Revenue chart ── */
const chartData = @json($chartData);

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: chartData.labels,
        datasets: [
            {
                label: 'Revenue (KES)',
                data: chartData.revenue,
                borderColor: '#7c3aed',
                backgroundColor: 'rgba(124,58,237,.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#7c3aed',
                pointRadius: 4,
                fill: true,
                tension: 0.4,
                yAxisID: 'y',
            },
            {
                label: 'Orders',
                data: chartData.orders,
                borderColor: '#ec4899',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointBackgroundColor: '#ec4899',
                pointRadius: 3,
                borderDash: [5, 4],
                fill: false,
                tension: 0.4,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                labels: { font: { size: 12 }, boxWidth: 14 }
            },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.datasetIndex === 0
                        ? ' KES ' + Number(ctx.raw).toLocaleString('en-KE', {minimumFractionDigits:2})
                        : ' ' + ctx.raw + ' orders'
                }
            }
        },
        scales: {
            x: {
                grid: { color: '#f3eeff' },
                ticks: { font: { size: 11 } }
            },
            y: {
                position: 'left',
                grid: { color: '#f3eeff' },
                ticks: {
                    font: { size: 11 },
                    callback: v => 'KES ' + Number(v).toLocaleString()
                }
            },
            y1: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: { font: { size: 11 } }
            }
        }
    }
});

/* ── Gateway donut ── */
@if($gatewayBreakdown->isNotEmpty())
const gwColors = ['#7c3aed','#ec4899','#2563eb','#16a34a','#ea580c','#94a3b8'];
new Chart(document.getElementById('gatewayChart'), {
    type: 'doughnut',
    data: {
        labels: @json($gatewayBreakdown->pluck('payment_method')),
        datasets: [{
            data: @json($gatewayBreakdown->pluck('revenue')->map(fn($v) => (float)$v)),
            backgroundColor: gwColors,
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' KES ' + Number(ctx.raw).toLocaleString('en-KE', {minimumFractionDigits:2})
                }
            }
        },
        cutout: '65%',
    }
});
@endif

/* ── Period switcher ── */
function setPeriod(val) {
    document.getElementById('periodInput').value = val;
    document.getElementById('customRange').style.display = val === 'custom' ? 'flex' : 'none';
    document.querySelectorAll('.period-pill').forEach(el => {
        el.classList.toggle('active', el.getAttribute('onclick').includes("'" + val + "'"));
    });
    if (val !== 'custom') {
        document.getElementById('reportForm').submit();
    }
}
</script>
@endpush