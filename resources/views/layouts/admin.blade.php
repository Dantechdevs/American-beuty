<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') — American Beauty</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,600&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
    --pink:           #F72585;
    --pink-light:     #ff6eb4;
    --pink-dark:      #c51a6e;
    --pink-soft:      #fff0f7;
    --pink-mid:       #fce4f3;
    --tango:          #F4511E;
    --tango-light:    #ff7a52;
    --tango-soft:     #fff4f0;
    --green:          #2DC653;
    --green-light:    #4ade80;
    --green-soft:     #f0fdf4;
    --purple:         #7C3AED;
    --purple-light:   #a78bfa;
    --purple-soft:    #f5f3ff;
    --white:          #ffffff;
    --off-white:      #fdf8fc;
    --primary:        var(--pink);
    --primary-dark:   var(--pink-dark);
    --border:         #f0dcea;
    --bg:             #fdf8fc;
    --card-bg:        #ffffff;
    --text:           #1a0a12;
    --text-muted:     #8a6070;
    --shadow:         0 2px 16px rgba(247,37,133,.07);
    --shadow-md:      0 4px 24px rgba(247,37,133,.1);
    --shadow-lg:      0 8px 40px rgba(247,37,133,.15);
    --radius:         14px;
    --radius-sm:      9px;
    --sb-bg:          #0e0118;
    --sb-bg2:         #160228;
    --sb-border:      rgba(124,58,237,.18);
    --sb-text:        #e2d4f8;
    --sb-muted:       #6b4d8a;
    --sb-hover:       rgba(124,58,237,.14);
    --sb-active:      rgba(124,58,237,.28);
    --sb-w:           270px;
    --topbar-h:       66px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    display: flex;
    min-height: 100vh;
    font-size: 14px;
    line-height: 1.5;
}

::-webkit-scrollbar       { width: 4px; height: 4px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(124,58,237,.3); border-radius: 4px; }

/* ═══════════════════════════════════════
   SIDEBAR
   ═══════════════════════════════════════ */
.sidebar {
    width: var(--sb-w);
    background: linear-gradient(180deg, var(--sb-bg) 0%, var(--sb-bg2) 100%);
    min-height: 100vh;
    position: fixed;
    left: 0; top: 0;
    display: flex;
    flex-direction: column;
    z-index: 200;
    overflow-y: auto;
    overflow-x: hidden;
    border-right: 1px solid var(--sb-border);
    box-shadow: 4px 0 32px rgba(124,58,237,.12);
}

/* ── BRAND — BIG BOLD ─────────────────── */
.sb-brand {
    padding: 1.6rem 1.4rem 1.4rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: .3rem;
    border-bottom: 1px solid var(--sb-border);
    flex-shrink: 0;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(124,58,237,.15) 0%, rgba(247,37,133,.08) 100%);
}
.sb-brand::before {
    content: '';
    position: absolute;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(124,58,237,.2) 0%, transparent 70%);
    top: -50px; right: -40px;
    pointer-events: none;
}
.sb-brand-logo {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, var(--purple) 0%, var(--pink) 100%);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: #fff;
    box-shadow: 0 6px 20px rgba(124,58,237,.5);
    margin-bottom: .4rem;
    flex-shrink: 0;
    position: relative; z-index: 1;
}
.sb-brand-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.55rem;
    font-weight: 900;
    line-height: 1.05;
    letter-spacing: -.01em;
    position: relative; z-index: 1;
    background: linear-gradient(135deg, #fff 30%, var(--purple-light) 70%, #fff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.sb-brand-name .word-beauty {
    background: linear-gradient(135deg, var(--purple-light) 0%, var(--pink-light) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-style: italic;
}
.sb-brand-sub {
    font-size: .65rem;
    color: var(--sb-muted);
    letter-spacing: .14em;
    text-transform: uppercase;
    font-weight: 600;
    position: relative; z-index: 1;
}
.sb-brand-pill {
    display: inline-flex; align-items: center; gap: .25rem;
    background: rgba(124,58,237,.2);
    border: 1px solid rgba(124,58,237,.35);
    border-radius: 20px;
    padding: .18rem .6rem;
    font-size: .6rem; font-weight: 700;
    color: var(--purple-light);
    letter-spacing: .08em; text-transform: uppercase;
    margin-top: .2rem;
    position: relative; z-index: 1;
}

/* ── Nav ─────────────────────────────── */
.sb-nav {
    flex: 1;
    padding: .8rem 0 1rem;
    overflow-y: auto;
    overflow-x: hidden;
}
.sb-nav::-webkit-scrollbar { width: 0; }

.sb-label {
    display: flex; align-items: center; gap: .5rem;
    padding: 1rem 1.4rem .35rem;
    font-size: .6rem; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase;
    color: var(--sb-muted); user-select: none;
}
.sb-label::after {
    content: ''; flex: 1; height: 1px;
    background: var(--sb-border);
}

.sb-item {
    display: flex; align-items: center; gap: .72rem;
    padding: .52rem 1rem;
    margin: .06rem .7rem;
    border-radius: 11px;
    color: var(--sb-text);
    text-decoration: none;
    font-size: .835rem; font-weight: 400;
    transition: all .18s ease;
    position: relative;
    white-space: nowrap;
}
.sb-item:hover {
    background: var(--sb-hover);
    color: #fff;
    transform: translateX(3px);
}
.sb-item:hover .sb-icon {
    background: rgba(124,58,237,.3);
    color: var(--purple-light);
}
.sb-item.active {
    background: var(--sb-active);
    color: #fff; font-weight: 600;
    box-shadow: 0 2px 12px rgba(124,58,237,.25);
}
.sb-item.active .sb-icon {
    background: linear-gradient(135deg, var(--purple), var(--pink));
    color: #fff;
    box-shadow: 0 3px 12px rgba(124,58,237,.45);
}
.sb-item.active::after {
    content: '';
    position: absolute; right: 0; top: 20%; bottom: 20%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-light), var(--pink));
    border-radius: 3px 0 0 3px;
}

.sb-icon {
    width: 31px; height: 31px;
    border-radius: 9px;
    background: rgba(255,255,255,.04);
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; flex-shrink: 0;
    transition: all .18s;
    color: var(--sb-muted);
}
.sb-item-text { flex: 1; line-height: 1; }
.sb-badge {
    background: linear-gradient(135deg, var(--pink), var(--tango));
    color: #fff; font-size: .6rem; font-weight: 700;
    padding: .14rem .42rem; border-radius: 20px; line-height: 1.4;
}

/* ── Sidebar footer ─────────────────── */
.sb-footer {
    padding: .9rem 1.1rem;
    border-top: 1px solid var(--sb-border);
    flex-shrink: 0;
    background: rgba(124,58,237,.05);
}
.sb-user {
    display: flex; align-items: center; gap: .75rem;
    padding: .65rem .8rem;
    border-radius: 12px;
    background: rgba(124,58,237,.1);
    border: 1px solid var(--sb-border);
}
.sb-avatar {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--purple), var(--pink));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .9rem; font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(124,58,237,.4);
}
.sb-user-info { min-width: 0; flex: 1; }
.sb-user-info strong { display: block; font-size: .82rem; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sb-user-info span   { font-size: .68rem; color: var(--sb-muted); }
.sb-logout {
    color: var(--sb-muted); font-size: .9rem;
    cursor: pointer; transition: color .15s;
    background: none; border: none; flex-shrink: 0; padding: .2rem;
}
.sb-logout:hover { color: var(--tango); }

/* ═══════════════════════════════════════
   MAIN WRAP
   ═══════════════════════════════════════ */
.main-wrap {
    margin-left: var(--sb-w);
    flex: 1; display: flex; flex-direction: column;
    min-height: 100vh; min-width: 0;
}

/* ── Topbar ─────────────────────────── */
.topbar {
    height: var(--topbar-h);
    background: #fff;
    border-bottom: 1.5px solid var(--border);
    display: flex; align-items: center;
    padding: 0 1.75rem; gap: 1rem;
    position: sticky; top: 0; z-index: 100;
    box-shadow: 0 1px 16px rgba(124,58,237,.07);
}
.topbar-breadcrumb { flex: 1; display: flex; flex-direction: column; gap: .1rem; }
.topbar-breadcrumb .page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; font-weight: 700; color: var(--text); line-height: 1;
}
.topbar-breadcrumb .page-sub { font-size: .72rem; color: var(--text-muted); }
.topbar-actions { display: flex; align-items: center; gap: .6rem; }

.tb-btn {
    width: 38px; height: 38px; border-radius: 10px;
    border: 1.5px solid var(--border); background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-muted); font-size: .88rem;
    transition: all .15s; text-decoration: none; position: relative;
}
.tb-btn:hover { background: var(--purple-soft); border-color: var(--purple); color: var(--purple); }
.tb-btn .notif-dot {
    position: absolute; top: 7px; right: 7px;
    width: 7px; height: 7px;
    background: var(--tango); border-radius: 50%; border: 1.5px solid #fff;
}
.tb-divider { width: 1px; height: 28px; background: var(--border); flex-shrink: 0; }

.tb-user {
    display: flex; align-items: center; gap: .6rem;
    padding: .4rem .8rem .4rem .4rem;
    border-radius: 11px; border: 1.5px solid var(--border);
    background: #fff; cursor: pointer; transition: all .15s;
    position: relative; user-select: none;
}
.tb-user:hover { background: var(--purple-soft); border-color: rgba(124,58,237,.3); }
.tb-avatar {
    width: 30px; height: 30px; border-radius: 8px;
    background: linear-gradient(135deg, var(--purple), var(--pink));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .75rem; font-weight: 700; flex-shrink: 0;
}
.tb-user-name { font-size: .83rem; font-weight: 600; color: var(--text); }
.tb-chevron { font-size: .65rem; color: var(--text-muted); transition: transform .2s; }
.tb-user.open .tb-chevron { transform: rotate(180deg); }

.tb-dropdown {
    display: none;
    position: absolute; top: calc(100% + .6rem); right: 0;
    background: #fff; border: 1.5px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow-lg);
    min-width: 210px; overflow: hidden; z-index: 300;
    animation: dropIn .18s ease;
}
.tb-dropdown.open { display: block; }
@keyframes dropIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
.tb-dropdown-header {
    padding: .85rem 1rem;
    background: linear-gradient(135deg, var(--purple-soft), var(--pink-soft));
    border-bottom: 1px solid var(--border);
}
.tb-dropdown-header strong { display: block; font-size: .85rem; color: var(--text); }
.tb-dropdown-header span   { font-size: .75rem; color: var(--text-muted); }
.tb-dropdown a, .tb-dropdown button {
    display: flex; align-items: center; gap: .65rem;
    padding: .72rem 1rem; font-size: .84rem; color: var(--text);
    text-decoration: none; transition: background .13s;
    background: none; border: none; width: 100%;
    cursor: pointer; font-family: inherit;
}
.tb-dropdown a:hover   { background: var(--purple-soft); color: var(--purple); }
.tb-dropdown a i       { color: var(--purple); width: 16px; text-align: center; }
.tb-dropdown button    { color: var(--tango); }
.tb-dropdown button:hover { background: var(--tango-soft); }
.tb-dropdown button i  { color: var(--tango); width: 16px; text-align: center; }
.tb-dropdown hr        { border: none; border-top: 1px solid var(--border); margin: .25rem 0; }

/* ── Page body ─────────────────────── */
.page-body { flex: 1; padding: 1.5rem 1.75rem 2rem; }
.flash-wrap { margin-bottom: 1rem; }

/* ═══════════════════════════════════════
   COMPONENTS
   ═══════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(205px, 1fr));
    gap: 1rem; margin-bottom: 1.5rem;
}
.stat-card {
    background: var(--card-bg); border-radius: var(--radius);
    padding: 1.2rem 1.3rem;
    display: flex; align-items: center; gap: 1rem;
    border: 1.5px solid var(--border); box-shadow: var(--shadow);
    transition: transform .18s, box-shadow .18s; cursor: default;
    position: relative; overflow: hidden;
}
.stat-card::before {
    content: ''; position: absolute; top: 0; right: 0;
    width: 80px; height: 80px; border-radius: 50%;
    background: radial-gradient(circle, rgba(124,58,237,.06) 0%, transparent 70%);
    transform: translate(20px,-20px);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.stat-icon {
    width: 50px; height: 50px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; flex-shrink: 0;
}
.stat-icon.pink   { background: linear-gradient(135deg,#fff0f7,#fce4f3); color: var(--pink);    box-shadow: 0 4px 14px rgba(247,37,133,.15); }
.stat-icon.tango  { background: linear-gradient(135deg,#fff4f0,#ffe0d6); color: var(--tango);   box-shadow: 0 4px 14px rgba(244,81,30,.15); }
.stat-icon.green  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); color: var(--green);   box-shadow: 0 4px 14px rgba(45,198,83,.15); }
.stat-icon.blue   { background: linear-gradient(135deg,#eff6ff,#dbeafe); color: #2563eb;        box-shadow: 0 4px 14px rgba(37,99,235,.15); }
.stat-icon.purple { background: linear-gradient(135deg,#f5f3ff,#ede9fe); color: var(--purple);  box-shadow: 0 4px 14px rgba(124,58,237,.15); }
.stat-icon.orange { background: linear-gradient(135deg,#fff7ed,#ffedd5); color: #f97316;        box-shadow: 0 4px 14px rgba(249,115,22,.15); }
.stat-icon.rose   { background: linear-gradient(135deg,#fff0f0,#fee2e2); color: #e11d48;        box-shadow: 0 4px 14px rgba(225,29,72,.15); }
.stat-value { font-size: 1.32rem; font-weight: 700; color: var(--text); line-height: 1; margin-bottom: .2rem; font-variant-numeric: tabular-nums; }
.stat-label { font-size: .74rem; color: var(--text-muted); font-weight: 500; }
.stat-trend { font-size: .72rem; font-weight: 600; margin-top: .3rem; display: flex; align-items: center; gap: .2rem; }
.stat-trend.up   { color: var(--green); }
.stat-trend.down { color: var(--tango); }

.card {
    background: var(--card-bg); border-radius: var(--radius);
    border: 1.5px solid var(--border); box-shadow: var(--shadow);
    overflow: hidden; margin-bottom: 1.25rem;
}
.card-header {
    padding: 1rem 1.3rem;
    border-bottom: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    gap: .75rem; flex-wrap: wrap;
    background: linear-gradient(120deg, #fff 40%, var(--purple-soft) 100%);
}
.card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; color: var(--text);
    display: flex; align-items: center; gap: .5rem;
}
.card-body { padding: 1.2rem 1.3rem; }

.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .84rem; }
thead th {
    padding: .7rem 1rem; text-align: left;
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: var(--purple);
    background: linear-gradient(120deg, var(--purple-soft), var(--pink-soft));
    border-bottom: 1.5px solid var(--border); white-space: nowrap;
}
tbody td {
    padding: .8rem 1rem; border-bottom: 1px solid #faf0f7;
    vertical-align: middle; color: var(--text);
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #fdf5fc; }

.badge {
    display: inline-flex; align-items: center; gap: .22rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .7rem; font-weight: 700; letter-spacing: .02em;
    white-space: nowrap; border: 1px solid transparent;
}
.badge-success { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.badge-warning { background: #fffbeb; color: #d97706; border-color: #fde68a; }
.badge-danger  { background: #fff1f2; color: var(--tango); border-color: #fecdd3; }
.badge-info    { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
.badge-pink    { background: var(--pink-soft); color: var(--pink); border-color: var(--pink-mid); }
.badge-tango   { background: var(--tango-soft); color: var(--tango); border-color: #fed7aa; }
.badge-purple  { background: var(--purple-soft); color: var(--purple); border-color: #ddd6fe; }
.badge-muted   { background: #f9f9f9; color: #888; border-color: #e5e5e5; }

.btn {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .58rem 1.15rem; border-radius: var(--radius-sm);
    font-size: .84rem; font-weight: 600; cursor: pointer; border: none;
    text-decoration: none; transition: all .18s ease;
    white-space: nowrap; font-family: inherit; line-height: 1;
}
.btn:disabled { opacity: .55; cursor: not-allowed; pointer-events: none; }
.btn-primary {
    background: linear-gradient(135deg, var(--purple) 0%, var(--pink) 100%);
    color: #fff; box-shadow: 0 4px 14px rgba(124,58,237,.28);
}
.btn-primary:hover { box-shadow: 0 6px 22px rgba(124,58,237,.38); transform: translateY(-1px); }
.btn-tango {
    background: linear-gradient(135deg, var(--tango) 0%, var(--tango-light) 100%);
    color: #fff; box-shadow: 0 4px 14px rgba(244,81,30,.28);
}
.btn-tango:hover { box-shadow: 0 6px 22px rgba(244,81,30,.38); transform: translateY(-1px); }
.btn-success {
    background: linear-gradient(135deg, var(--green) 0%, var(--green-light) 100%);
    color: #fff; box-shadow: 0 4px 14px rgba(45,198,83,.28);
}
.btn-success:hover { box-shadow: 0 6px 22px rgba(45,198,83,.38); transform: translateY(-1px); }
.btn-outline { background: #fff; color: var(--text); border: 1.5px solid var(--border); }
.btn-outline:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.btn-danger { background: var(--tango-soft); color: var(--tango); border: 1.5px solid #fecdd3; }
.btn-danger:hover { background: var(--tango); color: #fff; border-color: var(--tango); }
.btn-sm   { padding: .35rem .75rem; font-size: .77rem; border-radius: 7px; }
.btn-lg   { padding: .75rem 1.6rem; font-size: .95rem; }
.btn-icon { padding: .5rem; width: 36px; height: 36px; justify-content: center; }

.form-group { margin-bottom: 1.1rem; }
.form-label { display: block; font-size: .77rem; font-weight: 600; color: var(--text-muted); margin-bottom: .4rem; letter-spacing: .02em; }
.form-control {
    width: 100%; padding: .65rem .9rem;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: .87rem; font-family: inherit; outline: none;
    background: #fff; color: var(--text); transition: border-color .18s, box-shadow .18s;
}
.form-control:focus { border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.1); }
.form-control::placeholder { color: #c4a8b8; }
select.form-control { cursor: pointer; }
textarea.form-control { resize: vertical; min-height: 90px; }

.alert {
    padding: .85rem 1.15rem; border-radius: 11px; margin-bottom: 1rem;
    font-size: .85rem; font-weight: 500;
    display: flex; align-items: flex-start; gap: .65rem;
    border: 1px solid transparent;
}
.alert-success { background: var(--green-soft); color: #15803d; border-color: #bbf7d0; }
.alert-danger  { background: var(--tango-soft); color: #b91c1c; border-color: #fecdd3; }
.alert-warning { background: #fffbeb; color: #92400e; border-color: #fde68a; }
.alert-info    { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
.alert i { margin-top: .1rem; flex-shrink: 0; }

.pagination { display: flex; gap: .4rem; align-items: center; flex-wrap: wrap; }
.pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 8px;
    border: 1.5px solid var(--border); background: #fff; color: var(--text);
    font-size: .82rem; font-weight: 600; text-decoration: none; transition: all .15s;
}
.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, var(--purple), var(--pink));
    border-color: var(--purple); color: #fff;
    box-shadow: 0 3px 10px rgba(124,58,237,.3);
}
.pagination .page-item .page-link:hover:not(.active) {
    background: var(--purple-soft); border-color: var(--purple); color: var(--purple);
}

.empty-state {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 3rem 2rem; text-align: center; color: var(--text-muted);
}
.empty-state i { font-size: 2.5rem; margin-bottom: .75rem; opacity: .25; }
.empty-state p { font-size: .88rem; margin-bottom: 1rem; }
</style>
@stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <a href="{{ route('admin.dashboard') }}" class="sb-brand">
        <div class="sb-brand-logo"><i class="fas fa-spa"></i></div>
        <div class="sb-brand-name">
            American<br><span class="word-beauty">Beauty</span>
        </div>
        <div class="sb-brand-sub">Admin Panel</div>
        <div class="sb-brand-pill"><i class="fas fa-crown" style="font-size:.55rem"></i> Management Suite</div>
    </a>

    <nav class="sb-nav">

        <a href="{{ route('admin.dashboard') }}"
           class="sb-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-gauge-high"></i></span>
            <span class="sb-item-text">Dashboard</span>
        </a>

        <div class="sb-label">Product &amp; Stock</div>

        <a href="{{ route('admin.products.index') }}"
           class="sb-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-box-open"></i></span>
            <span class="sb-item-text">Products</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.purchase.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-cart-flatbed"></i></span>
            <span class="sb-item-text">Purchase</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.damages.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-box-archive"></i></span>
            <span class="sb-item-text">Damages</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-warehouse"></i></span>
            <span class="sb-item-text">Stock</span>
        </a>

        <div class="sb-label">POS &amp; Orders</div>

        <a href="{{ route('admin.pos.index') }}"
           class="sb-item {{ request()->routeIs('admin.pos.index') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-cash-register"></i></span>
            <span class="sb-item-text">POS Terminal</span>
        </a>

        <a href="{{ route('admin.pos.orders') }}"
           class="sb-item {{ request()->routeIs('admin.pos.orders') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-receipt"></i></span>
            <span class="sb-item-text">POS Orders</span>
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="sb-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-globe"></i></span>
            <span class="sb-item-text">Online Orders</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.return-orders.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-rotate-left"></i></span>
            <span class="sb-item-text">Return Orders</span>
        </a>

        <div class="sb-label">Promo</div>

        <a href="#" class="sb-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-ticket"></i></span>
            <span class="sb-item-text">Coupons</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-percent"></i></span>
            <span class="sb-item-text">Promotions</span>
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="sb-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-layer-group"></i></span>
            <span class="sb-item-text">Product Sections</span>
        </a>

        <div class="sb-label">Communications</div>

        <a href="#" class="sb-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-bell"></i></span>
            <span class="sb-item-text">Push Notifications</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-envelope-open-text"></i></span>
            <span class="sb-item-text">Subscribers</span>
        </a>

        <div class="sb-label">Users</div>

        <a href="#" class="sb-item {{ request()->routeIs('admin.administrators.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-user-shield"></i></span>
            <span class="sb-item-text">Administrators</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.delivery-boys.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-motorcycle"></i></span>
            <span class="sb-item-text">Delivery Boys</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="sb-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-users"></i></span>
            <span class="sb-item-text">Customers</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-id-badge"></i></span>
            <span class="sb-item-text">Employees</span>
        </a>

        <div class="sb-label">Accounts</div>

        <a href="#" class="sb-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-arrow-right-arrow-left"></i></span>
            <span class="sb-item-text">Transactions</span>
        </a>

        <div class="sb-label">Reports</div>

        <a href="#" class="sb-item {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-chart-line"></i></span>
            <span class="sb-item-text">Sales Report</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.reports.products') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-chart-bar"></i></span>
            <span class="sb-item-text">Products Report</span>
        </a>

        <a href="#" class="sb-item {{ request()->routeIs('admin.reports.credit') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-file-invoice-dollar"></i></span>
            <span class="sb-item-text">Credit Balance Report</span>
        </a>

        <div class="sb-label">Setup</div>

        <a href="{{ route('admin.settings.index') }}"
           class="sb-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <span class="sb-icon"><i class="fas fa-sliders"></i></span>
            <span class="sb-item-text">Settings</span>
        </a>

    </nav>

    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
            <div class="sb-user-info">
                <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
                <span>Administrator</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="sb-logout" title="Logout">
                    <i class="fas fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>

</aside>

{{-- MAIN --}}
<div class="main-wrap">

    <header class="topbar">
        <div class="topbar-breadcrumb">
            <span class="page-title">@yield('title', 'Dashboard')</span>
            <span class="page-sub">American Beauty · Admin</span>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('admin.pos.index') }}" class="tb-btn" title="POS Terminal">
                <i class="fas fa-cash-register"></i>
            </a>
            <a href="#" class="tb-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="notif-dot"></span>
            </a>
            <div class="tb-divider"></div>
            <div class="tb-user" id="tbUser" onclick="toggleDropdown()">
                <div class="tb-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                <span class="tb-user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                <i class="fas fa-chevron-down tb-chevron"></i>
                <div class="tb-dropdown" id="tbDropdown">
                    <div class="tb-dropdown-header">
                        <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
                        <span>{{ Auth::user()->email ?? 'admin@example.com' }}</span>
                    </div>
                    <a href="#"><i class="fas fa-user-pen"></i> Edit Profile</a>
                    <a href="#"><i class="fas fa-lock"></i> Change Password</a>
                    <hr>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button type="submit"><i class="fas fa-right-from-bracket"></i> Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    @if(session('success') || session('error') || $errors->any())
    <div class="flash-wrap" style="padding:.75rem 1.75rem 0">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-circle-check"></i><div>{{ session('success') }}</div></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-circle-xmark"></i><div>{{ session('error') }}</div></div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger"><i class="fas fa-circle-xmark"></i><div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div></div>
        @endif
    </div>
    @endif

    <main class="page-body">
        @yield('content')
    </main>

</div>

@stack('scripts')
<script>
function toggleDropdown() {
    const user = document.getElementById('tbUser');
    const drop = document.getElementById('tbDropdown');
    const isOpen = drop.classList.contains('open');
    drop.classList.toggle('open', !isOpen);
    user.classList.toggle('open', !isOpen);
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('#tbUser')) {
        document.getElementById('tbDropdown').classList.remove('open');
        document.getElementById('tbUser').classList.remove('open');
    }
});
document.querySelectorAll('.alert').forEach(function(el) {
    setTimeout(function() {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(function() { el.remove(); }, 400);
    }, 5000);
});
</script>
</body>
</html>