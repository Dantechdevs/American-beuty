@extends('layouts.admin')

@section('title', 'Low Stock Alerts')

@push('styles')
<style>
.low-stock-table th { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); }
.stock-bar-wrap { background: var(--border); border-radius: 20px; height: 6px; width: 100px; overflow: hidden; }
.stock-bar { height: 100%; border-radius: 20px; }
.badge-danger  { background: var(--pink-soft);   color: var(--tango);  font-size:.7rem; padding:.2rem .55rem; border-radius:20px; font-weight:700; }
.badge-warning { background: #fef9c3;             color: #a16207;       font-size:.7rem; padding:.2rem .55rem; border-radius:20px; font-weight:700; }
</style>
@endpush

@section('content')

<div class="page-header" style="margin-bottom:1.25rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-triangle-exclamation" style="color:var(--tango)"></i> Low Stock Alerts
        </h1>
        <p class="page-sub">Products that have fallen below their minimum stock threshold</p>
    </div>
    <a href="{{ route('admin.stock.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Stock
    </a>
</div>

{{-- Summary cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.25rem">
    <div class="sa-card" style="margin-bottom:0">
        <div class="sa-card-body" style="text-align:center;padding:1rem">
            <div style="font-size:2rem;font-weight:800;color:var(--tango)">{{ $outOfStock->count() }}</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:.2rem">Out of Stock</div>
        </div>
    </div>
    <div class="sa-card" style="margin-bottom:0">
        <div class="sa-card-body" style="text-align:center;padding:1rem">
            <div style="font-size:2rem;font-weight:800;color:var(--gold)">{{ $lowStock->count() }}</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:.2rem">Low Stock</div>
        </div>
    </div>
    <div class="sa-card" style="margin-bottom:0">
        <div class="sa-card-body" style="text-align:center;padding:1rem">
            <div style="font-size:2rem;font-weight:800;color:var(--purple)">{{ $outOfStock->count() + $lowStock->count() }}</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:.2rem">Total Alerts</div>
        </div>
    </div>
</div>

{{-- Out of Stock --}}
@if($outOfStock->isNotEmpty())
<div class="sa-card">
    <div class="sa-card-header">
        <h3><i class="fas fa-circle-xmark" style="color:var(--tango)"></i> Out of Stock</h3>
        <span class="badge-danger">{{ $outOfStock->count() }} products</span>
    </div>
    <div style="overflow-x:auto">
        <table class="table low-stock-table" style="margin:0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($outOfStock as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/'.$product->thumbnail) }}"
                                     style="width:34px;height:34px;border-radius:8px;object-fit:cover">
                            @else
                                <div style="width:34px;height:34px;border-radius:8px;background:var(--purple-soft);display:flex;align-items:center;justify-content:center;color:var(--purple);font-size:.8rem">
                                    <i class="fas fa-spa"></i>
                                </div>
                            @endif
                            <span style="font-weight:600;font-size:.85rem">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:.8rem;color:var(--muted)">{{ $product->sku ?? '—' }}</td>
                    <td style="font-size:.8rem">{{ $product->category?->name ?? 'Uncategorised' }}</td>
                    <td>
                        <span class="badge-danger">0 units</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.stock.adjust', $product->id) }}"
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Restock
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Low Stock --}}
@if($lowStock->isNotEmpty())
<div class="sa-card" style="margin-top:1.25rem">
    <div class="sa-card-header">
        <h3><i class="fas fa-triangle-exclamation" style="color:var(--gold)"></i> Low Stock</h3>
        <span class="badge-warning">{{ $lowStock->count() }} products</span>
    </div>
    <div style="overflow-x:auto">
        <table class="table low-stock-table" style="margin:0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Min. Threshold</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStock as $product)
                @php
                    $threshold = $product->low_stock_threshold ?? 10;
                    $pct = $threshold > 0 ? min(100, ($product->stock_quantity / $threshold) * 100) : 0;
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/'.$product->thumbnail) }}"
                                     style="width:34px;height:34px;border-radius:8px;object-fit:cover">
                            @else
                                <div style="width:34px;height:34px;border-radius:8px;background:var(--purple-soft);display:flex;align-items:center;justify-content:center;color:var(--purple);font-size:.8rem">
                                    <i class="fas fa-spa"></i>
                                </div>
                            @endif
                            <span style="font-weight:600;font-size:.85rem">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:.8rem;color:var(--muted)">{{ $product->sku ?? '—' }}</td>
                    <td style="font-size:.8rem">{{ $product->category?->name ?? 'Uncategorised' }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.6rem">
                            <span class="badge-warning">{{ $product->stock_quantity }} units</span>
                            <div class="stock-bar-wrap">
                                <div class="stock-bar"
                                     style="width:{{ $pct }}%;background:var(--gold)"></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.8rem;color:var(--muted)">{{ $threshold }} units</td>
                    <td>
                        <a href="{{ route('admin.stock.adjust', $product->id) }}"
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Restock
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- All clear --}}
@if($outOfStock->isEmpty() && $lowStock->isEmpty())
<div class="sa-card">
    <div class="sa-card-body" style="text-align:center;padding:3rem">
        <i class="fas fa-circle-check" style="font-size:2.5rem;color:var(--green);opacity:.6;display:block;margin-bottom:.75rem"></i>
        <div style="font-weight:700;font-size:1rem;margin-bottom:.3rem">All stock levels are healthy</div>
        <div style="color:var(--muted);font-size:.85rem">No products are currently below their minimum threshold.</div>
    </div>
</div>
@endif

@endsection