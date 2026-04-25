@extends('layouts.app')
@section('title', 'Appointment Confirmed — American Beauty')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap');

:root {
  --purple:    #7B2FBE;
  --purple-lt: #EDE0F8;
  --magenta:   #C8359D;
  --magenta-lt:#F9E0F4;
  --green:     #3DB54A;
  --green-lt:  #E0F5E3;
  --off-white: #FAF8FC;
  --charcoal:  #1E1225;
  --gray:      #6B6478;
  --border:    rgba(123,47,190,.15);
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Poppins', sans-serif; background: var(--off-white); color: var(--charcoal); }

/* ── PAGE WRAP ── */
.success-wrap {
  max-width: 680px;
  margin: 4rem auto;
  padding: 0 1.5rem 4rem;
}

/* ── CONFETTI HERO ── */
.success-hero {
  text-align: center;
  padding: 3rem 2rem 2rem;
  background: linear-gradient(135deg, #FAF4FF 0%, #F5E0FC 50%, #EFF8F0 100%);
  border-radius: 24px;
  border: 1px solid var(--border);
  margin-bottom: 1.5rem;
  position: relative;
  overflow: hidden;
}
.success-hero::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(123,47,190,.06) 1px, transparent 1px),
    linear-gradient(90deg, rgba(123,47,190,.06) 1px, transparent 1px);
  background-size: 50px 50px;
  pointer-events: none;
}
.success-icon {
  width: 90px; height: 90px;
  background: linear-gradient(135deg, var(--green), #28a035);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 2.5rem;
  margin: 0 auto 1.5rem;
  box-shadow: 0 12px 40px rgba(61,181,74,.35);
  position: relative;
  animation: popIn .5s cubic-bezier(.175,.885,.32,1.275) forwards;
}
@keyframes popIn {
  0%   { transform: scale(0); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}
.success-hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.8rem, 3.5vw, 2.4rem);
  font-weight: 700; color: var(--charcoal);
  margin-bottom: .6rem; position: relative;
}
.success-hero h1 span { color: var(--purple); }
.success-hero p {
  color: var(--gray); font-size: .9rem;
  line-height: 1.7; max-width: 420px;
  margin: 0 auto; position: relative;
}

/* ── BOOKING CARD ── */
.booking-card {
  background: #fff;
  border-radius: 20px;
  border: 1px solid var(--border);
  box-shadow: 0 8px 40px rgba(123,47,190,.08);
  overflow: hidden;
  margin-bottom: 1.5rem;
}
.booking-card-header {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  padding: 1.2rem 1.8rem;
  display: flex; align-items: center; justify-content: space-between;
}
.booking-card-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.1rem; font-weight: 700; color: #fff;
}
.booking-id {
  font-size: .72rem; color: rgba(255,255,255,.75);
  background: rgba(255,255,255,.15);
  padding: .3rem .8rem; border-radius: 20px;
  font-weight: 600; letter-spacing: .05em;
}
.booking-card-body { padding: 1.8rem; }

.detail-row {
  display: flex; align-items: flex-start;
  gap: 1rem; padding: .85rem 0;
  border-bottom: 1px solid var(--border);
}
.detail-row:last-child { border-bottom: none; }
.detail-icon {
  width: 38px; height: 38px; border-radius: 10px;
  background: var(--purple-lt);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; flex-shrink: 0;
}
.detail-content { flex: 1; }
.detail-label { font-size: .72rem; color: var(--gray); text-transform: uppercase; letter-spacing: .1em; margin-bottom: .2rem; }
.detail-value { font-size: .92rem; font-weight: 600; color: var(--charcoal); }
.detail-value.price { color: var(--purple); font-size: 1rem; }
.detail-value.free  { color: var(--green); }

/* ── STATUS BADGE ── */
.status-badge {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .35rem .9rem; border-radius: 30px;
  font-size: .78rem; font-weight: 700;
  background: var(--green-lt); color: var(--green);
  border: 1px solid rgba(61,181,74,.25);
}
.status-dot {
  width: 7px; height: 7px; border-radius: 50%;
  background: var(--green);
  animation: pulse 1.5s infinite;
}
@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: .5; transform: scale(1.3); }
}

/* ── ACTIONS ── */
.actions {
  display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
  margin-bottom: 1.5rem;
}
.btn-whatsapp {
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  padding: .9rem; border-radius: 14px;
  background: #25D366; color: #fff;
  text-decoration: none; font-weight: 700;
  font-size: .88rem; font-family: 'Poppins', sans-serif;
  box-shadow: 0 6px 20px rgba(37,211,102,.3);
  transition: all .25s;
}
.btn-whatsapp:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(37,211,102,.4); }

.btn-book-again {
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  padding: .9rem; border-radius: 14px;
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; text-decoration: none; font-weight: 700;
  font-size: .88rem; font-family: 'Poppins', sans-serif;
  box-shadow: 0 6px 20px rgba(123,47,190,.3);
  transition: all .25s;
}
.btn-book-again:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(123,47,190,.4); }

.btn-shop {
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  padding: .9rem; border-radius: 14px;
  border: 2px solid var(--purple); color: var(--purple);
  text-decoration: none; font-weight: 700;
  font-size: .88rem; font-family: 'Poppins', sans-serif;
  background: transparent; transition: all .25s;
  grid-column: span 2;
}
.btn-shop:hover { background: var(--purple); color: #fff; }

/* ── NOTE CARD ── */
.note-card {
  background: var(--magenta-lt);
  border: 1px solid rgba(200,53,157,.2);
  border-radius: 16px; padding: 1.2rem 1.5rem;
}
.note-card h4 { font-size: .85rem; font-weight: 700; color: var(--magenta); margin-bottom: .6rem; }
.note-card ul { list-style: none; }
.note-card ul li {
  font-size: .8rem; color: #8B1A6B;
  padding: .2rem 0; display: flex; gap: .5rem;
}

@media(max-width: 600px) {
  .actions { grid-template-columns: 1fr; }
  .btn-shop { grid-column: span 1; }
  .booking-card-header { flex-direction: column; align-items: flex-start; gap: .5rem; }
}
</style>
@endpush

@section('content')

<div class="success-wrap">

  {{-- Hero --}}
  <div class="success-hero">
    <div class="success-icon">✓</div>
    <h1>You're <span>Booked!</span></h1>
    <p>Your appointment has been received. We'll confirm shortly via SMS or a call. We can't wait to see you! 💜</p>
  </div>

  {{-- Booking Details Card --}}
  <div class="booking-card">
    <div class="booking-card-header">
      <h2>📋 Appointment Details</h2>
      <span class="booking-id">#{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="booking-card-body">

      <div class="detail-row">
        <div class="detail-icon">👤</div>
        <div class="detail-content">
          <div class="detail-label">Client</div>
          <div class="detail-value">{{ $appointment->client_name }}</div>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-icon">📱</div>
        <div class="detail-content">
          <div class="detail-label">Phone</div>
          <div class="detail-value">{{ $appointment->client_phone }}</div>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-icon">✨</div>
        <div class="detail-content">
          <div class="detail-label">Service</div>
          <div class="detail-value">{{ $appointment->service_name }}</div>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-icon">📅</div>
        <div class="detail-content">
          <div class="detail-label">Date & Time</div>
          <div class="detail-value">
            {{ $appointment->appointment_date->format('l, F j, Y') }}
            at {{ $appointment->appointment_time }}
          </div>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-icon">⏱️</div>
        <div class="detail-content">
          <div class="detail-label">Duration</div>
          <div class="detail-value">
            @php
              $d = $appointment->service_duration;
              echo $d >= 60
                ? floor($d/60).'hr'.($d%60 ? ' '.($d%60).'min' : '')
                : $d.' min';
            @endphp
          </div>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-icon">💰</div>
        <div class="detail-content">
          <div class="detail-label">Price</div>
          <div class="detail-value {{ $appointment->service_price == 0 ? 'free' : 'price' }}">
            {{ $appointment->service_price == 0 ? 'Free' : 'KSh '.number_format($appointment->service_price) }}
          </div>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-icon">🔖</div>
        <div class="detail-content">
          <div class="detail-label">Status</div>
          <div class="detail-value">
            <span class="status-badge">
              <span class="status-dot"></span>
              Pending Confirmation
            </span>
          </div>
        </div>
      </div>

      @if($appointment->notes)
      <div class="detail-row">
        <div class="detail-icon">📝</div>
        <div class="detail-content">
          <div class="detail-label">Notes</div>
          <div class="detail-value">{{ $appointment->notes }}</div>
        </div>
      </div>
      @endif

    </div>
  </div>

  {{-- Actions --}}
  <div class="actions">
    <a href="https://wa.me/254700000000?text={{ urlencode('Hi American Beauty! I just booked an appointment for '.$appointment->service_name.' on '.$appointment->appointment_date->format('M j, Y').' at '.$appointment->appointment_time.'. My name is '.$appointment->client_name.'. Booking #'.str_pad($appointment->id,5,'0',STR_PAD_LEFT)) }}"
       target="_blank" class="btn-whatsapp">
      💬 WhatsApp Us
    </a>
    <a href="{{ route('book.index') }}" class="btn-book-again">
      📅 Book Again
    </a>
    <a href="{{ route('products.index') }}" class="btn-shop">
      🛍️ Shop Our Products
    </a>
  </div>

  {{-- Note --}}
  <div class="note-card">
    <h4>💜 What happens next?</h4>
    <ul>
      <li>📞 We'll call or text to confirm your appointment</li>
      <li>📍 Arrive 5 minutes before your scheduled time</li>
      <li>💳 Pay on arrival — cash or M-PESA accepted</li>
      <li>❌ Cancel 24 hours in advance if needed</li>
      <li>❓ Questions? WhatsApp us using the button above</li>
    </ul>
  </div>

</div>

@endsection