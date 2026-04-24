{{--
    ── Notification Bell Component ──────────────────────────────
    Drop this inside your admin navbar where you want the bell.
    Requires: auth user, notifications routes registered.
--}}

<div class="notif-bell-wrap" style="position:relative">
    <button class="notif-bell-btn" id="notifBellBtn" onclick="toggleNotifDropdown()" title="Notifications">
        <i class="fas fa-bell"></i>
        <span class="notif-count" id="notifCount" style="display:none">0</span>
    </button>

    <div class="notif-dropdown" id="notifDropdown" style="display:none">
        <div class="notif-dropdown-header">
            <span style="font-weight:700;font-size:.88rem">Notifications</span>
            <button onclick="markAllRead()" class="notif-mark-all">Mark all read</button>
        </div>
        <div class="notif-dropdown-list" id="notifList">
            <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:.82rem">
                <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
        </div>
        <div class="notif-dropdown-footer">
            <a href="{{ route('admin.notifications.index') }}">
                <i class="fas fa-bell"></i> Manage Notifications
            </a>
        </div>
    </div>
</div>

<style>
.notif-bell-btn {
    position: relative;
    background: none; border: none;
    width: 38px; height: 38px;
    border-radius: 50%;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--muted);
    font-size: 1rem;
    transition: background .15s, color .15s;
}
.notif-bell-btn:hover { background: var(--purple-soft); color: var(--purple); }

.notif-count {
    position: absolute;
    top: 2px; right: 2px;
    background: var(--pink);
    color: #fff;
    font-size: .6rem; font-weight: 800;
    min-width: 16px; height: 16px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 3px;
    border: 2px solid #fff;
    pointer-events: none;
}

.notif-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    width: 340px;
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
    z-index: 9999;
    overflow: hidden;
}

.notif-dropdown-header {
    padding: .85rem 1rem;
    border-bottom: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
}

.notif-mark-all {
    font-size: .72rem; font-weight: 700;
    color: var(--purple); background: none; border: none;
    cursor: pointer; padding: 0;
}
.notif-mark-all:hover { text-decoration: underline; }

.notif-dropdown-list {
    max-height: 360px;
    overflow-y: auto;
}

.notif-item {
    display: flex; gap: .75rem;
    padding: .85rem 1rem;
    border-bottom: 1px solid #f3eeff;
    cursor: pointer;
    transition: background .13s;
    text-decoration: none; color: inherit;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #faf7ff; }
.notif-item.unread { background: #f5f3ff; }
.notif-item.unread:hover { background: #ede9fe; }

.notif-item-icon {
    width: 34px; height: 34px; border-radius: 50%;
    background: var(--purple-soft);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: .78rem; color: var(--purple);
}

.notif-item-body { flex: 1; min-width: 0; }
.notif-item-title {
    font-size: .82rem; font-weight: 700;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    margin-bottom: .15rem;
}
.notif-item-text {
    font-size: .75rem; color: var(--muted);
    overflow: hidden; text-overflow: ellipsis;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
}
.notif-item-time {
    font-size: .68rem; color: #94a3b8; margin-top: .25rem;
    white-space: nowrap;
}

.notif-unread-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--purple); flex-shrink: 0;
    align-self: center;
}

.notif-dropdown-footer {
    padding: .75rem 1rem;
    border-top: 1.5px solid var(--border);
    text-align: center;
    background: #faf7ff;
}
.notif-dropdown-footer a {
    font-size: .78rem; font-weight: 700;
    color: var(--purple); text-decoration: none;
    display: flex; align-items: center; justify-content: center; gap: .4rem;
}
.notif-dropdown-footer a:hover { text-decoration: underline; }
</style>

<script>
let notifOpen = false;

function toggleNotifDropdown() {
    notifOpen = !notifOpen;
    const dropdown = document.getElementById('notifDropdown');
    dropdown.style.display = notifOpen ? 'block' : 'none';
    if (notifOpen) loadNotifications();
}

// Close on outside click
document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.notif-bell-wrap');
    if (wrap && !wrap.contains(e.target) && notifOpen) {
        notifOpen = false;
        document.getElementById('notifDropdown').style.display = 'none';
    }
});

async function loadNotifications() {
    try {
        const res  = await fetch('{{ route("admin.notifications.recent") }}');
        const data = await res.json();
        renderNotifications(data);
    } catch (e) {
        document.getElementById('notifList').innerHTML =
            '<div style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.82rem">Failed to load.</div>';
    }
}

function renderNotifications(items) {
    const list = document.getElementById('notifList');
    if (!items.length) {
        list.innerHTML = '<div style="padding:2rem;text-align:center;color:#94a3b8;font-size:.82rem"><i class="fas fa-bell-slash" style="display:block;font-size:1.5rem;margin-bottom:.5rem;opacity:.3"></i>No notifications yet.</div>';
        return;
    }

    list.innerHTML = items.map(n => `
        <div class="notif-item ${!n.is_read ? 'unread' : ''}"
             onclick="handleNotifClick(${n.id}, '${n.url || ''}')">
            <div class="notif-item-icon">
                <i class="${n.icon}"></i>
            </div>
            <div class="notif-item-body">
                <div class="notif-item-title">${n.title}</div>
                <div class="notif-item-text">${n.body}</div>
                <div class="notif-item-time">${timeAgo(n.created_at)}</div>
            </div>
            ${!n.is_read ? '<div class="notif-unread-dot"></div>' : ''}
        </div>
    `).join('');
}

async function handleNotifClick(id, url) {
    // Mark as read
    await fetch(`/admin/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    updateCount();
    if (url) window.location.href = url;
}

async function markAllRead() {
    await fetch('{{ route("admin.notifications.read-all") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    updateCount();
    loadNotifications();
}

async function updateCount() {
    try {
        const res  = await fetch('{{ route("admin.notifications.unread-count") }}');
        const data = await res.json();
        const badge = document.getElementById('notifCount');
        badge.textContent = data.count;
        badge.style.display = data.count > 0 ? 'flex' : 'none';
    } catch {}
}

function timeAgo(dateStr) {
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60)    return 'Just now';
    if (diff < 3600)  return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
}

// Poll unread count every 60s
updateCount();
setInterval(updateCount, 60000);
</script>
