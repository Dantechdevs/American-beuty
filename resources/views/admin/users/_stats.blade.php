{{-- resources/views/admin/users/_stats.blade.php --}}
<div class="stats-grid" style="margin-bottom:1.25rem">
    <a href="{{ route('admin.users.administrators') }}" style="text-decoration:none"
       class="stat-card {{ $active==='admin' ? 'ring' : '' }}">
        <div class="stat-icon purple"><i class="fas fa-user-shield"></i></div>
        <div>
            <div class="stat-value">{{ $stats['admin'] }}</div>
            <div class="stat-label">Administrators</div>
        </div>
    </a>
    <a href="{{ route('admin.users.managers') }}" style="text-decoration:none"
       class="stat-card {{ $active==='manager' ? 'ring' : '' }}">
        <div class="stat-icon gold"><i class="fas fa-user-tie"></i></div>
        <div>
            <div class="stat-value">{{ $stats['manager'] }}</div>
            <div class="stat-label">Managers</div>
        </div>
    </a>
    <a href="{{ route('admin.users.pos-operators') }}" style="text-decoration:none"
       class="stat-card {{ $active==='pos_operator' ? 'ring' : '' }}">
        <div class="stat-icon blue"><i class="fas fa-computer"></i></div>
        <div>
            <div class="stat-value">{{ $stats['pos_operator'] }}</div>
            <div class="stat-label">POS Operators</div>
        </div>
    </a>
    <a href="{{ route('admin.users.delivery') }}" style="text-decoration:none"
       class="stat-card {{ $active==='delivery' ? 'ring' : '' }}">
        <div class="stat-icon tango"><i class="fas fa-motorcycle"></i></div>
        <div>
            <div class="stat-value">{{ $stats['delivery'] }}</div>
            <div class="stat-label">Delivery Personnel</div>
        </div>
    </a>
    <a href="{{ route('admin.users.index') }}" style="text-decoration:none"
       class="stat-card {{ $active==='customer' ? 'ring' : '' }}">
        <div class="stat-icon pink"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value">{{ $stats['customer'] }}</div>
            <div class="stat-label">Customers</div>
        </div>
    </a>
</div>