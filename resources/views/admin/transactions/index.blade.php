@extends('layouts.admin')
@section('title', 'Transactions')

@section('content')

{{-- Header --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-money-bill-transfer" style="color:var(--purple)"></i> Transactions
        </div>
        <div class="page-sub">Payment records and gateway activity</div>
    </div>
    <a href="{{ route('admin.transactions.export', request()->query()) }}"
       class="btn btn-outline btn-sm">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:1.25rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-money-bill-transfer"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
            <div class="stat-label">Total Transactions</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value">KES {{ number_format($stats['revenue'], 2) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['pending']) }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-circle-xmark"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['failed']) }}</div>
            <div class="stat-label">Failed</div>
        </div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

{{-- Filters --}}
<div class="card" style="margin-bottom:1rem">
    <div class="card-body" style="padding:.85rem 1.3rem">
        <form method="GET" style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center">

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search ref, customer, email…"
                style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;width:230px;outline:none">

            <select name="gateway" style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none;background:#fff">
                <option value="">All Gateways</option>
                <option value="mpesa"            {{ request('gateway')==='mpesa'            ? 'selected':'' }}>M-Pesa</option>
                <option value="card"             {{ request('gateway')==='card'             ? 'selected':'' }}>Card</option>
                <option value="cash_on_delivery" {{ request('gateway')==='cash_on_delivery' ? 'selected':'' }}>Cash on Delivery</option>
            </select>

            <select name="status" style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none;background:#fff">
                <option value="">All Status</option>
                <option value="success" {{ request('status')==='success' ? 'selected':'' }}>Success</option>
                <option value="pending" {{ request('status')==='pending' ? 'selected':'' }}>Pending</option>
                <option value="failed"  {{ request('status')==='failed'  ? 'selected':'' }}>Failed</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}"
                title="From date"
                style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none">

            <input type="date" name="date_to" value="{{ request('date_to') }}"
                title="To date"
                style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;outline:none">

            <button type="submit" class="btn btn-outline btn-sm">
                <i class="fas fa-search"></i> Filter
            </button>
            @if(request()->hasAny(['search','gateway','status','date_from','date_to']))
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-xmark"></i> Clear
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Customer</th>
                    <th>Order</th>
                    <th>Gateway</th>
                    <th>Transaction Ref</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td style="font-size:.82rem;color:var(--muted);font-weight:600">
                        #{{ $transaction->id }}
                    </td>
                    <td>
                        @php $user = optional($transaction->order)->user @endphp
                        @if($user)
                            <div style="font-weight:600;font-size:.87rem">{{ $user->name }}</div>
                            <div style="font-size:.76rem;color:var(--muted)">{{ $user->email }}</div>
                        @else
                            <span style="color:var(--muted);font-size:.84rem">—</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->order)
                            <a href="{{ route('admin.orders.show', $transaction->order) }}"
                               style="font-size:.83rem;color:var(--purple);font-weight:600;text-decoration:none">
                                #{{ $transaction->order->order_number ?? $transaction->order_id }}
                            </a>
                        @else
                            <span style="color:var(--muted);font-size:.83rem">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $gatewayMap = [
                                'mpesa'            => ['icon' => 'fa-mobile-screen-button', 'color' => 'var(--green)',  'label' => 'M-Pesa'],
                                'card'             => ['icon' => 'fa-credit-card',           'color' => 'var(--purple)', 'label' => 'Card'],
                                'cash_on_delivery' => ['icon' => 'fa-money-bill',            'color' => 'var(--tango)', 'label' => 'Cash on Delivery'],
                            ];
                            $gw = $gatewayMap[$transaction->gateway] ?? ['icon' => 'fa-circle-question', 'color' => 'var(--muted)', 'label' => ucfirst($transaction->gateway)];
                        @endphp
                        <span style="display:flex;align-items:center;gap:.4rem;font-size:.84rem;font-weight:600;color:{{ $gw['color'] }}">
                            <i class="fas {{ $gw['icon'] }}"></i> {{ $gw['label'] }}
                        </span>
                    </td>
                    <td>
                        @if($transaction->transaction_id)
                            <div style="display:flex;align-items:center;gap:.4rem">
                                <span style="font-family:monospace;font-size:.82rem;color:var(--text);background:var(--purple-soft);padding:.2rem .55rem;border-radius:5px">
                                    {{ $transaction->transaction_id }}
                                </span>
                                <button onclick="navigator.clipboard.writeText('{{ $transaction->transaction_id }}').then(()=>showToast('Copied!'))"
                                    class="btn btn-outline btn-sm" style="padding:.2rem .45rem" title="Copy">
                                    <i class="fas fa-copy" style="font-size:.68rem"></i>
                                </button>
                            </div>
                        @else
                            <span style="color:var(--muted);font-size:.83rem">—</span>
                        @endif
                    </td>
                    <td style="font-weight:700;color:var(--green);font-size:.9rem">
                        {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}
                    </td>
                    <td>
                        @php
                            $statusMap = [
                                'success' => 'badge-success',
                                'pending' => 'badge-warning',
                                'failed'  => 'badge-danger',
                            ];
                        @endphp
                        <span class="badge {{ $statusMap[$transaction->status] ?? 'badge-info' }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td style="font-size:.82rem;color:var(--muted)">
                        {{ $transaction->created_at->format('d M Y') }}<br>
                        <span style="font-size:.76rem">{{ $transaction->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;align-items:center">
                            {{-- View --}}
                            <button onclick="viewTransaction({{ $transaction->id }})"
                                class="btn btn-outline btn-sm" title="View details">
                                <i class="fas fa-eye"></i>
                            </button>
                            {{-- Status Update --}}
                            <button onclick="openStatusModal({{ $transaction->id }}, '{{ $transaction->status }}')"
                                class="btn btn-outline btn-sm" title="Update status">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:3rem;color:var(--muted)">
                        <i class="fas fa-money-bill-transfer" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                        No transactions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $transactions->links() }}</div>
</div>

{{-- ══════════ VIEW MODAL ══════════ --}}
<div id="viewModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:18px;width:100%;max-width:580px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;margin:1rem">
        <div style="padding:1.2rem 1.5rem;border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(120deg,#fff 45%,var(--purple-soft) 100%)">
            <h3 style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:flex;align-items:center;gap:.5rem">
                <i class="fas fa-receipt" style="color:var(--purple)"></i> Transaction Details
            </h3>
            <button onclick="closeModal('viewModal')" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted)">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div id="viewModalBody" style="padding:1.5rem;max-height:75vh;overflow-y:auto">
            <div style="text-align:center;padding:2rem;color:var(--muted)">
                <i class="fas fa-spinner fa-spin"></i> Loading…
            </div>
        </div>
    </div>
</div>

{{-- ══════════ STATUS MODAL ══════════ --}}
<div id="statusModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:18px;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;margin:1rem">
        <div style="padding:1.2rem 1.5rem;border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(120deg,#fff 45%,var(--purple-soft) 100%)">
            <h3 style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:flex;align-items:center;gap:.5rem">
                <i class="fas fa-pen" style="color:var(--purple)"></i> Update Status
            </h3>
            <button onclick="closeModal('statusModal')" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted)">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <form method="POST" id="statusForm" style="padding:1.5rem">
            @csrf @method('PATCH')
            <div class="form-group" style="margin-bottom:1.25rem">
                <label style="display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem">New Status</label>
                <select name="status" id="statusSelect"
                    style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;outline:none;background:#fff">
                    <option value="pending">Pending</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div style="display:flex;gap:.75rem">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Update
                </button>
                <button type="button" onclick="closeModal('statusModal')" class="btn btn-outline">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast --}}
<div id="toast" style="position:fixed;bottom:1.5rem;right:1.5rem;background:#1a0a2e;color:#fff;padding:.7rem 1.2rem;border-radius:10px;font-size:.83rem;font-weight:600;opacity:0;transition:opacity .3s;z-index:999;pointer-events:none">
    Copied!
</div>

@endsection

@push('scripts')
<script>
// ── Modal helpers ─────────────────────────────────────────────
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
['viewModal','statusModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModal(id);
    });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal('viewModal'); closeModal('statusModal'); }
});

// ── View transaction ──────────────────────────────────────────
function viewTransaction(id) {
    document.getElementById('viewModalBody').innerHTML =
        '<div style="text-align:center;padding:2rem;color:var(--muted)"><i class="fas fa-spinner fa-spin"></i> Loading…</div>';
    openModal('viewModal');

    fetch('/admin/transactions/' + id, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(t => {
        const statusColors = { success: 'badge-success', pending: 'badge-warning', failed: 'badge-danger' };
        const badge = statusColors[t.status] ?? 'badge-info';

        let payloadHtml = '';
        if (t.payload && Object.keys(t.payload).length) {
            payloadHtml = `
                <div style="margin-top:1rem">
                    <div style="font-size:.75rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem">Raw Payload</div>
                    <pre style="background:#f8f5ff;border:1.5px solid var(--border);border-radius:8px;padding:.85rem;font-size:.75rem;overflow-x:auto;white-space:pre-wrap;word-break:break-all">${JSON.stringify(t.payload, null, 2)}</pre>
                </div>`;
        }

        document.getElementById('viewModalBody').innerHTML = `
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <div style="font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Transaction Ref</div>
                    <div style="font-family:monospace;font-size:.88rem;font-weight:700;color:var(--purple);margin-top:.2rem">${t.transaction_id}</div>
                </div>
                <div>
                    <div style="font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Status</div>
                    <div style="margin-top:.2rem"><span class="badge ${badge}">${t.status.charAt(0).toUpperCase()+t.status.slice(1)}</span></div>
                </div>
                <div>
                    <div style="font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Amount</div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--green);margin-top:.2rem">${t.currency} ${t.amount}</div>
                </div>
                <div>
                    <div style="font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Gateway</div>
                    <div style="font-size:.88rem;font-weight:600;margin-top:.2rem">${t.gateway}</div>
                </div>
                <div>
                    <div style="font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Order</div>
                    <div style="font-size:.88rem;font-weight:600;color:var(--purple);margin-top:.2rem">#${t.order_number ?? t.order_id}</div>
                </div>
                <div>
                    <div style="font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Date</div>
                    <div style="font-size:.85rem;margin-top:.2rem">${t.created_at}</div>
                </div>
            </div>
            <div style="border-top:1.5px solid var(--border);padding-top:1rem">
                <div style="font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem">Customer</div>
                <div style="font-weight:600;font-size:.88rem">${t.customer}</div>
                <div style="font-size:.82rem;color:var(--muted)">${t.email}</div>
            </div>
            ${payloadHtml}
        `;
    })
    .catch(() => {
        document.getElementById('viewModalBody').innerHTML =
            '<div style="text-align:center;padding:2rem;color:var(--tango)"><i class="fas fa-circle-xmark"></i> Failed to load transaction.</div>';
    });
}

// ── Status modal ──────────────────────────────────────────────
function openStatusModal(id, currentStatus) {
    document.getElementById('statusForm').action = '/admin/transactions/' + id + '/status';
    document.getElementById('statusSelect').value = currentStatus;
    openModal('statusModal');
}

// ── Toast ─────────────────────────────────────────────────────
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.opacity = '1';
    setTimeout(() => t.style.opacity = '0', 2000);
}
</script>
@endpush