@extends('layouts.admin')
@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div style="display:flex;align-items:center;gap:.75rem">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i></a>
        <div>
            <div class="page-title"><i class="fas fa-file-invoice" style="color:var(--purple)"></i> {{ $invoice->invoice_number }}</div>
            <div class="page-sub">{{ $invoice->client_name }} &mdash; {{ $invoice->invoice_date->format('d M Y') }}</div>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
        @if($invoice->status !== 'paid')
        <form action="{{ route('admin.invoices.paid', $invoice) }}" method="POST" style="display:inline">
            @csrf @method('PATCH')
            <button class="btn btn-sm" style="background:#dcfce7;color:#16a34a;border:1.5px solid #bbf7d0;font-weight:600">
                <i class="fas fa-circle-check"></i> Mark Paid
            </button>
        </form>
        @endif
        <a href="{{ route('admin.invoices.print', $invoice) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-print"></i> Print</a>
        <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="btn btn-outline btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i> Edit</a>
    </div>
</div>

@if(session('success'))
<div class="flash success" style="margin-bottom:1rem"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 280px;gap:1.25rem;align-items:start">
    <div>
        {{-- Client + Meta --}}
        <div class="card" style="margin-bottom:1.25rem">
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;padding:1.25rem">
                <div>
                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:.5rem">Bill To</div>
                    <div style="font-weight:700;font-size:1rem;color:var(--text);margin-bottom:.2rem">{{ $invoice->client_name }}</div>
                    @if($invoice->client_phone)<div style="font-size:.85rem;color:var(--muted)"><i class="fas fa-phone" style="width:14px;color:var(--purple)"></i> {{ $invoice->client_phone }}</div>@endif
                    @if($invoice->client_address)<div style="font-size:.85rem;color:var(--muted)"><i class="fas fa-location-dot" style="width:14px;color:var(--purple)"></i> {{ $invoice->client_address }}</div>@endif
                </div>
                <div>
                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:.5rem">Invoice Details</div>
                    @foreach(['Invoice #'=>$invoice->invoice_number,'Date'=>$invoice->invoice_date->format('d M Y'),'Due Date'=>($invoice->due_date?$invoice->due_date->format('d M Y'):'—'),'Payment'=>(ucfirst($invoice->payment_method??'—'))] as $label=>$val)
                    <div style="display:flex;justify-content:space-between;font-size:.83rem;margin-bottom:.3rem">
                        <span style="color:var(--muted)">{{ $label }}</span>
                        <span style="font-weight:600;color:var(--text)">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="card" style="margin-bottom:1.25rem">
            <div class="card-body" style="padding:0">
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="background:linear-gradient(120deg,var(--pink-soft),#fff8fb)">
                            <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);border-bottom:1.5px solid var(--border);text-align:left">Description</th>
                            <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);border-bottom:1.5px solid var(--border);text-align:center;width:80px">Qty</th>
                            <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);border-bottom:1.5px solid var(--border);text-align:right;width:130px">Unit Price</th>
                            <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);border-bottom:1.5px solid var(--border);text-align:right;width:130px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr style="border-bottom:1px solid var(--border)">
                            <td style="padding:.8rem 1rem;font-size:.88rem;color:var(--text);font-weight:500">{{ $item->description }}</td>
                            <td style="padding:.8rem 1rem;text-align:center;font-size:.85rem;color:var(--muted)">{{ number_format($item->quantity, 2) }}</td>
                            <td style="padding:.8rem 1rem;text-align:right;font-size:.85rem;color:var(--muted)">KSh {{ number_format($item->unit_price, 0) }}</td>
                            <td style="padding:.8rem 1rem;text-align:right;font-size:.88rem;font-weight:600;color:var(--text)">KSh {{ number_format($item->subtotal, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Totals --}}
                <div style="display:flex;justify-content:flex-end;padding:1rem">
                    <div style="width:240px">
                        @foreach(['Subtotal'=>'KSh '.number_format($invoice->subtotal,0)] + ($invoice->discount>0?['Discount'=>'−KSh '.number_format($invoice->discount,0)]:[]) + ($invoice->tax>0?['Tax (VAT)'=>'KSh '.number_format($invoice->tax,0)]:[]) as $l=>$v)
                        <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:.4rem">
                            <span style="color:var(--muted)">{{ $l }}</span>
                            <span style="font-weight:600;color:{{ str_contains($v,'−')?'#16a34a':'var(--text)' }}">{{ $v }}</span>
                        </div>
                        @endforeach
                        <div style="height:1.5px;background:var(--border);margin:.5rem 0"></div>
                        <div style="display:flex;justify-content:space-between;font-size:1rem;font-weight:800;color:var(--text)">
                            <span>Total</span>
                            <span>KSh {{ number_format($invoice->total, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($invoice->notes)
        <div class="card">
            <div class="card-header"><span style="font-weight:700;font-size:.9rem;color:var(--text)"><i class="fas fa-note-sticky" style="color:var(--purple);margin-right:.4rem"></i>Notes</span></div>
            <div class="card-body" style="font-size:.88rem;color:var(--muted);line-height:1.7">{{ $invoice->notes }}</div>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1rem">
        <div class="card">
            <div class="card-header"><span style="font-weight:700;font-size:.9rem;color:var(--text)">Status</span></div>
            <div class="card-body">
                @php $colors = ['paid'=>'#dcfce7,#16a34a,#bbf7d0','sent'=>'#dbeafe,#2563eb,#bfdbfe','draft'=>'#fef9c3,#854d0e,#fef08a','cancelled'=>'#fee2e2,#dc2626,#fecaca']; $c = explode(',', $colors[$invoice->status] ?? $colors['draft']); @endphp
                <div style="background:{{ $c[0] }};color:{{ $c[1] }};border:1.5px solid {{ $c[2] }};padding:.6rem 1rem;border-radius:10px;font-weight:700;text-align:center;text-transform:capitalize;font-size:.95rem">
                    {{ $invoice->status }}
                </div>
                @if($invoice->paid_at)
                <div style="font-size:.75rem;color:var(--muted);text-align:center;margin-top:.5rem">Paid on {{ $invoice->paid_at->format('d M Y') }}</div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><span style="font-weight:700;font-size:.9rem;color:var(--text)">Actions</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:.6rem">
                <a href="{{ route('admin.invoices.print', $invoice) }}" target="_blank" class="btn btn-outline" style="justify-content:center"><i class="fas fa-print"></i> Print Invoice</a>
                <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="btn btn-outline" style="justify-content:center"><i class="fas fa-file-pdf"></i> Download PDF</a>
                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-primary" style="justify-content:center"><i class="fas fa-pen"></i> Edit Invoice</a>
                <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete this invoice?')">
                    @csrf @method('DELETE')
                    <button class="btn" style="width:100%;justify-content:center;background:#fff1f2;color:#e11d48;border:1.5px solid #fecdd3"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><span style="font-weight:700;font-size:.9rem;color:var(--text)">Created By</span></div>
            <div class="card-body" style="font-size:.85rem;color:var(--muted)">
                <div style="font-weight:600;color:var(--text)">{{ $invoice->creator->name ?? 'System' }}</div>
                <div>{{ $invoice->created_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
