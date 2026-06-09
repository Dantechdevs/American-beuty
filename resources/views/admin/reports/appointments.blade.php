@extends('layouts.admin')
@section('title', 'Appointments Report')

@push('styles')
<style>
.rpt-filters {
    background:#fff; border:1.5px solid var(--border); border-radius:var(--r);
    padding:1rem 1.25rem; margin-bottom:1.5rem;
    display:flex; flex-wrap:wrap; gap:.75rem; align-items:flex-end;
}
.rpt-filters .fg { display:flex; flex-direction:column; gap:.3rem; flex:1; min-width:130px; }
.rpt-filters label { font-size:.71rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
.rpt-filters input, .rpt-filters select {
    padding:.55rem .8rem; border:1.5px solid var(--border); border-radius:var(--r-sm);
    font-size:.84rem; font-family:inherit; outline:none; background:#fff; color:var(--text);
    transition:border-color .18s; width:100%;
}
.rpt-filters input:focus, .rpt-filters select:focus { border-color:var(--purple); box-shadow:0 0 0 3px rgba(124,58,237,.08); }
.period-pills { display:flex; gap:.4rem; flex-wrap:wrap; }
.period-pill {
    padding:.38rem .85rem; border:1.5px solid var(--border); border-radius:20px;
    font-size:.78rem; font-weight:600; cursor:pointer; background:#fff; color:var(--muted);
    text-decoration:none; transition:all .15s;
}
.period-pill.active, .period-pill:hover { background:var(--purple); color:#fff; border-color:var(--purple); }
.rpt-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
@media(max-width:900px){ .rpt-stats { grid-template-columns:repeat(2,1fr); } }
@media(max-width:500px){ .rpt-stats { grid-template-columns:1fr; } }
.rpt-stat { background:#fff; border:1.5px solid var(--border); border-radius:var(--r); padding:1.1rem 1.25rem; box-shadow:var(--shadow); }
.rpt-stat .rs-label { font-size:.72rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.07em; margin-bottom:.3rem; }
.rpt-stat .rs-val { font-size:1.55rem; font-weight:800; color:var(--text); line-height:1.1; }
.rpt-stat .rs-sub { font-size:.75rem; color:var(--muted); margin-top:.25rem; }
.rpt-card { background:#fff; border:1.5px solid var(--border); border-radius:var(--r); box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.25rem; }
.rpt-card-header {
    padding:.9rem 1.25rem; border-bottom:1.5px solid var(--border);
    background:linear-gradient(120deg,#fff 55%,var(--purple-soft) 100%);
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem;
}
.rpt-card-header h3 { font-family:'Playfair Display',serif; font-size:.95rem; font-weight:700; margin:0; display:flex; align-items:center; gap:.5rem; }
.rpt-card-header h3 i { color:var(--purple); }
.rpt-card-body { padding:1.25rem; }
.chart-wrap { position:relative; height:260px; }
.rpt-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
@media(max-width:800px){ .rpt-grid-2 { grid-template-columns:1fr; } }
.rpt-table { width:100%; border-collapse:collapse; font-size:.83rem; }
.rpt-table thead tr { background:#faf7ff; border-bottom:1.5px solid var(--border); }
.rpt-table thead th { padding:.65rem 1rem; text-align:left; font-size:.69rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.07em; }
.rpt-table tbody tr { border-bottom:1px solid #f3eeff; transition:background .13s; }
.rpt-table tbody tr:last-child { border-bottom:none; }
.rpt-table tbody tr:hover { background:#faf7ff; }
.rpt-table td { padding:.75rem 1rem; vertical-align:middle; }
.prog-bar { height:6px; border-radius:4px; background:var(--purple-soft); overflow:hidden; min-width:80px; }
.prog-bar-fill { height:100%; border-radius:4px; background:linear-gradient(90deg,var(--purple),var(--pink)); transition:width .4s ease; }
.badge { display:inline-flex; align-items:center; padding:.2rem .6rem; border-radius:20px; font-size:.72rem; font-weight:700; }
.badge-success { background:#d1fae5; color:#065f46; }
.badge-warning { background:#fef3c7; color:#92400e; }
.badge-danger  { background:#fee2e2; color:#991b1b; }
.badge-purple  { background:var(--purple-soft); color:var(--purple); }
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title"><i class="fas fa-spa" style="color:var(--purple)"></i> Appointments Report</div>
        <div class="page-sub">{{ $from->format('M d, Y') }} &mdash; {{ $to->format('M d, Y') }}</div>
    </div>
    <a href="{{ route('admin.reports.appointments.export', request()->query()) }}" class="btn btn-outline btn-sm">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

{{-- Filters --}}
<div class="rpt-filters">
    <div class="fg">
        <label>Period</label>
        <div class="period-pills">
            @foreach(['today'=>'Today','weekly'=>'This Week','monthly'=>'This Month','yearly'=>'This Year','custom'=>'Custom'] as $val=>$label)
                <a href="{{ request()->fullUrlWithQuery(['period'=>$val]) }}"
                   class="period-pill {{ $period===$val ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
    @if($period === 'custom')
    <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
        <input type="hidden" name="period" value="custom">
        <div class="fg"><label>From</label><input type="date" name="date_from" value="{{ request('date_from') }}"></div>
        <div class="fg"><label>To</label><input type="date" name="date_to" value="{{ request('date_to') }}"></div>
        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
    </form>
    @endif
</div>

{{-- Stats --}}
<div class="rpt-stats">
    <div class="rpt-stat">
        <div class="rs-label">Total Appointments</div>
        <div class="rs-val">{{ number_format($stats['total_appointments']) }}</div>
        <div class="rs-sub">{{ $stats['confirmed'] }} confirmed &middot; {{ $stats['completed'] }} completed</div>
    </div>
    <div class="rpt-stat">
        <div class="rs-label">Total Revenue</div>
        <div class="rs-val">KSh {{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="rs-sub">{{ $stats['paid_count'] }} paid appointments</div>
    </div>
    <div class="rpt-stat">
        <div class="rs-label">Avg. Booking Value</div>
        <div class="rs-val">KSh {{ number_format($stats['avg_value'], 0) }}</div>
        <div class="rs-sub">Per paid appointment</div>
    </div>
    <div class="rpt-stat">
        <div class="rs-label">Cancellations</div>
        <div class="rs-val">{{ $stats['cancelled'] }}</div>
        <div class="rs-sub">{{ $stats['pending'] }} still pending</div>
    </div>
</div>

{{-- Revenue Chart --}}
<div class="rpt-card">
    <div class="rpt-card-header"><h3><i class="fas fa-chart-line"></i> Revenue Over Time</h3></div>
    <div class="rpt-card-body">
        <div class="chart-wrap"><canvas id="revenueChart"></canvas></div>
    </div>
</div>

{{-- Top Services + Top Categories --}}
<div class="rpt-grid-2">
    <div class="rpt-card">
        <div class="rpt-card-header"><h3><i class="fas fa-star"></i> Top Services</h3></div>
        <div class="rpt-card-body" style="padding:0">
            @php $maxRev = $topServices->max('revenue') ?: 1; @endphp
            <table class="rpt-table">
                <thead><tr><th>Service</th><th>Bookings</th><th>Revenue</th><th style="width:100px">Share</th></tr></thead>
                <tbody>
                @forelse($topServices as $s)
                <tr>
                    <td style="font-weight:600">{{ $s->service_name }}</td>
                    <td>{{ $s->bookings }}</td>
                    <td>KSh {{ number_format($s->revenue, 0) }}</td>
                    <td><div class="prog-bar"><div class="prog-bar-fill" style="width:{{ round($s->revenue/$maxRev*100) }}%"></div></div></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:1.5rem">No data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="rpt-card">
        <div class="rpt-card-header"><h3><i class="fas fa-tags"></i> By Category</h3></div>
        <div class="rpt-card-body" style="padding:0">
            @php $maxCat = $topCategories->max('revenue') ?: 1; @endphp
            <table class="rpt-table">
                <thead><tr><th>Category</th><th>Bookings</th><th>Revenue</th><th style="width:100px">Share</th></tr></thead>
                <tbody>
                @forelse($topCategories as $c)
                <tr>
                    <td style="font-weight:600">{{ $c->service_category ?: 'Uncategorised' }}</td>
                    <td>{{ $c->bookings }}</td>
                    <td>KSh {{ number_format($c->revenue, 0) }}</td>
                    <td><div class="prog-bar"><div class="prog-bar-fill" style="width:{{ round($c->revenue/$maxCat*100) }}%"></div></div></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:1.5rem">No data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Payment + Status --}}
<div class="rpt-grid-2">
    <div class="rpt-card">
        <div class="rpt-card-header"><h3><i class="fas fa-credit-card"></i> Payment Methods</h3></div>
        <div class="rpt-card-body" style="padding:0">
            <table class="rpt-table">
                <thead><tr><th>Method</th><th>Count</th><th>Revenue</th></tr></thead>
                <tbody>
                @forelse($paymentBreakdown as $p)
                <tr>
                    <td style="font-weight:600;text-transform:capitalize">{{ $p->payment_method ?: 'Unknown' }}</td>
                    <td>{{ $p->count }}</td>
                    <td>KSh {{ number_format($p->revenue, 0) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--muted);padding:1.5rem">No data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="rpt-card">
        <div class="rpt-card-header"><h3><i class="fas fa-circle-half-stroke"></i> Status Breakdown</h3></div>
        <div class="rpt-card-body" style="padding:0">
            <table class="rpt-table">
                <thead><tr><th>Status</th><th>Count</th><th>Value</th></tr></thead>
                <tbody>
                @forelse($statusBreakdown as $s)
                <tr>
                    <td>
                        @php $cls = match($s->status){ 'confirmed'=>'badge-success','completed'=>'badge-purple','cancelled'=>'badge-danger',default=>'badge-warning' }; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($s->status) }}</span>
                    </td>
                    <td>{{ $s->count }}</td>
                    <td>KSh {{ number_format($s->total, 0) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--muted);padding:1.5rem">No data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent Appointments --}}
<div class="rpt-card">
    <div class="rpt-card-header"><h3><i class="fas fa-calendar-check"></i> Recent Appointments</h3></div>
    <div class="rpt-card-body" style="padding:0">
        <table class="rpt-table">
            <thead>
                <tr><th>Date</th><th>Client</th><th>Service</th><th>Price</th><th>Payment</th><th>Status</th></tr>
            </thead>
            <tbody>
            @forelse($recent as $a)
            <tr>
                <td>{{ $a->appointment_date->format('M d, Y') }}<br><small style="color:var(--muted)">{{ $a->appointment_time }}</small></td>
                <td style="font-weight:600">{{ $a->client_name }}<br><small style="color:var(--muted)">{{ $a->client_phone }}</small></td>
                <td>{{ $a->service_name }}</td>
                <td>KSh {{ number_format($a->service_price, 0) }}</td>
                <td>
                    @if($a->payment_status === 'paid')
                        <span class="badge badge-success">Paid</span>
                    @else
                        <span class="badge badge-warning">{{ ucfirst($a->payment_status) }}</span>
                    @endif
                </td>
                <td>
                    @php $cls = match($a->status){ 'confirmed'=>'badge-success','completed'=>'badge-purple','cancelled'=>'badge-danger',default=>'badge-warning' }; @endphp
                    <span class="badge {{ $cls }}">{{ ucfirst($a->status) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:1.5rem">No appointments in this period</td></tr>
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
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: chartData.labels,
        datasets: [{
            label: 'Revenue (KSh)',
            data: chartData.revenue,
            backgroundColor: 'rgba(124,58,237,.18)',
            borderColor: 'rgba(124,58,237,1)',
            borderWidth: 2,
            borderRadius: 4,
            yAxisID: 'y',
        },{
            label: 'Bookings',
            data: chartData.count,
            type: 'line',
            borderColor: '#ec4899',
            backgroundColor: 'rgba(236,72,153,.08)',
            pointBackgroundColor: '#ec4899',
            borderWidth: 2,
            tension: .35,
            yAxisID: 'y1',
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: {
            y:  { position: 'left',  beginAtZero: true, grid: { color: '#f3eeff' } },
            y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } }
        }
    }
});
</script>
@endpush
