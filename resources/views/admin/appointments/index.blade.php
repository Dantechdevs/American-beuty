@extends('layouts.admin')
@section('title', 'Appointments')

@section('content')
@push('styles')
<style>
@media(max-width:900px){
  .stats-grid{grid-template-columns:repeat(3,1fr) !important;}
  .filter-card .d-flex{flex-direction:column !important;gap:.75rem !important;}
  .filter-card select,.filter-card input{width:100% !important;}
}
@media(max-width:500px){
  .stats-grid{grid-template-columns:repeat(2,1fr) !important;}
  .stat-card{padding:.75rem !important;}
  .stat-val{font-size:1.2rem !important;}
}
</style>
@endpush


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
<div class="stats-grid" style="grid-template-columns:repeat(6,1fr);margin-bottom:1.5rem;overflow-x:auto">
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

{{-- ══════════════════════════════════════
     WALK-IN MODAL
══════════════════════════════════════ --}}
<div id="walkinModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:580px;max-height:90vh;overflow-y:auto;box-shadow:0 24px 60px rgba(0,0,0,.18);margin:1rem">
        <form action="{{ route('admin.appointments.store') }}" method="POST">
            @csrf
            {{-- Header --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1.5px solid var(--border);background:linear-gradient(120deg,#fff,var(--pink-soft))">
                <div style="display:flex;align-items:center;gap:.75rem">
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-person-walking-arrow-right" style="color:#fff;font-size:.85rem"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.95rem;color:var(--text)">Add Walk-in Client</div>
                        <div style="font-size:.75rem;color:var(--muted)">Record a manual visit or appointment</div>
                    </div>
                </div>
                <button type="button" onclick="closeModal('walkinModal')" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--muted)">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            {{-- Body --}}
            <div style="padding:1.5rem">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group" style="grid-column:span 2">
                        <label>Client Name <span style="color:var(--pink)">*</span></label>
                        <input type="text" name="client_name" class="form-control" placeholder="e.g. Jane Doe" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="client_phone" class="form-control" placeholder="07…">
                    </div>
                    <div class="form-group">
                        <label>Served By</label>
                        <select name="served_by" class="form-control">
                            <option value="">Select staff…</option>
                            @foreach(\App\Models\Employee::where("is_active",true)->orderBy("name")->get() as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Divider --}}
                    <div style="grid-column:span 2;display:flex;align-items:center;gap:.75rem;margin:.1rem 0">
                        <div style="height:1px;flex:1;background:linear-gradient(to right,var(--border),transparent)"></div>
                        <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--purple)"><i class="fas fa-spa" style="margin-right:.3rem"></i>Service</span>
                        <div style="height:1px;flex:1;background:linear-gradient(to left,var(--border),transparent)"></div>
                    </div>
                    <div class="form-group" style="grid-column:span 2">
                        <label>Service Name <span style="color:var(--pink)">*</span></label>
                        <input type="text" name="service_name" class="form-control" placeholder="e.g. Express HydraFacial" required list="servicesList">
                        <datalist id="servicesList">
                            @foreach(\App\Models\Service::where("is_active",true)->orderBy("name")->get() as $svc)
                            <option value="{{ $svc->name }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="service_category" class="form-control" placeholder="e.g. HydraFacials">
                    </div>
                    <div class="form-group">
                        <label>Service Price (KSh)</label>
                        <input type="number" name="service_price" class="form-control" placeholder="6000" min="0" step="0.01">
                    </div>
                    {{-- Divider --}}
                    <div style="grid-column:span 2;display:flex;align-items:center;gap:.75rem;margin:.1rem 0">
                        <div style="height:1px;flex:1;background:linear-gradient(to right,var(--border),transparent)"></div>
                        <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--purple)"><i class="fas fa-calendar" style="margin-right:.3rem"></i>Date & Time</span>
                        <div style="height:1px;flex:1;background:linear-gradient(to left,var(--border),transparent)"></div>
                    </div>
                    <div class="form-group">
                        <label>Date <span style="color:var(--pink)">*</span></label>
                        <input type="date" name="appointment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Time <span style="color:var(--pink)">*</span></label>
                        <input type="time" name="appointment_time" class="form-control" value="{{ date('H:i') }}" required>
                    </div>
                    {{-- Divider --}}
                    <div style="grid-column:span 2;display:flex;align-items:center;gap:.75rem;margin:.1rem 0">
                        <div style="height:1px;flex:1;background:linear-gradient(to right,var(--border),transparent)"></div>
                        <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--purple)"><i class="fas fa-money-bill" style="margin-right:.3rem"></i>Payment</span>
                        <div style="height:1px;flex:1;background:linear-gradient(to left,var(--border),transparent)"></div>
                    </div>
                    <div class="form-group">
                        <label>Amount Paid (KSh)</label>
                        <input type="number" name="amount_paid" class="form-control" placeholder="0" min="0" step="0.01" value="0">
                    </div>
                    <div class="form-group">
                        <label>Payment Status <span style="color:var(--pink)">*</span></label>
                        <select name="payment_status" class="form-control" required>
                            <option value="unpaid">Unpaid</option>
                            <option value="deposit">Deposit Paid</option>
                            <option value="paid">Fully Paid</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status <span style="color:var(--pink)">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any additional notes…"></textarea>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div style="display:flex;justify-content:flex-end;gap:.75rem;padding:1rem 1.5rem;border-top:1.5px solid var(--border);background:#fafafa;border-radius:0 0 16px 16px">
                <button type="button" onclick="closeModal('walkinModal')" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save" style="margin-right:.35rem"></i> Save Walk-in</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(id) { const m=document.getElementById(id); m.style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id) { const m=document.getElementById(id); m.style.display='none'; document.body.style.overflow=''; }
document.getElementById('walkinModal').addEventListener('click', function(e){ if(e.target===this) closeModal('walkinModal'); });
</script>
@endpush
@endsection