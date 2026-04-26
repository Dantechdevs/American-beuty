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

/* ══════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════ */
.book-topbar {
  display: flex; align-items: center; justify-content: space-between;
  padding: .75rem 1.25rem;
  background: #fff;
  border-bottom: 1px solid var(--border);
  position: sticky; top: 0; z-index: 100;
}
.book-topbar-logo {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem; font-weight: 700; color: var(--charcoal);
}
.book-topbar-logo span { color: var(--magenta); }
.hamburger-btn {
  width: 38px; height: 38px; border-radius: 9px;
  border: 1.5px solid var(--border);
  background: #fff; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  flex-direction: column; gap: 4px; padding: 8px;
  transition: border-color .2s;
}
.hamburger-btn:hover { border-color: var(--purple); }
.hamburger-btn span {
  display: block; width: 16px; height: 2px;
  background: var(--charcoal); border-radius: 2px;
}

/* ══════════════════════════════════════════
   INFO STRIP
══════════════════════════════════════════ */
.info-strip {
  background: var(--green-lt);
  border-bottom: 1px solid rgba(61,181,74,.2);
  padding: .5rem 1.25rem;
  overflow-x: auto;
  white-space: nowrap;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
}
.info-strip::-webkit-scrollbar { display: none; }
.info-strip-inner {
  display: inline-flex; align-items: center; gap: 1.5rem;
}
.info-strip-item {
  display: inline-flex; align-items: center; gap: .35rem;
  font-size: .72rem; font-weight: 500; color: #2d7a35; white-space: nowrap;
}
.info-strip-item .dot {
  width: 5px; height: 5px; border-radius: 50%;
  background: var(--green); flex-shrink: 0;
}

/* ══════════════════════════════════════════
   COMPACT HERO
══════════════════════════════════════════ */
.book-hero {
  background: linear-gradient(135deg, #FAF4FF 0%, #F5E0FC 50%, #EFF8F0 100%);
  padding: 1.2rem 1.25rem 1.1rem;
  border-bottom: 1px solid var(--border);
  position: relative; overflow: hidden;
}
.book-hero::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(123,47,190,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(123,47,190,.05) 1px, transparent 1px);
  background-size: 40px 40px;
  pointer-events: none;
}
.book-hero-eyebrow {
  display: inline-flex; align-items: center; gap: .35rem;
  font-size: .62rem; letter-spacing: .17em; text-transform: uppercase;
  color: var(--magenta); font-weight: 600; margin-bottom: .35rem;
  background: var(--magenta-lt); padding: .22rem .7rem; border-radius: 40px;
  border: 1px solid rgba(200,53,157,.2); position: relative;
}
.book-hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.25rem, 3.5vw, 1.6rem); font-weight: 700;
  color: var(--charcoal); line-height: 1.2; margin-bottom: .18rem;
  position: relative;
}
.book-hero h1 span { color: var(--purple); }
.book-hero p {
  color: var(--gray); font-size: .76rem; line-height: 1.5; position: relative;
}

/* ══════════════════════════════════════════
   LAYOUT
══════════════════════════════════════════ */
.book-wrap {
  max-width: 1100px; margin: 1.5rem auto; padding: 0 1rem;
  display: grid; grid-template-columns: 1fr 330px; gap: 1.5rem;
}

/* ══════════════════════════════════════════
   FORM CARD
══════════════════════════════════════════ */
.form-card {
  background: #fff; border-radius: 18px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 20px rgba(123,47,190,.07);
  overflow: hidden;
}
.form-card-header {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  padding: 1rem 1.4rem; color: #fff;
}
.form-card-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem; font-weight: 700;
}
.form-card-header p { font-size: .76rem; opacity: .85; margin-top: .15rem; }
.form-card-body { padding: 1.2rem 1.4rem; }

/* ══════════════════════════════════════════
   STEPS
══════════════════════════════════════════ */
.steps {
  display: flex; margin-bottom: 1.4rem;
  border-bottom: 2px solid var(--border); padding-bottom: .9rem;
}
.step { flex: 1; text-align: center; }
.step-num {
  width: 27px; height: 27px; border-radius: 50%;
  background: var(--purple-lt); color: var(--purple);
  font-size: .73rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto .28rem; transition: all .3s;
}
.step.active .step-num { background: linear-gradient(135deg, var(--purple), var(--magenta)); color: #fff; }
.step.done .step-num   { background: var(--green); color: #fff; }
.step-label { font-size: .66rem; color: var(--gray); font-weight: 500; }
.step.active .step-label { color: var(--purple); font-weight: 600; }

/* ══════════════════════════════════════════
   SECTION TITLES
══════════════════════════════════════════ */
.field-section { margin-bottom: 1.4rem; }
.field-section-title {
  font-size: .7rem; letter-spacing: .13em; text-transform: uppercase;
  color: var(--magenta); font-weight: 600; margin-bottom: .8rem;
  display: flex; align-items: center; gap: .45rem;
}
.field-section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* ══════════════════════════════════════════
   FIELDS
══════════════════════════════════════════ */
.field-group { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; }
.field { margin-bottom: .8rem; }
.field label { display: block; font-size: .76rem; font-weight: 600; color: var(--charcoal); margin-bottom: .3rem; }
.field label span { color: var(--magenta); }
.field input, .field select, .field textarea {
  width: 100%; padding: .6rem .85rem;
  border: 1.5px solid var(--border); border-radius: 9px;
  font-family: 'Poppins', sans-serif; font-size: .82rem;
  color: var(--charcoal); background: #fff;
  transition: border-color .2s, box-shadow .2s; outline: none;
}
.field input:focus, .field select:focus, .field textarea:focus {
  border-color: var(--purple);
  box-shadow: 0 0 0 3px rgba(123,47,190,.1);
}
.field textarea { resize: vertical; min-height: 76px; }
.field-error { font-size: .7rem; color: #e53e3e; margin-top: .22rem; }

/* ══════════════════════════════════════════
   CATEGORY TABS
══════════════════════════════════════════ */
.cat-tabs { display: flex; flex-wrap: wrap; gap: .35rem; margin-bottom: .8rem; }
.cat-tab {
  padding: .28rem .78rem; border-radius: 30px; font-size: .7rem;
  font-weight: 600; cursor: pointer; border: 1.5px solid var(--border);
  background: #fff; color: var(--gray); transition: all .2s;
  font-family: 'Poppins', sans-serif;
}
.cat-tab:hover { border-color: var(--purple); color: var(--purple); }
.cat-tab.active { background: linear-gradient(135deg, var(--purple), var(--magenta)); color: #fff; border-color: transparent; }

/* ══════════════════════════════════════════
   SERVICE LIST
══════════════════════════════════════════ */
.services-list {
  display: flex; flex-direction: column;
  max-height: 400px; overflow-y: auto;
  border: 1.5px solid var(--border);
  border-radius: 11px; margin-bottom: .8rem;
}
.services-list::-webkit-scrollbar { width: 3px; }
.services-list::-webkit-scrollbar-track { background: var(--purple-lt); }
.services-list::-webkit-scrollbar-thumb { background: var(--purple); border-radius: 3px; }

.service-group-header {
  padding: .4rem 1rem;
  background: var(--purple-lt);
  font-size: .63rem; font-weight: 700;
  letter-spacing: .13em; text-transform: uppercase;
  color: var(--purple);
  border-bottom: 1px solid var(--border);
  position: sticky; top: 0; z-index: 1;
}

.service-card {
  display: flex; align-items: flex-start; gap: .8rem;
  padding: .78rem 1rem; cursor: pointer;
  transition: background .15s; background: #fff;
  border-bottom: 1px solid var(--border);
}
.service-card:last-child { border-bottom: none; }
.service-card:hover { background: #FAF4FF; }
.service-card.selected { background: var(--purple-lt); }
.service-card input[type="radio"] { display: none; }

.service-card-body { flex: 1; min-width: 0; }
.service-name { font-size: .82rem; font-weight: 600; color: var(--charcoal); margin-bottom: .12rem; line-height: 1.3; }
.service-desc { font-size: .7rem; color: var(--gray); line-height: 1.4; margin-bottom: .3rem; }
.service-meta { display: flex; align-items: center; }
.service-price { font-size: .74rem; font-weight: 700; color: var(--charcoal); }
.service-price.free { color: var(--green); }
.service-duration {
  font-size: .7rem; color: var(--gray);
  padding-left: .5rem; margin-left: .4rem;
  border-left: 1px solid var(--border);
}

.service-check {
  width: 19px; height: 19px; border-radius: 50%;
  border: 2px solid var(--border); flex-shrink: 0; margin-top: .08rem;
  display: flex; align-items: center; justify-content: center;
  font-size: .58rem; color: transparent; transition: all .2s; background: #fff;
}
.service-card.selected .service-check {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  border-color: transparent; color: #fff;
}

/* ══════════════════════════════════════════
   TIME SLOTS
══════════════════════════════════════════ */
.time-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: .38rem; }
.time-slot {
  padding: .46rem .25rem; border: 1.5px solid var(--border);
  border-radius: 7px; text-align: center; cursor: pointer;
  font-size: .72rem; font-weight: 500; transition: all .2s;
  background: #fff; color: var(--charcoal); font-family: 'Poppins', sans-serif;
}
.time-slot:hover { border-color: var(--purple); color: var(--purple); }
.time-slot.selected { background: linear-gradient(135deg, var(--purple), var(--magenta)); color: #fff; border-color: transparent; }

/* ══════════════════════════════════════════
   SUBMIT BTN
══════════════════════════════════════════ */
.btn-book {
  width: 100%; padding: .85rem;
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff; border: none; border-radius: 11px;
  font-size: .92rem; font-weight: 700; cursor: pointer;
  font-family: 'Poppins', sans-serif;
  box-shadow: 0 5px 18px rgba(123,47,190,.28);
  transition: all .25s; margin-top: .7rem;
}
.btn-book:hover { transform: translateY(-2px); box-shadow: 0 9px 24px rgba(123,47,190,.38); }

/* ══════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════ */
.sidebar { display: flex; flex-direction: column; gap: 1.1rem; }

.summary-card {
  background: #fff; border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 18px rgba(123,47,190,.06);
  overflow: hidden; position: sticky; top: 4.2rem;
}
.summary-header { background: linear-gradient(135deg, var(--charcoal), #2D1050); padding: .9rem 1.2rem; color: #fff; }
.summary-header h3 { font-family: 'Playfair Display', serif; font-size: .95rem; font-weight: 700; }
.summary-body { padding: 1.1rem 1.2rem; }
.summary-row {
  display: flex; justify-content: space-between; align-items: flex-start;
  padding: .45rem 0; border-bottom: 1px solid var(--border); font-size: .8rem;
}
.summary-row:last-child { border-bottom: none; }
.summary-row .label { color: var(--gray); font-size: .72rem; }
.summary-row .value { font-weight: 600; color: var(--charcoal); text-align: right; max-width: 62%; font-size: .8rem; }
.summary-row .value.price { color: var(--purple); }
.summary-empty { text-align: center; padding: 1.4rem 1rem; color: var(--gray); font-size: .8rem; }
.summary-empty .icon { font-size: 1.8rem; margin-bottom: .35rem; }

/* ══════════════════════════════════════════
   ALERTS
══════════════════════════════════════════ */
.alert-error {
  background: #FEE2E2; border: 1px solid #FECACA;
  border-radius: 9px; padding: .8rem 1rem;
  margin-bottom: 1.1rem; font-size: .8rem; color: #991B1B;
}
.alert-error ul { margin-top: .3rem; padding-left: 1rem; }

/* ══════════════════════════════════════════
   INFO PANEL
══════════════════════════════════════════ */
.info-overlay {
  position: fixed; inset: 0; z-index: 200;
  background: rgba(30,18,37,.45);
  opacity: 0; pointer-events: none; transition: opacity .3s;
}
.info-overlay.open { opacity: 1; pointer-events: all; }

.info-panel {
  position: fixed; top: 0; right: -420px; bottom: 0;
  width: 380px; max-width: 92vw;
  background: #fff; z-index: 201;
  box-shadow: -8px 0 40px rgba(30,18,37,.18);
  transition: right .35s cubic-bezier(.4,0,.2,1);
  display: flex; flex-direction: column;
}
.info-panel.open { right: 0; }

.info-panel-close {
  position: absolute; top: .9rem; right: .9rem;
  width: 32px; height: 32px; border-radius: 8px;
  border: 1.5px solid var(--border); background: #fff;
  cursor: pointer; font-size: .95rem;
  display: flex; align-items: center; justify-content: center;
  color: var(--charcoal); transition: all .2s; z-index: 1;
}
.info-panel-close:hover { background: var(--purple-lt); border-color: var(--purple); color: var(--purple); }

.info-panel-body {
  padding: 2rem 1.5rem 1rem;
  overflow-y: auto;
  flex: 1;
}
.info-spa-name {
  font-size: 1.05rem; font-weight: 800; letter-spacing: .04em;
  text-transform: uppercase; color: var(--charcoal);
  margin-bottom: 1.4rem; padding-right: 2.8rem; line-height: 1.3;
}
.info-row {
  display: flex; align-items: flex-start;
  justify-content: space-between; gap: 1rem;
  padding: .85rem 0; border-bottom: 1px solid var(--border);
}
.info-row:last-of-type { border-bottom: none; }
.info-row-label {
  font-size: .64rem; font-weight: 700; color: var(--gray);
  text-transform: uppercase; letter-spacing: .1em; margin-bottom: .22rem;
}
.info-row-value { font-size: .82rem; color: var(--charcoal); line-height: 1.6; }
.info-row-value a { color: var(--charcoal); text-decoration: none; }
.info-row-value a:hover { color: var(--purple); }
.open-badge {
  display: inline-block; font-size: .65rem; font-weight: 700;
  background: var(--green-lt); color: var(--green);
  padding: .1rem .5rem; border-radius: 20px; margin-bottom: .18rem;
}
.info-row-icon {
  width: 34px; height: 34px; border-radius: 9px;
  border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: .92rem; flex-shrink: 0;
  transition: all .2s; text-decoration: none; color: var(--charcoal);
}
.info-row-icon:hover { border-color: var(--purple); background: var(--purple-lt); color: var(--purple); }
.hours-list { display: none; margin-top: .45rem; }
.hours-list.open { display: block; }
.hours-list li { display: flex; justify-content: space-between; font-size: .76rem; padding: .16rem 0; color: var(--gray); }
.hours-list li.today { color: var(--charcoal); font-weight: 600; }

/* Social buttons */
.info-social { display: flex; gap: .45rem; margin-top: 1rem; flex-wrap: wrap; }
.social-btn {
  width: 38px; height: 38px; border-radius: 9px;
  border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; text-decoration: none;
  transition: all .2s;
}
.social-btn.website  { color: var(--purple); }
.social-btn.instagram{ color: #E1306C; }
.social-btn.facebook { color: #1877F2; }
.social-btn.tiktok   { color: #010101; }
.social-btn.whatsapp { color: #25D366; }
.social-btn:hover    { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(123,47,190,.15); border-color: currentColor; }

/* ══════════════════════════════════════════
   INFO PANEL FOOTER
══════════════════════════════════════════ */
.info-panel-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--border);
  background: #faf8fc;
  flex-shrink: 0;
}

/* Auth buttons inside panel */
.panel-auth-user {
  display: flex; align-items: center; gap: .75rem;
  padding: .75rem 0; border-bottom: 1px solid var(--border);
  margin-bottom: .75rem;
}
.panel-auth-avatar {
  width: 40px; height: 40px; border-radius: 10px;
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: 700; font-size: .92rem; flex-shrink: 0;
}
.panel-auth-name {
  font-size: .83rem; font-weight: 700; color: var(--charcoal);
  overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.panel-auth-email {
  font-size: .7rem; color: var(--gray);
  overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.panel-btn {
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  width: 100%; padding: .75rem; border-radius: 10px;
  font-family: 'Poppins', sans-serif; font-size: .83rem; font-weight: 700;
  text-decoration: none; cursor: pointer; border: none;
  transition: all .22s; margin-bottom: .55rem;
}
.panel-btn:last-child { margin-bottom: 0; }
.panel-btn-primary {
  background: linear-gradient(135deg, var(--purple), var(--magenta));
  color: #fff;
  box-shadow: 0 4px 14px rgba(123,47,190,.28);
}
.panel-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(123,47,190,.38); color: #fff; }
.panel-btn-outline {
  background: #fff; color: var(--charcoal);
  border: 1.5px solid var(--border);
}
.panel-btn-outline:hover { border-color: var(--purple); color: var(--purple); }
.panel-btn-danger {
  background: #fff; color: #dc2626;
  border: 1.5px solid #fecaca; width: 100%; font-family: 'Poppins', sans-serif;
}
.panel-btn-danger:hover { background: #fef2f2; border-color: #dc2626; }

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media(max-width: 768px) {
  .book-wrap {
    grid-template-columns: 1fr;
    margin: 1rem auto;
    padding: 0 .75rem;
    gap: 1rem;
  }
  .field-group { grid-template-columns: 1fr; gap: .6rem; }
  .time-grid { grid-template-columns: repeat(3, 1fr); }
  .sidebar { order: -1; }
  .summary-card { position: static; }
  .services-list { max-height: 320px; }
}
</style>
@endpush

@section('content')

{{-- ── INFO PANEL OVERLAY ── --}}
<div class="info-overlay" id="info-overlay" onclick="closePanel()"></div>

{{-- ── INFO PANEL ── --}}
<div class="info-panel" id="info-panel">
    <button class="info-panel-close" onclick="closePanel()">
        <i class="fas fa-xmark"></i>
    </button>

    <div class="info-panel-body">
        <div class="info-spa-name">American Beauty Studio Spa</div>

        {{-- Address --}}
        <div class="info-row">
            <div class="info-row-left">
                <div class="info-row-label">Address</div>
                <div class="info-row-value">
                    Nairobi, Kenya<br>
                    <small style="color:var(--gray)">Exact location shared on confirmation</small>
                </div>
            </div>
            <a href="https://maps.google.com/?q=Nairobi+Kenya" target="_blank" class="info-row-icon" title="Open in Maps">
                <i class="fas fa-location-dot"></i>
            </a>
        </div>

        {{-- Phone --}}
        <div class="info-row">
            <div class="info-row-left">
                <div class="info-row-label">Phone</div>
                <div class="info-row-value">
                    <a href="tel:+254722794265">+254 722 794 265</a>
                </div>
            </div>
            <a href="tel:+254722794265" class="info-row-icon" title="Call us">
                <i class="fas fa-phone"></i>
            </a>
        </div>

        {{-- WhatsApp --}}
        <div class="info-row">
            <div class="info-row-left">
                <div class="info-row-label">WhatsApp</div>
                <div class="info-row-value">
                    <a href="https://wa.me/254722794265" target="_blank">+254 722 794 265</a>
                </div>
            </div>
            <a href="https://wa.me/254722794265" target="_blank" class="info-row-icon"
               title="Chat on WhatsApp" style="color:#25D366;border-color:rgba(37,211,102,.3)">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>

        {{-- Hours --}}
        <div class="info-row">
            <div class="info-row-left" style="flex:1">
                <div class="info-row-label">Hours</div>
                <div class="info-row-value">
                    <span class="open-badge">Open Today</span><br>
                    <span onclick="toggleHours()" style="cursor:pointer;font-size:.8rem;display:inline-flex;align-items:center;gap:.35rem">
                        Mon – Sat: 8:00 AM – 7:00 PM
                        <i class="fas fa-chevron-down" id="hours-chev" style="font-size:.6rem;transition:transform .2s"></i>
                    </span>
                    <ul class="hours-list" id="hours-list">
                        <li class="today"><span>Mon – Fri</span><span>8:00 AM – 7:00 PM</span></li>
                        <li class="today"><span>Saturday</span><span>9:00 AM – 6:00 PM</span></li>
                        <li><span>Sunday</span><span>Closed</span></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Email --}}
        <div class="info-row">
            <div class="info-row-left">
                <div class="info-row-label">Email</div>
                <div class="info-row-value">
                    <a href="mailto:info@americanbeauty.co.ke">info@americanbeauty.co.ke</a>
                </div>
            </div>
            <a href="mailto:info@americanbeauty.co.ke" class="info-row-icon" title="Send Email">
                <i class="fas fa-envelope"></i>
            </a>
        </div>

        {{-- Social --}}
        <div class="info-row-label" style="margin-top:1rem;margin-bottom:.55rem">Follow Us</div>
        <div class="info-social">
            <a href="#" class="social-btn website" title="Website">
                <i class="fas fa-globe"></i>
            </a>
            <a href="https://instagram.com/americanbeautyke" target="_blank"
               class="social-btn instagram" title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://facebook.com/americanbeautyke" target="_blank"
               class="social-btn facebook" title="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://tiktok.com/@americanbeautyke" target="_blank"
               class="social-btn tiktok" title="TikTok">
                <i class="fab fa-tiktok"></i>
            </a>
            <a href="https://wa.me/254722794265" target="_blank"
               class="social-btn whatsapp" title="WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>

    {{-- ── FOOTER — Sign In / Account ── --}}
    <div class="info-panel-footer">
        @auth
            {{-- Logged in state --}}
            <div class="panel-auth-user">
                <div class="panel-auth-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div class="panel-auth-name">{{ auth()->user()->name }}</div>
                    <div class="panel-auth-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            @if(auth()->user()->isStaff())
                <a href="{{ route('admin.dashboard') }}" class="panel-btn panel-btn-primary">
                    <i class="fas fa-gauge-high"></i> Go to Dashboard
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="panel-btn panel-btn-danger">
                    <i class="fas fa-right-from-bracket"></i> Sign Out
                </button>
            </form>
        @else
            {{-- Logged out state --}}
            <a href="{{ route('login') }}" class="panel-btn panel-btn-primary">
                <i class="fas fa-right-to-bracket"></i> Sign In to Your Account
            </a>
            <a href="{{ route('register') }}" class="panel-btn panel-btn-outline">
                <i class="fas fa-user-plus"></i> Create an Account
            </a>
        @endauth

        <p style="font-size:.67rem;color:var(--gray);text-align:center;margin-top:.75rem">
            © {{ date('Y') }} American Beauty Studio Spa
        </p>
    </div>
</div>

{{-- ── TOPBAR ── --}}
<div class="book-topbar">
    <div class="book-topbar-logo">American<span>Beauty</span></div>
    <button class="hamburger-btn" onclick="openPanel()" title="Info & Menu">
        <span></span><span></span><span></span>
    </button>
</div>

{{-- ── INFO STRIP ── --}}
<div class="info-strip">
    <div class="info-strip-inner">
        <span class="info-strip-item"><span class="dot"></span>Free cancellation 24hrs before</span>
        <span class="info-strip-item"><span class="dot"></span>Arrive 5 mins early</span>
        <span class="info-strip-item"><span class="dot"></span>SMS confirmation sent</span>
        <span class="info-strip-item"><span class="dot"></span>Pay on arrival or M-PESA</span>
        <span class="info-strip-item"><span class="dot"></span>Free skin consultation available</span>
    </div>
</div>

{{-- ── COMPACT HERO ── --}}
<div class="book-hero">
    <p class="book-hero-eyebrow">✦ Complimentary Consultation Available</p>
    <h1>Book Your <span>Appointment</span></h1>
    <p>Choose a service, pick a time — easy &amp; no app needed.</p>
</div>

<div class="book-wrap">

    {{-- ── BOOKING FORM ── --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-calendar-check"></i> New Appointment</h2>
            <p>Fill in your details below to book your session</p>
        </div>

        <div class="form-card-body">

            <div class="steps">
                <div class="step active" id="step-1">
                    <div class="step-num">1</div>
                    <div class="step-label">Service</div>
                </div>
                <div class="step" id="step-2">
                    <div class="step-num">2</div>
                    <div class="step-label">Date &amp; Time</div>
                </div>
                <div class="step" id="step-3">
                    <div class="step-num">3</div>
                    <div class="step-label">Your Info</div>
                </div>
            </div>

            @if($errors->any())
            <div class="alert-error">
                <strong>Please fix the following:</strong>
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ route('book.store') }}" method="POST" id="booking-form">
                @csrf

                {{-- STEP 1: SERVICE --}}
                <div id="section-service">
                    <div class="field-section">
                        <div class="field-section-title">
                            <i class="fas fa-sparkles" style="color:var(--magenta)"></i>
                            Choose a Service
                        </div>
                        <div class="cat-tabs" id="cat-tabs">
                            <button type="button" class="cat-tab active" data-cat="all">All</button>
                            @foreach($categories as $cat)
                                <button type="button" class="cat-tab" data-cat="{{ $cat }}">{{ $cat }}</button>
                            @endforeach
                        </div>
                        <div class="services-list" id="services-list">
                            @php $lastCat = null; @endphp
                            @foreach($services as $svc)
                                @if($svc['category'] !== $lastCat)
                                    <div class="service-group-header" data-cat-header="{{ $svc['category'] }}">
                                        {{ $svc['category'] }}
                                    </div>
                                    @php $lastCat = $svc['category']; @endphp
                                @endif
                                <label class="service-card"
                                       data-cat="{{ $svc['category'] }}"
                                       data-price="{{ $svc['price'] }}"
                                       data-duration="{{ $svc['duration'] }}"
                                       data-name="{{ $svc['name'] }}"
                                       data-price-label="{{ $svc['price_label'] }}">
                                    <input type="radio" name="service_name" value="{{ $svc['name'] }}"
                                           {{ old('service_name') === $svc['name'] ? 'checked' : '' }}>
                                    <div class="service-card-body">
                                        <div class="service-name">{{ $svc['name'] }}</div>
                                        @if(!empty($svc['description']))
                                            <div class="service-desc">{{ $svc['description'] }}</div>
                                        @endif
                                        <div class="service-meta">
                                            <span class="service-price {{ $svc['price'] == 0 ? 'free' : '' }}">
                                                {{ $svc['price_label'] }}
                                            </span>
                                            <span class="service-duration">{{ $svc['duration_label'] }}</span>
                                        </div>
                                    </div>
                                    <div class="service-check">
                                        <i class="fas fa-check" style="font-size:.55rem"></i>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('service_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- STEP 2: DATE & TIME --}}
                <div id="section-datetime">
                    <div class="field-section">
                        <div class="field-section-title">
                            <i class="fas fa-calendar-days" style="color:var(--magenta)"></i>
                            Pick a Date &amp; Time
                        </div>
                        <div class="field-group">
                            <div class="field">
                                <label>Date <span>*</span></label>
                                <input type="date" name="appointment_date"
                                       value="{{ old('appointment_date') }}"
                                       min="{{ date('Y-m-d') }}" id="date-input">
                                @error('appointment_date')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label>Time <span>*</span></label>
                                <input type="hidden" name="appointment_time" id="time-input"
                                       value="{{ old('appointment_time') }}">
                                <div class="time-grid" id="time-grid">
                                    @foreach($timeSlots as $slot)
                                        <button type="button"
                                                class="time-slot {{ old('appointment_time') === $slot ? 'selected' : '' }}"
                                                data-time="{{ $slot }}">{{ $slot }}</button>
                                    @endforeach
                                </div>
                                @error('appointment_time')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: CLIENT INFO --}}
                <div id="section-client">
                    <div class="field-section">
                        <div class="field-section-title">
                            <i class="fas fa-user" style="color:var(--magenta)"></i>
                            Your Details
                        </div>
                        <div class="field-group">
                            <div class="field">
                                <label>Full Name <span>*</span></label>
                                <input type="text" name="client_name"
                                       value="{{ old('client_name') }}"
                                       placeholder="Jane Doe">
                                @error('client_name')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label>Phone Number <span>*</span></label>
                                <input type="text" name="client_phone"
                                       value="{{ old('client_phone') }}"
                                       placeholder="0712 345 678">
                                @error('client_phone')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="field">
                            <label>Email Address</label>
                            <input type="email" name="client_email"
                                   value="{{ old('client_email') }}"
                                   placeholder="jane@example.com (optional)">
                            @error('client_email')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>Additional Notes</label>
                            <textarea name="notes"
                                      placeholder="Any allergies, skin concerns, or special requests...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-book">
                    <i class="fas fa-calendar-check"></i> Confirm Appointment
                </button>
            </form>
        </div>
    </div>

    {{-- ── SIDEBAR ── --}}
    <div class="sidebar">
        <div class="summary-card">
            <div class="summary-header">
                <h3><i class="fas fa-receipt"></i> Booking Summary</h3>
            </div>
            <div class="summary-body">
                <div class="summary-empty" id="summary-empty">
                    <div class="icon"><i class="fas fa-spa" style="font-size:1.8rem;opacity:.2;color:var(--purple)"></i></div>
                    <p>Select a service to see your summary</p>
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
            card.style.display = (cat === 'all' || card.dataset.cat === cat) ? 'flex' : 'none';
        });
        document.querySelectorAll('.service-group-header').forEach(h => {
            h.style.display = (cat === 'all' || h.dataset.catHeader === cat) ? 'block' : 'none';
        });
    });
});

// ── Service selection ────────────────────────────────────────
document.querySelectorAll('.service-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;

        document.getElementById('step-1').classList.add('done');
        document.getElementById('step-1').classList.remove('active');
        document.getElementById('step-2').classList.add('active');

        document.getElementById('summary-empty').style.display   = 'none';
        document.getElementById('summary-content').style.display = 'block';
        document.getElementById('sum-service').textContent  = this.dataset.name;
        document.getElementById('sum-category').textContent = this.dataset.cat;
        document.getElementById('sum-duration').textContent =
            this.querySelector('.service-duration')?.textContent.trim() ?? '';
        document.getElementById('sum-price').textContent = this.dataset.priceLabel;
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
    const d = new Date(this.value + 'T00:00:00');
    document.getElementById('sum-date').textContent = d.toLocaleDateString('en-KE', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
});

// ── Pre-select on validation error ───────────────────────────
const oldService = "{{ old('service_name') }}";
if (oldService) {
    document.querySelectorAll('.service-card').forEach(c => {
        if (c.dataset.name === oldService) c.click();
    });
}
const oldTime = "{{ old('appointment_time') }}";
if (oldTime) {
    document.querySelectorAll('.time-slot').forEach(s => {
        if (s.dataset.time === oldTime) s.click();
    });
}

// ── Info Panel ───────────────────────────────────────────────
function openPanel() {
    document.getElementById('info-panel').classList.add('open');
    document.getElementById('info-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closePanel() {
    document.getElementById('info-panel').classList.remove('open');
    document.getElementById('info-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
function toggleHours() {
    const list = document.getElementById('hours-list');
    const chev = document.getElementById('hours-chev');
    list.classList.toggle('open');
    chev.style.transform = list.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closePanel(); });
</script>
@endpush