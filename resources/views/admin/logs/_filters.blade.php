<div class="card" style="margin-bottom:1.25rem">
    <div class="card-body" style="padding:.85rem 1.25rem">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end">
            <div style="display:flex;flex-direction:column;gap:.3rem;flex:1;min-width:160px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Name or email…"
                       style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:150px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Action</label>
                <select name="action" style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action')===$action?'selected':'' }}>
                            {{ ucfirst(str_replace('_',' ',$action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:140px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
            </div>
            <div style="display:flex;flex-direction:column;gap:.3rem;min-width:140px">
                <label style="font-size:.71rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       style="padding:.55rem .8rem;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.84rem;font-family:inherit;outline:none">
            </div>
            <div style="display:flex;gap:.5rem;align-items:flex-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search','action','date_from','date_to']))
                    <a href="{{ $clearRoute }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-xmark"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>