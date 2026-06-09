@extends('layouts.admin')
@section('title', 'Invoices')

@section('content')

<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title"><i class="fas fa-file-invoice" style="color:var(--purple)"></i> Invoices</div>
        <div class="page-sub">Create and manage client invoices</div>
    </div>
    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Invoice
    </a>
</div>

@if(session('success'))
<div class="flash success" style="margin-bottom:1rem"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
@endif

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:1.25rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-file-invoice"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div><div class="stat-value">{{ $stats['paid'] }}</div><div class="stat-label">Paid</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-paper-plane"></i></div>
        <div><div class="stat-value">{{ $stats['sent'] }}</div><div class="stat-label">Sent</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-pen"></i></div>
        <div><div class="stat-value">{{ $stats['draft'] }}</div><div class="stat-label">Draft</div></div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1rem">
    <div class="card-body" style="padding:.85rem 1.3rem">
        <form method="GET" style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search invoice # or client…"
                style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;width:240px;outline:none">
            <select name="status" style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none;background:#fff">
                <option value="">All Statuses</option>
                <option value="draft"     {{ request('status')==='draft'     ? 'selected':'' }}>Draft</option>
                <option value="sent"      {{ request('status')==='sent'      ? 'selected':'' }}>Sent</option>
                <option value="paid"      {{ request('status')==='paid'      ? 'selected':'' }}>Paid</option>
                <option value="cancelled" {{ request('status')==='cancelled' ? 'selected':'' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i> Search</button>
            @if(request()->hasAny(['q','status']))
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body" style="padding:0">
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr>
                    @foreach(['Invoice #','Client','Date','Due Date','Total','Status','Actions'] as $h)
                    <th style="padding:.75rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);background:linear-gradient(120deg,var(--pink-soft),#fff8fb);border-bottom:1.5px solid var(--border);text-align:{{ $h==='Actions'?'right':'left' }}">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr style="border-bottom:1px solid var(--border)" onmouseover="this.style.background='#fff8fb'" onmouseout="this.style.background=''">
                    <td style="padding:.85rem 1rem">
                        <a href="{{ route('admin.invoices.show', $inv) }}" style="font-weight:700;color:var(--purple);text-decoration:none;font-size:.88rem">
                            {{ $inv->invoice_number }}
                        </a>
                    </td>
                    <td style="padding:.85rem 1rem">
                        <div style="font-weight:600;font-size:.88rem;color:var(--text)">{{ $inv->client_name }}</div>
                        @if($inv->client_phone)<div style="font-size:.75rem;color:var(--muted)">{{ $inv->client_phone }}</div>@endif
                    </td>
                    <td style="padding:.85rem 1rem;font-size:.85rem;color:var(--text)">{{ $inv->invoice_date->format('d M Y') }}</td>
                    <td style="padding:.85rem 1rem;font-size:.85rem;color:var(--muted)">
                        {{ $inv->due_date ? $inv->due_date->format('d M Y') : '—' }}
                    </td>
                    <td style="padding:.85rem 1rem;font-weight:700;font-size:.88rem;color:var(--text)">
                        KSh {{ number_format($inv->total, 0) }}
                    </td>
                    <td style="padding:.85rem 1rem">
                        @php $colors = ['paid'=>'#dcfce7,#16a34a,#bbf7d0','sent'=>'#dbeafe,#2563eb,#bfdbfe','draft'=>'#fef9c3,#854d0e,#fef08a','cancelled'=>'#fee2e2,#dc2626,#fecaca']; $c = explode(',', $colors[$inv->status] ?? $colors['draft']); @endphp
                        <span style="background:{{ $c[0] }};color:{{ $c[1] }};border:1px solid {{ $c[2] }};padding:.2rem .75rem;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:capitalize">
                            {{ $inv->status }}
                        </span>
                    </td>
                    <td style="padding:.85rem 1rem;text-align:right">
                        <div style="display:flex;justify-content:flex-end;gap:.4rem">
                            <a href="{{ route('admin.invoices.show', $inv) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.invoices.edit', $inv) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                            <a href="{{ route('admin.invoices.print', $inv) }}" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-print"></i></a>
                            <form action="{{ route('admin.invoices.destroy', $inv) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete {{ $inv->invoice_number }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background:#fff1f2;color:#e11d48;border:1.5px solid #fecdd3"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--muted)">
                    <i class="fas fa-file-invoice" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.3"></i>
                    <div style="font-weight:600">No invoices yet</div>
                    <small>Click "New Invoice" to create your first one</small>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="card-body" style="padding:.85rem 1.3rem;border-top:1.5px solid var(--border)">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

@endsection
