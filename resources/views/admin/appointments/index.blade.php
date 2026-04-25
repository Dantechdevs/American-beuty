@extends('layouts.admin')
@section('title', 'Appointments — American Beauty Admin')

@push('styles')
<style>
:root {
  --purple:    #7B2FBE;
  --purple-dk: #5A1F8A;
  --purple-lt: #EDE0F8;
  --magenta:   #C8359D;
  --magenta-lt:#F9E0F4;
  --green:     #3DB54A;
  --green-lt:  #E0F5E3;
  --amber:     #f4b942;
  --amber-lt:  #FFF8E7;
  --red:       #E53E3E;
  --red-lt:    #FEE2E2;
  --charcoal:  #1E1225;
  --gray:      #6B6478;
  --border:    rgba(123,47,190,.12);
  --white:     #fff;
}

/* ── PAGE HEADER ── */
.page-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
}
.page-header-left h1 {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem; font-weight: 700; color: var(--charcoal);
}
.page-header-left p { color: var(--gray); font-size: .88rem; margin-top: .2rem; }
.btn-primary {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; padding: .7rem 1.6rem; border-radius: 10px;
  font-size: .88rem; font-weight: 600; text-decoration: none;
  box-shadow: 0 4px 14px rgba(123,47,190,.3); transition: all .2s;
  display: inline-flex; align-items: center; gap: .5rem;
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(123,47,190,.4); }

/* ── STAT CARDS ── */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem; margin-bottom: 2rem;
}
.stat-card {
  background: var(--white); border-radius: 16px;
  padding: 1.3rem 1.5rem; border: 1px solid var(--border);
  box-shadow: 0 2px 12px rgba(123,47,190,.05);
  display: flex; align-items: center; gap: 1rem;
}
.stat-icon {
  width: 46px; height: 46px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem; flex-shrink: 0;
}
.stat-icon.purple  { background: var(--purple-lt); }
.stat-icon.amber   { background: var(--amber-lt); }
.stat-icon.green   { background: var(--green-lt); }
.stat-icon.magenta { background: var(--magenta-lt); }
.stat-icon.red     { background: var(--red-lt); }
.stat-icon.gray    { background: #F0EDF5; }
.stat-info strong {
  display: block; font-size: 1.6rem; font-weight: 700; color: var(--charcoal);
  line-height: 1;
}
.stat-info span { font-size: .75rem; color: var(--gray); margin-top: .2rem; display: block; }

/* ── FILTERS ── */
.filters-bar {
  background: var(--white); border-radius: 16px;
  padding: 1.2rem 1.5rem; border: 1px solid var(--border);
  margin-bottom: 1.5rem;
  display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;
}
.filter-group { display: flex; flex-direction: column; gap: .3rem; flex: 1; min-width: 140px; }
.filter-group label { font-size: .75rem; font-weight: 600; color: var(--gray); text-transform: uppercase; letter-spacing: .08em; }
.filter-group input,
.filter-group select {
  padding: .55rem .9rem; border: 1.5px solid var(--border);
  border-radius: 10px; font-size: .85rem; font-family: inherit;
  color: var(--charcoal); background: #fff; outline: none;
  transition: border-color .2s;
}
.filter-group input:focus,
.filter-group select:focus { border-color: var(--purple); }
.btn-filter {
  padding: .6rem 1.4rem; border-radius: 10px;
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; border: none; font-size: .85rem; font-weight: 600;
  cursor: pointer; font-family: inherit; transition: all .2s;
  white-space: nowrap;
}
.btn-filter:hover { transform: translateY(-1px); }
.btn-reset {
  padding: .6rem 1.2rem; border-radius: 10px;
  border: 1.5px solid var(--border); color: var(--gray);
  background: #fff; font-size: .85rem; font-weight: 600;
  cursor: pointer; font-family: inherit; text-decoration: none;
  transition: all .2s; white-space: nowrap;
  display: inline-flex; align-items: center;
}
.btn-reset:hover { border-color: var(--purple); color: var(--purple); }

/* ── TABLE CARD ── */
.table-card {
  background: var(--white); border-radius: 20px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 24px rgba(123,47,190,.06);
  overflow: hidden;
}
.table-card-header {
  padding: 1.2rem 1.8rem;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
.table-card-header h3 { font-size: 1rem; font-weight: 700; color: var(--charcoal); }
.table-card-header span { font-size: .8rem; color: var(--gray); }

table { width: 100%; border-collapse: collapse; }
thead tr {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
}
thead th {
  padding: .9rem 1.2rem; text-align: left;
  font-size: .75rem; font-weight: 700;
  color: #fff; letter-spacing: .08em; text-transform: uppercase;
  white-space: nowrap;
}
tbody tr {
  border-bottom: 1px solid var(--border);
  transition: background .15s;
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #FAF8FC; }
tbody td {
  padding: .9rem 1.2rem; font-size: .85rem;
  color: var(--charcoal); vertical-align: middle;
}

/* ── STATUS BADGES ── */
.badge {
  display: inline-flex; align-items: center; gap: .3rem;
  padding: .25rem .75rem; border-radius: 20px;
  font-size: .72rem; font-weight: 700; white-space: nowrap;
}
.badge-pending   { background: var(--amber-lt);   color: #92600A; }
.badge-confirmed { background: var(--green-lt);   color: #1A6B25; }
.badge-cancelled { background: var(--red-lt);     color: #9B1C1C; }
.badge-completed { background: var(--purple-lt);  color: var(--purple-dk); }
.badge-paid      { background: var(--green-lt);   color: #1A6B25; }
.badge-unpaid    { background: var(--amber-lt);   color: #92600A; }

/* ── ACTION BUTTONS ── */
.action-btns { display: flex; gap: .4rem; flex-wrap: wrap; }
.btn-action {
  padding: .3rem .75rem; border-radius: 8px;
  font-size: .72rem; font-weight: 600; cursor: pointer;
  border: none; font-family: inherit; transition: all .15s;
  text-decoration: none; display: inline-flex; align-items: center; gap: .3rem;
}
.btn-confirm  { background: var(--green-lt);  color: #1A6B25; }
.btn-confirm:hover  { background: var(--green); color: #fff; }
.btn-complete { background: var(--purple-lt); color: var(--purple-dk); }
.btn-complete:hover { background: var(--purple); color: #fff; }
.btn-cancel   { background: var(--red-lt);    color: #9B1C1C; }
.btn-cancel:hover   { background: var(--red);   color: #fff; }
.btn-view     { background: #F0EDF5;           color: var(--gray); }
.btn-view:hover     { background: var(--purple-lt); color: var(--purple); }

/* ── EMPTY STATE ── */
.empty-state {
  text-align: center; padding: 4rem 2rem;
  color: var(--gray);
}
.empty-state .icon { font-size: 3.5rem; margin-bottom: 1rem; }
.empty-state h3 { font-size: 1.1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .4rem; }
.empty-state p { font-size: .85rem; }

/* ── PAGINATION ── */
.pagination-wrap {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  font-size: .82rem; color: var(--gray); flex-wrap: wrap; gap: .5rem;
}

/* ── ALERTS ── */
.alert {
  padding: .9rem 1.2rem; border-radius: 12px;
  margin-bottom: 1.5rem; font-size: .85rem; font-weight: 500;
  display: flex; align-items: center; gap: .6rem;
}
.alert-success { background: var(--green-lt); color: #1A6B25; border: 1px solid rgba(61,181,74,.25); }
.alert-error   { background: var(--red-lt);   color: #9B1C1C; border: 1px solid rgba(229,62,62,.25); }

/* ── TODAY HIGHLIGHT ── */
.today-row { background: #FFFBF0 !important; }
.today-row:hover { background: #FFF8E0 !important; }

@media(max-width: 768px) {
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .filters-bar { flex-direction: column; }
  .filter-group { min-width: 100%; }
  .page-header { flex-direction: column; align-items: flex-start; }
  table { font-size: .78rem; }
  thead th, tbody td { padding: .7rem .8rem; }
}
</style>
@endpush

@section('content')

{{-- Alerts --}}
@if(session('success'))
  <div class="alert alert-success">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-error">❌ {{ session('error') }}</div>
@endif

{{-- Page Header --}}
<div class="page-header">
  <div class="page-header-left">
    <h1>📅 Appointments</h1>
    <p>Manage all client bookings from here</p>
  </div>
  <a href="{{ route('book.index') }}" target="_blank" class="btn-primary">
    ＋ New Booking
  </a>
</div>

{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon purple">📅</div>
    <div class="stat-info">
      <strong>{{ $total }}</strong>
      <span>Total</span>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber">⏳</div>
    <div class="stat-info">
      <strong>{{ $pending }}</strong>
      <span>Pending</span>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green">✅</div>
    <div class="stat-info">
      <strong>{{ $confirmed }}</strong>
      <span>Confirmed</span>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon magenta">🌟</div>
    <div class="stat-info">
      <strong>{{ $today }}</strong>
      <span>Today</span>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple">💜</div>
    <div class="stat-info">
      <strong>{{ $completed }}</strong>
      <span>Completed</span>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon red">❌</div>
    <div class="stat-info">
      <strong>{{ $cancelled }}</strong>
      <span>Cancelled</span>
    </div>
  </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.appointments.index') }}">
  <div class="filters-bar">
    <div class="filter-group">
      <label>Search</label>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, phone, service...">
    </div>
    <div class="filter-group">
      <label>Status</label>
      <select name="status">
        <option value="">All Statuses</option>
        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
      </select>
    </div>
    <div class="filter-group">
      <label>Category</label>
      <select name="category">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
      </select>
    </div>
    <div class="filter-group">
      <label>Date</label>
      <input type="date" name="date" value="{{ request('date') }}">
    </div>
    <button type="submit" class="btn-filter">🔍 Filter</button>
    <a href="{{ route('admin.appointments.index') }}" class="btn-reset">✕ Reset</a>
  </div>
</form>

{{-- Table --}}
<div class="table-card">
  <div class="table-card-header">
    <h3>All Appointments</h3>
    <span>{{ $appointments->total() }} total</span>
  </div>

  @if($appointments->isEmpty())
    <div class="empty-state">
      <div class="icon">📭</div>
      <h3>No appointments found</h3>
      <p>Bookings will appear here once clients book through your website.</p>
    </div>
  @else
  <div style="overflow-x:auto">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Client</th>
          <th>Service</th>
          <th>Date & Time</th>
          <th>Price</th>
          <th>Payment</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($appointments as $appt)
        <tr class="{{ $appt->appointment_date->isToday() ? 'today-row' : '' }}">

          {{-- ID --}}
          <td style="color:var(--gray);font-size:.78rem">
            #{{ str_pad($appt->id, 5, '0', STR_PAD_LEFT) }}
            @if($appt->appointment_date->isToday())
              <span style="display:block;font-size:.68rem;color:var(--magenta);font-weight:700">TODAY</span>
            @endif
          </td>

          {{-- Client --}}
          <td>
            <div style="font-weight:600">{{ $appt->client_name }}</div>
            <div style="font-size:.75rem;color:var(--gray)">{{ $appt->client_phone }}</div>
            @if($appt->client_email)
              <div style="font-size:.72rem;color:var(--gray)">{{ $appt->client_email }}</div>
            @endif
          </td>

          {{-- Service --}}
          <td>
            <div style="font-weight:500">{{ $appt->service_name }}</div>
            <div style="font-size:.72rem;color:var(--purple);font-weight:600">{{ $appt->service_category }}</div>
            <div style="font-size:.72rem;color:var(--gray)">
              {{ $appt->service_duration >= 60
                ? floor($appt->service_duration/60).'hr'.($appt->service_duration%60 ? ' '.($appt->service_duration%60).'min' : '')
                : $appt->service_duration.'min' }}
            </div>
          </td>

          {{-- Date & Time --}}
          <td>
            <div style="font-weight:600">{{ $appt->appointment_date->format('M d, Y') }}</div>
            <div style="font-size:.78rem;color:var(--gray)">{{ $appt->appointment_time }}</div>
          </td>

          {{-- Price --}}
          <td>
            <div style="font-weight:700;color:var(--purple)">
              {{ $appt->service_price == 0 ? 'Free' : 'KSh '.number_format($appt->service_price) }}
            </div>
          </td>

          {{-- Payment --}}
          <td>
            <span class="badge {{ $appt->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
              {{ $appt->payment_status === 'paid' ? '✅ Paid' : '⏳ Unpaid' }}
            </span>
            @if($appt->mpesa_code)
              <div style="font-size:.7rem;color:var(--gray);margin-top:.2rem">{{ $appt->mpesa_code }}</div>
            @endif
          </td>

          {{-- Status --}}
          <td>
            <span class="badge badge-{{ $appt->status }}">
              @switch($appt->status)
                @case('pending')   ⏳ Pending   @break
                @case('confirmed') ✅ Confirmed @break
                @case('completed') 💜 Completed @break
                @case('cancelled') ❌ Cancelled @break
              @endswitch
            </span>
          </td>

          {{-- Actions --}}
          <td>
            <div class="action-btns">

              {{-- Confirm --}}
              @if($appt->status === 'pending')
              <form method="POST" action="{{ route('admin.appointments.status', $appt) }}" style="display:inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button type="submit" class="btn-action btn-confirm">✅ Confirm</button>
              </form>
              @endif

              {{-- Complete --}}
              @if(in_array($appt->status, ['pending','confirmed']))
              <form method="POST" action="{{ route('admin.appointments.status', $appt) }}" style="display:inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn-action btn-complete">💜 Done</button>
              </form>
              @endif

              {{-- Cancel --}}
              @if(!in_array($appt->status, ['cancelled','completed']))
              <form method="POST" action="{{ route('admin.appointments.status', $appt) }}" style="display:inline"
                onsubmit="return confirm('Cancel this appointment?')">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="btn-action btn-cancel">❌ Cancel</button>
              </form>
              @endif

              {{-- Mark Paid --}}
              @if($appt->payment_status === 'unpaid' && $appt->service_price > 0)
              <form method="POST" action="{{ route('admin.appointments.payment', $appt) }}" style="display:inline">
                @csrf @method('PATCH')
                <input type="hidden" name="payment_status" value="paid">
                <button type="submit" class="btn-action btn-confirm">💰 Paid</button>
              </form>
              @endif

              {{-- Delete --}}
              <form method="POST" action="{{ route('admin.appointments.destroy', $appt) }}" style="display:inline"
                onsubmit="return confirm('Permanently delete this appointment?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-action btn-cancel">🗑</button>
              </form>

            </div>
          </td>

        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="pagination-wrap">
    <span>Showing {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }} of {{ $appointments->total() }}</span>
    {{ $appointments->links() }}
  </div>
  @endif

</div>

@endsection