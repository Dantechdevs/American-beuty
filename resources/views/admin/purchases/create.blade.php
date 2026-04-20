@extends('layouts.admin')

@section('title', 'New Purchase')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PURCHASE — CREATE
   ═══════════════════════════════════════════════════════════ */

.purchase-form-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 960px) { .purchase-form-grid { grid-template-columns: 1fr; } }

/* ── Cards ── */
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
    display: flex;
    align-items: center;
    gap: .6rem;
}
.pf-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.pf-card-header h3 i { color: var(--purple); }
.pf-card-body { padding: 1.25rem; }

/* ── Form fields ── */
.pf-row {
    display: grid;
    gap: 1rem;
    margin-bottom: 1rem;
}
.pf-row.cols-2 { grid-template-columns: 1fr 1fr; }
.pf-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
@media (max-width: 600px) {
    .pf-row.cols-2,
    .pf-row.cols-3 { grid-template-columns: 1fr; }
}
.pf-row:last-child { margin-bottom: 0; }

.pf-field { display: flex; flex-direction: column; gap: .35rem; }
.pf-label {
    font-size: .72rem;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .05em;
}
.pf-label span { color: var(--pink); margin-left: .15rem; }

.pf-input,
.pf-select,
.pf-textarea {
    padding: .62rem .9rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .87rem;
    font-family: inherit;
    outline: none;
    background: #fff;
    color: var(--text);
    transition: border-color .18s, box-shadow .18s;
    width: 100%;
}
.pf-input:focus,
.pf-select:focus,
.pf-textarea:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.pf-input.is-error,
.pf-select.is-error { border-color: var(--tango); }
.pf-error-msg {
    font-size: .72rem;
    color: var(--tango);
    display: flex;
    align-items: center;
    gap: .3rem;
}
.pf-textarea { resize: vertical; min-height: 80px; }

/* ── Payment status toggle ── */
.pay-status-group {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .5rem;
}
.pay-status-btn {
    padding: .6rem .5rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    background: #fff;
    cursor: pointer;
    font-size: .78rem;
    font-weight: 600;
    font-family: inherit;
    color: var(--muted);
    text-align: center;
    transition: all .18s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .22rem;
}
.pay-status-btn i { font-size: .95rem; }
.pay-status-btn:hover { border-color: var(--purple); color: var(--purple); }
.pay-status-btn.active-paid    { border-color: #22c55e; background: #f0fdf4; color: #15803d; }
.pay-status-btn.active-partial { border-color: #f59e0b; background: #fffbeb; color: #a16207; }
.pay-status-btn.active-unpaid  { border-color: var(--pink); background: var(--pink-soft); color: var(--pink); }

/* ── Products table ── */
.items-table-wrap { overflow-x: auto; }
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
    padding: .65rem .85rem;
    text-align: left;
    font-size: .7rem;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
}
.items-table thead th:last-child { width: 40px; }
.items-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .12s;
}
.items-table tbody tr:hover { background: #faf7ff; }
.items-table td { padding: .65rem .85rem; vertical-align: middle; }

.item-product-select {
    padding: .5rem .75rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .84rem;
    font-family: inherit;
    outline: none;
    width: 100%;
    min-width: 180px;
    background: #fff;
    color: var(--text);
    transition: border-color .18s;
}
.item-product-select:focus { border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.08); }

.item-num-input {
    padding: .5rem .65rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .84rem;
    font-family: inherit;
    outline: none;
    width: 100%;
    min-width: 90px;
    text-align: right;
    background: #fff;
    color: var(--text);
    transition: border-color .18s;
    font-variant-numeric: tabular-nums;
}
.item-num-input:focus { border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.08); }

.item-subtotal {
    font-weight: 700;
    color: var(--purple);
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
    text-align: right;
    min-width: 110px;
}

.item-remove-btn {
    width: 28px; height: 28px;
    border-radius: 7px;
    border: 1.5px solid var(--border);
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem;
    color: var(--muted);
    transition: all .15s;
}
.item-remove-btn:hover { border-color: var(--tango); color: var(--tango); background: var(--pink-soft); }

/* Add row button */
.add-row-btn {
    margin-top: .85rem;
    display: flex;
    align-items: center;
    gap: .45rem;
    padding: .55rem 1rem;
    border: 1.5px dashed var(--purple);
    border-radius: var(--r-sm);
    background: var(--purple-soft);
    color: var(--purple);
    font-size: .83rem;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    transition: all .18s;
    width: 100%;
    justify-content: center;
}
.add-row-btn:hover { background: rgba(124,58,237,.12); }

/* ── Summary sidebar ── */
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .55rem 0;
    border-bottom: 1px solid var(--border);
    font-size: .85rem;
    color: var(--muted);
}
.summary-row:last-of-type { border-bottom: none; }
.summary-row.grand {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    padding-top: .7rem;
    border-top: 2px solid var(--border);
    margin-top: .35rem;
}
.summary-row.grand span:last-child { color: var(--pink); }
.summary-val {
    font-weight: 600;
    font-variant-numeric: tabular-nums;
    color: var(--text);
}

/* Submit button */
.pf-submit-btn {
    width: 100%;
    padding: .9rem 1rem;
    background: linear-gradient(135deg, #7C3AED 0%, #F72585 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    box-shadow: 0 6px 20px rgba(124,58,237,.35);
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .55rem;
    letter-spacing: .02em;
    margin-top: 1rem;
}
.pf-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(124,58,237,.48);
}
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-plus-circle" style="color:var(--purple)"></i>
            New Purchase
        </h1>
        <p class="page-sub">Record a new stock purchase from a supplier</p>
    </div>
    <a href="{{ route('admin.purchase.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.purchase.store') }}" id="purchaseForm">
@csrf

<div class="purchase-form-grid">

    {{-- ════ LEFT COLUMN ════ --}}
    <div>

        {{-- Purchase Info --}}
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-file-invoice"></i> Purchase Details</h3>
            </div>
            <div class="pf-card-body">

                <div class="pf-row cols-2">
                    <div class="pf-field">
                        <label class="pf-label">Invoice No <span>*</span></label>
                        <input type="text" name="invoice_no"
                               class="pf-input {{ $errors->has('invoice_no') ? 'is-error':'' }}"
                               value="{{ old('invoice_no', $invoice_no) }}" required>
                        @error('invoice_no')
                            <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Supplier <span>*</span></label>
                        <select name="supplier_id"
                                class="pf-select {{ $errors->has('supplier_id') ? 'is-error':'' }}" required>
                            <option value="">— Select supplier —</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected':'' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="pf-row cols-2">
                    <div class="pf-field">
                        <label class="pf-label">Purchase Date <span>*</span></label>
                        <input type="datetime-local" name="purchase_date"
                               class="pf-input {{ $errors->has('purchase_date') ? 'is-error':'' }}"
                               value="{{ old('purchase_date', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('purchase_date')
                            <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Payment Time</label>
                        <input type="datetime-local" name="payment_time"
                               class="pf-input"
                               value="{{ old('payment_time') }}">
                    </div>
                </div>

                <div class="pf-row">
                    <div class="pf-field">
                        <label class="pf-label">Notes</label>
                        <textarea name="notes" class="pf-textarea"
                                  placeholder="Optional notes about this purchase…">{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>
        </div>

        {{-- Products ─────────────────────────────────────────── --}}
        <div class="pf-card">
            <div class="pf-card-header">
                <h3><i class="fas fa-boxes-stacked"></i> Products</h3>
            </div>
            <div class="pf-card-body">

                @error('items')
                    <div class="flash flash-error" style="margin-bottom:1rem">
                        <i class="fas fa-circle-exclamation"></i>
                        <div>{{ $message }}</div>
                    </div>
                @enderror

                <div class="items-table-wrap">
                    <table class="items-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="text-align:right">Qty</th>
                                <th style="text-align:right">Unit Cost (KSh)</th>
                                <th style="text-align:right">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            {{-- JS renders rows here --}}
                        </tbody>
                    </table>
                </div>

                <button type="button" class="add-row-btn" onclick="addRow()">
                    <i class="fas fa-plus"></i> Add Product Row
                </button>

            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ════ RIGHT COLUMN ════ --}}
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
                        <button type="button" class="pay-status-btn active-unpaid"
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
                           value="{{ old('payment_status', 'unpaid') }}">
                    @error('payment_status')
                        <span class="pf-error-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>

                <div class="pf-field">
                    <label class="pf-label">Amount Paid (KSh) <span>*</span></label>
                    <input type="number" name="paid_amount" id="paidAmountInput"
                           class="pf-input {{ $errors->has('paid_amount') ? 'is-error':'' }}"
                           value="{{ old('paid_amount', 0) }}"
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
                    <span>Items</span>
                    <span class="summary-val" id="summaryItems">0</span>
                </div>
                <div class="summary-row">
                    <span>Total Qty</span>
                    <span class="summary-val" id="summaryQty">0</span>
                </div>
                <div class="summary-row">
                    <span>Amount Paid</span>
                    <span class="summary-val" id="summaryPaid" style="color:var(--green)">KSh 0.00</span>
                </div>
                <div class="summary-row">
                    <span>Balance</span>
                    <span class="summary-val" id="summaryBalance" style="color:var(--tango)">KSh 0.00</span>
                </div>
                <div class="summary-row grand">
                    <span>TOTAL</span>
                    <span id="summaryTotal">KSh 0.00</span>
                </div>

                <button type="submit" class="pf-submit-btn">
                    <i class="fas fa-floppy-disk"></i> Save Purchase
                </button>

                <a href="{{ route('admin.purchase.index') }}"
                   class="btn btn-outline" style="width:100%;margin-top:.65rem;text-align:center;display:block">
                    Cancel
                </a>

            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end grid --}}
</form>

{{-- Products data for JS --}}
<script>
const PRODUCTS = @json($products->map(fn($p) => [
    'id'    => $p->id,
    'name'  => $p->name,
    'price' => $p->price,
]));
</script>

@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════
   PURCHASE CREATE — JS
   ═══════════════════════════════════════════════════════ */

let rowIndex = 0;

/* ── Add a product row ── */
function addRow() {
    const tbody = document.getElementById('itemsBody');
    const idx   = rowIndex++;

    const tr = document.createElement('tr');
    tr.id    = 'row-' + idx;
    tr.innerHTML = `
        <td>
            <select class="item-product-select"
                    name="items[${idx}][product_id]"
                    onchange="onProductChange(this, ${idx})"
                    required>
                <option value="">— Select product —</option>
                ${PRODUCTS.map(p => `<option value="${p.id}" data-price="${p.price}">${escH(p.name)}</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="number" class="item-num-input"
                   name="items[${idx}][quantity]"
                   id="qty-${idx}"
                   value="1" min="1" step="1"
                   oninput="calcRow(${idx})"
                   required>
        </td>
        <td>
            <input type="number" class="item-num-input"
                   name="items[${idx}][unit_cost]"
                   id="cost-${idx}"
                   value="0.00" min="0" step="0.01"
                   oninput="calcRow(${idx})"
                   required>
        </td>
        <td class="item-subtotal" id="sub-${idx}">KSh 0.00</td>
        <td>
            <button type="button" class="item-remove-btn" onclick="removeRow(${idx})">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateSummary();
}

/* ── Auto-fill unit cost when product selected ── */
function onProductChange(sel, idx) {
    const opt   = sel.options[sel.selectedIndex];
    const price = parseFloat(opt.dataset.price) || 0;
    document.getElementById('cost-' + idx).value = price.toFixed(2);
    calcRow(idx);
}

/* ── Calc single row subtotal ── */
function calcRow(idx) {
    const qty  = parseFloat(document.getElementById('qty-'  + idx)?.value) || 0;
    const cost = parseFloat(document.getElementById('cost-' + idx)?.value) || 0;
    const sub  = qty * cost;
    const cell = document.getElementById('sub-' + idx);
    if (cell) cell.textContent = 'KSh ' + fmt(sub);
    updateSummary();
}

/* ── Remove row ── */
function removeRow(idx) {
    const row = document.getElementById('row-' + idx);
    if (row) row.remove();
    updateSummary();
}

/* ── Recalculate summary ── */
function updateSummary() {
    const rows  = document.querySelectorAll('#itemsBody tr');
    let total   = 0;
    let totalQty = 0;

    rows.forEach(tr => {
        const idx   = tr.id.replace('row-', '');
        const qty   = parseFloat(document.getElementById('qty-'  + idx)?.value) || 0;
        const cost  = parseFloat(document.getElementById('cost-' + idx)?.value) || 0;
        total   += qty * cost;
        totalQty += qty;
    });

    const paid    = parseFloat(document.getElementById('paidAmountInput').value) || 0;
    const balance = Math.max(0, total - paid);

    document.getElementById('summaryItems').textContent   = rows.length;
    document.getElementById('summaryQty').textContent     = totalQty;
    document.getElementById('summaryTotal').textContent   = 'KSh ' + fmt(total);
    document.getElementById('summaryPaid').textContent    = 'KSh ' + fmt(paid);
    document.getElementById('summaryBalance').textContent = 'KSh ' + fmt(balance);
}

/* ── Payment status toggle ── */
function setPayStatus(value) {
    document.getElementById('paymentStatusInput').value = value;
    document.querySelectorAll('.pay-status-btn').forEach(btn => {
        btn.classList.remove('active-paid', 'active-partial', 'active-unpaid');
        if (btn.dataset.value === value) {
            btn.classList.add('active-' + value);
        }
    });

    // If paid → set paid amount = total
    if (value === 'paid') {
        const rows  = document.querySelectorAll('#itemsBody tr');
        let total = 0;
        rows.forEach(tr => {
            const idx  = tr.id.replace('row-', '');
            const qty  = parseFloat(document.getElementById('qty-'  + idx)?.value) || 0;
            const cost = parseFloat(document.getElementById('cost-' + idx)?.value) || 0;
            total += qty * cost;
        });
        document.getElementById('paidAmountInput').value = total.toFixed(2);
    }

    // If unpaid → zero out
    if (value === 'unpaid') {
        document.getElementById('paidAmountInput').value = '0.00';
    }

    updateSummary();
}

/* ── Restore old values on validation error ── */
@if(old('items'))
    @foreach(old('items') as $i => $item)
        (function() {
            addRow();
            const idx = {{ $i }};
            const sel  = document.querySelector(`select[name="items[${idx}][product_id]"]`);
            const qty  = document.getElementById('qty-'  + idx);
            const cost = document.getElementById('cost-' + idx);
            if (sel)  sel.value  = '{{ $item['product_id'] ?? '' }}';
            if (qty)  qty.value  = '{{ $item['quantity']   ?? 1 }}';
            if (cost) cost.value = '{{ $item['unit_cost']  ?? 0 }}';
            calcRow(idx);
        })();
    @endforeach
@else
    // Start with one empty row
    addRow();
@endif

// Restore payment status
setPayStatus('{{ old('payment_status', 'unpaid') }}');

/* ── Helpers ── */
function fmt(n) {
    return parseFloat(n).toLocaleString('en-KE', { minimumFractionDigits:2, maximumFractionDigits:2 });
}
function escH(str) {
    return String(str).replace(/[&<>"']/g, m =>
        ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

// Keep summary live as paid amount is typed
document.getElementById('paidAmountInput').addEventListener('input', updateSummary);
</script>
@endpush