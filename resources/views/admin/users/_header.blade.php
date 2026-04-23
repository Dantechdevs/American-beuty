{{-- resources/views/admin/users/_header.blade.php --}}
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas {{ $icon }}" style="color:{{ $color }}"></i> {{ $title }}
        </div>
        <div class="page-sub">Manage {{ strtolower($title) }} accounts</div>
    </div>
    <a href="{{ route('admin.users.create', ['role' => $roleKey]) }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add New
    </a>
</div>