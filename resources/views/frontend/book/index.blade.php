@extends('layouts.app')
@section('title', 'Book an Appointment — American Beauty')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap');

:root {
  --purple:    #7B2FBE;
  --purple-dk: #5A1F8A;
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

/* ── PAGE HERO ── */
.book-hero {
  background: linear-gradient(135deg, #FAF4FF 0%, #F5E0FC 40%, #EFF8F0 100%);
  padding: 4rem 1.5rem 3rem;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.book-hero::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(123,47,190,.06) 1px, transparent 1px),
    linear-gradient(90deg, rgba(123,47,190,.06) 1px, transparent 1px);
  background-size: 60px 60px;
  pointer-events: none;
}
.book-hero-eyebrow {
  display: inline-flex; align-items: center; gap: .5rem;
  font-size: .75rem; letter-spacing: .22em; text-transform: uppercase;
  color: var(--magenta); font-weight: 600; margin-bottom: 1rem;
  background: var(--magenta-lt); padding: .35rem 1rem; border-radius: 40px;
  border: 1px solid rgba(200,53,157,.2); position: relative;
}
.book-hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem, 4vw, 3rem); font-weight: 700;
  color: var(--charcoal); margin-bottom: .8rem; position: relative;
}
.book-hero h1 span { color: var(--purple); }
.book-hero p {
  color: var(--gray); font-size: .95rem; max-width: 500px;
  margin: 0 auto; line-height: 1.7; position: relative;
}

/* ── LAYOUT ── */
.book-wrap {
  max-width: 1100px; margin: 3rem auto; padding: 0 1.5rem;
  display: grid; grid-template-columns: 1fr 360px; gap: 2rem;
}

/* ── FORM CARD ── */
.form-card {
  background: #fff; border-radius: 24px;
  border: 1px solid var(--border);
  box-shadow: 0 8px 40px rgba(123,47,190,.08);
  overflow: hidden;
}
.form-card-header {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  padding: 1.5rem 2rem; color: #fff;
}
.form-card-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem; font-weight: 700;
}
.form-card-header p { font-size: .82rem; opacity: .85; margin-top: .3rem; }
.form-card-body { padding: 2rem; }

/* ── STEPS ── */
.steps {
  display: flex; gap: 0; margin-bottom: 2rem;
  border-bottom: 2px solid var(--border); padding-bottom: 1.2rem;
}
.step {
  flex: 1; text-align: center; position: relative;
}
.step-num {
  width: 32px; height: 32px; border-radius: 50%;
  background: var(--purple-lt); color: var(--purple);
  font-size: .82rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto .4rem;
  transition: all .3s;
}
.step.active .step-num {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff;
}
.step.done .step-num {
  background: var(--green); color: #fff;
}
.step-label { font-size: .72rem; color: var(--gray); font-weight: 500; }
.step.active .step-label { color: var(--purple); font-weight: 600; }

/* ── SECTION TITLES ── */
.field-section { margin-bottom: 1.8rem; }
.field-section-title {
  font-size: .78rem; letter-spacing: .15em; text-transform: uppercase;
  color: var(--magenta); font-weight: 600; margin-bottom: 1rem;
  display: flex; align-items: center; gap: .5rem;
}
.field-section-title::after {
  content: ''; flex: 1; height: 1px; background: var(--border);
}

/* ── FIELDS ── */
.field-group { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.field { margin-bottom: 1rem; }
.field label {
  display: block; font-size: .82rem; font-weight: 600;
  color: var(--charcoal); margin-bottom: .4rem;
}
.field label span { color: var(--magenta); }
.field input, .field select, .field textarea {
  width: 100%; padding: .75rem 1rem;
  border: 1.5px solid var(--border); border-radius: 12px;
  font-family: 'Poppins', sans-serif; font-size: .88rem;
  color: var(--charcoal); background: #fff;
  transition: border-color .2s, box-shadow .2s;
  outline: none;
}
.field input:focus, .field select:focus, .field textarea:focus {
  border-color: var(--purple);
  box-shadow: 0 0 0 3px rgba(123,47,190,.1);
}
.field textarea { resize: vertical; min-height: 90px; }
.field-error { font-size: .75rem; color: #e53e3e; margin-top: .3rem; }

/* ── CATEGORY TABS ── */
.cat-tabs {
  display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1rem;
}
.cat-tab {
  padding: .4rem 1rem; border-radius: 30px; font-size: .78rem;
  font-weight: 600; cursor: pointer; border: 1.5px solid var(--border);
  background: #fff; color: var(--gray); transition: all .2s;
  font-family: 'Poppins', sans-serif;
}
.cat-tab:hover { border-color: var(--purple); color: var(--purple); }
.cat-tab.active {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; border-color: transparent;
}

/* ── SERVICE CARDS ── */
.services-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;
  max-height: 320px; overflow-y: auto;
  padding-right: .3rem; margin-bottom: 1rem;
}
.services-grid::-webkit-scrollbar { width: 4px; }
.services-grid::-webkit-scrollbar-track { background: var(--purple-lt); border-radius: 4px; }
.services-grid::-webkit-scrollbar-thumb { background: var(--purple); border-radius: 4px; }

.service-card {
  border: 1.5px solid var(--border); border-radius: 14px;
  padding: .9rem; cursor: pointer; transition: all .2s;
  background: #fff; position: relative;
}
.service-card:hover { border-color: var(--purple); transform: translateY(-2px); }
.service-card.selected {
  border-color: var(--purple);
  background: var(--purple-lt);
  box-shadow: 0 4px 16px rgba(123,47,190,.15);
}
.service-card input[type="radio"] { display: none; }
.service-name { font-size: .83rem; font-weight: 600; color: var(--charcoal); margin-bottom: .4rem; }
.service-meta { display: flex; justify-content: space-between; align-items: center; }
.service-price { font-size: .82rem; font-weight: 700; color: var(--purple); }
.service-price.free { color: var(--green); }
.service-duration {
  font-size: .72rem; color: var(--gray);
  background: var(--purple-lt); padding: .2rem .6rem;
  border-radius: 20px;
}
.service-check {
  position: absolute; top: .6rem; right: .6rem;
  width: 20px; height: 20px; border-radius: 50%;
  background: var(--purple); color: #fff;
  font-size: .7rem; display: none;
  align-items: center; justify-content: center;
}
.service-card.selected .service-check { display: flex; }

/* ── TIME SLOTS ── */
.time-grid {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: .5rem;
}
.time-slot {
  padding: .55rem; border: 1.5px solid var(--border);
  border-radius: 10px; text-align: center; cursor: pointer;
  font-size: .8rem; font-weight: 500; transition: all .2s;
  background: #fff; color: var(--charcoal);
  font-family: 'Poppins', sans-serif;
}
.time-slot:hover { border-color: var(--purple); color: var(--purple); }
.time-slot.selected {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; border-color: transparent;
}

/* ── SUBMIT BTN ── */
.btn-book {
  width: 100%; padding: 1rem;
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; border: none; border-radius: 14px;
  font-size: 1rem; font-weight: 700; cursor: pointer;
  font-family: 'Poppins', sans-serif;
  box-shadow: 0 6px 24px rgba(123,47,190,.35);
  transition: all .25s; margin-top: 1rem;
}
.btn-book:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(123,47,190,.45); }

/* ── SIDEBAR ── */
.sidebar { display: flex; flex-direction: column; gap: 1.5rem; }

.summary-card {
  background: #fff; border-radius: 20px;
  border: 1px solid var(--border);
  box-shadow: 0 8px 30px rgba(123,47,190,.06);
  overflow: hidden; position: sticky; top: 1.5rem;
}
.summary-header {
  background: linear-gradient(135deg, var(--charcoal), #2D1050);
  padding: 1.2rem 1.5rem; color: #fff;
}
.summary-header h3 {
  font-family: 'Playfair Display', serif;
  font-size: 1.1rem; font-weight: 700;
}
.summary-body { padding: 1.5rem; }
.summary-row {
  display: flex; justify-content: space-between; align-items: flex-start;
  padding: .6rem 0; border-bottom: 1px solid var(--border);
  font-size: .85rem;
}
.summary-row:last-child { border-bottom: none; }
.summary-row .label { color: var(--gray); font-size: .78rem; }
.summary-row .value { font-weight: 600; color: var(--charcoal); text-align: right; max-width: 60%; }
.summary-row .value.price { color: var(--purple); font-size: 1rem; }
.summary-empty {
  text-align: center; padding: 2rem 1rem;
  color: var(--gray); font-size: .85rem;
}
.summary-empty .icon { font-size: 2.5rem; margin-bottom: .5rem; }

.info-card {
  background: var(--green-lt); border-radius: 16px;
  padding: 1.2rem; border: 1px solid rgba(61,181,74,.2);
}
.info-card h4 { font-size: .85rem; font-weight: 700; color: var(--green); margin-bottom: .6rem; }
.info-card ul { list-style: none; }
.info-card ul li {
  font-size: .8rem; color: #2d7a35; padding: .25rem 0;
  display: flex; align-items: flex-start; gap: .4rem;
}

/* ── ALERTS ── */
.alert-error {
  background: #FEE2E2; border: 1px solid #FECACA;
  border-radius: 12px; padding: 1rem 1.2rem;
  margin-bottom: 1.5rem; font-size: .85rem; color: #991B1B;
}
.alert-error ul { margin-top: .4rem; padding-left: 1.2rem; }

@media(max-width: 768px) {
  .book-wrap { grid-template-columns: 1fr; }
  .field-group { grid-template-columns: 1fr; }
  .services-grid { grid-template-columns: 1fr; }
  .time-grid { grid-template-columns: repeat(3, 1fr); }
  .sidebar { order: -1; }
  .summary-card { position: static; }
}
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="book-hero">
  <p class="book-hero-eyebrow">✦ Complimentary Consultation Available</p>
  <h1>Book Your <span>Appointment</span></h1>
  <p>Choose your service, pick a time, and we'll take care of the rest. Easy, fast, and no app needed.</p>
</div>

<div class="book-wrap">

  {{-- ── BOOKING FORM ── --}}
  <div class="form-card">
    <div class="form-card-header">
      <h2>📅 New Appointment</h2>
      <p>Fill in your details below to book your session</p>
    </div>

    <div class="form-card-body">

      {{-- Steps --}}
      <div class="steps">
        <div class="step active" id="step-1">
          <div class="step-num">1</div>
          <div class="step-label">Service</div>
        </div>
        <div class="step" id="step-2">
          <div class="step-num">2</div>
          <div class="step-label">Date & Time</div>
        </div>
        <div class="step" id="step-3">
          <div class="step-num">3</div>
          <div class="step-label">Your Info</div>
        </div>
      </div>

      {{-- Errors --}}
      @if($errors->any())
      <div class="alert-error">
        <strong>Please fix the following:</strong>
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('book.store') }}" method="POST" id="booking-form">
        @csrf

        {{-- ── STEP 1: SERVICE ── --}}
        <div id="section-service">
          <div class="field-section">
            <div class="field-section-title">✨ Choose a Service</div>

            {{-- Category filter tabs --}}
            <div class="cat-tabs" id="cat-tabs">
              <button type="button" class="cat-tab active" data-cat="all">All</button>
              @foreach($categories as $cat)
                <button type="button" class="cat-tab" data-cat="{{ $cat }}">{{ $cat }}</button>
              @endforeach
            </div>

            {{-- Services grid --}}
            <div class="services-grid" id="services-grid">
              @foreach($services as $svc)
              <label class="service-card" data-cat="{{ $svc['category'] }}" data-price="{{ $svc['price'] }}" data-duration="{{ $svc['duration'] }}" data-name="{{ $svc['name'] }}">
                <input type="radio" name="service_name" value="{{ $svc['name'] }}" {{ old('service_name') === $svc['name'] ? 'checked' : '' }}>
                <div class="service-check">✓</div>
                <div class="service-name">{{ $svc['name'] }}</div>
                <div class="service-meta">
                  <span class="service-price {{ $svc['price'] == 0 ? 'free' : '' }}">
                    {{ $svc['price'] == 0 ? 'Free' : 'KSh ' . number_format($svc['price']) }}
                  </span>
                  <span class="service-duration">{{ $svc['duration'] >= 60 ? floor($svc['duration']/60).'hr'.($svc['duration']%60 ? ' '.($svc['duration']%60).'min' : '') : $svc['duration'].'min' }}</span>
                </div>
              </label>
              @endforeach
            </div>
            @error('service_name')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- ── STEP 2: DATE & TIME ── --}}
        <div id="section-datetime">
          <div class="field-section">
            <div class="field-section-title">📅 Pick a Date & Time</div>
            <div class="field-group">
              <div class="field">
                <label>Date <span>*</span></label>
                <input type="date" name="appointment_date"
                  value="{{ old('appointment_date') }}"
                  min="{{ date('Y-m-d') }}"
                  id="date-input">
                @error('appointment_date')<div class="field-error">{{ $message }}</div>@enderror
              </div>
              <div class="field">
                <label>Time <span>*</span></label>
                <input type="hidden" name="appointment_time" id="time-input" value="{{ old('appointment_time') }}">
                <div class="time-grid" id="time-grid">
                  @foreach($timeSlots as $slot)
                  <button type="button" class="time-slot {{ old('appointment_time') === $slot ? 'selected' : '' }}"
                    data-time="{{ $slot }}">{{ $slot }}</button>
                  @endforeach
                </div>
                @error('appointment_time')<div class="field-error">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        {{-- ── STEP 3: CLIENT INFO ── --}}
        <div id="section-client">
          <div class="field-section">
            <div class="field-section-title">👤 Your Details</div>
            <div class="field-group">
              <div class="field">
                <label>Full Name <span>*</span></label>
                <input type="text" name="client_name" value="{{ old('client_name') }}" placeholder="Jane Doe">
                @error('client_name')<div class="field-error">{{ $message }}</div>@enderror
              </div>
              <div class="field">
                <label>Phone Number <span>*</span></label>
                <input type="text" name="client_phone" value="{{ old('client_phone') }}" placeholder="0712 345 678">
                @error('client_phone')<div class="field-error">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="field">
              <label>Email Address</label>
              <input type="email" name="client_email" value="{{ old('client_email') }}" placeholder="jane@example.com (optional)">
              @error('client_email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
              <label>Additional Notes</label>
              <textarea name="notes" placeholder="Any allergies, skin concerns, or special requests...">{{ old('notes') }}</textarea>
              @error('notes')<div class="field-error">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <button type="submit" class="btn-book">
          ✦ Confirm Appointment
        </button>

      </form>
    </div>
  </div>

  {{-- ── SIDEBAR ── --}}
  <div class="sidebar">

    {{-- Booking Summary --}}
    <div class="summary-card">
      <div class="summary-header">
        <h3>🧾 Booking Summary</h3>
      </div>
      <div class="summary-body" id="summary-body">
        <div class="summary-empty" id="summary-empty">
          <div class="icon">✨</div>
          <p>Select a service to see your booking summary</p>
        </div>
        <div id="summary-content" style="display:none">
          <div class="summary-row">
            <span class="label">Service</span>
            <span class="value" id="sum-service">—</span>
          </div>
          <div class="summary-row">
            <span class="label">Category</span>
            <span class="value" id="sum-category">—</span>
          </div>
          <div class="summary-row">
            <span class="label">Duration</span>
            <span class="value" id="sum-duration">—</span>
          </div>
          <div class="summary-row">
            <span class="label">Date</span>
            <span class="value" id="sum-date">—</span>
          </div>
          <div class="summary-row">
            <span class="label">Time</span>
            <span class="value" id="sum-time">—</span>
          </div>
          <div class="summary-row">
            <span class="label">Price</span>
            <span class="value price" id="sum-price">—</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Info card --}}
    <div class="info-card">
      <h4>📌 What to know</h4>
      <ul>
        <li>✅ Free cancellation 24hrs before</li>
        <li>✅ Arrive 5 minutes early</li>
        <li>✅ Confirmation sent via SMS</li>
        <li>✅ Pay on arrival or via M-PESA</li>
        <li>✅ Free skin consultation available</li>
      </ul>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
// ── Category filter ──────────────────────────────────────────
document.querySelectorAll('.cat-tab').forEach(tab => {
  tab.addEventListener('click', function () {
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    this.classList.add('active');
    const cat = this.dataset.cat;
    document.querySelectorAll('.service-card').forEach(card => {
      card.style.display = (cat === 'all' || card.dataset.cat === cat) ? 'block' : 'none';
    });
  });
});

// ── Service selection ────────────────────────────────────────
document.querySelectorAll('.service-card').forEach(card => {
  card.addEventListener('click', function () {
    document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
    this.classList.add('selected');
    this.querySelector('input[type="radio"]').checked = true;

    const name     = this.dataset.name;
    const price    = parseInt(this.dataset.price);
    const duration = parseInt(this.dataset.duration);
    const category = this.dataset.cat;

    // Update step indicator
    document.getElementById('step-1').classList.add('done');
    document.getElementById('step-1').classList.remove('active');
    document.getElementById('step-2').classList.add('active');

    // Update summary
    document.getElementById('summary-empty').style.display   = 'none';
    document.getElementById('summary-content').style.display = 'block';
    document.getElementById('sum-service').textContent  = name;
    document.getElementById('sum-category').textContent = category;
    document.getElementById('sum-duration').textContent = duration >= 60
      ? Math.floor(duration/60) + 'hr' + (duration%60 ? ' ' + duration%60 + 'min' : '')
      : duration + ' min';
    document.getElementById('sum-price').textContent = price === 0
      ? 'Free'
      : 'KSh ' + price.toLocaleString();
  });
});

// ── Time slot selection ──────────────────────────────────────
document.querySelectorAll('.time-slot').forEach(slot => {
  slot.addEventListener('click', function () {
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    this.classList.add('selected');
    document.getElementById('time-input').value = this.dataset.time;

    document.getElementById('step-2').classList.add('done');
    document.getElementById('step-2').classList.remove('active');
    document.getElementById('step-3').classList.add('active');

    document.getElementById('sum-time').textContent = this.dataset.time;
  });
});

// ── Date change ──────────────────────────────────────────────
document.getElementById('date-input').addEventListener('change', function () {
  const date = new Date(this.value);
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  document.getElementById('sum-date').textContent = date.toLocaleDateString('en-KE', options);
});

// ── Pre-select if old values exist (after validation error) ──
const oldService = "{{ old('service_name') }}";
if (oldService) {
  document.querySelectorAll('.service-card').forEach(card => {
    if (card.dataset.name === oldService) card.click();
  });
}
const oldTime = "{{ old('appointment_time') }}";
if (oldTime) {
  document.querySelectorAll('.time-slot').forEach(slot => {
    if (slot.dataset.time === oldTime) slot.click();
  });
}
</script>
@endpush