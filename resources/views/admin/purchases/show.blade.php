@extends('layouts.admin')

@section('title', 'Purchase ' . $purchase->invoice_no)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PURCHASE — SHOW
   ═══════════════════════════════════════════════════════════ */

.show-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 960px) { .show-grid { grid-template-columns: 1fr; } }

/* ── Cards ── */
.ps-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.ps-card:last-child { margin-bottom: 0; }
.ps-card-header {
    padding: .9rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ps-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.ps-card-header h3 i { color: var(--purple); }
.ps-card-body { padding: 1.25rem; }

/* ── Invoice hero banner ── */
.invoice-hero {
    background: linear-gradient(135deg, #5B21B6 0%, #7C3AED 55%, #F72585 100%);
    border-radius: var(--r);
    padding: 1.5rem 1.75rem;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
    box-shadow: 0 6px 24px rgba(124,58,237,.35);
    flex-wrap: wrap;
    gap: 1rem;
}
.invoice-hero-left h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 .25rem;
    letter-spacing: .02em;
}
.invoice-hero-left p {
    font-size: .82rem;
    opacity: .8;
    margin: 0;
}
.invoice-hero-right { text-align: right; }
.invoice-hero-right .hero-status {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    opacity: .8;
    margin-bottom: .3rem;
}
.invoice-hero-right .hero-amount {
    font-size: 1.75rem;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
    letter-spacing: -.01em;
}

/* ── Meta grid ── */
.meta-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
@media (max-width: 700px) { .meta-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 440px) { .meta-grid { grid-template-columns: 1fr; } }

.meta-item { display: flex; flex-direction: column; gap: .25rem; }
.meta-label {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--muted);
}
.meta-value {
    font-size: .88rem;
    font-weight: 600;
    color: var(--text);
}

/* ── Items table ── */
.items-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .84rem;
}
.items-table thead tr {
    background: #faf7ff;
    border-bottom: 1.5px solid var(--border);
}
.items-table thead th {
    padding: .7rem 1rem;
    text-align: left;
    font-size: .7rem;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
}
.items-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .12s;
}
.items-table tbody tr:last-child { border-bottom: none; }
.items-table tbody tr:hover { background: #faf7ff; }
.items-table td { padding: .85rem 1rem; vertical-align: middle; }
.items-table tfoot tr { background: #faf7ff; border-top: 2px solid var(--border); }
.items-table tfoot td {
    padding: .8rem 1rem;
    font-weight: 700;
    font-size: .9rem;
    color: var(--text);
}

.product-cell { display: flex; align-items: center; gap: .65rem; }
.product-thumb {
    width: 36px; height: 36px;
    border-radius: 9px;
    object-fit: cover;
    background: var(--purple-soft);
    flex-shrink: 0;
}
.product-thumb-placeholder {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--purple-soft), var(--pink-soft));
    display: flex; align-items: center; justify-content: center;
    color: var(--purple); font-size: .85rem;
    flex-shrink: 0;
}
.product-name { font-weight: 600; color: var(--text); line-height: 1.3; }

/* ── Payment timeline ── */
.timeline { display: flex; flex-direction: column; gap: 0; }
.timeline-item {
    display: flex;
    gap: 1rem;
    position: relative;
    padding-bottom: 1.25rem;
}
.timeline-item:last-child { padding-bottom: 0; }
.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 32px;
    bottom: 0;
    width: 2px;
    background: var(--border);
}
.timeline-dot {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.timeline-dot.purple { background: var(--purple-soft); color: var(--purple); border: 2px solid var(--purple); }
.timeline-dot.green  { background: var(--green-soft);  color: var(--green);  border: 2px solid var(--green); }
.timeline-dot.tango  { background: var(--pink-soft);   color: var(--tango);  border: 2px solid var(--tango); }
.timeline-dot.muted  { background: #f1f5f9; color: var(--muted); border: 2px solid var(--border); }

.timeline-content { flex: 1; padding-top: .3rem; }
.timeline-title {
    font-size: .84rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: .15rem;
}
.timeline-time {
    font-size: .72rem;
    color: var(--muted);
}

/* ── Returns table ── */
.returns-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .83rem;
}
.returns-table thead th {
    padding: .6rem .9rem;
    text-align: left;
    font-size: .7rem;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    background: #faf7ff;
    border-bottom: 1.5px solid var(--border);
}
.returns-table tbody td {
    padding: .75rem .9rem;
    border-bottom: 1px solid var(--border);
    color: var(--text);
}
.returns-table tbody tr:last-child td { border-bottom: none; }

/* ── Sidebar summary rows ── */
.sidebar-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .6rem 0;
    border-bottom: 1px solid var(--border);
    font-size: .84rem;
}
.sidebar-row:last-of-type { border-bottom: none; }
.sidebar-row.grand {
    font-size: .95rem;
    font-weight: 700;
    color: var(--text);
    padding-top: .75rem;
    border-top: 2px solid var(--border);
    margin-top: .25rem;
    border-bottom: none;
}
.sidebar-row.grand span:last-child { color: var(--pink); }

/* Action buttons */
.action-stack {
    display: flex;
    flex-direction: column;
    gap: .6rem;
}
</style>
@endpush

@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i><div>{{ session('success') }}</div>
    </div>
@endif

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.25rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-file-invoice" style="color:var(--purple)"></i>
            Purchase Details
        </h1>
        <p class="page-sub">{{ $purchase->invoice_no }} · {{ $purchase->supplier->name }}</p>
    </div>
    <a href="{{ route('admin.purchase.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

{{-- ── Invoice hero ── --}}
<div class="invoice-hero">
    <div class="invoice-hero-left">
        <h2>{{ $purchase->invoice_no }}</h2>
        <p>
            <i class="fas fa-building"></i> {{ $purchase->supplier->name }}
            &nbsp;·&nbsp;
            <i class="fas fa-calendar-days"></i> {{ $purchase->purchase_date->format('d M Y, H:i') }}
            &nbsp;·&nbsp;
            <i class="fas fa-user"></i> {{ $purchase->createdBy->name ?? 'N/A' }}
        </p>
    </div>
    <div class="invoice-hero-right">
        <div class="hero-status">
            @if($purchase->payment_status === 'paid')
                ✅ Fully Paid
            @elseif($purchase->payment_status === 'partial')
                ⏳ Partially Paid
            @else
                ❌ Unpaid
            @endif
        </div>
        <div class="hero-amount">KSh {{ number_format($purchase->total_amount, 2) }}</div>
    </div>
</div>

<div class="show-grid">

    {{-- ════ LEFT ════ --}}
    <div>

        {{-- Purchase meta --}}
        <div class="ps-card">
            <div class="ps-card-header">
                <h3><i class="fas fa-circle-info"></i> Purchase Info</h3>
                <span class="badge badge-{{ $purchase->payment_status === 'paid' ? 'paid' : ($purchase->payment_status === 'partial' ? 'partial' : 'unpaid') }}">
                    @if($purchase->payment_status === 'paid')
                        <i class="fas fa-circle-check"></i> Paid
                    @elseif($purchase->payment_status === 'partial')
                        <i class="fas fa-circle-half-stroke"></i> Partial
                    @else
                        <i class="fas fa-clock"></i> Unpaid
                    @endif
                </span>
            </div>
            <div class="ps-card-body">
                <div class="meta-grid">
                    <div class="meta-item">
                        <span class="meta-label">Invoice No</span>
                        <span class="meta-value" style="color:var(--purple)">{{ $purchase->invoice_no }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Supplier</span>
                        <span class="meta-value">{{ $purchase->supplier->name }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Purchase Date</span>
                        <span class="meta-value">{{ $purchase->purchase_date->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Payment Time</span>
                        <span class="meta-value">
                            {{ $purchase->payment_time ? $purchase->payment_time->format('d M Y, H:i') : '—' }}
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Recorded By</span>
                        <span class="meta-value">{{ $purchase->createdBy->name ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Created At</span>
                        <span class="meta-value">{{ $purchase->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($purchase->notes)
                    <div class="meta-item" style="grid-column: 1 / -1">
                        <span class="meta-label">Notes</span>
                        <span class="meta-value" style="font-weight:400;color:var(--muted)">{{ $purchase->notes }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Items table --}}
        <div class="ps-card">
            <div class="ps-card-header">
                <h3><i class="fas fa-boxes-stacked"></i> Items Purchased</h3>
                <span style="font-size:.78rem;color:var(--muted);font-weight:600">
                    {{ $purchase->items->count() }} {{ Str::plural('product', $purchase->items->count()) }}
                </span>
            </div>
            <div style="overflow-x:auto">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:right">Unit Cost</th>
                            <th style="text-align:right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->items as $item)
                        <tr>
                            <td>
                                <div class="product-cell">
                                    @if($item->product->thumbnail ?? false)
                                        <img src="{{ asset('storage/'.$item->product->thumbnail) }}"
                                             alt="{{ $item->product->name }}"
                                             class="product-thumb">
                                    @else
                                        <div class="product-thumb-placeholder">
                                            <i class="fas fa-spa"></i>
                                        </div>
                                    @endif
                                    <span class="product-name">{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td style="text-align:center">
                                <span style="
                                    background:var(--purple-soft);color:var(--purple);
                                    font-weight:700;font-size:.78rem;
                                    padding:.2rem .6rem;border-radius:20px">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td style="text-align:right;font-variant-numeric:tabular-nums;color:var(--muted)">
                                KSh {{ number_format($item->unit_cost, 2) }}
                            </td>
                            <td style="text-align:right;font-weight:700;font-variant-numeric:tabular-nums;color:var(--purple)">
                                KSh {{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="color:var(--muted);font-size:.8rem">
                                {{ $purchase->items->sum('quantity') }} units total
                            </td>
                            <td style="text-align:right;color:var(--muted)">Total</td>
                            <td style="text-align:right;color:var(--pink);font-size:.95rem">
                                KSh {{ number_format($purchase->total_amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Returns --}}
        @if($purchase->returns->count())
        <div class="ps-card">
            <div class="ps-card-header">
                <h3><i class="fas fa-rotate-left"></i> Returns</h3>
                <span style="font-size:.78rem;color:var(--tango);font-weight:600">
                    {{ $purchase->returns->count() }} {{ Str::plural('return', $purchase->returns->count()) }}
                </span>
            </div>
            <div style="overflow-x:auto">
                <table class="returns-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Reason</th>
                            <th>Returned At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->returns as $return)
                        <tr>
                            <td style="font-weight:600">{{ $return->product->name }}</td>
                            <td>
                                <span style="
                                    background:var(--pink-soft);color:var(--pink);
                                    font-weight:700;font-size:.78rem;
                                    padding:.2rem .55rem;border-radius:20px">
                                    {{ $return->quantity }}
                                </span>
                            </td>
                            <td style="color:var(--muted)">{{ $return->reason ?? '—' }}</td>
                            <td style="color:var(--muted);white-space:nowrap">
                                {{ $return->returned_at->format('d M Y, H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>{{-- end left --}}

    {{-- ════ RIGHT ════ --}}
    <div>

        {{-- Payment summary --}}
        <div class="ps-card">
            <div class="ps-card-header">
                <h3><i class="fas fa-wallet"></i> Payment Summary</h3>
            </div>
            <div class="ps-card-body">
                <div class="sidebar-row">
                    <span style="color:var(--muted)">Total Amount</span>
                    <span style="font-weight:600;font-variant-numeric:tabular-nums">
                        KSh {{ number_format($purchase->total_amount, 2) }}
                    </span>
                </div>
                <div class="sidebar-row">
                    <span style="color:var(--muted)">Amount Paid</span>
                    <span style="font-weight:600;color:var(--green);font-variant-numeric:tabular-nums">
                        KSh {{ number_format($purchase->paid_amount, 2) }}
                    </span>
                </div>
                <div class="sidebar-row">
                    <span style="color:var(--muted)">Balance</span>
                    @php $balance = $purchase->total_amount - $purchase->paid_amount; @endphp
                    <span style="font-weight:600;color:{{ $balance > 0 ? 'var(--tango)' : 'var(--green)' }};font-variant-numeric:tabular-nums">
                        KSh {{ number_format($balance, 2) }}
                    </span>
                </div>
                <div class="sidebar-row grand">
                    <span>Status</span>
                    <span class="badge badge-{{ $purchase->payment_status === 'paid' ? 'paid' : ($purchase->payment_status === 'partial' ? 'partial' : 'unpaid') }}">
                        {{ ucfirst($purchase->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Payment timeline --}}
        <div class="ps-card">
            <div class="ps-card-header">
                <h3><i class="fas fa-timeline"></i> Timeline</h3>
            </div>
            <div class="ps-card-body">
                <div class="timeline">

                    <div class="timeline-item">
                        <div class="timeline-dot purple">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Purchase Recorded</div>
                            <div class="timeline-time">
                                {{ $purchase->created_at->format('d M Y, H:i') }}
                                · by {{ $purchase->createdBy->name ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot purple">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Purchase Date</div>
                            <div class="timeline-time">
                                {{ $purchase->purchase_date->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    @if($purchase->payment_time)
                    <div class="timeline-item">
                        <div class="timeline-dot green">
                            <i class="fas fa-circle-check"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Payment Made</div>
                            <div class="timeline-time">
                                {{ $purchase->payment_time->format('d M Y, H:i') }}
                                · KSh {{ number_format($purchase->paid_amount, 2) }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="timeline-item">
                        <div class="timeline-dot muted">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title" style="color:var(--muted)">Payment Pending</div>
                            <div class="timeline-time">
                                Balance: KSh {{ number_format($balance, 2) }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($purchase->returns->count())
                    <div class="timeline-item">
                        <div class="timeline-dot tango">
                            <i class="fas fa-rotate-left"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">
                                {{ $purchase->returns->count() }} Return(s) Processed
                            </div>
                            <div class="timeline-time">
                                Latest: {{ $purchase->returns->last()->returned_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="ps-card">
            <div class="ps-card-header">
                <h3><i class="fas fa-bolt"></i> Actions</h3>
            </div>
            <div class="ps-card-body">
                <div class="action-stack">

                    <a href="{{ route('admin.purchase.edit', $purchase->id) }}"
                       class="btn btn-primary" style="width:100%;justify-content:center">
                        <i class="fas fa-pencil"></i> Edit Purchase
                    </a>

                    @if($purchase->payment_status !== 'paid')
                    <form method="POST" action="{{ route('admin.purchase.update', $purchase->id) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="payment_status" value="paid">
                        <input type="hidden" name="paid_amount" value="{{ $purchase->total_amount }}">
                        <input type="hidden" name="payment_time" value="{{ now()->format('Y-m-d\TH:i') }}">
                        <input type="hidden" name="notes" value="{{ $purchase->notes }}">
                        <button type="submit" class="btn btn-outline"
                                style="width:100%;justify-content:center;border-color:var(--green);color:var(--green)"
                                onclick="return confirm('Mark this purchase as fully paid?')">
                            <i class="fas fa-circle-check"></i> Mark as Paid
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.purchase.return.form', $purchase->id) }}"
                       class="btn btn-outline"
                       style="width:100%;justify-content:center;border-color:var(--tango);color:var(--tango)">
                        <i class="fas fa-rotate-left"></i> Process Return
                    </a>

                    <form method="POST"
                          action="{{ route('admin.purchase.destroy', $purchase->id) }}"
                          onsubmit="return confirm('Delete this purchase? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline"
                                style="width:100%;justify-content:center;border-color:var(--tango);color:var(--tango)">
                            <i class="fas fa-trash"></i> Delete Purchase
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>{{-- end right --}}

</div>

@endsection