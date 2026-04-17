<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Dashboard') — American Beauty Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        :root{--rose:#c8847a;--rose-dk:#a05e56;--sidebar:#1a1a2e;--sidebar-hover:#16213e;--charcoal:#2c2c2c;--border:#e8ddd6;--cream:#faf7f4;--white:#fff;}
        body{font-family:'DM Sans',sans-serif;background:#f4f5f7;color:var(--charcoal);display:flex;min-height:100vh;}
        a{text-decoration:none;color:inherit;}

        /* SIDEBAR */
        .sidebar{width:240px;background:var(--sidebar);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:200;overflow-y:auto;}
        .sidebar-brand{padding:1.5rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.08);}
        .sidebar-brand-name{font-size:1.3rem;font-weight:700;color:#fff;letter-spacing:.03em;}
        .sidebar-brand-name span{color:var(--rose);}
        .sidebar-brand-sub{font-size:.72rem;color:#888;margin-top:.1rem;letter-spacing:.08em;text-transform:uppercase;}
        .sidebar-nav{padding:1rem 0;flex:1;}
        .nav-section{padding:.5rem 1.2rem .25rem;font-size:.68rem;letter-spacing:.15em;text-transform:uppercase;color:#555;font-weight:600;}
        .nav-item{display:flex;align-items:center;gap:.8rem;padding:.65rem 1.2rem;color:#aaa;font-size:.88rem;transition:all .2s;cursor:pointer;border-left:3px solid transparent;}
        .nav-item:hover{background:var(--sidebar-hover);color:#fff;border-left-color:var(--rose);}
        .nav-item.active{background:var(--sidebar-hover);color:#fff;border-left-color:var(--rose);}
        .nav-item i{width:18px;text-align:center;font-size:.9rem;}
        .sidebar-footer{padding:1.2rem;border-top:1px solid rgba(255,255,255,.08);}
        .sidebar-user{display:flex;align-items:center;gap:.8rem;}
        .sidebar-avatar{width:36px;height:36px;background:var(--rose);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.9rem;}
        .sidebar-user-info span{display:block;font-size:.82rem;color:#ddd;}
        .sidebar-user-info small{font-size:.72rem;color:#888;}

        /* MAIN */
        .main{margin-left:240px;flex:1;display:flex;flex-direction:column;min-height:100vh;}
        .topbar{background:var(--white);border-bottom:1px solid var(--border);padding:.85rem 2rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;}
        .topbar-title{font-size:1.1rem;font-weight:600;}
        .topbar-actions{display:flex;align-items:center;gap:1rem;}
        .btn-store{font-size:.83rem;color:var(--rose);border:1px solid var(--rose);padding:.4rem .9rem;border-radius:20px;transition:all .2s;}
        .btn-store:hover{background:var(--rose);color:#fff;}
        .content{padding:2rem;flex:1;}

        /* FLASH */
        .flash{padding:.8rem 1.2rem;border-radius:10px;margin-bottom:1.5rem;font-size:.88rem;display:flex;align-items:center;gap:.6rem;}
        .flash-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
        .flash-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}

        /* CARDS */
        .card{background:var(--white);border-radius:16px;border:1px solid var(--border);overflow:hidden;}
        .card-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
        .card-header h3{font-size:1rem;font-weight:600;}
        .card-body{padding:1.5rem;}

        /* STATS */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.2rem;margin-bottom:2rem;}
        .stat-card{background:var(--white);border-radius:16px;padding:1.5rem;border:1px solid var(--border);display:flex;align-items:center;gap:1.2rem;}
        .stat-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;}
        .stat-icon.rose{background:#fdf0ec;color:var(--rose);}
        .stat-icon.blue{background:#e8f4fd;color:#2980b9;}
        .stat-icon.green{background:#e8f8f0;color:#27ae60;}
        .stat-icon.orange{background:#fef5e7;color:#e67e22;}
        .stat-value{font-size:1.7rem;font-weight:700;line-height:1;}
        .stat-label{font-size:.8rem;color:#888;margin-top:.3rem;}

        /* TABLE */
        .table-wrap{overflow-x:auto;}
        table{width:100%;border-collapse:collapse;font-size:.88rem;}
        th{padding:.8rem 1rem;text-align:left;font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;color:#888;background:var(--cream);font-weight:600;}
        td{padding:.9rem 1rem;border-bottom:1px solid var(--border);vertical-align:middle;}
        tr:last-child td{border:none;}
        tr:hover td{background:#fafafa;}

        /* BADGES */
        .badge{padding:.25rem .7rem;border-radius:20px;font-size:.73rem;font-weight:600;display:inline-block;}
        .badge-success{background:#d4edda;color:#155724;}
        .badge-warning{background:#fff3cd;color:#856404;}
        .badge-danger{background:#f8d7da;color:#721c24;}
        .badge-info{background:#d1ecf1;color:#0c5460;}
        .badge-primary{background:#e8f4fd;color:#2980b9;}
        .badge-secondary{background:#e9ecef;color:#495057;}

        /* BUTTONS */
        .btn{padding:.5rem 1rem;border-radius:8px;font-size:.85rem;font-weight:500;cursor:pointer;border:none;font-family:inherit;transition:all .2s;display:inline-flex;align-items:center;gap:.4rem;}
        .btn-primary{background:var(--rose);color:#fff;}.btn-primary:hover{background:var(--rose-dk);}
        .btn-sm{padding:.35rem .7rem;font-size:.78rem;}
        .btn-outline{border:1px solid var(--border);background:#fff;color:#555;}.btn-outline:hover{border-color:var(--rose);color:var(--rose);}
        .btn-danger{background:#e74c3c;color:#fff;}.btn-danger:hover{background:#c0392b;}
        .btn-success{background:#27ae60;color:#fff;}.btn-success:hover{background:#219a52;}

        /* FORM */
        .form-group{margin-bottom:1.2rem;}
        .form-group label{display:block;font-size:.82rem;font-weight:600;color:#555;margin-bottom:.35rem;letter-spacing:.03em;}
        .form-group input,.form-group select,.form-group textarea{width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.9rem;font-family:inherit;transition:border-color .2s;background:#fff;}
        .form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:var(--rose);}
        .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
        .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;}
        .form-check{display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.88rem;}
        .form-check input{width:auto;accent-color:var(--rose);}

        /* PAGINATION */
        .pagination-wrap{display:flex;justify-content:center;gap:.4rem;padding:1rem;flex-wrap:wrap;}
        .pagination-wrap a,.pagination-wrap span{padding:.4rem .8rem;border-radius:8px;font-size:.83rem;border:1px solid var(--border);background:#fff;}
        .pagination-wrap a:hover{border-color:var(--rose);color:var(--rose);}

        @media(max-width:900px){.sidebar{transform:translateX(-100%);}.main{margin-left:0;}}
    </style>
    @stack('styles')
</head>
<body>
<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-name">American<span>Beauty</span></div>
        <div class="sidebar-brand-sub">Admin Panel</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <div class="nav-section">Catalogue</div>
        <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active':'' }}">
            <i class="fas fa-box"></i> Products
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active':'' }}">
            <i class="fas fa-tags"></i> Categories
        </a>
        <div class="nav-section">Sales</div>
        <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active':'' }}">
            <i class="fas fa-shopping-bag"></i> Orders
        </a>
        <div class="nav-section">Users</div>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active':'' }}">
            <i class="fas fa-users"></i> Customers
        </a>
        <div class="nav-section">Config</div>
        <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active':'' }}">
            <i class="fas fa-cog"></i> Settings
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div class="sidebar-user-info">
                <span>{{ auth()->user()->name }}</span>
                <small>Administrator</small>
            </div>
        </div>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <div class="topbar-title">@yield('title','Dashboard')</div>
        <div class="topbar-actions">
            <a href="{{ route('home') }}" class="btn-store" target="_blank"><i class="fas fa-external-link-alt"></i> View Store</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;color:#888;cursor:pointer;font-size:.85rem;font-family:inherit;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>
@stack('scripts')
</body>
</html>
