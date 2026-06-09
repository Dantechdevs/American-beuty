<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice {{ $invoice->invoice_number }} - American Beauty</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Georgia,serif;font-size:13px;color:#1a1a2e;background:#f5f5f0;padding:30px}
.wrap{max-width:680px;margin:0 auto;background:#fff;border:1px solid #ddd;padding:36px 40px 32px}
.header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px}
.brand-name{font-size:15px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase}
.brand-sub{font-size:9px;letter-spacing:1.5px;text-transform:uppercase;color:#444;margin-top:1px}
.brand-address{font-size:10.5px;color:#333;line-height:1.7;margin-top:10px}
.inv-title{font-size:26px;font-weight:700;letter-spacing:3px;text-transform:uppercase}
.inv-no{font-size:22px;font-weight:700;letter-spacing:2px}
.cust-row{display:flex;border:1.5px solid #1a1a2e;margin-bottom:0}
.cust-left{flex:1.2;border-right:1.5px solid #1a1a2e}
.cust-left-hdr{background:#1a1a2e;color:#fff;font-size:9px;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:5px 12px;text-align:center}
.cust-left-body{padding:8px 12px 10px;font-size:11px;line-height:1.9;color:#222}
.fl{font-size:9px;text-transform:uppercase;letter-spacing:1px;color:#888;display:inline-block;width:70px}
.cust-right{flex:1}
.mc{display:flex;border-bottom:1.5px solid #1a1a2e;font-size:10.5px}
.mc:last-child{border-bottom:none}
.ml{background:#f0f0ec;font-size:9px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#555;padding:6px 10px;width:90px;display:flex;align-items:center;border-right:1.5px solid #1a1a2e;flex-shrink:0}
.mv{padding:6px 10px;font-weight:600;color:#1a1a2e;font-size:10.5px}
table.it{width:100%;border-collapse:collapse;border:1.5px solid #1a1a2e;border-top:none}
table.it thead tr{background:#fff;border-bottom:1.5px solid #1a1a2e}
table.it thead th{padding:8px 10px;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;font-weight:700;color:#1a1a2e;text-align:center;border-right:1.5px solid #1a1a2e}
table.it thead th:last-child{border-right:none}
table.it tbody tr{border-bottom:1px solid #d0d0d0}
table.it tbody td{padding:7px 10px;font-size:11.5px;vertical-align:middle;border-right:1.5px solid #1a1a2e;color:#1a1a2e}
table.it tbody td:last-child{border-right:none;text-align:right;font-weight:600}
.tot-wrap{border:1.5px solid #1a1a2e;border-top:none;display:flex;justify-content:flex-end;margin-bottom:20px}
.tot-tbl{width:260px;border-collapse:collapse;border-left:1.5px solid #1a1a2e}
.tot-tbl td{padding:6px 12px;font-size:11.5px;border-bottom:1px solid #ddd}
.tot-tbl tr:last-child td{border-bottom:none}
.ta{text-align:right;font-weight:600}
.grand{background:#1a1a2e;color:#fff}
.grand td{font-size:12.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase}
.footer{border-top:1.5px solid #1a1a2e;padding-top:12px;display:flex;justify-content:space-between;align-items:flex-end;margin-top:4px}
.pbar{max-width:680px;margin:0 auto 16px;display:flex;gap:10px;justify-content:flex-end}
.bp,.bb{padding:9px 22px;border-radius:4px;font-size:13px;font-weight:600;cursor:pointer;border:none;text-decoration:none;display:inline-flex;align-items:center;gap:7px}
.bp{background:#1a1a2e;color:#fff}
.bb{background:#f0f0ec;color:#1a1a2e;border:1.5px solid #ccc}
.badge-paid{display:inline-block;background:#27ae60;color:#fff;font-size:9px;letter-spacing:1px;text-transform:uppercase;padding:2px 8px;border-radius:2px;font-weight:700}
.badge-draft{display:inline-block;background:#e67e22;color:#fff;font-size:9px;letter-spacing:1px;text-transform:uppercase;padding:2px 8px;border-radius:2px;font-weight:700}
@media print{body{background:#fff;padding:0}.wrap{border:none;padding:20px 28px;max-width:100%}.no-print{display:none}}
</style>
</head>
<body>

<div class="pbar no-print">
    <a href="{{ route('admin.invoices.show', $invoice) }}" class="bb">← Back</a>
    <button class="bp" onclick="window.print()">🖨 Print</button>
    <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="bp" style="background:#c8102e">⬇ PDF</a>
</div>

<div class="wrap">
    <div class="header">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                <div style="font-size:36px;font-weight:700;font-style:italic;line-height:1;letter-spacing:-3px">A<span style="font-size:28px">B</span></div>
                <div>
                    <div class="brand-name">American Beauty Suppliers</div>
                    <div class="brand-sub">Dealers in Mary Kay Products</div>
                </div>
            </div>
            <div class="brand-address">
                Bazaar Plaza, Moi Avenue<br>Biashara Street<br>Mezzanine 1, Unit 4, Room 4<br>
                0722 794 265<br><strong>MPESA TILL: 223813</strong>
            </div>
        </div>
        <div style="text-align:center;padding-top:4px">
            <div class="inv-title">Invoice</div>
            <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:6px">
                <span style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase">No.</span>
                <span class="inv-no">{{ $invoice->invoice_number }}</span>
            </div>
        </div>
    </div>

    <div class="cust-row">
        <div class="cust-left">
            <div class="cust-left-hdr">Customer Details</div>
            <div class="cust-left-body">
                <div><span class="fl">Name:</span><strong>{{ $invoice->client_name }}</strong></div>
                @if($invoice->client_phone)<div><span class="fl">Tel:</span>{{ $invoice->client_phone }}</div>@endif
                @if($invoice->client_address)<div><span class="fl">Address:</span>{{ $invoice->client_address }}</div>@endif
            </div>
        </div>
        <div class="cust-right">
            <div class="mc"><div class="ml">Date</div><div class="mv">{{ $invoice->invoice_date->format('d M Y') }}</div></div>
            <div class="mc"><div class="ml">Invoice #</div><div class="mv">{{ $invoice->invoice_number }}</div></div>
            <div class="mc"><div class="ml">Payment</div><div class="mv">{{ ucfirst(str_replace('_',' ',$invoice->payment_method??'N/A')) }}</div></div>
            <div class="mc"><div class="ml">Status</div><div class="mv"><span class="{{ $invoice->status==='paid'?'badge-paid':'badge-draft' }}">{{ $invoice->status }}</span></div></div>
            @if($invoice->due_date)<div class="mc"><div class="ml">Due</div><div class="mv">{{ $invoice->due_date->format('d M Y') }}</div></div>@endif
            @if($invoice->servedBy)<div class="mc"><div class="ml">Served By</div><div class="mv">{{ $invoice->servedBy->name }}</div></div>@endif
        </div>
    </div>

    <table class="it">
        <thead>
            <tr>
                <th style="text-align:left;width:50%">Description</th>
                <th style="width:10%">QTY</th>
                <th style="width:18%">@</th>
                <th style="width:22%;text-align:right;border-right:none">KSH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td style="text-align:left;font-weight:600">{{ $item->description }}</td>
                <td style="text-align:center">{{ number_format($item->quantity,2) }}</td>
                <td style="text-align:right">{{ number_format($item->unit_price,0) }}</td>
                <td>{{ number_format($item->subtotal,0) }}</td>
            </tr>
            @endforeach
            @for($i=count($invoice->items);$i<10;$i++)
            <tr style="height:26px"><td></td><td></td><td></td><td></td></tr>
            @endfor
        </tbody>
    </table>

    <div class="tot-wrap">
        <table class="tot-tbl">
            <tr><td>Subtotal</td><td class="ta">KSh {{ number_format($invoice->subtotal,0) }}</td></tr>
            @if($invoice->discount>0)<tr><td>Discount</td><td class="ta" style="color:#27ae60">-KSh {{ number_format($invoice->discount,0) }}</td></tr>@endif
            @if($invoice->tax>0)<tr><td>VAT</td><td class="ta">KSh {{ number_format($invoice->tax,0) }}</td></tr>@endif
            <tr class="grand"><td>Total</td><td class="ta">KSh {{ number_format($invoice->total,0) }}</td></tr>
        </table>
    </div>

    @if($invoice->notes)
    <div style="border:1.5px solid #ddd;padding:10px 14px;margin-bottom:20px;background:#fafaf8;font-size:11.5px">
        <div style="font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#999;margin-bottom:4px">Notes</div>
        <p style="color:#444">{{ $invoice->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <div>
            <div style="font-weight:700;font-size:11px">E.&amp;O.E</div>
            <div style="font-weight:700;font-style:italic;font-size:12px">Accounts are due on demand</div>
        </div>
        <div style="text-align:right">
            <div style="font-size:9px;color:#bbb;letter-spacing:1px;text-transform:uppercase">Thank you for your business</div>
            <div style="font-size:10.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase">American Beauty Suppliers</div>
        </div>
    </div>
</div>
</body>
</html>