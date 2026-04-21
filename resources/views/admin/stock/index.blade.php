@extends('layouts.admin')

@section('title', 'Stock')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   STOCK — INDEX
   ═══════════════════════════════════════════════════════════ */

/* ── Stat cards ── */
.stock-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 900px) { .stock-stats { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 500px) { .stock-stats { grid-template-columns: 1fr; } }

/* ── Filters ── */
.stock-filters {
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
.stock-filters .filter-group {
    display: flex; flex-direction: column;
    gap: .3rem; flex: 1; min-width: 140px;
}
.stock-filters label {
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.stock-filters input,
.stock-filters select {
    padding: .55rem .8rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .85rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s; width: 100%;
}
.stock-filters input:focus,
.stock-filters select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}

/* ── Table card ── */
.stock-table-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.stock-table-header {
    padding: 1rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
}
.stock-table-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem; margin: 0;
}
.stock-table-header h3 i { color: var(--purple); }

.stock-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.stock-table thead tr {
    background: #faf7ff;
    border-bottom: 1.5px solid var(--border);
}
.stock-table thead th {
    padding: .75rem 1rem; text-align: left;
    font-size: .71rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase;
    letter-spacing: .06em; white-space: nowrap;
}
.stock-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .15s;
}
.stock-table tbody tr:last-child { border-bottom: none; }
.stock-table tbody tr:hover { background: #faf7ff; }
.stock-table td { padding: .85rem 1rem; vertical-align: middle; }

/* Product cell */
.product-cell { display: flex; align-items: center; gap: .65rem; }
.product-thumb {
    width: 38px; height: 38px; border-radius: 9px;
    object-fit: cover; flex-shrink: 0;
}
.product-thumb-placeholder {
    width: 38px; height: 38px; border-radius: 9px;
    background: linear-gradient(135deg, var(--purple-soft), var(--pink-soft));
    display: flex; align-items: center; justify-content: center;
    color: var(--purple); font-size: .85rem; flex-shrink: 0;
}
.product-name { font-weight: 600; color: var(--text); line-height: 1.3; }
.product-sku  { font-size: .71rem; color: var(--muted); margin-top: .1rem; }

/* Stock level bar */
.stock-level-wrap { min-width: 130px; }
.stock-level-bar {
    height: 6px; border-radius: 10px;
    background: var(--border); overflow: hidden;
    margin-top: .35rem;
}
.stock-level-fill {
    height: 100%; border-radius: 10px;
    transition: width .3s ease;
}
.stock-level-fill.ok      { background: linear-gradient(90deg, var(--green), var(--green-lt)); }
.stock-level-fill.low     { background: linear-gradient(90deg, var(--gold), #fbbf24); }
.stock-level-fill.out     { background: linear-gradient(90deg, var(--tango), var(--pink)); }
.stock-qty {
    font-size: .88rem; font-weight: 700;
    font-variant-numeric: tabular-nums;
}
.stock-qty.ok   { color: var(--green); }
.stock-qty.low  { color: var(--gold); }
.stock-qty.out  { color: var(--tango); }

/* Status badge */
.stock-status-ok  { background: var(--green-soft);  color: #15803d; border: 1px solid #bbf7d0; }
.stock-status-low { background: #fef9c3;             color: #a16207; border: 1px solid #fde68a; }
.stock-status-out { background: var(--pink-soft);    color: var(--pink); border: 1px solid #fecdd3; }

/* Alert threshold pill */
.alert-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 600;
    color: var(--muted); background: #f8f5ff;
    border: 1px solid var(--border);
    border-radius: 20px; padding: .18rem .55rem;
    cursor: pointer; transition: all .15s;
}
.alert-pill:hover { border-color: var(--purple); color: var(--purple); }

/* Action buttons */
.tbl-actions { display: flex; gap: .4rem; align-items: center; }
.tbl-btn {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1.5px solid var(--border); background: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .76rem; color: var(--muted);
    transition: all .15s; text-decoration: none;
}
.tbl-btn:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.tbl-btn.green:hover { border-color: var(--green); color: var(--green); background: var(--green-soft); }
.tbl-btn.tango:hover { border-color: var(--tango); color: var(--tango); background: var(--pink-soft); }

/* Empty */
.stock-empty {
    padding: 3.5rem 1rem; text-align: center; color: var(--muted);
}
.stock-empty i { font-size: 2.5rem; opacity: .15; color: var(--purple); display: block; margin-bottom: .75rem; }

/* Pagination */
.stock-pagination {
    padding: .85rem 1.25rem;
    border-top: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    font-size: .82rem; color: var(--muted);
    background: #faf7ff; flex-wrap: wrap; gap: .5rem;
}

/* Alert modal */
.alert-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,1,20,.55); backdrop-filter: blur(4px);
    z-index: 500; align-items: center; justify-content: center;
}
.alert-modal-overlay.show { display: flex; }
.alert-modal {
    background: #fff; border-radius: 18px;
    padding: 1.75rem; width: 360px; max-width: 95vw;
    box-shadow: 0 20px 60px rgba(124,58,237,.2);
    animation: modalIn .2s ease;
}
@keyframes modalIn { from { opacity:0; transform:scale(.94); } to { opacity:1; transform:scale(1); } }
.alert-modal h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; margin-bottom: 1rem;
    display: flex; align-items: center; gap: .5rem;
}
.alert-modal h3 i { color: var(--gold); }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-warehouse" style="color:var(--purple)"></i> Stock
        </h1>
        <p class="page-sub">Monitor and manage product stock levels</p>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.stock.low') }}" class="btn btn-outline">
            <i class="fas fa-triangle-exclamation" style="color:var(--gold)"></i> Low Stock
        </a>
        <a href="{{ route('admin.stock.damaged') }}" class="btn btn-outline">
            <i class="fas fa-box-archive" style="color:var(--tango)"></i> Damaged
        </a>
        <a href="{{ route('admin.stock.history') }}" class="btn btn-primary">
            <i class="fas fa-clock-rotate-left"></i> History
        </a>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i><div>{{ session('success') }}</div>
    </div>
@endif

{{-- Stat cards --}}
<div class="stock-stats">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-boxes-stacked"></i></div>
        <div class="stat-info">
            <span class="stat-label">Total Products</span>
            <span class="stat-value">{{ number_format($stats['total_products']) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-cubes"></i></div>
        <div class="stat-info">
            <span class="stat-label">Total Units</span>
            <span class="stat-value">{{ number_format($stats['total_units']) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-info">
            <span class="stat-label">Low Stock</span>
            <span class="stat-value">{{ number_format($stats['low_stock']) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-ban"></i></div>
        <div class="stat-info">
            <span class="stat-label">Out of Stock</span>
            <span class="stat-value">{{ number_format($stats['out_of_stock']) }}</span>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.stock.index') }}" class="stock-filters">
    <div class="filter-group">
        <label>Search</label>
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Product name or SKU…">
    </div>
    <div class="filter-group" style="max-width:160px">
        <label>Category</label>
        <select name="category_id">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ request('category_id') == $cat->id ? 'selected':'' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="filter-group" style="max-width:160px">
        <label>Stock Status</label>
        <select name="stock_status">
            <option value="">All</option>
            <option value="ok"  {{ request('stock_status') === 'ok'  ? 'selected':'' }}>In Stock</option>
            <option value="low" {{ request('stock_status') === 'low' ? 'selected':'' }}>Low Stock</option>
            <option value="out" {{ request('stock_status') === 'out' ? 'selected':'' }}>Out of Stock</option>
        </select>
    </div>
    <div style="display:flex;gap:.5rem;align-items:flex-end;padding-bottom:1px">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request()->hasAny(['search','category_id','stock_status']))
            <a href="{{ route('admin.stock.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-xmark"></i> Clear
            </a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="stock-table-card">
    <div class="stock-table-header">
        <h3>
            <i class="fas fa-list"></i> All Products
            <span style="font-size:.75rem;font-weight:600;color:var(--muted);font-family:inherit">
                ({{ $products->total() }})
            </span>
        </h3>
        <span style="font-size:.78rem;color:var(--muted)">
            Sorted by lowest stock first
        </span>
    </div>

    <div style="overflow-x:auto">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Stock Level</th>
                    <th style="text-align:center">Status</th>
                    <th>Alert At</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                @php
                    $qty       = $product->stock_quantity;
                    $threshold = $product->stockAlert?->low_stock_threshold ?? 10;
                    $pct       = $qty <= 0 ? 0 : min(100, ($qty / max($threshold * 3, 1)) * 100);
                    $status    = $qty <= 0 ? 'out' : ($qty <= $threshold ? 'low' : 'ok');
                @endphp
                <tr>
                    {{-- Product --}}
                    <td>
                        <div class="product-cell">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/'.$product->thumbnail) }}"
                                     alt="{{ $product->name }}" class="product-thumb">
                            @else
                                <div class="product-thumb-placeholder">
                                    <i class="fas fa-spa"></i>
                                </div>
                            @endif
                            <div>
                                <div class="product-name">{{ $product->name }}</div>
                                @if($product->sku)
                                    <div class="product-sku">SKU: {{ $product->sku }}</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td>
                        <span style="font-size:.8rem;color:var(--muted)">
                            {{ $product->category?->name ?? '—' }}
                        </span>
                    </td>

                    {{-- Stock level bar --}}
                    <td>
                        <div class="stock-level-wrap">
                            <span class="stock-qty {{ $status }}">
                                {{ number_format($qty) }} units
                            </span>
                            <div class="stock-level-bar">
                                <div class="stock-level-fill {{ $status }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </td>

                    {{-- Status badge --}}
                    <td style="text-align:center">
                        <span class="badge stock-status-{{ $status }}">
                            @if($status === 'ok')
                                <i class="fas fa-circle-check"></i> In Stock
                            @elseif($status === 'low')
                                <i class="fas fa-triangle-exclamation"></i> Low
                            @else
                                <i class="fas fa-ban"></i> Out
                            @endif
                        </span>
                    </td>

                    {{-- Alert threshold --}}
                    <td>
                        <span class="alert-pill"
                              onclick="openAlertModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $threshold }})">
                            <i class="fas fa-bell"></i>
                            ≤ {{ $threshold }}
                        </span>
                    </td>

                    {{-- Last updated --}}
                    <td style="color:var(--muted);font-size:.8rem;white-space:nowrap">
                        {{ $product->updated_at->diffForHumans() }}
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="tbl-actions">
                            <a href="{{ route('admin.stock.adjust', $product->id) }}"
                               class="tbl-btn green" title="Adjust Stock">
                                <i class="fas fa-sliders"></i>
                            </a>
                            <a href="{{ route('admin.stock.history', ['product_id' => $product->id]) }}"
                               class="tbl-btn" title="View History">
                                <i class="fas fa-clock-rotate-left"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="tbl-btn" title="Edit Product">
                                <i class="fas fa-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="stock-empty">
                            <i class="fas fa-warehouse"></i>
                            <p>No products found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="stock-pagination">
        <span>
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }}
            of {{ $products->total() }} products
        </span>
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Alert threshold modal --}}
<div class="alert-modal-overlay" id="alertModal">
    <div class="alert-modal">
        <h3><i class="fas fa-bell"></i> Set Low Stock Alert</h3>
        <p style="font-size:.83rem;color:var(--muted);margin-bottom:1rem">
            Alert when stock drops to or below this number for
            <strong id="alertProductName"></strong>.
        </p>
        <form method="POST" id="alertForm">
            @csrf
            <div class="pf-field" style="margin-bottom:1rem">
                <label style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.35rem">
                    Alert Threshold
                </label>
                <input type="number" name="low_stock_threshold" id="alertThreshold"
                       min="1" value="10"
                       style="width:100%;padding:.62rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;outline:none;transition:border-color .18s"
                       onfocus="this.style.borderColor='var(--purple)'"
                       onblur="this.style.borderColor='var(--border)'">
            </div>
            <div style="display:flex;gap:.65rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-floppy-disk"></i> Save
                </button>
                <button type="button" class="btn btn-outline" onclick="closeAlertModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openAlertModal(productId, productName, threshold) {
    document.getElementById('alertProductName').textContent = productName;
    document.getElementById('alertThreshold').value = threshold;
    document.getElementById('alertForm').action =
        '/admin/stock/' + productId + '/alert';
    document.getElementById('alertModal').classList.add('show');
}
function closeAlertModal() {
    document.getElementById('alertModal').classList.remove('show');
}
document.getElementById('alertModal').addEventListener('click', function(e) {
    if (e.target === this) closeAlertModal();
});
</script>
@endpush