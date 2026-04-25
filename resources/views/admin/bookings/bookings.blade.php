@extends('layouts.app')
@section('title', 'Bookings Dashboard')

@section('content')
<div style="max-width:1100px;margin:3rem auto;padding:0 1.5rem">
  <h1 style="font-family:'Playfair Display',serif;color:#7B2FBE;margin-bottom:2rem">
    📅 Bookings Dashboard
  </h1>

  {{-- Summary Cards --}}
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2.5rem">
    <div style="background:#fff;border-radius:16px;padding:1.5rem;border:1px solid rgba(123,47,190,.15);text-align:center">
      <strong style="font-size:2rem;color:#7B2FBE">{{ $total }}</strong>
      <p style="color:#6B6478;font-size:.85rem">Total Bookings</p>
    </div>
    <div style="background:#fff;border-radius:16px;padding:1.5rem;border:1px solid rgba(123,47,190,.15);text-align:center">
      <strong style="font-size:2rem;color:#3DB54A">{{ $confirmed }}</strong>
      <p style="color:#6B6478;font-size:.85rem">Confirmed</p>
    </div>
    <div style="background:#fff;border-radius:16px;padding:1.5rem;border:1px solid rgba(123,47,190,.15);text-align:center">
      <strong style="font-size:2rem;color:#C8359D">{{ $cancelled }}</strong>
      <p style="color:#6B6478;font-size:.85rem">Cancelled</p>
    </div>
    <div style="background:#fff;border-radius:16px;padding:1.5rem;border:1px solid rgba(123,47,190,.15);text-align:center">
      <strong style="font-size:2rem;color:#f4b942">{{ $today }}</strong>
      <p style="color:#6B6478;font-size:.85rem">Today</p>
    </div>
  </div>

  {{-- Bookings Table --}}
  <div style="background:#fff;border-radius:20px;overflow:hidden;border:1px solid rgba(123,47,190,.15)">
    <table style="width:100%;border-collapse:collapse;font-size:.88rem">
      <thead>
        <tr style="background:linear-gradient(135deg,#7B2FBE,#C8359D);color:#fff">
          <th style="padding:1rem 1.2rem;text-align:left">Square ID</th>
          <th style="padding:1rem 1.2rem;text-align:left">Customer</th>
          <th style="padding:1rem 1.2rem;text-align:left">Service</th>
          <th style="padding:1rem 1.2rem;text-align:left">Appointment</th>
          <th style="padding:1rem 1.2rem;text-align:left">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bookings as $b)
        <tr style="border-bottom:1px solid rgba(123,47,190,.08)">
          <td style="padding:.9rem 1.2rem;font-size:.75rem;color:#6B6478">{{ Str::limit($b->square_booking_id,12) }}</td>
          <td style="padding:.9rem 1.2rem">{{ $b->customer_name ?? '—' }}</td>
          <td style="padding:.9rem 1.2rem">{{ $b->service_name ?? '—' }}</td>
          <td style="padding:.9rem 1.2rem">{{ $b->appointment_at?->format('M d, Y H:i') ?? '—' }}</td>
          <td style="padding:.9rem 1.2rem">
            <span style="
              padding:.25rem .8rem;border-radius:20px;font-size:.75rem;font-weight:600;
              background:{{ $b->status === 'confirmed' ? '#E0F5E3' : ($b->status === 'cancelled' ? '#FCE4EC' : '#EDE0F8') }};
              color:{{ $b->status === 'confirmed' ? '#3DB54A' : ($b->status === 'cancelled' ? '#C8359D' : '#7B2FBE') }}
            ">{{ ucfirst($b->status) }}</span>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="padding:2rem;text-align:center;color:#6B6478">No bookings yet.</td></tr>
        @endforelse
      </tbody>
    </table>
    <div style="padding:1rem 1.2rem">{{ $bookings->links() }}</div>
  </div>
</div>
@endsection