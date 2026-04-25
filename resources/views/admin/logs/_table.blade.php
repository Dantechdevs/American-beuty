<table>
    <thead>
        <tr>
            <th>User</th>
            <th>Action</th>
            <th>Description</th>
            <th>IP Address</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($logs as $log)
        <tr>
            <td>
                @if($log->user)
                    <div style="font-weight:600;font-size:.85rem">{{ $log->user->name }}</div>
                    <div style="font-size:.72rem;color:var(--muted)">{{ $log->user->email }}</div>
                @else
                    <span style="color:var(--muted);font-size:.82rem">Deleted user</span>
                @endif
            </td>
            <td>
                <span class="badge {{ $log->action_badge }}">
                    {{ $log->action_label }}
                </span>
            </td>
            <td style="font-size:.82rem;color:var(--text);max-width:280px">
                {{ $log->description }}
                @if($log->metadata)
                    <div style="font-size:.72rem;color:var(--muted);margin-top:.15rem">
                        @foreach($log->metadata as $key => $val)
                            <span style="margin-right:.5rem">
                                <strong>{{ $key }}:</strong> {{ is_array($val) ? json_encode($val) : $val }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </td>
            <td style="font-size:.78rem;color:var(--muted);font-family:monospace">
                {{ $log->ip_address ?? '—' }}
            </td>
            <td style="font-size:.78rem;color:var(--muted);white-space:nowrap">
                {{ $log->created_at->format('d M Y H:i') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">
                <div class="empty-state">
                    <i class="fas fa-clock-rotate-left"></i>
                    <p>No activity logs found.</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>