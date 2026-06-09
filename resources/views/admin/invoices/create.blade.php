@extends('layouts.admin')
@section('title', 'New Invoice')

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div style="display:flex;align-items:center;gap:.75rem">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
        <div>
            <div class="page-title"><i class="fas fa-file-invoice" style="color:var(--purple)"></i> New Invoice</div>
            <div class="page-sub">Fill in the details to create a new invoice</div>
        </div>
    </div>
</div>

@if(session('error'))
<div class="flash danger" style="margin-bottom:1rem"><i class="fas fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

<form action="{{ route('admin.invoices.store') }}" method="POST" id="invoiceForm">
@csrf
<div style="display:grid;grid-template-columns:1fr 300px;gap:1.25rem;align-items:start">

    {{-- Left --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem">

        {{-- Client Details --}}
        <div class="card">
            <div class="card-header">
                <span style="font-weight:700;font-size:.9rem;color:var(--text)"><i class="fas fa-user" style="color:var(--purple);margin-right:.4rem"></i>Client Details</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group" style="grid-column:span 2">
                        <label>Client Name <span style="color:var(--pink)">*</span></label>
                        <input type="text" name="client_name" class="form-control" value="{{ old('client_name') }}" required placeholder="e.g. Jane Doe">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="client_phone" class="form-control" value="{{ old('client_phone') }}" placeholder="07…">
                    </div>
                    <div class="form-group" style="grid-column:span 2">
                        <label>Address</label>
                        <input type="text" name="client_address" class="form-control" value="{{ old('client_address') }}" placeholder="Nairobi, Kenya">
                    </div>
                </div>
            </div>
        </div>

        {{-- Line Items --}}
        <div class="card">
            <div class="card-header">
                <span style="font-weight:700;font-size:.9rem;color:var(--text)"><i class="fas fa-list" style="color:var(--purple);margin-right:.4rem"></i>Line Items</span>
                <button type="button" onclick="addItem()" class="btn btn-outline btn-sm"><i class="fas fa-plus"></i> Add Item</button>
            </div>
            <div class="card-body" style="padding:0">
                <table style="width:100%;border-collapse:collapse" id="itemsTable">
                    <thead>
                        <tr style="background:linear-gradient(120deg,var(--pink-soft),#fff8fb)">
                            <th style="padding:.65rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);border-bottom:1.5px solid var(--border);text-align:left">Description</th>
                            <th style="padding:.65rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);border-bottom:1.5px solid var(--border);width:90px;text-align:center">Qty</th>
                            <th style="padding:.65rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);border-bottom:1.5px solid var(--border);width:130px;text-align:right">Unit Price</th>
                            <th style="padding:.65rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);border-bottom:1.5px solid var(--border);width:130px;text-align:right">Subtotal</th>
                            <th style="padding:.65rem 1rem;border-bottom:1.5px solid var(--border);width:44px"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        {{-- rows injected by JS --}}
                    </tbody>
                </table>
                <div style="padding:.75rem 1rem;text-align:right;border-top:1.5px solid var(--border)">
                    <button type="button" onclick="addItem()" class="btn btn-outline btn-sm"><i class="fas fa-plus"></i> Add Row</button>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="card">
            <div class="card-header">
                <span style="font-weight:700;font-size:.9rem;color:var(--text)"><i class="fas fa-note-sticky" style="color:var(--purple);margin-right:.4rem"></i>Notes</span>
            </div>
            <div class="card-body">
                <textarea name="notes" class="form-control" rows="3" placeholder="Payment instructions, terms, or additional notes…">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

        {{-- Invoice Meta --}}
        <div class="card">
            <div class="card-header">
                <span style="font-weight:700;font-size:.9rem;color:var(--text)"><i class="fas fa-circle-info" style="color:var(--purple);margin-right:.4rem"></i>Invoice Info</span>
            </div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:.85rem">
                    <div class="form-group" style="margin:0">
                        <label>Invoice Date <span style="color:var(--pink)">*</span></label>
                        <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label>Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                    </div>
                    <div class="form-group" style="margin:0">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="">Select…</option>
                            <option value="cash"          {{ old('payment_method')==='cash'          ? 'selected':'' }}>Cash</option>
                            <option value="mpesa"         {{ old('payment_method')==='mpesa'         ? 'selected':'' }}>M-Pesa</option>
                            <option value="bank_transfer" {{ old('payment_method')==='bank_transfer' ? 'selected':'' }}>Bank Transfer</option>
                            <option value="card"          {{ old('payment_method')==='card'          ? 'selected':'' }}>Card</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label>Status <span style="color:var(--pink)">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ old('status')==='draft' ? 'selected':'' }}>Draft</option>
                            <option value="sent"  {{ old('status')==='sent'  ? 'selected':'' }}>Sent</option>
                            <option value="paid"  {{ old('status')==='paid'  ? 'selected':'' }}>Paid</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Totals --}}
        <div class="card">
            <div class="card-header">
                <span style="font-weight:700;font-size:.9rem;color:var(--text)"><i class="fas fa-calculator" style="color:var(--purple);margin-right:.4rem"></i>Totals</span>
            </div>
            <div class="card-body" style="padding:.75rem 1rem">
                <div style="display:flex;flex-direction:column;gap:.6rem">
                    <div style="display:flex;justify-content:space-between;font-size:.85rem">
                        <span style="color:var(--muted)">Subtotal</span>
                        <span id="subtotalDisp" style="font-weight:600">KSh 0</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.85rem">
                        <span style="color:var(--muted)">Discount</span>
                        <input type="number" name="discount" id="discountInput" class="form-control"
                            style="width:110px;padding:.3rem .6rem;font-size:.82rem;text-align:right"
                            value="{{ old('discount',0) }}" min="0" step="0.01" oninput="recalc()">
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.85rem">
                        <span style="color:var(--muted)">Tax (VAT)</span>
                        <input type="number" name="tax" id="taxInput" class="form-control"
                            style="width:110px;padding:.3rem .6rem;font-size:.82rem;text-align:right"
                            value="{{ old('tax',0) }}" min="0" step="0.01" oninput="recalc()">
                    </div>
                    <div style="height:1px;background:var(--border)"></div>
                    <div style="display:flex;justify-content:space-between;font-size:1rem;font-weight:800;color:var(--text)">
                        <span>Total</span>
                        <span id="totalDisp">KSh 0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
            <i class="fas fa-save"></i> Create Invoice
        </button>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline" style="width:100%;justify-content:center">Cancel</a>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
let itemCount = 0;

function addItem(desc='', qty=1, price=0) {
    const i = itemCount++;
    const sub = (qty * price).toFixed(0);
    const row = document.createElement('tr');
    row.style.borderBottom = '1px solid var(--border)';
    row.innerHTML = `
        <td style="padding:.5rem .75rem">
            <input type="text" name="items[${i}][description]" class="form-control" style="font-size:.85rem"
                placeholder="Item or service description" value="${desc}" required>
        </td>
        <td style="padding:.5rem .75rem">
            <input type="number" name="items[${i}][quantity]" class="form-control item-qty"
                style="font-size:.85rem;text-align:center" value="${qty}" min="0.01" step="0.01" required oninput="calcRow(this)">
        </td>
        <td style="padding:.5rem .75rem">
            <input type="number" name="items[${i}][unit_price]" class="form-control item-price"
                style="font-size:.85rem;text-align:right" value="${price}" min="0" step="0.01" required oninput="calcRow(this)">
        </td>
        <td style="padding:.5rem .75rem;text-align:right;font-weight:600;font-size:.85rem" class="row-sub">
            KSh ${Number(sub).toLocaleString()}
        </td>
        <td style="padding:.5rem .75rem;text-align:center">
            <button type="button" onclick="removeRow(this)" style="background:none;border:none;color:#e11d48;cursor:pointer;font-size:.9rem">
                <i class="fas fa-times"></i>
            </button>
        </td>`;
    document.getElementById('itemsBody').appendChild(row);
    recalc();
}

function removeRow(btn) {
    btn.closest('tr').remove();
    recalc();
}

function calcRow(input) {
    const row  = input.closest('tr');
    const qty  = parseFloat(row.querySelector('.item-qty').value)   || 0;
    const price= parseFloat(row.querySelector('.item-price').value) || 0;
    const sub  = qty * price;
    row.querySelector('.row-sub').textContent = 'KSh ' + sub.toLocaleString('en-KE', {maximumFractionDigits:0});
    recalc();
}

function recalc() {
    let subtotal = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
        const qty   = parseFloat(row.querySelector('.item-qty')?.value)   || 0;
        const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
        subtotal += qty * price;
    });
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const tax      = parseFloat(document.getElementById('taxInput').value)      || 0;
    const total    = Math.max(0, subtotal - discount + tax);
    document.getElementById('subtotalDisp').textContent = 'KSh ' + subtotal.toLocaleString('en-KE', {maximumFractionDigits:0});
    document.getElementById('totalDisp').textContent    = 'KSh ' + total.toLocaleString('en-KE', {maximumFractionDigits:0});
}

// Start with 3 blank rows
addItem(); addItem(); addItem();
</script>
@endpush
