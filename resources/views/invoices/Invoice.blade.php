<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }} – American Beauty Suppliers</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            font-size: 13px;
            color: #1a1a2e;
            background: #f5f5f0;
            padding: 30px;
        }

        .invoice-wrap {
            max-width: 680px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            padding: 36px 40px 32px 40px;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        /* AB monogram matching the physical pad style */
        .brand-monogram {
            font-size: 36px;
            font-weight: 700;
            font-style: italic;
            color: #1a1a2e;
            font-family: 'Georgia', serif;
            line-height: 1;
            letter-spacing: -3px;
        }

        .brand-name-block {
            line-height: 1.25;
        }

        .brand-name {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #1a1a2e;
        }

        .brand-sub {
            font-size: 9px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #444;
            margin-top: 1px;
        }

        .brand-address {
            font-size: 10.5px;
            color: #333;
            line-height: 1.7;
            margin-top: 10px;
        }

        /* ── RIGHT SIDE: INVOICE title + number ── */
        .invoice-meta {
            text-align: center;
            padding-top: 4px;
        }

        .invoice-title {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #1a1a2e;
        }

        .invoice-no-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 6px;
        }

        .invoice-no-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #1a1a2e;
        }

        .invoice-no-value {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            letter-spacing: 2px;
        }

        /* ── CUSTOMER + DATE/ORDER/DELIVERY BOX ── */
        .customer-row {
            display: flex;
            border: 1.5px solid #1a1a2e;
            margin-bottom: 0;
        }

        .customer-left {
            flex: 1.2;
            border-right: 1.5px solid #1a1a2e;
        }

        .customer-left-header {
            background: #1a1a2e;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 12px;
            text-align: center;
        }

        .customer-left-body {
            padding: 8px 12px 10px 12px;
            font-size: 11px;
            line-height: 1.9;
            color: #222;
        }

        .customer-left-body .field-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            display: inline-block;
            width: 70px;
        }

        .customer-right {
            flex: 1;
        }

        .meta-cell {
            display: flex;
            border-bottom: 1.5px solid #1a1a2e;
            font-size: 10.5px;
        }

        .meta-cell:last-child {
            border-bottom: none;
        }

        .meta-cell .meta-label {
            background: #f0f0ec;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #555;
            padding: 6px 10px;
            width: 90px;
            display: flex;
            align-items: center;
            border-right: 1.5px solid #1a1a2e;
            flex-shrink: 0;
        }

        .meta-cell .meta-value {
            padding: 6px 10px;
            font-weight: 600;
            color: #1a1a2e;
            font-size: 10.5px;
        }

        /* ── ITEMS TABLE ── */
        /* Matching physical: Description | QTY | @ | KSH */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #1a1a2e;
            border-top: none;
            margin-bottom: 0;
        }

        table.items-table thead tr {
            background: #fff;
            border-bottom: 1.5px solid #1a1a2e;
        }

        table.items-table thead th {
            padding: 8px 10px;
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: 700;
            color: #1a1a2e;
            text-align: center;
            border-right: 1.5px solid #1a1a2e;
        }

        table.items-table thead th:first-child {
            text-align: center;
            width: 50%;
        }

        table.items-table thead th:last-child {
            border-right: none;
        }

        table.items-table tbody tr {
            border-bottom: 1px solid #d0d0d0;
        }

        table.items-table tbody tr:last-child {
            border-bottom: none;
        }

        table.items-table tbody td {
            padding: 7px 10px;
            font-size: 11.5px;
            vertical-align: middle;
            border-right: 1.5px solid #1a1a2e;
            color: #1a1a2e;
        }

        table.items-table tbody td:last-child {
            border-right: none;
            text-align: right;
            font-weight: 600;
        }

        table.items-table tbody td:nth-child(2),
        table.items-table tbody td:nth-child(3) {
            text-align: center;
        }

        table.items-table tbody td:nth-child(4) {
            text-align: right;
        }

        .product-name { font-weight: 600; }
        .product-sku  { font-size: 9.5px; color: #999; margin-top: 1px; }

        /* ── TOTAL ROW at bottom of table ── */
        .total-row-wrap {
            border: 1.5px solid #1a1a2e;
            border-top: none;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .totals-table {
            width: 260px;
            border-collapse: collapse;
            border-left: 1.5px solid #1a1a2e;
        }

        .totals-table td {
            padding: 6px 12px;
            font-size: 11.5px;
            border-bottom: 1px solid #ddd;
        }

        .totals-table tr:last-child td { border-bottom: none; }
        .totals-table .t-label { color: #555; }
        .totals-table .t-amount { text-align: right; font-weight: 600; }

        .totals-table .grand-total-row {
            background: #1a1a2e;
            color: #fff;
        }

        .totals-table .grand-total-row td {
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .totals-table .discount-row .t-amount { color: #27ae60; }

        /* ── PAYMENT STATUS BADGES ── */
        .badge-paid {
            display: inline-block; background: #27ae60; color: #fff;
            font-size: 9px; letter-spacing: 1px; text-transform: uppercase;
            padding: 2px 8px; border-radius: 2px; font-weight: 700;
        }
        .badge-pending {
            display: inline-block; background: #e67e22; color: #fff;
            font-size: 9px; letter-spacing: 1px; text-transform: uppercase;
            padding: 2px 8px; border-radius: 2px; font-weight: 700;
        }
        .badge-unpaid {
            display: inline-block; background: #c8102e; color: #fff;
            font-size: 9px; letter-spacing: 1px; text-transform: uppercase;
            padding: 2px 8px; border-radius: 2px; font-weight: 700;
        }

        /* ── M-PESA SECTION ── */
        .mpesa-section {
            border: 1.5px solid #1a1a2e;
            margin-bottom: 20px;
        }

        .mpesa-header {
            background: #1a1a2e;
            color: #fff;
            padding: 5px 14px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .mpesa-body {
            padding: 10px 14px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            font-size: 11px;
        }

        .mpesa-item .mlabel {
            font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase;
            color: #999; display: block; margin-bottom: 2px;
        }

        .mpesa-item strong { color: #1a1a2e; }

        /* ── NOTES ── */
        .notes-section {
            border: 1.5px solid #ddd;
            padding: 10px 14px;
            margin-bottom: 20px;
            background: #fafaf8;
            font-size: 11.5px;
        }

        .notes-label {
            font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
            color: #999; margin-bottom: 4px;
        }

        /* ── FOOTER ── matching physical: E.&O.E + Accounts are due on demand ── */
        .footer {
            border-top: 1.5px solid #1a1a2e;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 4px;
        }

        .footer-left {
            font-size: 10.5px;
            color: #333;
            line-height: 1.8;
        }

        .footer-left .eoe {
            font-weight: 700;
            font-size: 11px;
        }

        .footer-left .due {
            font-weight: 700;
            font-style: italic;
            font-size: 12px;
            color: #1a1a2e;
        }

        .footer-right {
            text-align: right;
            font-size: 10px;
            color: #888;
        }

        .footer-tagline {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #1a1a2e;
            text-transform: uppercase;
            margin-top: 3px;
        }

        /* ── PRINT BAR ── */
        .print-bar {
            max-width: 680px;
            margin: 0 auto 16px auto;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-print, .btn-back {
            padding: 9px 22px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .btn-print { background: #1a1a2e; color: #fff; }

        .btn-back {
            background: #f0f0ec;
            color: #1a1a2e;
            border: 1.5px solid #ccc;
        }

        /* ── PRINT STYLES ── */
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-wrap { border: none; padding: 20px 28px; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

{{-- ── PRINT BAR (screen only, hidden on PDF) ── --}}
@if(!isset($isPdf))
<div class="print-bar no-print">
    <a href="{{ $backUrl }}" class="btn-back">&#8592; Back</a>
    <button class="btn-print" onclick="window.print()">&#128438; Print</button>
    <a href="{{ $pdfUrl }}" class="btn-print" style="background:#c8102e">&#8659; Download PDF</a>
</div>
@endif

<div class="invoice-wrap">

    {{-- ── HEADER: matches physical pad layout ── --}}
    <div class="header">
        {{-- Left: AB monogram + name + address --}}
        <div>
            <div class="brand-logo">
                <div class="brand-monogram">A<span style="font-size:28px">B</span></div>
                <div class="brand-name-block">
                    <div class="brand-name">American Beauty Suppliers</div>
                    <div class="brand-sub">Dealers in Mary Kay Products</div>
                </div>
            </div>
            <div class="brand-address">
                Bazaar Plaza, Moi Avenue<br>
                Biashara Street<br>
                Mezzanine 1, Unit 4, Room 4<br>
                0722 794 265<br>
                <strong>MPESA TILL NUMBER: 223813</strong>
            </div>
        </div>

        {{-- Right: INVOICE + number ── --}}
        <div class="invoice-meta">
            <div class="invoice-title">Invoice</div>
            <div class="invoice-no-row">
                <span class="invoice-no-label">Invoice No.</span>
                <span class="invoice-no-value">{{ $order->order_number }}</span>
            </div>
        </div>
    </div>

    {{-- ── CUSTOMER DETAILS + DATE/ORDER/DELIVERY ── --}}
    <div class="customer-row">
        <div class="customer-left">
            <div class="customer-left-header">Customer Details</div>
            <div class="customer-left-body">
                <div>
                    <span class="field-label">Name:</span>
                    <strong>{{ $order->first_name }} {{ $order->last_name }}</strong>
                </div>
                <div>
                    <span class="field-label">Telephone:</span>
                    {{ $order->phone }}
                </div>
                <div>
                    <span class="field-label">Email:</span>
                    {{ $order->email }}
                </div>
                <div>
                    <span class="field-label">Address:</span>
                    {{ $order->address_line_1 }}@if($order->address_line_2), {{ $order->address_line_2 }}@endif,
                    {{ $order->city }}@if($order->county), {{ $order->county }}@endif, {{ $order->country }}
                </div>
            </div>
        </div>
        <div class="customer-right">
            <div class="meta-cell">
                <div class="meta-label">Date</div>
                <div class="meta-value">{{ $order->created_at->format('d M Y') }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Order No.</div>
                <div class="meta-value">{{ $order->order_number }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Payment</div>
                <div class="meta-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Status</div>
                <div class="meta-value">
                    @if($order->payment_status === 'paid')
                        <span class="badge-paid">Paid</span>
                    @elseif($order->payment_status === 'pending')
                        <span class="badge-pending">Pending</span>
                    @else
                        <span class="badge-unpaid">{{ ucfirst($order->payment_status) }}</span>
                    @endif
                </div>
            </div>
            @if($order->paid_at)
            <div class="meta-cell">
                <div class="meta-label">Paid On</div>
                <div class="meta-value">{{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y') }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── ITEMS TABLE: Description | QTY | @ | KSH (matches physical pad) ── --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align:center">Description</th>
                <th style="width:10%">QTY</th>
                <th style="width:14%">@</th>
                <th style="width:16%;text-align:right;border-right:none">KSH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <div class="product-name">{{ $item->product_name }}</div>
                    @if($item->product && $item->product->sku)
                        <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                    @endif
                </td>
                <td style="text-align:center">{{ $item->quantity }}</td>
                <td style="text-align:right">{{ number_format($item->price, 0) }}</td>
                <td>{{ number_format($item->price * $item->quantity, 0) }}</td>
            </tr>
            @endforeach
            {{-- Filler rows to mimic physical invoice pad --}}
            @for($i = count($order->items); $i < 10; $i++)
            <tr style="height:26px">
                <td></td><td></td><td></td><td></td>
            </tr>
            @endfor
        </tbody>
    </table>

    {{-- ── TOTALS ── --}}
    <div class="total-row-wrap">
        <table class="totals-table">
            <tr>
                <td class="t-label">Subtotal</td>
                <td class="t-amount">KSh {{ number_format($order->subtotal, 0) }}</td>
            </tr>
            <tr>
                <td class="t-label">Shipping</td>
                <td class="t-amount">
                    {{ $order->shipping > 0 ? 'KSh '.number_format($order->shipping, 0) : 'Free' }}
                </td>
            </tr>
            @if($order->discount > 0)
            <tr class="discount-row">
                <td class="t-label">Discount</td>
                <td class="t-amount">−KSh {{ number_format($order->discount, 0) }}</td>
            </tr>
            @endif
            @if($order->tax > 0)
            <tr>
                <td class="t-label">VAT</td>
                <td class="t-amount">KSh {{ number_format($order->tax, 0) }}</td>
            </tr>
            @endif
            <tr class="grand-total-row">
                <td class="t-label">Total</td>
                <td class="t-amount">KSh {{ number_format($order->total, 0) }}</td>
            </tr>
        </table>
    </div>

    {{-- ── M-PESA TRANSACTION ── --}}
    @if($order->mpesa)
    <div class="mpesa-section">
        <div class="mpesa-header">M-Pesa Transaction</div>
        <div class="mpesa-body">
            <div class="mpesa-item">
                <span class="mlabel">Phone</span>
                <strong>{{ $order->mpesa->phone_number }}</strong>
            </div>
            <div class="mpesa-item">
                <span class="mlabel">Receipt No.</span>
                <strong>{{ $order->mpesa->mpesa_receipt_number ?? '—' }}</strong>
            </div>
            <div class="mpesa-item">
                <span class="mlabel">Amount Paid</span>
                <strong>KSh {{ number_format($order->mpesa->amount, 0) }}</strong>
            </div>
        </div>
    </div>
    @endif

    {{-- ── NOTES ── --}}
    @if($order->notes)
    <div class="notes-section">
        <div class="notes-label">Notes</div>
        <p style="color:#444;margin-top:4px">{{ $order->notes }}</p>
    </div>
    @endif

    {{-- ── FOOTER: matches physical pad exactly ── --}}
    <div class="footer">
        <div class="footer-left">
            <div class="eoe">E.&amp;O.E</div>
            <div class="due">Accounts are due on demand</div>
        </div>
        <div class="footer-right">
            <div style="font-size:9px;color:#bbb;letter-spacing:1px;text-transform:uppercase">Thank you for your business</div>
            <div class="footer-tagline">American Beauty Suppliers</div>
        </div>
    </div>

</div>
</body>
</html>