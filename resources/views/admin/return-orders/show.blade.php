@extends('layouts.admin')
@section('title', 'Return ' . $returnOrder->return_number)

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
        <a href="{{ route('admin.return-orders.index') }}" style="font-size:.85rem;color:#888;text-decoration:none">
            ← Back to Returns
        </a>
        <h2 style="font-size:1.3rem;font-weight:700;margin-top:.3rem">{{ $returnOrder->return_number }}</h2>
    </div>
    <span class="badge {{ $returnOrder->getStatusBadgeClass() }}" style="font-size:.85rem;padding:.4rem .9rem">
        {{ ucfirst($returnOrder->status) }}
    </span>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem">

    {{-- Left Column --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem">

        {{-- Return Details --}}
        <div class="card">
            <div class="card-header"><strong>Return Details</strong></div>
            <div style="padding:1.2rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Return Number</div>
                    <div style="font-weight:600">{{ $returnOrder->return_number }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Initiated By</div>
                    <div style="font-weight:600">{{ ucfirst($returnOrder->initiated_by) }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Reason</div>
                    <div>{{ \App\Models\ReturnOrder::REASONS[$returnOrder->reason] ?? ucfirst($returnOrder->reason) }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Quantity</div>
                    <div>{{ $returnOrder->quantity }}</div>
                </div>
                <div style="grid-column:span 2">
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Description</div>
                    <div style="background:#f9f9f9;padding:.8rem;border-radius:8px;font-size:.88rem">
                        {{ $returnOrder->description }}
                    </div>
                </div>
                @if($returnOrder->photo)
                <div style="grid-column:span 2">
                    <div style="font-size:.75rem;color:#888;margin-bottom:.5rem">Attached Photo</div>
                    <img src="{{ asset('storage/'.$returnOrder->photo) }}"
                         style="max-width:100%;max-height:300px;border-radius:8px;border:1px solid var(--border)">
                </div>
                @endif
            </div>
        </div>

        {{-- Order & Product --}}
        <div class="card">
            <div class="card-header"><strong>Order & Product</strong></div>
            <div style="padding:1.2rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Order Number</div>
                    <a href="{{ route('admin.orders.show', $returnOrder->order) }}" style="color:var(--primary);font-weight:600">
                        {{ $returnOrder->order->order_number ?? '—' }}
                    </a>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Order Date</div>
                    <div>{{ $returnOrder->order->created_at->format('d M Y') }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Product</div>
                    <div style="font-weight:500">{{ $returnOrder->product->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Unit Price</div>
                    <div>KSh {{ number_format($returnOrder->orderItem->price ?? 0, 0) }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Stock Restored</div>
                    <span class="badge {{ $returnOrder->stock_restored ? 'badge-success' : 'badge-warning' }}">
                        {{ $returnOrder->stock_restored ? 'Yes' : 'No' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Customer --}}
        <div class="card">
            <div class="card-header"><strong>Customer</strong></div>
            <div style="padding:1.2rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Name</div>
                    <div style="font-weight:500">{{ $returnOrder->user->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Email</div>
                    <div>{{ $returnOrder->user->email ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Phone</div>
                    <div>{{ $returnOrder->user->phone ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#888;margin-bottom:.2rem">Return Submitted</div>
                    <div>{{ $returnOrder->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Right Column --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem">

        {{-- Update Status --}}
        @if(!$returnOrder->isClosed())
        <div class="card">
            <div class="card-header"><strong>Update Status</strong></div>
            <form method="POST" action="{{ route('admin.return-orders.update-status', $returnOrder) }}" style="padding:1.2rem;display:flex;flex-direction:column;gap:1rem">
                @csrf @method('PATCH')

                <div>
                    <label style="font-size:.8rem;font-weight:600;display:block;margin-bottom:.4rem">Status</label>
                    <select name="status" style="width:100%;padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit">
                        @foreach(['pending','reviewing','approved','rejected','refunded','closed'] as $s)
                            <option value="{{ $s }}" {{ $returnOrder->status == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-size:.8rem;font-weight:600;display:block;margin-bottom:.4rem">Refund Amount (KSh)</label>
                    <input type="number" name="refund_amount" step="0.01" min="0"
                           value="{{ $returnOrder->refund_amount }}"
                           style="width:100%;padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit">
                </div>

                <div>
                    <label style="font-size:.8rem;font-weight:600;display:block;margin-bottom:.4rem">Refund Method</label>
                    <select name="refund_method" style="width:100%;padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit">
                        <option value="">— Select —</option>
                        @foreach(\App\Models\ReturnOrder::REFUND_METHODS as $key => $label)
                            <option value="{{ $key }}" {{ $returnOrder->refund_method == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-size:.8rem;font-weight:600;display:block;margin-bottom:.4rem">Admin Notes</label>
                    <textarea name="admin_notes" rows="3"
                              style="width:100%;padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;resize:vertical"
                              placeholder="Internal notes...">{{ $returnOrder->admin_notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-sm" style="width:100%">Update Return</button>
            </form>
        </div>
        @endif

        {{-- Refund Summary --}}
        @if($returnOrder->refund_amount)
        <div class="card">
            <div class="card-header"><strong>Refund Summary</strong></div>
            <div style="padding:1.2rem;display:flex;flex-direction:column;gap:.8rem">
                <div style="display:flex;justify-content:space-between;font-size:.88rem">
                    <span style="color:#888">Refund Amount</span>
                    <strong>KSh {{ number_format($returnOrder->refund_amount, 0) }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.88rem">
                    <span style="color:#888">Refund Method</span>
                    <span>{{ \App\Models\ReturnOrder::REFUND_METHODS[$returnOrder->refund_method] ?? '—' }}</span>
                </div>
                @if($returnOrder->reviewed_at)
                <div style="display:flex;justify-content:space-between;font-size:.88rem">
                    <span style="color:#888">Reviewed</span>
                    <span>{{ $returnOrder->reviewed_at->format('d M Y') }}</span>
                </div>
                @endif
                @if($returnOrder->reviewer)
                <div style="display:flex;justify-content:space-between;font-size:.88rem">
                    <span style="color:#888">Reviewed By</span>
                    <span>{{ $returnOrder->reviewer->name }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Admin Notes --}}
        @if($returnOrder->admin_notes)
        <div class="card">
            <div class="card-header"><strong>Admin Notes</strong></div>
            <div style="padding:1.2rem;font-size:.88rem;color:#555;line-height:1.6">
                {{ $returnOrder->admin_notes }}
            </div>
        </div>
        @endif

        {{-- Danger Zone --}}
        @if($returnOrder->isPending())
        <div class="card" style="border-color:#fee2e2">
            <div class="card-header" style="background:#fff5f5"><strong style="color:#dc2626">Danger Zone</strong></div>
            <div style="padding:1.2rem">
                <form method="POST" action="{{ route('admin.return-orders.destroy', $returnOrder) }}"
                      onsubmit="return confirm('Delete this return order?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm" style="background:#dc2626;color:#fff;width:100%">
                        Delete Return
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection