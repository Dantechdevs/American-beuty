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
            --border:      #f0dcea;
            --bg:          #fdf8fc;
            --card:        #ffffff;
            --text:        #1a0a12;
            --muted:       #8a6070;
            --shadow:      0 2px 16px rgba(247,37,133,.07);
            --shadow-md:   0 4px 24px rgba(247,37,133,.11);
            --shadow-lg:   0 8px 40px rgba(247,37,133,.16);
            --r:           14px;
            --r-sm:        9px;

            /* Sidebar — deep dark purple-black */
            --sb:          #0a0114;
            --sb2:         #110120;
            --sb-border:   rgba(124,58,237,.18);
            --sb-text:     #ddd0f5;
            --sb-muted:    #5a3d7a;
            --sb-hover:    rgba(124,58,237,.13);
            --sb-active:   rgba(124,58,237,.28);
            --sb-w:        268px;
            --bar-h:       64px;
        }

        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh;font-size:14px;}
        a{text-decoration:none;color:inherit;}
        ::-webkit-scrollbar{width:4px;height:4px;}
        ::-webkit-scrollbar-thumb{background:rgba(124,58,237,.25);border-radius:4px;}

        /* ════════════════════════════════════════
           SIDEBAR
           ════════════════════════════════════════ */
        .sidebar{
            width:var(--sb-w);
            background:linear-gradient(180deg, var(--sb) 0%, var(--sb2) 100%);
            position:fixed;top:0;left:0;height:100vh;
            display:flex;flex-direction:column;
            z-index:200;overflow-y:auto;overflow-x:hidden;
            border-right:1px solid var(--sb-border);
            box-shadow:4px 0 32px rgba(124,58,237,.15);
        }
        .sidebar::-webkit-scrollbar-thumb{background:rgba(124,58,237,.2);}

        /* ── BRAND ── */
        .sb-brand{
            padding:1.5rem 1.3rem 1.3rem;
            display:flex;flex-direction:column;
            gap:.3rem;
            border-bottom:1px solid var(--sb-border);
            flex-shrink:0;
            position:relative;overflow:hidden;
            background:linear-gradient(135deg,rgba(124,58,237,.18) 0%,rgba(247,37,133,.1) 100%);
        }
        /* decorative glow blob */
        .sb-brand::before{
            content:'';position:absolute;
            width:180px;height:180px;border-radius:50%;
            background:radial-gradient(circle,rgba(124,58,237,.25) 0%,transparent 70%);
            top:-60px;right:-50px;pointer-events:none;
        }
        .sb-brand::after{
            content:'';position:absolute;
            width:100px;height:100px;border-radius:50%;
            background:radial-gradient(circle,rgba(247,37,133,.15) 0%,transparent 70%);
            bottom:-30px;left:10px;pointer-events:none;
        }

        /* Logo icon */
        .sb-logo-icon{
            width:46px;height:46px;border-radius:14px;flex-shrink:0;
            background:linear-gradient(135deg,var(--purple) 0%,var(--pink) 100%);
            display:flex;align-items:center;justify-content:center;
            font-size:1.2rem;color:#fff;
            box-shadow:0 6px 20px rgba(124,58,237,.55);
            position:relative;z-index:1;
            margin-bottom:.5rem;
        }

        /* Big brand name */
        .sb-brand-american{
            font-family:'Playfair Display',serif;
            font-size:1.65rem;
            font-weight:900;
            line-height:1;
            letter-spacing:-.02em;
            color:#fff;
            position:relative;z-index:1;
        }
        .sb-brand-beauty{
            font-family:'Playfair Display',serif;
            font-size:1.65rem;
            font-weight:900;
            font-style:italic;
            line-height:1;
            letter-spacing:-.01em;
            background:linear-gradient(135deg,var(--purple-lt) 0%,var(--pink-lt) 60%,#fff 100%);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            background-clip:text;
            position:relative;z-index:1;
        }
        .sb-brand-sub{
            font-size:.63rem;color:var(--sb-muted);
            letter-spacing:.14em;text-transform:uppercase;
            font-weight:600;margin-top:.25rem;
            position:relative;z-index:1;
        }
        .sb-brand-pill{
            display:inline-flex;align-items:center;gap:.28rem;
            background:rgba(124,58,237,.22);
            border:1px solid rgba(124,58,237,.38);
            border-radius:20px;padding:.18rem .65rem;
            font-size:.58rem;font-weight:700;
            color:var(--purple-lt);
            letter-spacing:.08em;text-transform:uppercase;
            margin-top:.3rem;width:fit-content;
            position:relative;z-index:1;
        }

        /* Nav */
        .sb-nav{flex:1;padding:.6rem 0 1rem;}
        .sb-nav::-webkit-scrollbar{width:0;}

        .sb-section{
            display:flex;align-items:center;gap:.5rem;
            padding:.95rem 1.25rem .3rem;
            font-size:.6rem;font-weight:700;letter-spacing:.13em;
            text-transform:uppercase;color:var(--sb-muted);user-select:none;
        }
        .sb-section::after{content:'';flex:1;height:1px;background:var(--sb-border);}

        .sb-link{
            display:flex;align-items:center;gap:.7rem;
            padding:.52rem .95rem;margin:.05rem .62rem;
            border-radius:10px;color:var(--sb-text);
            font-size:.835rem;font-weight:400;
            transition:all .18s ease;position:relative;white-space:nowrap;
        }
        .sb-link:hover{
            background:var(--sb-hover);color:#fff;
            transform:translateX(3px);
        }
        .sb-link:hover .sb-ico{background:rgba(124,58,237,.28);color:var(--purple-lt);}
        .sb-link.active{background:var(--sb-active);color:#fff;font-weight:600;box-shadow:0 2px 12px rgba(124,58,237,.22);}
        .sb-link.active .sb-ico{
            background:linear-gradient(135deg,var(--purple),var(--pink));
            color:#fff;box-shadow:0 3px 12px rgba(124,58,237,.5);
        }
        .sb-link.active::after{
            content:'';position:absolute;right:0;top:18%;bottom:18%;
            width:3px;
            background:linear-gradient(to bottom,var(--purple-lt),var(--pink-lt));
            border-radius:3px 0 0 3px;
        }

        .sb-ico{
            width:29px;height:29px;border-radius:8px;flex-shrink:0;
            background:rgba(255,255,255,.04);
            display:flex;align-items:center;justify-content:center;
            font-size:.78rem;color:var(--sb-muted);transition:all .18s;
        }
        .sb-txt{flex:1;line-height:1;}
        .sb-badge{
            background:linear-gradient(135deg,var(--pink),var(--tango));
            color:#fff;font-size:.59rem;font-weight:700;
            padding:.13rem .42rem;border-radius:20px;line-height:1.4;
        }

        /* Sidebar footer */
        .sb-foot{
            padding:.85rem 1.15rem;
            border-top:1px solid var(--sb-border);flex-shrink:0;
            background:rgba(124,58,237,.06);
        }
        .sb-user{
            display:flex;align-items:center;gap:.72rem;
            padding:.6rem .75rem;border-radius:11px;
            background:rgba(124,58,237,.1);border:1px solid var(--sb-border);
        }
        .sb-av{
            width:34px;height:34px;border-radius:9px;flex-shrink:0;
            background:linear-gradient(135deg,var(--purple),var(--pink));
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:.88rem;font-weight:700;
            box-shadow:0 3px 10px rgba(124,58,237,.4);
        }
        .sb-uname{display:block;font-size:.8rem;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .sb-urole{font-size:.67rem;color:var(--sb-muted);}
        .sb-logout{
            margin-left:auto;background:none;border:none;cursor:pointer;
            color:var(--sb-muted);font-size:.88rem;padding:.2rem;flex-shrink:0;
            transition:color .15s;
        }
        .sb-logout:hover{color:var(--tango);}

        /* ════════════════════════════════════════
           MAIN
           ════════════════════════════════════════ */
        .main{margin-left:var(--sb-w);flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0;}

        /* Topbar */
        .topbar{
            height:var(--bar-h);background:var(--card);
            border-bottom:1.5px solid var(--border);
            display:flex;align-items:center;padding:0 1.75rem;gap:1rem;
            position:sticky;top:0;z-index:100;
            box-shadow:0 1px 14px rgba(124,58,237,.07);
        }
        .topbar-left{flex:1;}
        .topbar-title{
            font-family:'Playfair Display',serif;
            font-size:1.1rem;font-weight:700;color:var(--text);line-height:1;
        }
        .topbar-sub{font-size:.71rem;color:var(--muted);margin-top:.1rem;}
        .topbar-right{display:flex;align-items:center;gap:.6rem;}

        .tb-icon{
            width:37px;height:37px;border-radius:9px;
            border:1.5px solid var(--border);background:var(--card);
            display:flex;align-items:center;justify-content:center;
            color:var(--muted);font-size:.86rem;cursor:pointer;
            transition:all .15s;text-decoration:none;position:relative;
        }
        .tb-icon:hover{background:var(--purple-soft);border-color:var(--purple);color:var(--purple);}
        .tb-dot{
            position:absolute;top:7px;right:7px;
            width:6px;height:6px;background:var(--tango);
            border-radius:50%;border:1.5px solid var(--card);
        }
        .tb-divider{width:1px;height:26px;background:var(--border);}

        .tb-user{
            display:flex;align-items:center;gap:.55rem;
            padding:.35rem .75rem .35rem .4rem;
            border-radius:10px;border:1.5px solid var(--border);
            background:var(--card);cursor:pointer;
            transition:all .15s;position:relative;user-select:none;
        }
        .tb-user:hover{background:var(--purple-soft);border-color:rgba(124,58,237,.3);}
        .tb-av{
            width:28px;height:28px;border-radius:7px;
            background:linear-gradient(135deg,var(--purple),var(--pink));
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:.72rem;font-weight:700;
        }
        .tb-uname{font-size:.82rem;font-weight:600;color:var(--text);}
        .tb-chev{font-size:.62rem;color:var(--muted);transition:transform .2s;}
        .tb-user.open .tb-chev{transform:rotate(180deg);}

        .tb-drop{
            display:none;position:absolute;top:calc(100% + .55rem);right:0;
            background:var(--card);border:1.5px solid var(--border);
            border-radius:var(--r);box-shadow:var(--shadow-lg);
            min-width:200px;overflow:hidden;z-index:300;
            animation:dropIn .17s ease;
        }
        .tb-drop.open{display:block;}
        @keyframes dropIn{from{opacity:0;transform:translateY(-5px);}to{opacity:1;transform:translateY(0);}}
        .tb-drop-head{
            padding:.8rem 1rem;
            background:linear-gradient(135deg,var(--purple-soft),var(--pink-soft));
            border-bottom:1px solid var(--border);
        }
        .tb-drop-head strong{display:block;font-size:.84rem;color:var(--text);}
        .tb-drop-head span{font-size:.73rem;color:var(--muted);}
        .tb-drop a,.tb-drop button{
            display:flex;align-items:center;gap:.6rem;
            padding:.68rem 1rem;font-size:.83rem;color:var(--text);
            background:none;border:none;width:100%;cursor:pointer;
            font-family:inherit;transition:background .13s;
        }
        .tb-drop a i{color:var(--purple);width:14px;text-align:center;}
        .tb-drop a:hover{background:var(--purple-soft);color:var(--purple);}
        .tb-drop button i{color:var(--tango);width:14px;text-align:center;}
        .tb-drop button{color:var(--tango);}
        .tb-drop button:hover{background:var(--tango-soft);}
        .tb-drop hr{border:none;border-top:1px solid var(--border);margin:.2rem 0;}

        /* Content */
        .content{flex:1;padding:1.5rem 1.75rem 2rem;}

        /* Flash */
        .flash{
            padding:.8rem 1.1rem;border-radius:11px;
            margin-bottom:1rem;font-size:.85rem;font-weight:500;
            display:flex;align-items:center;gap:.6rem;
            border:1px solid transparent;
        }
        .flash-success{background:var(--green-soft);color:#15803d;border-color:#bbf7d0;}
        .flash-error  {background:var(--tango-soft);color:#b91c1c;border-color:#fecdd3;}
        .flash-warning{background:#fffbeb;color:#92400e;border-color:#fde68a;}

        /* ════════════════════════════════════════
           DESIGN SYSTEM
           ════════════════════════════════════════ */

        /* Stats */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;}
        .stat-card{
            background:var(--card);border-radius:var(--r);
            padding:1.15rem 1.25rem;
            display:flex;align-items:center;gap:1rem;
            border:1.5px solid var(--border);box-shadow:var(--shadow);
            transition:transform .18s,box-shadow .18s;
            position:relative;overflow:hidden;
        }
        .stat-card::after{
            content:'';position:absolute;top:-20px;right:-20px;
            width:80px;height:80px;border-radius:50%;
            background:radial-gradient(circle,rgba(124,58,237,.05) 0%,transparent 70%);
        }
        .stat-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-md);}
        .stat-icon{
            width:50px;height:50px;border-radius:14px;
            display:flex;align-items:center;justify-content:center;
            font-size:1.15rem;flex-shrink:0;
        }
        .stat-icon.pink  {background:linear-gradient(135deg,#fff0f7,#fce4f3);color:var(--pink);  box-shadow:0 4px 14px rgba(247,37,133,.14);}
        .stat-icon.tango {background:linear-gradient(135deg,#fff4f0,#ffe0d6);color:var(--tango); box-shadow:0 4px 14px rgba(244,81,30,.14);}
        .stat-icon.green {background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:var(--green); box-shadow:0 4px 14px rgba(45,198,83,.14);}
        .stat-icon.blue  {background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#2563eb;      box-shadow:0 4px 14px rgba(37,99,235,.14);}
        .stat-icon.purple{background:linear-gradient(135deg,#f5f3ff,#ede9fe);color:var(--purple);box-shadow:0 4px 14px rgba(124,58,237,.14);}
        .stat-icon.orange{background:linear-gradient(135deg,#fff7ed,#ffedd5);color:#f97316;      box-shadow:0 4px 14px rgba(249,115,22,.14);}
        .stat-icon.rose  {background:linear-gradient(135deg,#fff0f0,#fee2e2);color:#e11d48;      box-shadow:0 4px 14px rgba(225,29,72,.14);}
        .stat-icon.gold  {background:linear-gradient(135deg,#fffbeb,#fef3c7);color:var(--gold);  box-shadow:0 4px 14px rgba(245,158,11,.14);}
        .stat-value{font-size:1.28rem;font-weight:700;color:var(--text);line-height:1;margin-bottom:.2rem;}
        .stat-label{font-size:.74rem;color:var(--muted);font-weight:500;}
        .stat-trend{font-size:.72rem;font-weight:600;margin-top:.28rem;display:flex;align-items:center;gap:.2rem;}
        .stat-trend.up{color:var(--green);}
        .stat-trend.down{color:var(--tango);}

        /* Cards */
        .card{background:var(--card);border-radius:var(--r);border:1.5px solid var(--border);box-shadow:var(--shadow);overflow:hidden;margin-bottom:1.25rem;}
        .card-header{
            padding:1rem 1.3rem;border-bottom:1.5px solid var(--border);
            display:flex;align-items:center;justify-content:space-between;gap:.75rem;
            background:linear-gradient(120deg,#fff 45%,var(--purple-soft) 100%);
            flex-wrap:wrap;
        }
        .card-header h3{
            font-family:'Playfair Display',serif;
            font-size:.95rem;font-weight:700;color:var(--text);
            display:flex;align-items:center;gap:.5rem;
        }
        .card-body{padding:1.2rem 1.3rem;}

        /* Tables */
        .table-wrap{overflow-x:auto;}
        table{width:100%;border-collapse:collapse;font-size:.84rem;}
        thead th{
            padding:.72rem 1rem;text-align:left;
            font-size:.68rem;font-weight:700;text-transform:uppercase;
            letter-spacing:.08em;color:var(--purple);
            background:linear-gradient(120deg,var(--purple-soft),var(--pink-soft));
            border-bottom:1.5px solid var(--border);white-space:nowrap;
        }
        tbody td{padding:.8rem 1rem;border-bottom:1px solid #faf0f7;vertical-align:middle;color:var(--text);}
        tbody tr:last-child td{border-bottom:none;}
        tbody tr:hover td{background:#fdf5fc;}

        /* Badges */
        .badge{
            display:inline-flex;align-items:center;gap:.22rem;
            padding:.22rem .65rem;border-radius:20px;
            font-size:.7rem;font-weight:700;white-space:nowrap;
            border:1px solid transparent;
        }
        .badge-success{background:#f0fdf4;color:#16a34a;border-color:#bbf7d0;}
        .badge-warning{background:#fffbeb;color:#d97706;border-color:#fde68a;}
        .badge-danger {background:#fff1f2;color:var(--tango);border-color:#fecdd3;}
        .badge-info   {background:#eff6ff;color:#2563eb;border-color:#bfdbfe;}
        .badge-pink   {background:var(--pink-soft);color:var(--pink);border-color:var(--pink-mid);}
        .badge-tango  {background:var(--tango-soft);color:var(--tango);border-color:#fed7aa;}
        .badge-purple {background:var(--purple-soft);color:var(--purple);border-color:#ddd6fe;}
        .badge-gold   {background:var(--gold-soft);color:var(--gold);border-color:#fde68a;}
        .badge-muted  {background:#f5f5f5;color:#777;border-color:#e5e5e5;}

        /* Buttons */
        .btn{
            display:inline-flex;align-items:center;gap:.42rem;
            padding:.55rem 1.1rem;border-radius:var(--r-sm);
            font-size:.84rem;font-weight:600;cursor:pointer;border:none;
            text-decoration:none;transition:all .18s ease;
            white-space:nowrap;font-family:inherit;line-height:1;
        }
        .btn:disabled{opacity:.5;cursor:not-allowed;pointer-events:none;}
        .btn-primary{
            background:linear-gradient(135deg,var(--purple),var(--pink));
            color:#fff;box-shadow:0 4px 14px rgba(124,58,237,.28);
        }
        .btn-primary:hover{box-shadow:0 6px 22px rgba(124,58,237,.4);transform:translateY(-1px);}
        .btn-pink{
            background:linear-gradient(135deg,var(--pink),var(--pink-lt));
            color:#fff;box-shadow:0 4px 14px rgba(247,37,133,.28);
        }
        .btn-pink:hover{box-shadow:0 6px 22px rgba(247,37,133,.38);transform:translateY(-1px);}
        .btn-tango{
            background:linear-gradient(135deg,var(--tango),var(--tango-lt));
            color:#fff;box-shadow:0 4px 14px rgba(244,81,30,.28);
        }
        .btn-tango:hover{box-shadow:0 6px 22px rgba(244,81,30,.38);transform:translateY(-1px);}
        .btn-success{
            background:linear-gradient(135deg,var(--green),var(--green-lt));
            color:#fff;box-shadow:0 4px 14px rgba(45,198,83,.25);
        }
        .btn-success:hover{box-shadow:0 6px 22px rgba(45,198,83,.35);transform:translateY(-1px);}
        .btn-outline{background:#fff;color:var(--text);border:1.5px solid var(--border);}
        .btn-outline:hover{border-color:var(--purple);color:var(--purple);background:var(--purple-soft);}
        .btn-danger{background:var(--tango-soft);color:var(--tango);border:1.5px solid #fecdd3;}
        .btn-danger:hover{background:var(--tango);color:#fff;border-color:var(--tango);}
        .btn-sm{padding:.33rem .72rem;font-size:.76rem;border-radius:7px;}
        .btn-lg{padding:.72rem 1.55rem;font-size:.94rem;}
        .btn-icon{padding:.5rem;width:36px;height:36px;justify-content:center;}

        /* Forms */
        .form-group{margin-bottom:1.1rem;}
        .form-group label{display:block;font-size:.77rem;font-weight:600;color:var(--muted);margin-bottom:.38rem;letter-spacing:.02em;}
        .form-group input,.form-group select,.form-group textarea{
            width:100%;padding:.63rem .9rem;
            border:1.5px solid var(--border);border-radius:var(--r-sm);
            font-size:.87rem;font-family:inherit;background:#fff;color:var(--text);
            transition:border-color .18s,box-shadow .18s;outline:none;
        }
        .form-group input:focus,.form-group select:focus,.form-group textarea:focus{
            border-color:var(--purple);box-shadow:0 0 0 3px rgba(124,58,237,.1);
        }
        .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
        .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;}

        /* Pagination */
        .pagination-wrap{display:flex;justify-content:center;gap:.4rem;padding:1rem;flex-wrap:wrap;}
        .pagination-wrap a,.pagination-wrap span{
            padding:.4rem .8rem;border-radius:8px;font-size:.82rem;
            border:1.5px solid var(--border);background:#fff;color:var(--muted);
            transition:all .15s;
        }
        .pagination-wrap a:hover{border-color:var(--purple);color:var(--purple);background:var(--purple-soft);}
        .pagination-wrap .active{
            background:linear-gradient(135deg,var(--purple),var(--pink));
            border-color:var(--purple);color:#fff;
            box-shadow:0 3px 10px rgba(124,58,237,.28);
        }

        /* Empty state */
        .empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 2rem;color:var(--muted);text-align:center;}
        .empty-state i{font-size:2.5rem;margin-bottom:.75rem;opacity:.2;}
        .empty-state p{font-size:.88rem;margin-bottom:1rem;}

        @media(max-width:900px){
            .sidebar{transform:translateX(-100%);}
            .main{margin-left:0;}
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">

    {{-- Big Bold Brand --}}
    <a href="{{ route('admin.dashboard') }}" class="sb-brand" style="text-decoration:none">
        <div class="sb-logo-icon"><i class="fas fa-spa"></i></div>
        <div class="sb-brand-american">American</div>
        <div class="sb-brand-beauty">Beauty</div>
        <div class="sb-brand-sub">Admin Panel</div>
        <div class="sb-brand-pill">
            <i class="fas fa-crown" style="font-size:.52rem"></i>
            Management Suite
        </div>
    </a>

    <nav class="sb-nav">

        <a href="{{ route('admin.dashboard') }}"
           class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-gauge-high"></i></span>
            <span class="sb-txt">Dashboard</span>
        </a>

        <div class="sb-section">Product &amp; Stock</div>

        <a href="{{ route('admin.products.index') }}"
           class="sb-link {{ request()->routeIs('admin.products.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-box-open"></i></span>
            <span class="sb-txt">Products</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.purchase.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-cart-flatbed"></i></span>
            <span class="sb-txt">Purchase</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.damages.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-box-archive"></i></span>
            <span class="sb-txt">Damages</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.stock.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-warehouse"></i></span>
            <span class="sb-txt">Stock</span>
        </a>

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

        <a href="#" class="sb-link {{ request()->routeIs('admin.return-orders.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-rotate-left"></i></span>
            <span class="sb-txt">Return Orders</span>
        </a>

        <div class="sb-section">Promo</div>

        <a href="#" class="sb-link {{ request()->routeIs('admin.coupons.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-ticket"></i></span>
            <span class="sb-txt">Coupons</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.promotions.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-percent"></i></span>
            <span class="sb-txt">Promotions</span>
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="sb-link {{ request()->routeIs('admin.categories.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-layer-group"></i></span>
            <span class="sb-txt">Product Sections</span>
        </a>

        <div class="sb-section">Communications</div>

        <a href="#" class="sb-link {{ request()->routeIs('admin.notifications.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-bell"></i></span>
            <span class="sb-txt">Push Notifications</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.subscribers.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-envelope-open-text"></i></span>
            <span class="sb-txt">Subscribers</span>
        </a>

        <div class="sb-section">Users</div>

        <a href="#" class="sb-link {{ request()->routeIs('admin.administrators.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-user-shield"></i></span>
            <span class="sb-txt">Administrators</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.delivery-boys.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-motorcycle"></i></span>
            <span class="sb-txt">Delivery Boys</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="sb-link {{ request()->routeIs('admin.users.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-users"></i></span>
            <span class="sb-txt">Customers</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.employees.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-id-badge"></i></span>
            <span class="sb-txt">Employees</span>
        </a>

        <div class="sb-section">Accounts</div>

        <a href="#" class="sb-link {{ request()->routeIs('admin.transactions.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-arrow-right-arrow-left"></i></span>
            <span class="sb-txt">Transactions</span>
        </a>

        <div class="sb-section">Reports</div>

        <a href="#" class="sb-link {{ request()->routeIs('admin.reports.sales') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-chart-line"></i></span>
            <span class="sb-txt">Sales Report</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.reports.products') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-chart-bar"></i></span>
            <span class="sb-txt">Products Report</span>
        </a>

        <a href="#" class="sb-link {{ request()->routeIs('admin.reports.credit') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-file-invoice-dollar"></i></span>
            <span class="sb-txt">Credit Balance Report</span>
        </a>

        <div class="sb-section">Setup</div>

        <a href="{{ route('admin.settings.index') }}"
           class="sb-link {{ request()->routeIs('admin.settings.*') ? 'active':'' }}">
            <span class="sb-ico"><i class="fas fa-sliders"></i></span>
            <span class="sb-txt">Settings</span>
        </a>

    </nav>

    <div class="sb-foot">
        <div class="sb-user">
            <div class="sb-av">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div style="flex:1;min-width:0">
                <span class="sb-uname">{{ auth()->user()->name ?? 'Admin' }}</span>
                <span class="sb-urole">Administrator</span>
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
<div class="main">

    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">@yield('title','Dashboard')</div>
            <div class="topbar-sub">American Beauty · Admin Panel</div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('admin.pos.index') }}" class="tb-icon" title="Open POS Terminal">
                <i class="fas fa-cash-register"></i>
            </a>
            <a href="{{ route('home') }}" class="tb-icon" title="View Store" target="_blank">
                <i class="fas fa-store"></i>
            </a>
            <a href="#" class="tb-icon" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="tb-dot"></span>
            </a>
            <div class="tb-divider"></div>
            <div class="tb-user" id="tbUser" onclick="toggleDrop()">
                <div class="tb-av">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <span class="tb-uname">{{ auth()->user()->name ?? 'Admin' }}</span>
                <i class="fas fa-chevron-down tb-chev"></i>
                <div class="tb-drop" id="tbDrop">
                    <div class="tb-drop-head">
                        <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
                        <span>{{ auth()->user()->email ?? '' }}</span>
                    </div>
                    <a href="#"><i class="fas fa-user-pen"></i> Edit Profile</a>
                    <a href="#"><i class="fas fa-lock"></i> Change Password</a>
                    <hr>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-right-from-bracket"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
function toggleDrop(){
    const u=document.getElementById('tbUser');
    const d=document.getElementById('tbDrop');
    const open=d.classList.contains('open');
    d.classList.toggle('open',!open);
    u.classList.toggle('open',!open);
}
document.addEventListener('click',function(e){
    if(!e.target.closest('#tbUser')){
        document.getElementById('tbDrop').classList.remove('open');
        document.getElementById('tbUser').classList.remove('open');
    }
});
document.querySelectorAll('.flash').forEach(function(el){
    setTimeout(function(){
        el.style.transition='opacity .4s';
        el.style.opacity='0';
        setTimeout(function(){el.remove();},400);
    },5000);
});
</script>
</body>
</html>