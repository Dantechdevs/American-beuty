@extends('layouts.admin')

@section('title', 'Edit ' . $purchase->invoice_no)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PURCHASE — EDIT  (reuses create styles, adds edit-only)
   ═══════════════════════════════════════════════════════════ */
.purchase-form-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 960px) { .purchase-form-grid { grid-template-columns: 1fr; } }

.pf-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.pf-card:last-child { margin-bottom: 0; }
.pf-card-header {
    padding: .9rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
}
.pf-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.pf-card-header h3 i { color: var(--purple); }
.pf-card-body { padding: 1.25rem; }

.pf-row { display: grid; gap: 1rem; margin-bottom: 1rem; }
.pf-row.cols-2 { grid-template-columns: 1fr 1fr; }
.pf-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
@media (max-width:600px) { .pf-row.cols-2, .pf-row.cols-3 { grid-template-columns: 1fr; } }
.pf-row:last-child { margin-bottom: 0; }

.pf-field { display: flex; flex-direction: column; gap: .35rem; }
.pf-label {
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.pf-label span { color: var(--pink); margin-left: .15rem; }
.pf-input, .pf-select, .pf-textarea {
    padding: .62rem .9rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .87rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s, box-shadow .18s; width: 100%;
}
.pf-input:focus, .pf-select:focus, .pf-textarea:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.pf-input.is-error, .pf-select.is-error { border-color: var(--tango); }
.pf-input:disabled, .pf-select:disabled {
    background: #f8f7ff; color: var(--muted); cursor: not-allowed;
}
.pf-error-msg { font-size:.72rem; color:var(--tango); display:flex; align-items:center; gap:.3rem; }
.pf-textarea { resize: vertical; min-height: 80px; }

/* Locked invoice banner */
.locked-notice {
    display: flex; align-items: center; gap: .65rem;
    padding: .65rem .9rem;
    background: #faf7ff;
    border: 1.5px solid var(--purple-soft);
    border-radius: var(--r-sm);
    font-size: .8rem; color: var(--purple); font-weight: 600;
    margin-bottom: 1rem;
}
.locked-notice i { font-size: .9rem; flex-shrink: 0; }

/* Payment status toggle */
.pay-status-group { display: grid; grid-template-columns: repeat(3,1fr); gap: .5rem; }
.pay-status-btn {
    padding: .6rem .5rem; border: 1.5px solid var(--border);
    border-radius: var(--r-sm); background: #fff; cursor: pointer;
    font-size: .78rem; font-weight: 600; font-family: inherit;
    color: var(--muted); text-align: center; transition: all .18s;
    display: flex; flex-direction: column; align-items: center; gap: .22rem;
}
.pay-status-btn i { font-size: .95rem; }
.pay-status-btn:hover { border-color: var(--purple); color: var(--purple); }
.pay-status-btn.active-paid    { border-color: #22c55e; background: #f0fdf4; color: #15803d; }
.pay-status-btn.active-partial { border-color: #f59e0b; background: #fffbeb; color: #a16207; }
.pay-status-btn.active-unpaid  { border-color: var(--pink); background: var(--pink-soft); color: var(--pink); }

/* Items table */
.items-table-wrap { overflow-x: auto; }
.items-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.items-table thead tr { background: #faf7ff; border-bottom: 1.5px solid var(--border); }
.items-table thead th {
    padding: .65rem .85rem; text-align: left;
    font-size: .7rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .06em; white-space: nowrap;
}
.items-table thead th:last-child { width: 40px; }
.items-table tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
.items-table tbody tr:hover { background: #faf7ff; }
.items-table td { padding: .65rem .85rem; vertical-align: middle; }
.item-product-select {
    padding: .5rem .75rem; border: 1.5px solid var(--border);
    border-radius: var(--r-sm); font-size: .84rem; font-family: inherit;
    outline: none; width: 100%; min-width: 180px;
    background: #fff; color: var(--text); transition: border-color .18s;
}
.item-product-select:focus { border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.08); }
.item-num-input {
    padding: .5rem .65rem; border: 1.5px solid var(--border);
    border-radius: var(--r-sm); font-size: .84rem; font-family: inherit;
    outline: none; width: 100%; min-width: 90px; text-align: right;
    background: #fff; color: var(--text); transition: border-color .18s;
    font-variant-numeric: tabular-nums;
}
.item-num-input:focus { border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.08); }
.item-subtotal {
    font-weight: 700; color: var(--purple);
    font-variant-numeric: tabular-nums; white-space: nowrap;
    text-align: right; min-width: 110px;
}
.item-remove-btn {
    width: 28px; height: 28px; border-radius: 7px;
    border: 1.5px solid var(--border); background: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; color: var(--muted); transition: all .15s;
}
.item-remove-btn:hover { border-color: var(--tango); color: var(--tango); background: var(--pink-soft); }
.add-row-btn {
    margin-top: .85rem;
    display: flex; align-items: center; gap: .45rem;
    padding: .55rem 1rem;
    border: 1.5px dashed var(--purple); border-radius: var(--r-sm);
    background: var(--purple-soft); color: var(--purple);
    font-size: .83rem; font-weight: 600; font-family: inherit;
    cursor: pointer; transition: all .18s; width: 100%; justify-content: center;
}
.add-row-btn:hover { background: rgba(124,58,237,.12); }

/* Summary sidebar */
.summary-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .55rem 0; border-bottom: 1px solid var(--border); font-size: .85rem; color: var(--muted);
}
.summary-row:last-of-type { border-bottom: none; }
.summary-row.grand {
    font-size: 1rem; font-weight: 700; color: var(--text);
    padding-top: .7rem; border-top: 2px solid var(--border);
    margin-top: .35rem; border-bottom: none;
}
.summary-row.grand span:last-child { color: var(--pink); }

/* Submit */
.pf-submit-btn {
    width: 100%; padding: .9rem 1rem;
    background: linear-gradient(135deg, #7C3AED 0%, #F72585 100%);
    color: #fff; border: none; border-radius: 12px;
    font-size: .95rem; font-weight: 700; cursor: pointer; font-family: inherit;
    box-shadow: 0 6px 20px rgba(124,58,237,.35); transition: all .2s;
    display: flex; align-items: center; justify-content: center;
    gap: .55rem; letter-spacing: .02em; margin-top: 1rem;
}
.pf-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(124,58,237,.48); }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-pencil" style="color:var(--purple)"></i>
            Edit Purchase
        </h1>
        <p class="page-sub">{{ $purchase->invoice_no }} · {{ $purchase->supplier->name }}</p>
    </div>
    <a href="{{ route('admin.purchase.show', $purchase->id) }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.purchase.update', $purchase->id) }}" id="editForm">
@csrf @method('PUT')

<div class="purchase-form-grid">

    {{-- ════ LEFT ════ --}}
    <div>

        {{-- Purchase details --}}
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-file-invoice"></i> Purchase Details</h3>
            </div>
            <div class="pf-card-body">

                <div class="locked-notice">
                    <i class="fas fa-lock"></i>
                    Invoice number, supplier and products are locked after creation.
                    Only payment info and notes can be updated.
                </div>

                <div class="pf-row cols-2">
                    <div class="pf-field">
                        <label class="pf-label">Invoice No</label>
                        <input type="text" class="pf-input" value="{{ $purchase->invoice_no }}" disabled>
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Supplier</label>
                        <input type="text" class="pf-input" value="{{ $purchase->supplier->name }}" disabled>
                    </div>
                </div>

                <div class="pf-row cols-2">
                    <div class="pf-field">
                        <label class="pf-label">Purchase Date</label>
                        <input type="text" class="pf-input"
                               value="{{ $purchase->purchase_date->format('d M Y, H:i') }}" disabled>
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Payment Time <span>*</span></label>
                        <input type="datetime-local" name="payment_time"
                               class="pf-input {{ $errors->has('payment_time') ? 'is-error':'' }}"
                               value="{{ old('payment_time', $purchase->payment_time?->format('Y-m-d\TH:i')) }}">
                        @error('payment_time')
                            <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="pf-row">
                    <div class="pf-field">
                        <label class="pf-label">Notes</label>
                        <textarea name="notes" class="pf-textarea"
                                  placeholder="Optional notes…">{{ old('notes', $purchase->notes) }}</textarea>
                    </div>
                </div>

            </div>
        </div>

        {{-- Products (read-only view) --}}
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-boxes-stacked"></i> Products</h3>
                <span style="font-size:.75rem;color:var(--muted);font-weight:600">
                    Read-only · {{ $purchase->items->count() }} items
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
                            <td style="font-weight:600">{{ $item->product->name }}</td>
                            <td style="text-align:center">
                                <span style="
                                    background:var(--purple-soft);color:var(--purple);
                                    font-weight:700;font-size:.78rem;
                                    padding:.2rem .6rem;border-radius:20px">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td style="text-align:right;color:var(--muted);font-variant-numeric:tabular-nums">
                                KSh {{ number_format($item->unit_cost, 2) }}
                            </td>
                            <td style="text-align:right;font-weight:700;color:var(--purple);font-variant-numeric:tabular-nums">
                                KSh {{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#faf7ff;border-top:2px solid var(--border)">
                            <td colspan="2" style="padding:.75rem 1rem;color:var(--muted);font-size:.8rem">
                                {{ $purchase->items->sum('quantity') }} units total
                            </td>
                            <td style="padding:.75rem 1rem;text-align:right;color:var(--muted);font-weight:600">Total</td>
                            <td style="padding:.75rem 1rem;text-align:right;font-weight:700;color:var(--pink);font-size:.95rem">
                                KSh {{ number_format($purchase->total_amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ════ RIGHT ════ --}}
    <div>

        {{-- Payment --}}
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-credit-card"></i> Payment</h3>
            </div>
            <div class="pf-card-body">

                <div class="pf-field" style="margin-bottom:1rem">
                    <label class="pf-label">Payment Status <span>*</span></label>
                    <div class="pay-status-group">
                        <button type="button" class="pay-status-btn"
                                data-value="unpaid" onclick="setPayStatus('unpaid')">
                            <i class="fas fa-clock"></i> Unpaid
                        </button>
                        <button type="button" class="pay-status-btn"
                                data-value="partial" onclick="setPayStatus('partial')">
                            <i class="fas fa-circle-half-stroke"></i> Partial
                        </button>
                        <button type="button" class="pay-status-btn"
                                data-value="paid" onclick="setPayStatus('paid')">
                            <i class="fas fa-circle-check"></i> Paid
                        </button>
                    </div>
                    <input type="hidden" name="payment_status" id="paymentStatusInput"
                           value="{{ old('payment_status', $purchase->payment_status) }}">
                    @error('payment_status')
                        <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>

                <div class="pf-field">
                    <label class="pf-label">Amount Paid (KSh) <span>*</span></label>
                    <input type="number" name="paid_amount" id="paidAmountInput"
                           class="pf-input {{ $errors->has('paid_amount') ? 'is-error':'' }}"
                           value="{{ old('paid_amount', $purchase->paid_amount) }}"
                           min="0" step="0.01" oninput="updateSummary()">
                    @error('paid_amount')
                        <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Summary --}}
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-calculator"></i> Summary</h3>
            </div>
            <div class="pf-card-body">

                <div class="summary-row">
                    <span>Total Amount</span>
                    <span style="font-weight:600;font-variant-numeric:tabular-nums">
                        KSh {{ number_format($purchase->total_amount, 2) }}
                    </span>
                </div>
                <div class="summary-row">
                    <span>Amount Paid</span>
                    <span style="color:var(--green);font-weight:600;font-variant-numeric:tabular-nums"
                          id="summaryPaid">
                        KSh {{ number_format($purchase->paid_amount, 2) }}
                    </span>
                </div>
                <div class="summary-row">
                    <span>Balance</span>
                    <span style="color:var(--tango);font-weight:600;font-variant-numeric:tabular-nums"
                          id="summaryBalance">
                        KSh {{ number_format($purchase->total_amount - $purchase->paid_amount, 2) }}
                    </span>
                </div>
                <div class="summary-row grand">
                    <span>Grand Total</span>
                    <span>KSh {{ number_format($purchase->total_amount, 2) }}</span>
                </div>

                <button type="submit" class="pf-submit-btn">
                    <i class="fas fa-floppy-disk"></i> Save Changes
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
const TOTAL = {{ $purchase->total_amount }};

function setPayStatus(value) {
    document.getElementById('paymentStatusInput').value = value;
    document.querySelectorAll('.pay-status-btn').forEach(btn => {
        btn.classList.remove('active-paid','active-partial','active-unpaid');
        if (btn.dataset.value === value) btn.classList.add('active-' + value);
    });
    if (value === 'paid') {
        document.getElementById('paidAmountInput').value = TOTAL.toFixed(2);
    }
    if (value === 'unpaid') {
        document.getElementById('paidAmountInput').value = '0.00';
    }
    updateSummary();
}

function updateSummary() {
    const paid    = parseFloat(document.getElementById('paidAmountInput').value) || 0;
    const balance = Math.max(0, TOTAL - paid);
    document.getElementById('summaryPaid').textContent    = 'KSh ' + fmt(paid);
    document.getElementById('summaryBalance').textContent = 'KSh ' + fmt(balance);
}

function fmt(n) {
    return parseFloat(n).toLocaleString('en-KE', { minimumFractionDigits:2, maximumFractionDigits:2 });
}

// Restore status on load
setPayStatus('{{ old('payment_status', $purchase->payment_status) }}');
</script>
@endpush