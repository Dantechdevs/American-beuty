@extends('layouts.admin')
@section('title','Customers')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.3rem;font-weight:700">Customers</h2>
</div>
<div class="card">
    <div class="card-header">
        <form method="GET" style="display:flex;gap:.8rem">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." style="padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;width:260px">
            <button type="submit" class="btn btn-outline btn-sm">Search</button>
            @if(request('search'))<a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">Clear</a>@endif
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Customer</th><th>Phone</th><th>Orders</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.88rem">{{ $user->name }}</div>
                        <div style="font-size:.76rem;color:#888">{{ $user->email }}</div>
                    </td>
                    <td style="font-size:.85rem;color:#666">{{ $user->phone ?? '—' }}</td>
                    <td><span class="badge badge-info">{{ $user->orders_count }}</span></td>
                    <td style="font-size:.8rem;color:#888">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $user->is_active?'badge-success':'badge-secondary' }}">
                            {{ $user->is_active?'Active':'Suspended' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.toggle',$user) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-outline btn-sm">
                                {{ $user->is_active ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:#aaa;padding:3rem">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $users->links() }}</div>
</div>
@endsection
