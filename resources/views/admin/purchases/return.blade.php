@extends('layouts.admin')

@section('title', 'Return — ' . $purchase->invoice_no)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PURCHASE — RETURN
   ═══════════════════════════════════════════════════════════ */
.return-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 860px) { .return-grid { grid-template-columns: 1fr; } }

.pf-card {
    background: #fff; border: 1.5px solid var(--border);
    border-radius: var(--r); box-shadow: var(--shadow);
    overflow: hidden; margin-bottom: 1.25rem;
}
.pf-card:last-child { margin-bottom: 0; }
.pf-card-header {
    padding: .9rem 1.25rem; border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--pink-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
}
.pf-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.pf-card-header h3 i { color: var(--tango); }
.pf-card-body { padding: 1.25rem; }

/* Return warning banner */
.return-warning {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .85rem 1rem;
    background: #fffbeb; border: 1.5px solid #fde68a;
    border-radius: var(--r-sm);
    font-size: .82rem; color: #92400e; margin-bottom: 1.25rem;
    line-height: 1.5;
}
.return-warning i { font-size: 1rem; flex-shrink: 0; margin-top: .1rem; color: #f59e0b; }

/* Return items table */
.return-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.return-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.return-table thead th {
    padding: .65rem .9rem; text-align: left;
    font-size: .7rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .06em; white-space: nowrap;
}
.return-table tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
.return-table tbody tr:hover { background: #faf7ff; }
.return-table td { padding: .8rem .9rem; vertical-align: middle; }

/* Checkbox toggle row */
.return-check {
    width: 18px; height: 18px;
    cursor: pointer; accent-color: var(--tango);
}
.return-table tbody tr.selected-row { background: #fff7ed; }
.return-table tbody tr.selected-row td:first-child { border-left: 3px solid var(--tango); }

.ret-qty-input {
    padding: .45rem .65rem; border: 1.5px solid var(--border);
    border-radius: var(--r-sm); font-size: .84rem; font-family: inherit;
    outline: none; width: 80px; text-align: right;
    background: #fff; color: var(--text); transition: border-color .18s;
    font-variant-numeric: tabular-nums;
}
.ret-qty-input:focus { border-color: var(--tango); box-shadow: 0 0 0 3px rgba(247,37,133,.08); }
.ret-qty-input:disabled { background: #f1f5f9; color: var(--muted); cursor: not-allowed; }

.ret-reason-input {
    padding: .45rem .75rem; border: 1.5px solid var(--border);
    border-radius: var(--r-sm); font-size: .82rem; font-family: inherit;
    outline: none; width: 100%; min-width: 160px;
    background: #fff; color: var(--text); transition: border-color .18s;
}
.ret-reason-input:focus { border-color: var(--tango); box-shadow: 0 0 0 3px rgba(247,37,133,.08); }
.ret-reason-input:disabled { background: #f1f5f9; color: var(--muted); cursor: not-allowed; }

/* Sidebar summary */
.ret-summary-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .55rem 0; border-bottom: 1px solid var(--border);
    font-size: .84rem; color: var(--muted);
}
.ret-summary-row:last-of-type { border-bottom: none; }

/* Submit button */
.ret-submit-btn {
    width: 100%; padding: .9rem 1rem;
    background: linear-gradient(135deg, #f59e0b 0%, var(--tango) 100%);
    color: #fff; border: none; border-radius: 12px;
    font-size: .95rem; font-weight: 700; cursor: pointer; font-family: inherit;
    box-shadow: 0 6px 20px rgba(247,37,133,.3); transition: all .2s;
    display: flex; align-items: center; justify-content: center;
    gap: .55rem; letter-spacing: .02em; margin-top: 1rem;
}
.ret-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(247,37,133,.42); }
.ret-submit-btn:disabled {
    opacity: .4; cursor: not-allowed;
    transform: none; box-shadow: none;
    background: #e2d6f5;
}
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.25rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-rotate-left" style="color:var(--tango)"></i>
            Process Return
        </h1>
        <p class="page-sub">{{ $purchase->invoice_no }} · {{ $purchase->supplier->name }}</p>
    </div>
    <a href="{{ route('admin.purchase.show', $purchase->id) }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

{{-- Warning --}}
<div class="return-warning">
    <i class="fas fa-triangle-exclamation"></i>
    <div>
        <strong>Stock will be deducted automatically.</strong>
        Selecting a product and submitting this form will reduce its stock quantity.
        This action cannot be undone — double-check quantities before submitting.
    </div>
</div>

<form method="POST"
      action="{{ route('admin.purchase.return.store', $purchase->id) }}"
      id="returnForm">
@csrf

<div class="return-grid">

    {{-- ════ LEFT ════ --}}
    <div>
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-boxes-stacked"></i> Select Items to Return</h3>
                <span style="font-size:.78rem;color:var(--muted);font-weight:600">
                    {{ $purchase->items->count() }} {{ Str::plural('item', $purchase->items->count()) }}
                </span>
            </div>

            @error('returns')
                <div class="flash flash-error" style="margin:.75rem 1.25rem 0">
                    <i class="fas fa-circle-exclamation"></i><div>{{ $message }}</div>
                </div>
            @enderror

            <div style="overflow-x:auto">
                <table class="return-table">
                    <thead>
                        <tr>
                            <th style="width:40px"></th>
                            <th>Product</th>
                            <th style="text-align:center">Purchased</th>
                            <th style="text-align:center">Return Qty</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->items as $index => $item)
                        <tr id="ret-row-{{ $index }}">
                            {{-- Checkbox --}}
                            <td>
                                <input type="checkbox"
                                       class="return-check"
                                       id="chk-{{ $index }}"
                                       onchange="toggleRow({{ $index }}, {{ $item->product_id }}, {{ $item->quantity }})">
                            </td>

                            {{-- Product name --}}
                            <td>
                                <div style="font-weight:600;color:var(--text)">
                                    {{ $item->product->name }}
                                </div>
                                <div style="font-size:.72rem;color:var(--muted);margin-top:.15rem">
                                    Unit cost: KSh {{ number_format($item->unit_cost, 2) }}
                                </div>
                            </td>

                            {{-- Purchased qty --}}
                            <td style="text-align:center">
                                <span style="
                                    background:var(--purple-soft);color:var(--purple);
                                    font-weight:700;font-size:.78rem;
                                    padding:.2rem .6rem;border-radius:20px">
                                    {{ $item->quantity }}
                                </span>
                            </td>

                            {{-- Return qty --}}
                            <td>
                                <input type="number"
                                       class="ret-qty-input"
                                       name="returns[{{ $index }}][quantity]"
                                       id="ret-qty-{{ $index }}"
                                       value="1"
                                       min="1"
                                       max="{{ $item->quantity }}"
                                       disabled
                                       oninput="updateSummary()">
                                <input type="hidden"
                                       name="returns[{{ $index }}][product_id]"
                                       id="ret-pid-{{ $index }}"
                                       value=""
                                       disabled>
                            </td>

                            {{-- Reason --}}
                            <td>
                                <input type="text"
                                       class="ret-reason-input"
                                       name="returns[{{ $index }}][reason]"
                                       id="ret-reason-{{ $index }}"
                                       placeholder="Reason (optional)"
                                       disabled>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>{{-- end left --}}

    {{-- ════ RIGHT ════ --}}
    <div>

        {{-- Invoice reference --}}
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-file-invoice"></i> Purchase Ref</h3>
            </div>
            <div class="pf-card-body">
                <div class="ret-summary-row">
                    <span>Invoice</span>
                    <span style="font-weight:700;color:var(--purple)">{{ $purchase->invoice_no }}</span>
                </div>
                <div class="ret-summary-row">
                    <span>Supplier</span>
                    <span style="font-weight:600">{{ $purchase->supplier->name }}</span>
                </div>
                <div class="ret-summary-row">
                    <span>Total Items</span>
                    <span style="font-weight:600">{{ $purchase->items->count() }}</span>
                </div>
                <div class="ret-summary-row">
                    <span>Purchase Date</span>
                    <span style="color:var(--muted)">{{ $purchase->purchase_date->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Return summary --}}
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-calculator"></i> Return Summary</h3>
            </div>
            <div class="pf-card-body">

                <div class="ret-summary-row">
                    <span>Items Selected</span>
                    <span style="font-weight:700;color:var(--tango)" id="retSummaryItems">0</span>
                </div>
                <div class="ret-summary-row">
                    <span>Total Qty Returning</span>
                    <span style="font-weight:700;color:var(--tango)" id="retSummaryQty">0</span>
                </div>

                <button type="submit"
                        class="ret-submit-btn"
                        id="retSubmitBtn"
                        disabled
                        onclick="return confirm('Process this return? Stock will be reduced.')">
                    <i class="fas fa-rotate-left"></i> Submit Return
                </button>

                <a href="{{ route('admin.purchase.show', $purchase->id) }}"
                   class="btn btn-outline"
                   style="width:100%;margin-top:.65rem;text-align:center;display:block">
                    Cancel
                </a>

            </div>
        </div>

    </div>{{-- end right --}}

</div>
</form>

@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════
   RETURN FORM — JS
   ═══════════════════════════════════════════════════════ */

// Product IDs mapped by row index (from Blade)
const PRODUCT_IDS = {
    @foreach($purchase->items as $index => $item)
        {{ $index }}: {{ $item->product_id }},
    @endforeach
};

function toggleRow(idx, productId, maxQty) {
    const chk     = document.getElementById('chk-' + idx);
    const row     = document.getElementById('ret-row-' + idx);
    const qtyInp  = document.getElementById('ret-qty-' + idx);
    const pidInp  = document.getElementById('ret-pid-' + idx);
    const reason  = document.getElementById('ret-reason-' + idx);
    const checked = chk.checked;

    // Toggle disabled
    qtyInp.disabled  = !checked;
    pidInp.disabled  = !checked;
    reason.disabled  = !checked;

    // Set / clear hidden product_id value
    pidInp.value = checked ? productId : '';

    // Visual highlight
    row.classList.toggle('selected-row', checked);

    updateSummary();
}

function updateSummary() {
    let items = 0;
    let qty   = 0;

    document.querySelectorAll('.return-check').forEach((chk, idx) => {
        if (chk.checked) {
            items++;
            qty += parseInt(document.getElementById('ret-qty-' + idx)?.value) || 0;
        }
    });

    document.getElementById('retSummaryItems').textContent = items;
    document.getElementById('retSummaryQty').textContent   = qty;
    document.getElementById('retSubmitBtn').disabled       = items === 0;
}
</script>
@endpush