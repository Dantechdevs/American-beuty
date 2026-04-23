{{-- resources/views/admin/users/_table.blade.php --}}
<div class="card">
    <div class="card-header" style="flex-wrap:wrap;gap:.75rem">
        <form method="GET" style="display:flex;gap:.6rem;flex:1;min-width:200px">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by name or email…"
                style="padding:.5rem .9rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-family:inherit;flex:1;outline:none">
            <button type="submit" class="btn btn-outline btn-sm">Search</button>
            @if(request('search'))
                <a href="{{ request()->url() }}" class="btn btn-outline btn-sm">Clear</a>
            @endif
        </form>
        <a href="{{ route('admin.users.create', ['role' => $roleKey]) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add {{ $roleLabel }}
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Phone</th>
                    @if($roleKey === 'customer')<th>Orders</th>@endif
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem">
                            <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.82rem;flex-shrink:0">
                                {{ strtoupper(substr($user->name,0,2)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:.88rem">{{ $user->name }}</div>
                                <div style="font-size:.75rem;color:var(--muted)">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.84rem;color:var(--muted)">{{ $user->phone ?? '—' }}</td>
                    @if($roleKey === 'customer')
                        <td><span class="badge badge-info">{{ $user->orders_count ?? 0 }}</span></td>
                    @endif
                    <td><span class="badge {{ $user->role_badge }}">{{ $user->role_label }}</span></td>
                    <td style="font-size:.8rem;color:var(--muted)">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $user->is_active ? 'Active' : 'Suspended' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="margin:0">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline btn-sm"
                                    title="{{ $user->is_active ? 'Suspend' : 'Activate' }}">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')"
                                  style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:var(--muted)">
                        <i class="fas fa-users" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                        No {{ $roleLabel }}s found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $users->links() }}</div>
</div>