@extends('layouts.admin')

@section('title', 'Subscribers')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-envelope-open-text me-2 text-primary"></i>Subscribers</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subscribers.export') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}"
               class="btn btn-success btn-sm">
                <i class="fas fa-file-csv me-1"></i> Export CSV
            </a>
            <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add Subscriber
            </a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#broadcastModal">
                <i class="fas fa-paper-plane me-1"></i> Broadcast Message
            </button>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['label' => 'Total',     'value' => $stats['total'],    'icon' => 'fa-users',              'color' => 'primary'],
            ['label' => 'Active',    'value' => $stats['active'],   'icon' => 'fa-check-circle',       'color' => 'success'],
            ['label' => 'Email',     'value' => $stats['email'],    'icon' => 'fa-envelope',           'color' => 'info'],
            ['label' => 'SMS',       'value' => $stats['sms'],      'icon' => 'fa-comment-sms',        'color' => 'warning'],
            ['label' => 'WhatsApp',  'value' => $stats['whatsapp'], 'icon' => 'fa-brands fa-whatsapp', 'color' => 'success'],
            ['label' => 'Push',      'value' => $stats['push'],     'icon' => 'fa-bell',               'color' => 'danger'],
        ] as $stat)
        <div class="col-6 col-md-2">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas {{ $stat['icon'] }} fa-2x text-{{ $stat['color'] }} mb-2"></i>
                <h5 class="fw-bold mb-0">{{ $stat['value'] }}</h5>
                <small class="text-muted">{{ $stat['label'] }}</small>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Search name, email, phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach(['email','sms','whatsapp','push'] as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="source" class="form-select form-select-sm">
                        <option value="">All Sources</option>
                        <option value="footer_form"   {{ request('source') == 'footer_form'   ? 'selected' : '' }}>Footer Form</option>
                        <option value="checkout"      {{ request('source') == 'checkout'      ? 'selected' : '' }}>Checkout</option>
                        <option value="manual"        {{ request('source') == 'manual'        ? 'selected' : '' }}>Manual</option>
                        <option value="registration"  {{ request('source') == 'registration'  ? 'selected' : '' }}>Registration</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Unsubscribed</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary btn-sm w-100">Filter</button>
                    <a href="{{ route('admin.subscribers.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                        <td>{{ $subscriber->id }}</td>
                        <td>{{ $subscriber->name ?? '—' }}</td>
                        <td>
                            @if($subscriber->email)<div><small><i class="fas fa-envelope text-muted me-1"></i>{{ $subscriber->email }}</small></div>@endif
                            @if($subscriber->phone)<div><small><i class="fas fa-phone text-muted me-1"></i>{{ $subscriber->phone }}</small></div>@endif
                        </td>
                        <td>
                            @php $typeColors = ['email'=>'info','sms'=>'warning','whatsapp'=>'success','push'=>'danger']; @endphp
                            <span class="badge bg-{{ $typeColors[$subscriber->type] ?? 'secondary' }}">{{ ucfirst($subscriber->type) }}</span>
                        </td>
                        <td><small class="text-muted">{{ str_replace('_', ' ', ucfirst($subscriber->source)) }}</small></td>
                        <td>{{ $subscriber->tag ?? '—' }}</td>
                        <td>
                            @if($subscriber->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Unsubscribed</span>
                            @endif
                        </td>
                        <td><small>{{ $subscriber->subscribed_at?->format('d M Y') ?? '—' }}</small></td>
                        <td>
                            @if($subscriber->is_active)
                            <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST"
                                  onsubmit="return confirm('Unsubscribe this person?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-user-minus"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No subscribers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $subscribers->links() }}</div>
    </div>
</div>

{{-- Broadcast Modal --}}
<div class="modal fade" id="broadcastModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.subscribers.send-message') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Broadcast Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Send To</label>
                        <select name="type" class="form-select">
                            <option value="all">All Subscribers</option>
                            <option value="email">Email Subscribers Only</option>
                            <option value="sms">SMS Subscribers Only</option>
                            <option value="whatsapp">WhatsApp Subscribers Only</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" required placeholder="Message subject">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="5" required placeholder="Write your message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i>Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection