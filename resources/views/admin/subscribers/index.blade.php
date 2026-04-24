@extends('layouts.admin')

@section('title', 'Subscribers')

@section('content')

{{-- Header --}}
<div class="page-header mb-4">
    <div>
        <div class="page-title">
            <i class="fas fa-envelope-open-text" style="color:var(--pink)"></i>
            Subscribers
        </div>
        <div class="page-sub">Manage all your subscribers across channels</div>
    </div>
    <div style="display:flex;gap:.6rem;flex-wrap:wrap">
        <a href="{{ route('admin.subscribers.export') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}"
           class="btn btn-success btn-sm">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
        <a href="{{ route('admin.subscribers.create') }}" class="btn btn-pink btn-sm">
            <i class="fas fa-plus"></i> Add Subscriber
        </a>
        <button type="button" class="btn btn-sm"
                style="background:var(--gold-soft);color:var(--gold);border:1.5px solid #fde68a"
                onclick="document.getElementById('broadcastModal').style.display='flex'">
            <i class="fas fa-paper-plane"></i> Broadcast Message
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fill,minmax(150px,1fr));margin-bottom:1.5rem">

    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-value">{{ $stats['active'] }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-envelope"></i></div>
        <div>
            <div class="stat-value">{{ $stats['email'] }}</div>
            <div class="stat-label">Email</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-comment-sms"></i></div>
        <div>
            <div class="stat-value">{{ $stats['sms'] }}</div>
            <div class="stat-label">SMS</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-brands fa-whatsapp"></i></div>
        <div>
            <div class="stat-value">{{ $stats['whatsapp'] }}</div>
            <div class="stat-label">WhatsApp</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-bell"></i></div>
        <div>
            <div class="stat-value">{{ $stats['push'] }}</div>
            <div class="stat-label">Push</div>
        </div>
    </div>

</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1.25rem">
    <div style="padding:1rem 1.3rem">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
            <div style="flex:2;min-width:180px">
                <input type="text" name="search"
                       placeholder="Search name, email, phone…"
                       value="{{ request('search') }}"
                       style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;background:#fff;color:var(--text);outline:none"
                       onfocus="this.style.borderColor='var(--pink)'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <div style="flex:1;min-width:120px">
                <select name="type"
                        style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;background:#fff;color:var(--text);outline:none">
                    <option value="">All Types</option>
                    @foreach(['email','sms','whatsapp','push'] as $t)
                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;min-width:120px">
                <select name="source"
                        style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;background:#fff;color:var(--text);outline:none">
                    <option value="">All Sources</option>
                    <option value="footer_form"  {{ request('source') == 'footer_form'  ? 'selected' : '' }}>Footer Form</option>
                    <option value="checkout"     {{ request('source') == 'checkout'     ? 'selected' : '' }}>Checkout</option>
                    <option value="manual"       {{ request('source') == 'manual'       ? 'selected' : '' }}>Manual</option>
                    <option value="registration" {{ request('source') == 'registration' ? 'selected' : '' }}>Registration</option>
                </select>
            </div>
            <div style="flex:1;min-width:120px">
                <select name="status"
                        style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;background:#fff;color:var(--text);outline:none">
                    <option value="">All Status</option>
                    <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Unsubscribed</option>
                </select>
            </div>
            <div style="display:flex;gap:.5rem;flex-shrink:0">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-rotate-left"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list" style="color:var(--pink)"></i> Subscriber List</h3>
        <span class="badge badge-pink">{{ $subscribers->total() }} records</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Tag</th>
                    <th>Status</th>
                    <th>Subscribed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                <tr>
                    <td style="color:var(--muted);font-size:.8rem">{{ $subscriber->id }}</td>
                    <td style="font-weight:600">{{ $subscriber->name ?? '—' }}</td>
                    <td>
                        @if($subscriber->email)
                            <div style="font-size:.8rem;color:var(--muted)">
                                <i class="fas fa-envelope" style="font-size:.7rem;margin-right:.3rem;color:var(--pink)"></i>{{ $subscriber->email }}
                            </div>
                        @endif
                        @if($subscriber->phone)
                            <div style="font-size:.8rem;color:var(--muted);margin-top:.15rem">
                                <i class="fas fa-phone" style="font-size:.7rem;margin-right:.3rem;color:var(--green)"></i>{{ $subscriber->phone }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @php
                            $typeBadge = [
                                'email'    => 'badge-info',
                                'sms'      => 'badge-gold',
                                'whatsapp' => 'badge-success',
                                'push'     => 'badge-danger',
                            ];
                        @endphp
                        <span class="badge {{ $typeBadge[$subscriber->type] ?? 'badge-muted' }}">
                            {{ ucfirst($subscriber->type) }}
                        </span>
                    </td>
                    <td style="font-size:.8rem;color:var(--muted)">
                        {{ str_replace('_', ' ', ucfirst($subscriber->source ?? '')) }}
                    </td>
                    <td style="font-size:.8rem">{{ $subscriber->tag ?? '—' }}</td>
                    <td>
                        @if($subscriber->is_active)
                            <span class="badge badge-success">
                                <i class="fas fa-circle" style="font-size:.4rem"></i> Active
                            </span>
                        @else
                            <span class="badge badge-muted">Unsubscribed</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:var(--muted)">
                        {{ $subscriber->subscribed_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td>
                        @if($subscriber->is_active)
                        <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST"
                              onsubmit="return confirm('Unsubscribe this person?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm btn-icon" title="Unsubscribe">
                                <i class="fas fa-user-minus"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-envelope-open"></i>
                            <p>No subscribers found.</p>
                            <a href="{{ route('admin.subscribers.create') }}" class="btn btn-pink btn-sm">
                                <i class="fas fa-plus"></i> Add First Subscriber
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:.75rem 1rem;border-top:1.5px solid var(--border)">
        {{ $subscribers->links() }}
    </div>
</div>

{{-- Broadcast Modal --}}
<div id="broadcastModal"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(26,10,18,.5);align-items:center;justify-content:center;padding:1rem">
    <div style="background:#fff;border-radius:var(--r);width:100%;max-width:480px;box-shadow:var(--shadow-lg);overflow:hidden;animation:dropIn .2s cubic-bezier(.16,1,.3,1)">

        <div class="card-header">
            <h3><i class="fas fa-paper-plane" style="color:var(--pink)"></i> Broadcast Message</h3>
            <button type="button"
                    onclick="document.getElementById('broadcastModal').style.display='none'"
                    style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted);line-height:1">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.subscribers.send-message') }}" method="POST" style="padding:1.3rem">
            @csrf
            <div class="form-group">
                <label>Send To</label>
                <select name="type">
                    <option value="all">All Subscribers</option>
                    <option value="email">Email Only</option>
                    <option value="sms">SMS Only</option>
                    <option value="whatsapp">WhatsApp Only</option>
                </select>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" required placeholder="Message subject">
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" rows="5" required placeholder="Write your message…"
                          style="width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-family:inherit;font-size:.87rem;background:#fff;color:var(--text);resize:vertical;outline:none;transition:border-color .18s"
                          onfocus="this.style.borderColor='var(--pink)'"
                          onblur="this.style.borderColor='var(--border)'"></textarea>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:.6rem;padding-top:.25rem">
                <button type="button" class="btn btn-outline btn-sm"
                        onclick="document.getElementById('broadcastModal').style.display='none'">
                    Cancel
                </button>
                <button type="submit" class="btn btn-pink btn-sm">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('broadcastModal').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
</script>
@endpush

@endsection