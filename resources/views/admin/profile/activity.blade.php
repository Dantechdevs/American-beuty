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
                        <th>Description &amp; Changes</th>
                        <th>Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    @php
                        $newValues = $log->properties['attributes'] ?? [];
                        $oldValues = $log->properties['old'] ?? [];
                        $hasChanges = !empty($oldValues) && !empty($newValues);
                    @endphp
                    <tr style="vertical-align:top">

                        {{-- Action badge --}}
                        <td style="padding-top:1rem">
                            @php
                                $event = $log->event ?? 'action';
                                $badgeClass = match($event) {
                                    'created' => 'badge-success',
                                    'updated' => 'badge-warning',
                                    'deleted' => 'badge-danger',
                                    default   => 'badge-purple',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($event) }}
                            </span>
                        </td>

                        {{-- Description + changed fields --}}
                        <td style="font-size:.84rem">
                            <div style="color:var(--text);font-weight:500;margin-bottom:.4rem">
                                {{ $log->description ?? '—' }}
                            </div>

                            @if($hasChanges)
                            <div style="display:flex;flex-direction:column;gap:.3rem;margin-top:.5rem">
                                @foreach($newValues as $field => $newVal)
                                    @if(isset($oldValues[$field]) && $oldValues[$field] !== $newVal)
                                    <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;font-size:.76rem">
                                        <span style="color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.04em;min-width:80px">
                                            {{ str_replace('_', ' ', $field) }}
                                        </span>
                                        {{-- Old value --}}
                                        <span style="background:rgba(239,68,68,.1);color:#ef4444;padding:.15rem .5rem;border-radius:5px;text-decoration:line-through">
                                            {{ is_bool($oldValues[$field]) ? ($oldValues[$field] ? 'Yes' : 'No') : ($oldValues[$field] ?? '—') }}
                                        </span>
                                        <i class="fas fa-arrow-right" style="color:var(--muted);font-size:.65rem"></i>
                                        {{-- New value --}}
                                        <span style="background:rgba(34,197,94,.1);color:#22c55e;padding:.15rem .5rem;border-radius:5px">
                                            {{ is_bool($newVal) ? ($newVal ? 'Yes' : 'No') : ($newVal ?? '—') }}
                                        </span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        </td>

                        {{-- Date & time --}}
                        <td style="font-size:.8rem;color:var(--muted);white-space:nowrap;padding-top:1rem">
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
                Actions like logging in, editing products, updating orders, and changing settings
                will appear here automatically.
            </div>
        </div>
        @endif
    </div>

</div>
@endsection