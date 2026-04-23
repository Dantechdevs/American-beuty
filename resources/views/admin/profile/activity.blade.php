@extends('layouts.admin')
@section('title', 'Activity Log')

@section('content')
<div class="page-header" style="margin-bottom:1.5rem">
    <div>
        <div class="page-title">
            <i class="fas fa-clock-rotate-left" style="color:var(--purple)"></i> Activity Log
        </div>
        <div class="page-sub">Your recent actions in the admin panel</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:1.5rem;align-items:start">

    {{-- Side nav --}}
    <div class="card">
        <div class="card-body" style="text-align:center;padding:2rem 1.5rem">

            @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                     alt="{{ auth()->user()->name }}"
                     style="width:80px;height:80px;border-radius:20px;object-fit:cover;border:3px solid var(--border);margin-bottom:1rem">
            @else
                <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:700;margin:0 auto 1rem">
                    {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                </div>
            @endif

            <div style="font-weight:700;font-size:1rem;color:var(--text)">{{ auth()->user()->name }}</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:.2rem">{{ auth()->user()->email }}</div>
            <div style="margin-top:.75rem">
                <span class="badge {{ auth()->user()->role_badge }}">{{ auth()->user()->role_label }}</span>
            </div>

            <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:.5rem">
                <a href="{{ route('admin.profile.edit') }}"
                   class="btn btn-outline btn-sm" style="justify-content:center">
                    <i class="fas fa-user-pen"></i> Edit Profile
                </a>
                <a href="{{ route('admin.profile.password') }}"
                   class="btn btn-outline btn-sm" style="justify-content:center">
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <a href="{{ route('admin.profile.activity') }}"
                   class="btn btn-primary btn-sm" style="justify-content:center">
                    <i class="fas fa-clock-rotate-left"></i> Activity Log
                </a>
            </div>
        </div>
    </div>

    {{-- Log table --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clock-rotate-left"></i> Recent Activity</h3>
        </div>

        @if(isset($logs) && $logs->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>
                            <span class="badge badge-purple">
                                {{ ucfirst($log->event ?? $log->description) }}
                            </span>
                        </td>
                        <td style="font-size:.84rem;color:var(--muted)">
                            {{ $log->description ?? '—' }}
                        </td>
                        <td style="font-size:.8rem;color:var(--muted);white-space:nowrap">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $logs->links() }}</div>

        @else
        <div class="empty-state" style="padding:3.5rem 2rem">
            <i class="fas fa-clock-rotate-left"></i>
            <p>No activity recorded yet.</p>
            <div style="font-size:.78rem;color:var(--muted);max-width:340px;text-align:center;line-height:1.6">
                Activity logging requires the
                <code style="background:var(--purple-soft);padding:.1rem .4rem;border-radius:4px;color:var(--purple)">spatie/laravel-activitylog</code>
                package. Install it to automatically track admin actions.
            </div>
            <div style="margin-top:1rem;background:#1a0a2e;color:#a78bfa;padding:.75rem 1.25rem;border-radius:10px;font-size:.8rem;font-family:monospace;letter-spacing:.02em">
                composer require spatie/laravel-activitylog
            </div>
        </div>
        @endif
    </div>

</div>
@endsection