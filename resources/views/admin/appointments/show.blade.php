@extends('layouts.admin')
@section('title', 'Appointment — ' . $appointment->client_name)

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-calendar-check" style="color:var(--pink)"></i>
            Appointment #{{ $appointment->id }}
        </div>
        <div class="page-sub">
            Booked {{ $appointment->created_at->diffForHumans() }}
        </div>
    </div>
    <div style="display:flex;gap:.65rem;flex-wrap:wrap">
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST"
              onsubmit="return confirm('Delete this appointment permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.25rem">

    {{-- ── LEFT COLUMN ── --}}
    <div>

        {{-- Client Info --}}
        <div class="card" style="margin-bottom:1.25rem">
            <div class="card-header">
                <h3><i class="fas fa-user"></i> Client Information</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div>
                        <div style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Name</div>
                        <div style="font-weight:600">{{ $appointment->client_name }}</div>
                    </div>
                    <div>
                        <div style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Phone</div>
                        <div>
                            <a href="tel:{{ $appointment->client_phone }}" style="font-weight:600;color:var(--purple)">
                                {{ $appointment->client_phone }}
                            </a>
                            &nbsp;
                            <a href="https://wa.me/254{{ ltrim($appointment->client_phone,'0') }}"
                               target="_blank" style="color:#25D366;font-size:.8rem">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                    @if($appointment->client_email)
                    <div>
                        <div style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Email</div>
                        <div>
                            <a href="mailto:{{ $appointment->client_email }}" style="color:var(--purple)">
                                {{ $appointment->client_email }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Service Info --}}
        <div class="card" style="margin-bottom:1.25rem">
            <div class="card-header">
                <h3><i class="fas fa-spa"></i> Service Details</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div>
                        <div style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Service</div>
                        <div style="font-weight:600">{{ $appointment->service_name }}</div>
                        <div style="font-size:.78rem;color:var(--muted)">{{ $appointment->service_category }}</div>
                    </div>
                    <div>
                        <div style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Duration</div>
                        <div style="font-weight:600">{{ $appointment->service_duration }} minutes</div>
                    </div>
                    <div>
                        <div style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Date & Time</div>
                        <div style="font-weight:600">{{ $appointment->appointment_date->format('l, d M Y') }}</div>
                        <div style="font-size:.82rem;color:var(--muted)">at {{ $appointment->appointment_time }}</div>
                    </div>
                    <div>
                        <div style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Price</div>
                        <div style="font-weight:700;font-size:1.1rem;color:var(--purple)">{{ $appointment->formatted_price }}</div>
                    </div>
                </div>

                @if($appointment->notes)
                <div style="margin-top:1rem;padding-top:1rem;border-top:1.5px solid var(--border)">
                    <div style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.4rem">Client Notes</div>
                    <div style="font-size:.85rem;color:var(--text);background:var(--purple-soft);padding:.75rem 1rem;border-radius:var(--r-sm)">
                        {{ $appointment->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Assign Beautician --}}
        <div class="card" style="margin-bottom:1.25rem">
            <div class="card-header">
                <h3><i class="fas fa-user-nurse"></i> Assign Beautician</h3>
            </div>
            <div class="card-body">
                @if($appointment->employee)
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;padding:.75rem;background:var(--green-soft);border-radius:var(--r-sm);border:1.5px solid #bbf7d0">
                        <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0">
                            {{ strtoupper(substr($appointment->employee->name,0,2)) }}
                        </div>
                        <div style="flex:1">
                            <div style="font-weight:700;font-size:.88rem">{{ $appointment->employee->name }}</div>
                            <div style="font-size:.74rem;color:var(--muted)">{{ $appointment->employee->role_label }}</div>
                            @if($appointment->assignedBy)
                                <div style="font-size:.7rem;color:var(--muted)">
                                    Assigned by {{ $appointment->assignedBy->name }}
                                </div>
                            @endif
                        </div>
                        <form action="{{ route('admin.appointments.unassign', $appointment) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-user-slash"></i> Unassign
                            </button>
                        </form>
                    </div>
                @endif

                <form action="{{ route('admin.appointments.assign', $appointment) }}" method="POST"
                      style="display:flex;gap:.65rem;align-items:flex-end">
                    @csrf
                    <div style="flex:1">
                        <label style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.35rem">
                            {{ $appointment->employee ? 'Reassign to' : 'Assign to' }}
                        </label>
                        <select name="employee_id" required
                                style="width:100%;padding:.6rem .85rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
                            <option value="">— Select Beautician —</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $appointment->employee_id == $emp->id ? 'selected':'' }}>
                                    {{ $emp->name }} ({{ $emp->role_label }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-check"></i> Assign
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ── --}}
    <div>

        {{-- Status Card --}}
        <div class="card" style="margin-bottom:1.25rem">
            <div class="card-header">
                <h3><i class="fas fa-circle-info"></i> Status</h3>
            </div>
            <div class="card-body">
                <div style="text-align:center;margin-bottom:1.25rem">
                    <span class="badge {{ $appointment->status_badge }}"
                          style="font-size:.85rem;padding:.4rem 1.1rem">
                        {{ $appointment->status_label }}
                    </span>
                </div>

                {{-- Status timeline --}}
                <div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.25rem">
                    @foreach(['pending'=>'Pending','confirmed'=>'Confirmed','completed'=>'Completed','cancelled'=>'Cancelled'] as $s => $label)
                    <div style="display:flex;align-items:center;gap:.65rem;opacity:{{ $appointment->status === $s ? '1' : '.45' }}">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{
                            $s==='confirmed' ? 'var(--green)' : ($s==='cancelled' ? 'var(--tango)' : ($s==='completed' ? 'var(--purple)' : 'var(--gold)'))
                        }};flex-shrink:0"></div>
                        <span style="font-size:.82rem;font-weight:{{ $appointment->status===$s ? '700' : '400' }}">
                            {{ $label }}
                        </span>
                        @if($s==='confirmed' && $appointment->confirmed_at)
                            <span style="font-size:.7rem;color:var(--muted);margin-left:auto">{{ $appointment->confirmed_at->format('d M H:i') }}</span>
                        @elseif($s==='completed' && $appointment->completed_at)
                            <span style="font-size:.7rem;color:var(--muted);margin-left:auto">{{ $appointment->completed_at->format('d M H:i') }}</span>
                        @elseif($s==='cancelled' && $appointment->cancelled_at)
                            <span style="font-size:.7rem;color:var(--muted);margin-left:auto">{{ $appointment->cancelled_at->format('d M H:i') }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Update status form --}}
                <form action="{{ route('admin.appointments.status', $appointment) }}" method="POST">
                    @csrf @method('PATCH')
                    <div style="margin-bottom:.75rem">
                        <label style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.35rem">
                            Change Status
                        </label>
                        <select name="status" required
                                style="width:100%;padding:.6rem .85rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
                            <option value="pending"   {{ $appointment->status==='pending'   ?'selected':'' }}>Pending</option>
                            <option value="confirmed" {{ $appointment->status==='confirmed' ?'selected':'' }}>Confirmed</option>
                            <option value="completed" {{ $appointment->status==='completed' ?'selected':'' }}>Completed</option>
                            <option value="cancelled" {{ $appointment->status==='cancelled' ?'selected':'' }}>Cancelled</option>
                        </select>
                    </div>
                    <div style="margin-bottom:.75rem" id="cancel-reason-wrap"
                         style="{{ $appointment->status==='cancelled'?'':'display:none' }}">
                        <label style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.35rem">
                            Cancellation Reason
                        </label>
                        <textarea name="cancellation_reason" rows="2"
                                  placeholder="Reason for cancellation…"
                                  style="width:100%;padding:.6rem .85rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.82rem;font-family:inherit;outline:none;resize:vertical">{{ $appointment->cancellation_reason }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        <i class="fas fa-floppy-disk"></i> Update Status
                    </button>
                </form>

                @if($appointment->cancellation_reason)
                    <div style="margin-top:.75rem;padding:.65rem .85rem;background:var(--tango-soft);border-radius:var(--r-sm);font-size:.78rem;color:var(--tango)">
                        <strong>Reason:</strong> {{ $appointment->cancellation_reason }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Payment Card --}}
        <div class="card" style="margin-bottom:1.25rem">
            <div class="card-header">
                <h3><i class="fas fa-credit-card"></i> Payment</h3>
            </div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.65rem">
                    <span style="font-size:.82rem;color:var(--muted)">Service Price</span>
                    <strong>{{ $appointment->formatted_price }}</strong>
                </div>
                @if($appointment->deposit_amount > 0)
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.65rem">
                    <span style="font-size:.82rem;color:var(--muted)">Deposit Paid</span>
                    <strong style="color:var(--green)">{{ $appointment->formatted_deposit }}</strong>
                </div>
                @endif
                @if($appointment->mpesa_code)
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.65rem">
                    <span style="font-size:.82rem;color:var(--muted)">M-Pesa Code</span>
                    <strong style="font-family:monospace;font-size:.82rem">{{ $appointment->mpesa_code }}</strong>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:.65rem;border-top:1.5px solid var(--border)">
                    <span style="font-size:.82rem;color:var(--muted)">Payment Status</span>
                    <span class="badge {{ $appointment->isPaid() ? 'badge-success' : 'badge-warning' }}">
                        {{ $appointment->isPaid() ? 'Paid' : 'Unpaid' }}
                    </span>
                </div>

                {{-- WhatsApp quick message --}}
                <div style="margin-top:1rem;padding-top:1rem;border-top:1.5px solid var(--border)">
                    <a href="https://wa.me/254{{ ltrim($appointment->client_phone,'0') }}?text={{ urlencode('Hello '.$appointment->client_name.'! Your appointment for '.$appointment->service_name.' on '.$appointment->appointment_date->format('d M Y').' at '.$appointment->appointment_time.' has been confirmed. Thank you — American Beauty Studio Spa.') }}"
                       target="_blank"
                       class="btn btn-success" style="width:100%;justify-content:center;background:#25D366;box-shadow:0 4px 14px rgba(37,211,102,.25)">
                        <i class="fab fa-whatsapp"></i> Send WhatsApp Confirmation
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.querySelector('select[name="status"]').addEventListener('change', function () {
    const wrap = document.getElementById('cancel-reason-wrap');
    wrap.style.display = this.value === 'cancelled' ? 'block' : 'none';
});
</script>
@endpush

@endsection