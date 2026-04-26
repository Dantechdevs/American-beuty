@extends('layouts.admin')
@section('title', 'Appointments')

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-calendar-check" style="color:var(--pink)"></i> Appointments
        </div>
        <div class="page-sub">Manage all spa bookings and appointments</div>
    </div>
    <a href="{{ route('book.index') }}" target="_blank" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-up-right-from-square"></i> View Booking Page
    </a>
</div>

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(6,1fr);margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-calendar"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div><div class="stat-value">{{ $stats['confirmed'] }}</div><div class="stat-label">Confirmed</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-calendar-day"></i></div>
        <div><div class="stat-value">{{ $stats['today'] }}</div><div class="stat-label">Today</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-flag-checkered"></i></div>
        <div><div class="stat-value">{{ $stats['completed'] }}</div><div class="stat-label">Completed</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon tango"><i class="fas fa-circle-xmark"></i></div>
        <div><div class="stat-value">{{ $stats['cancelled'] }}</div><div class="stat-label">Cancelled</div></div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1.25rem">
    <div class="card-body" style="padding:.85rem 1.25rem">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
            <div style="display:flex;flex-direction:column;gap:.3rem;flex:1;min-width:160px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Name, phone or service…"
                       style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:130px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Status</label>
                <select name="status" style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
                    <option value="">All</option>
                    <option value="pending"   {{ request('status')==='pending'   ?'selected':'' }}>Pending</option>
                    <option value="confirmed" {{ request('status')==='confirmed' ?'selected':'' }}>Confirmed</option>
                    <option value="completed" {{ request('status')==='completed' ?'selected':'' }}>Completed</option>
                    <option value="cancelled" {{ request('status')==='cancelled' ?'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:160px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Category</label>
                <select name="category" style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:140px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Date</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
            </div>
            <div style="display:flex;gap:.5rem;align-items:flex-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search','status','category','date']))
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-xmark"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-calendar-check"></i> All Appointments</h3>
        <span style="font-size:.78rem;color:var(--muted)">{{ $appointments->total() }} records</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Date & Time</th>
                    <th>Beautician</th>
                    <th>Price</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                <tr>
                    {{-- Client --}}
                    <td>
                        <div style="font-weight:600;font-size:.86rem">{{ $appt->client_name }}</div>
                        <div style="font-size:.75rem;color:var(--muted)">{{ $appt->client_phone }}</div>
                        @if($appt->client_email)
                            <div style="font-size:.72rem;color:var(--muted)">{{ $appt->client_email }}</div>
                        @endif
                    </td>

                    {{-- Service --}}
                    <td>
                        <div style="font-weight:600;font-size:.83rem;max-width:180px">{{ $appt->service_name }}</div>
                        <div style="font-size:.72rem;color:var(--muted)">{{ $appt->service_category }}</div>
                        <div style="font-size:.72rem;color:var(--muted)">
                            <i class="fas fa-clock" style="font-size:.6rem"></i>
                            {{ $appt->service_duration }} mins
                        </div>
                    </td>

                    {{-- Date & Time --}}
                    <td style="white-space:nowrap">
                        <div style="font-weight:600;font-size:.84rem">
                            {{ $appt->appointment_date->format('d M Y') }}
                        </div>
                        <div style="font-size:.78rem;color:var(--muted)">
                            <i class="fas fa-clock" style="font-size:.6rem"></i>
                            {{ $appt->appointment_time }}
                        </div>
                        @if($appt->appointment_date->isToday())
                            <span class="badge badge-pink" style="font-size:.62rem;margin-top:.2rem">Today</span>
                        @elseif($appt->appointment_date->isFuture())
                            <span class="badge badge-info" style="font-size:.62rem;margin-top:.2rem">Upcoming</span>
                        @endif
                    </td>

                    {{-- Beautician --}}
                    <td>
                        @if($appt->employee)
                            <div style="display:flex;align-items:center;gap:.5rem">
                                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:700;flex-shrink:0">
                                    {{ strtoupper(substr($appt->employee->name,0,2)) }}
                                </div>
                                <div>
                                    <div style="font-size:.8rem;font-weight:600">{{ $appt->employee->name }}</div>
                                    <div style="font-size:.68rem;color:var(--muted)">{{ $appt->employee->role_label }}</div>
                                </div>
                            </div>
                        @else
                            <span style="font-size:.78rem;color:var(--muted);font-style:italic">Unassigned</span>
                        @endif
                    </td>

                    {{-- Price --}}
                    <td>
                        <strong style="font-size:.86rem">{{ $appt->formatted_price }}</strong>
                        @if($appt->deposit_amount > 0)
                            <div style="font-size:.72rem;color:var(--muted)">
                                Deposit: {{ $appt->formatted_deposit }}
                            </div>
                        @endif
                    </td>

                    {{-- Payment --}}
                    <td>
                        <span class="badge {{ $appt->isPaid() ? 'badge-success' : 'badge-warning' }}">
                            {{ $appt->isPaid() ? 'Paid' : 'Unpaid' }}
                        </span>
                        @if($appt->mpesa_code)
                            <div style="font-size:.68rem;color:var(--muted);margin-top:.15rem;font-family:monospace">
                                {{ $appt->mpesa_code }}
                            </div>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge {{ $appt->status_badge }}">
                            {{ $appt->status_label }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div style="display:flex;gap:.35rem;flex-wrap:wrap">
                            <a href="{{ route('admin.appointments.show', $appt) }}"
                               class="btn btn-outline btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Quick status change --}}
                            @if($appt->status === 'pending')
                                <form action="{{ route('admin.appointments.status', $appt) }}" method="POST" style="margin:0">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-success btn-sm" title="Confirm">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            @if(in_array($appt->status, ['pending','confirmed']))
                                <form action="{{ route('admin.appointments.status', $appt) }}" method="POST" style="margin:0">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Cancel"
                                            onclick="return confirm('Cancel this appointment?')">
                                        <i class="fas fa-xmark"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-calendar-check"></i>
                            <p>No appointments found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $appointments->withQueryString()->links() }}</div>
</div>
@endsection