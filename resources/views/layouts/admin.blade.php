php

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Dashboard') — American Beauty Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}

        :root{
            --pink:        #F72585;
            --pink-lt:     #ff6eb4;
            --pink-dk:     #c51a6e;
            --pink-soft:   #fff0f7;
            --pink-mid:    #fce4f3;
            --tango:       #F4511E;
            --tango-lt:    #ff7a52;
            --tango-soft:  #fff4f0;
            --green:       #2DC653;
            --green-lt:    #4ade80;
            --green-soft:  #f0fdf4;
            --purple:      #7C3AED;
            --purple-lt:   #a78bfa;
            --purple-soft: #f5f3ff;
            --gold:        #F59E0B;
            --gold-soft:   #fffbeb;

            --border:      #fce4f3;
            --bg:          #fff8fb;
            --card:        #ffffff;
            --text:        #1a0a12;
            --muted:       #8a6070;
            --shadow:      0 2px 16px rgba(247,37,133,.05);
            --shadow-md:   0 4px 24px rgba(247,37,133,.09);
            --shadow-lg:   0 8px 40px rgba(247,37,133,.13);
            --r:           14px;
            --r-sm:        9px;

            --sb:          #f8f5ff;
            --sb-border:   #ede8f5;
            --sb-text:     #3d2060;
            --sb-muted:    #9b7ec0;
            --sb-hover:    #f3eeff;
            --sb-active-bg:#ede8ff;
            --sb-active-c: #5b21b6;
            --sb-w:        268px;
            --bar-h:       66px;
        }

        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh;font-size:14px;}
        a{text-decoration:none;color:inherit;}
        ::-webkit-scrollbar{width:5px;height:5px;}
        ::-webkit-scrollbar-track{background:#f3eeff;}
        ::-webkit-scrollbar-thumb{background:rgba(124,58,237,.2);border-radius:4px;}

        /* ════ SIDEBAR ════ */
        .sidebar{width:var(--sb-w);background:var(--sb);position:fixed;top:0;left:0;height:100vh;display:flex;flex-direction:column;z-index:200;overflow-y:auto;overflow-x:hidden;border-right:1.5px solid var(--sb-border);box-shadow:4px 0 24px rgba(124,58,237,.08);}
        .sb-brand{padding:1.4rem 1.3rem 1.3rem;display:flex;flex-direction:column;gap:.15rem;border-bottom:1.5px solid #ede8f5;flex-shrink:0;position:relative;overflow:hidden;background:linear-gradient(160deg,#f3eeff 0%,#f8f5ff 60%,#ffffff 100%);}
        .sb-brand::before{content:'';position:absolute;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,.1) 0%,transparent 70%);top:-60px;right:-50px;pointer-events:none;}
        .sb-brand::after{content:'';position:absolute;width:120px;height:120px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,.06) 0%,transparent 70%);bottom:-30px;left:-20px;pointer-events:none;}
        .sb-logo-icon{width:46px;height:46px;border-radius:14px;flex-shrink:0;background:linear-gradient(135deg,var(--purple) 0%,var(--pink) 100%);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;box-shadow:0 6px 20px rgba(124,58,237,.35);position:relative;z-index:1;margin-bottom:.6rem;}
        .sb-brand-line1{font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:900;line-height:1;letter-spacing:-.02em;color:#1a0a2e;position:relative;z-index:1;}
        .sb-brand-line2{font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:900;font-style:italic;line-height:1;background:linear-gradient(135deg,var(--purple) 0%,var(--pink) 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;position:relative;z-index:1;}
        .sb-brand-sub{font-size:.6rem;color:var(--sb-muted);letter-spacing:.16em;text-transform:uppercase;font-weight:600;margin-top:.35rem;position:relative;z-index:1;}
        .sb-brand-pill{display:inline-flex;align-items:center;gap:.25rem;background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.2);border-radius:20px;padding:.18rem .65rem;font-size:.57rem;font-weight:700;color:var(--purple);letter-spacing:.08em;text-transform:uppercase;margin-top:.35rem;width:fit-content;position:relative;z-index:1;}
        .sb-nav{flex:1;padding:.55rem 0 1rem;overflow-y:auto;overflow-x:hidden;}
        .sb-nav::-webkit-scrollbar{width:3px;}
        .sb-nav::-webkit-scrollbar-thumb{background:rgba(124,58,237,.15);}
        .sb-section{display:flex;align-items:center;gap:.5rem;padding:.9rem 1.25rem .28rem;font-size:.59rem;font-weight:700;letter-spacing:.13em;text-transform:uppercase;color:var(--sb-muted);user-select:none;}
        .sb-section::after{content:'';flex:1;height:1px;background:#ede8f5;}
        .sb-link{display:flex;align-items:center;gap:.68rem;padding:.5rem .92rem;margin:.04rem .6rem;border-radius:10px;color:var(--sb-text);font-size:.83rem;font-weight:400;transition:all .17s ease;position:relative;white-space:nowrap;}
        .sb-link:hover{background:var(--sb-hover);color:var(--purple);}
        .sb-link:hover .sb-ico{background:#ede8ff;color:var(--purple);}
        .sb-link.active{background:var(--sb-active-bg);color:var(--sb-active-c);font-weight:600;box-shadow:0 2px 12px rgba(124,58,237,.12);}
        .sb-link.active .sb-ico{background:linear-gradient(135deg,var(--purple),var(--pink));color:#fff;box-shadow:0 3px 10px rgba(124,58,237,.35);}
        .sb-link.active::after{content:'';position:absolute;right:0;top:20%;bottom:20%;width:3px;background:linear-gradient(to bottom,var(--purple),var(--pink));border-radius:3px 0 0 3px;}
        .sb-link.soon{opacity:.5;cursor:default;}
        .sb-link.soon:hover{background:none;color:var(--sb-text);}
        .sb-link.soon:hover .sb-ico{background:rgba(124,58,237,.04);color:var(--sb-muted);}
        .sb-ico{width:28px;height:28px;border-radius:8px;flex-shrink:0;background:rgba(124,58,237,.06);display:flex;align-items:center;justify-content:center;font-size:.77rem;color:var(--sb-muted);transition:all .17s;}
        .sb-txt{flex:1;line-height:1;}
        .sb-badge{background:linear-gradient(135deg,var(--pink),var(--tango));color:#fff;font-size:.58rem;font-weight:700;padding:.12rem .4rem;border-radius:20px;line-height:1.4;}
        .sb-soon-pill{font-size:.56rem;font-weight:700;background:#f3eeff;color:var(--purple);border:1px solid rgba(124,58,237,.18);border-radius:10px;padding:.1rem .38rem;letter-spacing:.05em;}
        .sb-foot{padding:.85rem 1.1rem;border-top:1.5px solid #ede8f5;flex-shrink:0;background:#f8f5ff;}
        .sb-user{display:flex;align-items:center;gap:.7rem;padding:.58rem .72rem;border-radius:11px;background:#fff;border:1.5px solid #ede8f5;box-shadow:0 1px 6px rgba(124,58,237,.06);}
        .sb-av{width:33px;height:33px;border-radius:9px;flex-shrink:0;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.86rem;font-weight:700;}
        .sb-uname{display:block;font-size:.8rem;color:#1a0a2e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:600;}
        .sb-urole{font-size:.67rem;color:var(--sb-muted);}
        .sb-logout{margin-left:auto;background:none;border:none;cursor:pointer;color:var(--sb-muted);font-size:.88rem;padding:.2rem;flex-shrink:0;transition:color .15s;}
        .sb-logout:hover{color:var(--purple);}

        /* ════ MAIN ════ */
        .main{margin-left:var(--sb-w);flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0;}

        /* ════ TOPBAR ════ */
        .topbar{height:var(--bar-h);background:rgba(255,255,255,.92);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1.5px solid #fce4f3;display:flex;align-items:center;padding:0 1.75rem;gap:1rem;position:sticky;top:0;z-index:100;box-shadow:0 2px 16px rgba(247,37,133,.06);}
        .topbar-left{flex:1;display:flex;align-items:center;gap:.65rem;}
        .topbar-page-icon{width:36px;height:36px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,var(--pink),var(--pink-lt));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.8rem;box-shadow:0 3px 12px rgba(247,37,133,.28);}
        .topbar-breadcrumb{display:flex;flex-direction:column;gap:.05rem;}
        .topbar-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--text);line-height:1.1;letter-spacing:-.01em;}
        .topbar-sub{display:flex;align-items:center;gap:.3rem;font-size:.68rem;color:var(--muted);font-weight:400;}
        .topbar-sub .sub-sep{color:#fce4f3;font-size:.75rem;}
        .topbar-sub .sub-live{display:inline-flex;align-items:center;gap:.28rem;color:var(--green);font-weight:600;font-size:.65rem;}
        .topbar-sub .sub-live::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--green);box-shadow:0 0 0 2px rgba(45,198,83,.25);animation:pulse 2s infinite;}
        @keyframes pulse{0%,100%{box-shadow:0 0 0 2px rgba(45,198,83,.25);}50%{box-shadow:0 0 0 5px rgba(45,198,83,.08);}}
        .topbar-right{display:flex;align-items:center;gap:.4rem;}
        .tb-search{display:flex;align-items:center;gap:.5rem;background:#fff8fb;border:1.5px solid #fce4f3;border-radius:10px;padding:.38rem .75rem;transition:all .2s;cursor:text;min-width:160px;}
        .tb-search:focus-within{background:#fff;border-color:rgba(247,37,133,.4);box-shadow:0 0 0 3px rgba(247,37,133,.08);min-width:200px;}
        .tb-search i{font-size:.75rem;color:var(--muted);flex-shrink:0;}
        .tb-search input{background:none;border:none;outline:none;font-family:'DM Sans',sans-serif;font-size:.8rem;color:var(--text);width:100%;}
        .tb-search input::placeholder{color:var(--muted);}
        .tb-divider{width:1px;height:24px;background:#fce4f3;margin:0 .2rem;}
        .tb-icon{width:38px;height:38px;border-radius:11px;border:1.5px solid #fce4f3;background:rgba(255,255,255,.9);display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:.85rem;cursor:pointer;transition:all .18s;text-decoration:none;position:relative;box-shadow:0 1px 4px rgba(247,37,133,.04);}
        .tb-icon:hover{background:var(--pink-soft);border-color:rgba(247,37,133,.3);color:var(--pink);transform:translateY(-1px);box-shadow:0 4px 14px rgba(247,37,133,.12);}
        .tb-icon-label{position:absolute;bottom:-22px;left:50%;transform:translateX(-50%);white-space:nowrap;font-size:.6rem;font-weight:600;background:#1a0a12;color:#fff;padding:.18rem .45rem;border-radius:5px;opacity:0;pointer-events:none;transition:opacity .15s;letter-spacing:.03em;}
        .tb-icon:hover .tb-icon-label{opacity:1;}
        .tb-pos{display:flex;align-items:center;gap:.45rem;padding:.38rem .9rem;border-radius:10px;background:linear-gradient(135deg,var(--pink),var(--pink-lt));color:#fff;font-size:.78rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;box-shadow:0 3px 14px rgba(247,37,133,.3);transition:all .18s;white-space:nowrap;letter-spacing:.01em;}
        .tb-pos:hover{box-shadow:0 6px 22px rgba(247,37,133,.4);transform:translateY(-1px);color:#fff;}
        .tb-pos i{font-size:.78rem;}
        .tb-user-wrap{position:relative;}
        .tb-user{display:flex;align-items:center;gap:.5rem;padding:.3rem .65rem .3rem .3rem;border-radius:50px;border:1.5px solid #fce4f3;background:rgba(255,255,255,.9);cursor:pointer;transition:all .18s;user-select:none;box-shadow:0 1px 6px rgba(247,37,133,.06);}
        .tb-user:hover{background:var(--pink-soft);border-color:rgba(247,37,133,.3);box-shadow:0 4px 16px rgba(247,37,133,.1);}
        .tb-av{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--pink),var(--pink-lt));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.76rem;font-weight:700;box-shadow:0 2px 8px rgba(247,37,133,.3);flex-shrink:0;overflow:hidden;}
        .tb-user-info{display:flex;flex-direction:column;gap:.02rem;}
        .tb-uname{font-size:.82rem;font-weight:700;color:var(--text);line-height:1.1;}
        .tb-urole{font-size:.62rem;color:var(--muted);font-weight:400;}
        .tb-chev{font-size:.6rem;color:var(--muted);transition:transform .22s;margin-left:.1rem;}
        .tb-user.open .tb-chev{transform:rotate(180deg);}
        .tb-drop{display:none;position:absolute;top:calc(100% + .8rem);right:0;background:#fff;border:1.5px solid #fce4f3;border-radius:18px;box-shadow:0 0 0 1px rgba(247,37,133,.04),0 10px 40px rgba(247,37,133,.12),0 2px 8px rgba(0,0,0,.06);min-width:240px;overflow:hidden;z-index:300;animation:dropIn .2s cubic-bezier(.16,1,.3,1);}
        .tb-drop.open{display:block;}
        @keyframes dropIn{from{opacity:0;transform:translateY(-8px) scale(.97);}to{opacity:1;transform:translateY(0) scale(1);}}
        .tb-drop-head{padding:1.1rem 1.2rem;background:linear-gradient(135deg,#fff0f7 0%,#fdf5fb 100%);border-bottom:1.5px solid #fce4f3;position:relative;overflow:hidden;}
        .tb-drop-head::before{content:'';position:absolute;width:80px;height:80px;border-radius:50%;background:radial-gradient(circle,rgba(247,37,133,.1) 0%,transparent 70%);top:-25px;right:-20px;}
        .tb-drop-head-inner{display:flex;align-items:center;gap:.75rem;position:relative;z-index:1;}
        .tb-drop-av{width:40px;height:40px;border-radius:12px;flex-shrink:0;background:linear-gradient(135deg,var(--pink),var(--pink-lt));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;font-weight:700;box-shadow:0 4px 14px rgba(247,37,133,.35);overflow:hidden;}
        .tb-drop-name{font-size:.9rem;font-weight:700;color:var(--text);line-height:1.2;}
        .tb-drop-email{font-size:.72rem;color:var(--muted);margin-top:.1rem;}
        .tb-drop-badge{display:inline-flex;align-items:center;gap:.22rem;background:rgba(247,37,133,.08);color:var(--pink);border:1px solid rgba(247,37,133,.2);border-radius:20px;padding:.12rem .5rem;font-size:.58rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin-top:.3rem;}
        .tb-drop-badge i{font-size:.5rem;}
        .tb-drop-menu{padding:.4rem 0;}
        .tb-drop-item{display:flex;align-items:center;gap:.75rem;padding:.65rem 1.2rem;font-size:.83rem;color:var(--text);background:none;border:none;width:100%;cursor:pointer;font-family:inherit;transition:background .13s;text-decoration:none;}
        .tb-drop-item:hover{background:#fff0f7;color:var(--pink);}
        .tb-drop-icon{width:28px;height:28px;border-radius:8px;flex-shrink:0;background:var(--pink-soft);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--pink);transition:all .15s;}
        .tb-drop-item:hover .tb-drop-icon{background:#fce4f3;}
        .tb-drop-item.danger{color:var(--tango);}
        .tb-drop-item.danger .tb-drop-icon{background:var(--tango-soft);color:var(--tango);}
        .tb-drop-item.danger:hover{background:var(--tango-soft);}
        .tb-drop-sep{height:1px;background:#fce4f3;margin:.3rem .8rem;}

        /* ════ CONTENT ════ */
        .content{flex:1;padding:1.5rem 1.75rem 2rem;}

        /* Flash */
        .flash{padding:.8rem 1.1rem;border-radius:11px;margin-bottom:1rem;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:.6rem;border:1px solid transparent;}
        .flash-success{background:var(--green-soft);color:#15803d;border-color:#bbf7d0;}
        .flash-error{background:var(--tango-soft);color:#b91c1c;border-color:#fecdd3;}
        .flash-warning{background:#fffbeb;color:#92400e;border-color:#fde68a;}

        /* ════ DESIGN SYSTEM ════ */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;}
        .stat-card{background:var(--card);border-radius:var(--r);padding:1.15rem 1.25rem;display:flex;align-items:center;gap:1rem;border:1.5px solid var(--border);box-shadow:var(--shadow);transition:transform .18s,box-shadow .18s;position:relative;overflow:hidden;}
        .stat-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-md);}
        .stat-icon{width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;flex-shrink:0;}
        .stat-icon.pink  {background:linear-gradient(135deg,#fff0f7,#fce4f3);color:var(--pink);box-shadow:0 4px 14px rgba(247,37,133,.12);}
        .stat-icon.tango {background:linear-gradient(135deg,#fff4f0,#ffe0d6);color:var(--tango);box-shadow:0 4px 14px rgba(244,81,30,.12);}
        .stat-icon.green {background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:var(--green);box-shadow:0 4px 14px rgba(45,198,83,.12);}
        .stat-icon.blue  {background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#2563eb;box-shadow:0 4px 14px rgba(37,99,235,.12);}
        .stat-icon.purple{background:linear-gradient(135deg,#f5f3ff,#ede9fe);color:var(--purple);box-shadow:0 4px 14px rgba(124,58,237,.12);}
        .stat-icon.orange{background:linear-gradient(135deg,#fff7ed,#ffedd5);color:#f97316;box-shadow:0 4px 14px rgba(249,115,22,.12);}
        .stat-icon.rose  {background:linear-gradient(135deg,#fff0f0,#fee2e2);color:#e11d48;box-shadow:0 4px 14px rgba(225,29,72,.12);}
        .stat-icon.gold  {background:linear-gradient(135deg,#fffbeb,#fef3c7);color:var(--gold);box-shadow:0 4px 14px rgba(245,158,11,.12);}
        .stat-value{font-size:1.28rem;font-weight:700;color:var(--text);line-height:1;margin-bottom:.2rem;}
        .stat-label{font-size:.74rem;color:var(--muted);font-weight:500;}
        .stat-trend{font-size:.72rem;font-weight:600;margin-top:.28rem;display:flex;align-items:center;gap:.2rem;}
        .stat-trend.up{color:var(--green);}
        .stat-trend.down{color:var(--tango);}
        .card{background:var(--card);border-radius:var(--r);border:1.5px solid var(--border);box-shadow:var(--shadow);overflow:hidden;margin-bottom:1.25rem;}
        .card-header{padding:1rem 1.3rem;border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;background:linear-gradient(120deg,#fff 45%,var(--pink-soft) 100%);flex-wrap:wrap;}
        .card-header h3{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:.5rem;}
        .card-body{padding:1.2rem 1.3rem;}
        .table-wrap{overflow-x:auto;}
        table{width:100%;border-collapse:collapse;font-size:.84rem;}
        thead th{padding:.72rem 1rem;text-align:left;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--pink);background:linear-gradient(120deg,var(--pink-soft),#fff8fb);border-bottom:1.5px solid var(--border);white-space:nowrap;}
        tbody td{padding:.8rem 1rem;border-bottom:1px solid #fff0f7;vertical-align:middle;color:var(--text);}
        tbody tr:last-child td{border-bottom:none;}
        tbody tr:hover td{background:#fff5f9;}
        .badge{display:inline-flex;align-items:center;gap:.22rem;padding:.22rem .65rem;border-radius:20px;font-size:.7rem;font-weight:700;white-space:nowrap;border:1px solid transparent;}
        .badge-success{background:#f0fdf4;color:#16a34a;border-color:#bbf7d0;}
        .badge-warning{background:#fffbeb;color:#d97706;border-color:#fde68a;}
        .badge-danger{background:#fff1f2;color:var(--tango);border-color:#fecdd3;}
        .badge-info{background:#eff6ff;color:#2563eb;border-color:#bfdbfe;}
        .badge-pink{background:var(--pink-soft);color:var(--pink);border-color:var(--pink-mid);}
        .badge-tango{background:var(--tango-soft);color:var(--tango);border-color:#fed7aa;}
        .badge-purple{background:var(--purple-soft);color:var(--purple);border-color:#ddd6fe;}
        .badge-gold{background:var(--gold-soft);color:var(--gold);border-color:#fde68a;}
        .badge-muted{background:#f5f5f5;color:#777;border-color:#e5e5e5;}
        .btn{display:inline-flex;align-items:center;gap:.42rem;padding:.55rem 1.1rem;border-radius:var(--r-sm);font-size:.84rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .18s ease;white-space:nowrap;font-family:inherit;line-height:1;}
        .btn:disabled{opacity:.5;cursor:not-allowed;pointer-events:none;}
        .btn-primary{background:linear-gradient(135deg,var(--pink),var(--pink-lt));color:#fff;box-shadow:0 4px 14px rgba(247,37,133,.25);}
        .btn-primary:hover{box-shadow:0 6px 22px rgba(247,37,133,.38);transform:translateY(-1px);}
        .btn-pink{background:linear-gradient(135deg,var(--pink),var(--pink-lt));color:#fff;box-shadow:0 4px 14px rgba(247,37,133,.25);}
        .btn-pink:hover{box-shadow:0 6px 22px rgba(247,37,133,.35);transform:translateY(-1px);}
        .btn-tango{background:linear-gradient(135deg,var(--tango),var(--tango-lt));color:#fff;box-shadow:0 4px 14px rgba(244,81,30,.25);}
        .btn-tango:hover{box-shadow:0 6px 22px rgba(244,81,30,.35);transform:translateY(-1px);}
        .btn-success{background:linear-gradient(135deg,var(--green),var(--green-lt));color:#fff;box-shadow:0 4px 14px rgba(45,198,83,.22);}
        .btn-success:hover{box-shadow:0 6px 22px rgba(45,198,83,.32);transform:translateY(-1px);}
        .btn-outline{background:#fff;color:var(--text);border:1.5px solid var(--border);}
        .btn-outline:hover{border-color:var(--pink);color:var(--pink);background:var(--pink-soft);}
        .btn-danger{background:var(--tango-soft);color:var(--tango);border:1.5px solid #fecdd3;}
        .btn-danger:hover{background:var(--tango);color:#fff;border-color:var(--tango);}
        .btn-sm{padding:.33rem .72rem;font-size:.76rem;border-radius:7px;}
        .btn-lg{padding:.72rem 1.55rem;font-size:.94rem;}
        .btn-icon{padding:.5rem;width:36px;height:36px;justify-content:center;}
        .form-group{margin-bottom:1.1rem;}
        .form-group label{display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem;letter-spacing:.02em;}
        .form-group input,.form-group select,.form-group textarea{width:100%;padding:.63rem .9rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.87rem;font-family:inherit;background:#fff;color:var(--text);transition:border-color .18s,box-shadow .18s;outline:none;}
        .form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--pink);box-shadow:0 0 0 3px rgba(247,37,133,.09);}
        .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
        .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;}
        .pagination-wrap{display:flex;justify-content:center;gap:.4rem;padding:1rem;flex-wrap:wrap;}
        .pagination-wrap a,.pagination-wrap span{padding:.4rem .8rem;border-radius:8px;font-size:.82rem;border:1.5px solid var(--border);background:#fff;color:var(--muted);transition:all .15s;}
        .pagination-wrap a:hover{border-color:var(--pink);color:var(--pink);background:var(--pink-soft);}
        .pagination-wrap .active{background:linear-gradient(135deg,var(--pink),var(--pink-lt));border-color:var(--pink);color:#fff;box-shadow:0 3px 10px rgba(247,37,133,.25);}
        .empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 2rem;color:var(--muted);text-align:center;}
        .empty-state i{font-size:2.5rem;margin-bottom:.75rem;opacity:.2;}
        .empty-state p{font-size:.88rem;margin-bottom:1rem;}
        .page-header{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;}
        .page-title{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:.55rem;margin-bottom:.2rem;}
        .page-sub{font-size:.8rem;color:var(--muted);}

        /* ════ NOTIFICATION BELL ════ */
        .notif-bell-wrap{position:relative;}
        .notif-bell-btn{position:relative;background:none;border:1.5px solid #fce4f3;width:38px;height:38px;border-radius:11px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:.85rem;transition:all .18s;background:rgba(255,255,255,.9);box-shadow:0 1px 4px rgba(247,37,133,.04);}
        .notif-bell-btn:hover{background:var(--pink-soft);border-color:rgba(247,37,133,.3);color:var(--pink);transform:translateY(-1px);box-shadow:0 4px 14px rgba(247,37,133,.12);}
        .notif-count{position:absolute;top:2px;right:2px;background:linear-gradient(135deg,var(--tango),var(--pink));color:#fff;font-size:.6rem;font-weight:800;min-width:16px;height:16px;border-radius:8px;display:flex;align-items:center;justify-content:center;padding:0 3px;border:2px solid #fff;pointer-events:none;}
        .notif-dropdown{position:absolute;top:calc(100% + 10px);right:0;width:340px;background:#fff;border:1.5px solid #fce4f3;border-radius:var(--r);box-shadow:0 0 0 1px rgba(247,37,133,.04),0 10px 40px rgba(247,37,133,.12),0 2px 8px rgba(0,0,0,.06);z-index:9999;overflow:hidden;animation:dropIn .2s cubic-bezier(.16,1,.3,1);}
        .notif-dropdown-header{padding:.85rem 1rem;border-bottom:1.5px solid #fce4f3;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(120deg,#fff 45%,var(--pink-soft) 100%);}
        .notif-mark-all{font-size:.72rem;font-weight:700;color:var(--purple);background:none;border:none;cursor:pointer;padding:0;}
        .notif-mark-all:hover{text-decoration:underline;}
        .notif-dropdown-list{max-height:360px;overflow-y:auto;}
        .notif-item{display:flex;gap:.75rem;padding:.85rem 1rem;border-bottom:1px solid #fff0f7;cursor:pointer;transition:background .13s;text-decoration:none;color:inherit;}
        .notif-item:last-child{border-bottom:none;}
        .notif-item:hover{background:#fff5f9;}
        .notif-item.unread{background:#fff0f7;}
        .notif-item.unread:hover{background:#fce4f3;}
        .notif-item-icon{width:34px;height:34px;border-radius:50%;background:var(--pink-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.78rem;color:var(--pink);}
        .notif-item-body{flex:1;min-width:0;}
        .notif-item-title{font-size:.82rem;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:.15rem;}
        .notif-item-text{font-size:.75rem;color:var(--muted);overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;}
        .notif-item-time{font-size:.68rem;color:#94a3b8;margin-top:.25rem;white-space:nowrap;}
        .notif-unread-dot{width:7px;height:7px;border-radius:50%;background:var(--pink);flex-shrink:0;align-self:center;}
        .notif-dropdown-footer{padding:.75rem 1rem;border-top:1.5px solid #fce4f3;text-align:center;background:#fff8fb;}
        .notif-dropdown-footer a{font-size:.78rem;font-weight:700;color:var(--purple);text-decoration:none;display:flex;align-items:center;justify-content:center;gap:.4rem;}
        .notif-dropdown-footer a:hover{text-decoration:underline;}

        @media(max-width:900px){
            .sidebar{transform:translateX(-100%);}
            .main{margin-left:0;}
            .tb-search{display:none;}
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══════════════════════════════════ SIDEBAR ══════════════════════════════════ --}}
<aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sb-brand" style="text-decoration:none">
        <div class="sb-logo-icon"><i class="fas fa-spa"></i></div>
        <div class="sb-brand-line1">American</div>
        <div class="sb-brand-line2">Beauty</div>
        <div class="sb-brand-sub">Admin Panel</div>
        <div class="sb-brand-pill">
            <i class="fas fa-crown" style="font-size:.5rem"></i>
            Management Suite
        </div>
    </a>

    <nav class="sb-nav">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-gauge-high"></i></span>
            <span class="sb-txt">Dashboard</span>
        </a>

        {{-- ── Product & Stock ── --}}
        <div class="sb-section">Product &amp; Stock</div>

        <a href="{{ route('admin.products.index') }}"
           class="sb-link {{ request()->routeIs('admin.products.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-box-open"></i></span>
            <span class="sb-txt">Products</span>
        </a>

        <a href="{{ route('admin.purchase.index') }}"
           class="sb-link {{ request()->routeIs('admin.purchase.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-cart-flatbed"></i></span>
            <span class="sb-txt">Purchases</span>
            @php $unpaidCount = \App\Models\Purchase::where('payment_status','unpaid')->count(); @endphp
            @if($unpaidCount > 0)
                <span class="sb-badge">{{ $unpaidCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.supplier.index') }}"
           class="sb-link {{ request()->routeIs('admin.supplier.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-building"></i></span>
            <span class="sb-txt">Suppliers</span>
        </a>

        <a href="{{ route('admin.stock.index') }}"
           class="sb-link {{ request()->routeIs('admin.stock.index') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-warehouse"></i></span>
            <span class="sb-txt">Stock</span>
        </a>

        <a href="{{ route('admin.stock.history') }}"
           class="sb-link {{ request()->routeIs('admin.stock.history') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-clock-rotate-left"></i></span>
            <span class="sb-txt">Stock History</span>
        </a>

        <a href="{{ route('admin.stock.low') }}"
           class="sb-link {{ request()->routeIs('admin.stock.low') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-triangle-exclamation"></i></span>
            <span class="sb-txt">Low Stock</span>
            @php $lowCount = \App\Models\Product::where('stock_quantity','<=',10)->where('stock_quantity','>',0)->count(); @endphp
            @if($lowCount > 0)
                <span class="sb-badge">{{ $lowCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.stock.damaged') }}"
           class="sb-link {{ request()->routeIs('admin.stock.damaged') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-box-archive"></i></span>
            <span class="sb-txt">Damaged / Expired</span>
        </a>

        {{-- ── POS & Orders ── --}}
        <div class="sb-section">POS &amp; Orders</div>

        <a href="{{ route('admin.pos.index') }}"
           class="sb-link {{ request()->routeIs('admin.pos.index') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-cash-register"></i></span>
            <span class="sb-txt">POS Terminal</span>
        </a>

        <a href="{{ route('admin.pos.orders') }}"
           class="sb-link {{ request()->routeIs('admin.pos.orders') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-receipt"></i></span>
            <span class="sb-txt">POS Orders</span>
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="sb-link {{ request()->routeIs('admin.orders.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-globe"></i></span>
            <span class="sb-txt">Online Orders</span>
        </a>

        <a href="{{ route('admin.return-orders.index') }}" class="sb-link">
           <span class="sb-ico"><i class="fas fa-rotate-left"></i></span>
           <span class="sb-txt">Return Orders</span>
        </a>
        <a href="{{ route('customer.return-orders.index') }}" class="sb-link">
            <span class="sb-ico"><i class="fas fa-rotate-left"></i></span>
            <span class="sb-txt">My Returns</span>
        </a>

        {{-- ── Promo ── --}}
        <div class="sb-section">Promo</div>

        <a href="{{ route('admin.coupons.index') }}"
           class="sb-link {{ request()->routeIs('admin.coupons.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-ticket"></i></span>
            <span class="sb-txt">Coupons</span>
            @php $activeCoupons = \App\Models\Coupon::where('is_active',true)->count(); @endphp
            @if($activeCoupons > 0)
                <span class="sb-badge">{{ $activeCoupons }}</span>
            @endif
        </a>

        <a href="{{ route('admin.promotions.index') }}"
           class="sb-link {{ request()->routeIs('admin.promotions.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-percent"></i></span>
            <span class="sb-txt">Promotions</span>
            @php $activePromos = \App\Models\Promotion::where('is_active',true)->count(); @endphp
            @if($activePromos > 0)
                <span class="sb-badge">{{ $activePromos }}</span>
            @endif
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="sb-link {{ request()->routeIs('admin.categories.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-layer-group"></i></span>
            <span class="sb-txt">Product Sections</span>
        </a>

        {{-- ── Logs ── --}}
        <div class="sb-section">Logs</div>

        <a href="#" class="sb-link soon">
            <span class="sb-ico"><i class="fas fa-mobile-screen-button"></i></span>
            <span class="sb-txt">M-Pesa Logs</span>
            <span class="sb-soon-pill">Soon</span>
        </a>

        <a href="#" class="sb-link soon">
            <span class="sb-ico"><i class="fas fa-users-viewfinder"></i></span>
            <span class="sb-txt">Customer Logs</span>
            <span class="sb-soon-pill">Soon</span>
        </a>

        <a href="#" class="sb-link soon">
            <span class="sb-ico"><i class="fas fa-user-tie"></i></span>
            <span class="sb-txt">Manager Logs</span>
            <span class="sb-soon-pill">Soon</span>
        </a>

        <a href="#" class="sb-link soon">
            <span class="sb-ico"><i class="fas fa-computer"></i></span>
            <span class="sb-txt">POS Operator Logs</span>
            <span class="sb-soon-pill">Soon</span>
        </a>

        {{-- ── Communications ── --}}
        <div class="sb-section">Communications</div>

        <a href="{{ route('admin.notifications.index') }}"
           class="sb-link {{ request()->routeIs('admin.notifications.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-bell"></i></span>
            <span class="sb-txt">Push Notifications</span>
        </a>
        {{-- For future implementation of SMS notifications via Twilio --}}
       <label style="display:flex;align-items:center;gap:.6rem;font-size:.85rem;color:var(--text);cursor:pointer">
         <input type="checkbox" name="send_sms" value="1"
           style="width:16px;height:16px;accent-color:var(--pink);cursor:pointer">
         <span>Also send SMS via Twilio</span>
         <span class="badge badge-success" style="font-size:.65rem">Live</span>
         </label>

        <a href="#" class="sb-link soon">
            <span class="sb-ico"><i class="fas fa-envelope-open-text"></i></span>
            <span class="sb-txt">Subscribers</span>
            <span class="sb-soon-pill">Soon</span>
        </a>

        {{-- ── Users ── --}}
        <div class="sb-section">Users</div>

        <a href="{{ route('admin.users.administrators') }}"
           class="sb-link {{ request()->routeIs('admin.users.administrators') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-user-shield"></i></span>
            <span class="sb-txt">Administrators</span>
            @php $adminCount = \App\Models\User::where('role','admin')->count(); @endphp
            @if($adminCount > 0)
                <span class="sb-badge">{{ $adminCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.users.managers') }}"
           class="sb-link {{ request()->routeIs('admin.users.managers') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-user-tie"></i></span>
            <span class="sb-txt">Managers</span>
        </a>

        <a href="{{ route('admin.users.pos-operators') }}"
           class="sb-link {{ request()->routeIs('admin.users.pos-operators') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-computer"></i></span>
            <span class="sb-txt">POS Operators</span>
        </a>

        <a href="{{ route('admin.users.delivery') }}"
           class="sb-link {{ request()->routeIs('admin.users.delivery') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-motorcycle"></i></span>
            <span class="sb-txt">Delivery Personnel</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="sb-link {{ request()->routeIs('admin.users.index') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-users"></i></span>
            <span class="sb-txt">Customers</span>
        </a>

        <a href="{{ route('admin.employees.index') }}"
           class="sb-link {{ request()->routeIs('admin.employees.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-id-badge"></i></span>
            <span class="sb-txt">Employees</span>
        </a>

        {{-- ── Accounts ── --}}
        <div class="sb-section">Accounts</div>

        <a href="{{ route('admin.transactions.index') }}"
           class="sb-link {{ request()->routeIs('admin.transactions.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-arrow-right-arrow-left"></i></span>
            <span class="sb-txt">Transactions</span>
        </a>

        {{-- ── Reports ── --}}
        <div class="sb-section">Reports</div>

        <a href="{{ route('admin.reports.sales') }}"
           class="sb-link {{ request()->routeIs('admin.reports.sales*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-chart-line"></i></span>
            <span class="sb-txt">Sales Report</span>
        </a>

        <a href="{{ route('admin.reports.products') }}"
           class="sb-link {{ request()->routeIs('admin.reports.products*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-chart-bar"></i></span>
            <span class="sb-txt">Products Report</span>
        </a>

        <a href="#" class="sb-link soon">
            <span class="sb-ico"><i class="fas fa-file-invoice-dollar"></i></span>
            <span class="sb-txt">Credit Balance Report</span>
            <span class="sb-soon-pill">Soon</span>
        </a>

        {{-- ── Attendance ── --}}
        <div class="sb-section">Attendance</div>

        <a href="{{ route('admin.attendance.terminal') }}"
           class="sb-link {{ request()->routeIs('admin.attendance.terminal') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-fingerprint"></i></span>
            <span class="sb-txt">Clock In / Out</span>
        </a>

        <a href="{{ route('admin.attendance.today') }}"
           class="sb-link {{ request()->routeIs('admin.attendance.today') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-calendar-day"></i></span>
            <span class="sb-txt">Today</span>
        </a>

        <a href="{{ route('admin.attendance.index') }}"
           class="sb-link {{ request()->routeIs('admin.attendance.index') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-list-check"></i></span>
            <span class="sb-txt">All Records</span>
        </a>

        <a href="{{ route('admin.attendance.report') }}"
           class="sb-link {{ request()->routeIs('admin.attendance.report') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-chart-gantt"></i></span>
            <span class="sb-txt">Report</span>
        </a>

        <a href="{{ route('admin.shifts.index') }}"
           class="sb-link {{ request()->routeIs('admin.shifts.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-clock"></i></span>
            <span class="sb-txt">Shifts</span>
        </a>

        {{-- ── Setup ── --}}
        <div class="sb-section">Setup</div>

        <a href="{{ route('admin.settings.index') }}"
           class="sb-link {{ request()->routeIs('admin.settings.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-sliders"></i></span>
            <span class="sb-txt">Settings</span>
        </a>

    </nav>

    <div class="sb-foot">
        <div class="sb-user">
            @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                     alt="{{ auth()->user()->name }}"
                     style="width:33px;height:33px;border-radius:9px;object-fit:cover;flex-shrink:0">
            @else
                <div class="sb-av">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            @endif
            <div style="flex:1;min-width:0">
                <span class="sb-uname">{{ auth()->user()->name ?? 'Admin' }}</span>
                <span class="sb-urole">{{ auth()->user()->role_label ?? 'Administrator' }}</span>
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

{{-- ══════════════════════════════════ MAIN ══════════════════════════════════ --}}
<div class="main">

    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-page-icon"><i class="fas fa-gauge-high"></i></div>
            <div class="topbar-breadcrumb">
                <div class="topbar-title">@yield('title','Dashboard')</div>
                <div class="topbar-sub">
                    <span>American Beauty</span>
                    <span class="sub-sep">·</span>
                    <span>Admin Panel</span>
                    <span class="sub-sep">·</span>
                    <span class="sub-live">Live</span>
                </div>
            </div>
        </div>
        <div class="topbar-right">
            <div class="tb-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Quick search…">
            </div>
            <a href="{{ route('admin.pos.index') }}" class="tb-pos">
                <i class="fas fa-cash-register"></i> POS
            </a>
            <div class="tb-divider"></div>
            <a href="{{ route('home') }}" class="tb-icon" target="_blank" rel="noopener">
                <i class="fas fa-store"></i>
                <span class="tb-icon-label">View Store</span>
            </a>

            {{-- ── Notification Bell ── --}}
            <div class="notif-bell-wrap">
                <button class="notif-bell-btn" id="notifBellBtn" onclick="toggleNotifDropdown()" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notif-count" id="notifCount" style="display:none">0</span>
                </button>
                <div class="notif-dropdown" id="notifDropdown" style="display:none">
                    <div class="notif-dropdown-header">
                        <span style="font-weight:700;font-size:.88rem">Notifications</span>
                        <button onclick="markAllRead()" class="notif-mark-all">Mark all read</button>
                    </div>
                    <div class="notif-dropdown-list" id="notifList">
                        <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:.82rem">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                    <div class="notif-dropdown-footer">
                        <a href="{{ route('admin.notifications.index') }}">
                            <i class="fas fa-bell"></i> Manage Notifications
                        </a>
                    </div>
                </div>
            </div>

            <div class="tb-divider"></div>
            <div class="tb-user-wrap">
                <div class="tb-user" id="tbUser" onclick="toggleDrop()">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}"
                             alt="{{ auth()->user()->name }}"
                             style="width:32px;height:32px;border-radius:50%;object-fit:cover;flex-shrink:0;box-shadow:0 2px 8px rgba(247,37,133,.3)">
                    @else
                        <div class="tb-av">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    @endif
                    <div class="tb-user-info">
                        <span class="tb-uname">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <span class="tb-urole">{{ auth()->user()->role_label ?? 'Administrator' }}</span>
                    </div>
                    <i class="fas fa-chevron-down tb-chev"></i>
                </div>
                <div class="tb-drop" id="tbDrop">
                    <div class="tb-drop-head">
                        <div class="tb-drop-head-inner">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                     alt="{{ auth()->user()->name }}"
                                     style="width:40px;height:40px;border-radius:12px;object-fit:cover;flex-shrink:0;box-shadow:0 4px 14px rgba(247,37,133,.35)">
                            @else
                                <div class="tb-drop-av">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                            @endif
                            <div>
                                <div class="tb-drop-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                                <div class="tb-drop-email">{{ auth()->user()->email ?? 'admin@americanbeauty.com' }}</div>
                                <div class="tb-drop-badge">
                                    <i class="fas fa-crown"></i>
                                    {{ auth()->user()->role_label ?? 'Administrator' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tb-drop-menu">
                        <a href="{{ route('admin.profile.edit') }}" class="tb-drop-item">
                            <span class="tb-drop-icon"><i class="fas fa-user-pen"></i></span> Edit Profile
                        </a>
                        <a href="{{ route('admin.profile.password') }}" class="tb-drop-item">
                            <span class="tb-drop-icon"><i class="fas fa-lock"></i></span> Change Password
                        </a>
                        <a href="{{ route('admin.profile.activity') }}" class="tb-drop-item">
                            <span class="tb-drop-icon"><i class="fas fa-clock-rotate-left"></i></span> Activity Log
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="tb-drop-item">
                            <span class="tb-drop-icon"><i class="fas fa-sliders"></i></span> Settings
                        </a>
                        <div class="tb-drop-sep"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0">
                            @csrf
                            <button type="submit" class="tb-drop-item danger">
                                <span class="tb-drop-icon"><i class="fas fa-right-from-bracket"></i></span> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    <div style="padding:.75rem 1.75rem 0">
        @if(session('success'))
            <div class="flash flash-success"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error"><i class="fas fa-circle-xmark"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="flash flash-error">
                <i class="fas fa-circle-xmark"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
        @endif
    </div>

    <div class="content">
        @yield('content')
    </div>

</div>

@stack('scripts')
<script>
/* ── User dropdown ── */
function toggleDrop(){
    const u=document.getElementById('tbUser');
    const d=document.getElementById('tbDrop');
    const open=d.classList.contains('open');
    d.classList.toggle('open',!open);
    u.classList.toggle('open',!open);
}
document.addEventListener('click',function(e){
    if(!e.target.closest('#tbUser')&&!e.target.closest('#tbDrop')){
        document.getElementById('tbDrop').classList.remove('open');
        document.getElementById('tbUser').classList.remove('open');
    }
});

/* ── Flash auto-hide ── */
document.querySelectorAll('.flash').forEach(function(el){
    setTimeout(function(){
        el.style.transition='opacity .4s';
        el.style.opacity='0';
        setTimeout(function(){el.remove();},400);
    },5000);
});

/* ── Notification Bell ── */
let notifOpen = false;

function toggleNotifDropdown() {
    notifOpen = !notifOpen;
    const dropdown = document.getElementById('notifDropdown');
    dropdown.style.display = notifOpen ? 'block' : 'none';
    if (notifOpen) loadNotifications();
}

document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.notif-bell-wrap');
    if (wrap && !wrap.contains(e.target) && notifOpen) {
        notifOpen = false;
        document.getElementById('notifDropdown').style.display = 'none';
    }
});

async function loadNotifications() {
    try {
        const res  = await fetch('{{ route("admin.notifications.recent") }}');
        const data = await res.json();
        renderNotifications(data);
    } catch (e) {
        document.getElementById('notifList').innerHTML =
            '<div style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.82rem">Failed to load.</div>';
    }
}

function renderNotifications(items) {
    const list = document.getElementById('notifList');
    if (!items.length) {
        list.innerHTML = '<div style="padding:2rem;text-align:center;color:#94a3b8;font-size:.82rem"><i class="fas fa-bell-slash" style="display:block;font-size:1.5rem;margin-bottom:.5rem;opacity:.3"></i>No notifications yet.</div>';
        return;
    }
    list.innerHTML = items.map(n => `
        <div class="notif-item ${!n.is_read ? 'unread' : ''}"
             onclick="handleNotifClick(${n.id}, '${n.url || ''}')">
            <div class="notif-item-icon"><i class="${n.icon}"></i></div>
            <div class="notif-item-body">
                <div class="notif-item-title">${n.title}</div>
                <div class="notif-item-text">${n.body}</div>
                <div class="notif-item-time">${timeAgo(n.created_at)}</div>
            </div>
            ${!n.is_read ? '<div class="notif-unread-dot"></div>' : ''}
        </div>
    `).join('');
}

async function handleNotifClick(id, url) {
    await fetch(`/admin/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    updateCount();
    if (url) window.location.href = url;
}

async function markAllRead() {
    await fetch('{{ route("admin.notifications.read-all") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    updateCount();
    loadNotifications();
}

async function updateCount() {
    try {
        const res  = await fetch('{{ route("admin.notifications.unread-count") }}');
        const data = await res.json();
        const badge = document.getElementById('notifCount');
        badge.textContent = data.count;
        badge.style.display = data.count > 0 ? 'flex' : 'none';
    } catch {}
}

function timeAgo(dateStr) {
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60)    return 'Just now';
    if (diff < 3600)  return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
}

updateCount();
setInterval(updateCount, 60000);
</script>
</body>
</html>