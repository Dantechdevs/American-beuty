@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Invoices</h1>
</div>

{{-- ── FILTERS ── --}}
<form method="GET" action="{{ route('admin.invoices.index') }}" class="card card-body mb-4 p-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:1px">Search Order No.</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="e.g. AB-BU6GJ8WT"
                   class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:1px">Order Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:1px">Payment</label>
            <select name="payment_status" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach(['pending','paid','failed'] as $p)
                    <option value="{{ $p }}" {{ request('payment_status') === $p ? 'selected' : '' }}>
                        {{ ucfirst($p) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:1px">Source</label>
            <select name="source" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="online"  {{ request('source') === 'online'  ? 'selected' : '' }}>Online</option>
                <option value="pos"     {{ request('source') === 'pos'     ? 'selected' : '' }}>POS</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-dark w-100">Filter</button>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
        </div>
    </div>
</form>

{{-- ── TABLE ── --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice No.</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Source</th>
                        <th>Order Status</th>
                        <th>Payment</th>
                        <th class="text-end">Total (KSh)</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $order)
                    <tr>
                        <td>
                            <span class="fw-semibold text-danger">{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div class="text-muted small">{{ $order->phone }}</div>
                        </td>
                        <td class="text-nowrap">
                            {{ $order->created_at->format('d M Y') }}
                        </td>
                        <td>
                            @if($order->source === 'pos')
                                <span class="badge bg-primary">POS</span>
                            @else
                                <span class="badge bg-secondary">Online</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending'    => 'warning',
                                    'processing' => 'info',
                                    'shipped'    => 'primary',
                                    'delivered'  => 'success',
                                    'cancelled'  => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </td>
                        <td class="text-end fw-semibold">
                            {{ number_format($order->total, 0) }}
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('admin.orders.invoice', $order->id) }}"
                               class="btn btn-sm btn-outline-dark"
                               title="View Invoice">
                                &#128196; View
                            </a>
                            <a href="{{ route('admin.orders.invoice.pdf', $order->id) }}"
                               class="btn btn-sm btn-outline-danger"
                               title="Download PDF">
                                &#8659; PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            No invoices found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($invoices->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Showing {{ $invoices->firstItem() }}–{{ $invoices->lastItem() }} of {{ $invoices->total() }} invoices
        </div>
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection