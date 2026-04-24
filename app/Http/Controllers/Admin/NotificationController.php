<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ScheduledNotification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $service) {}

    // ── Admin page: compose + history ────────────────────────
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'send');

        $sent = Notification::with('user')
            ->whereIn('type', ['general', 'custom'])
            ->latest()
            ->paginate(20, ['*'], 'sent_page');

        $scheduled = ScheduledNotification::with('creator')
            ->latest('scheduled_at')
            ->paginate(20, ['*'], 'sched_page');

        $users = User::where('is_active', 1)->orderBy('name')->get(['id', 'name', 'email', 'role']);

        return view('admin.notifications.index', compact('sent', 'scheduled', 'users', 'tab'));
    }

    // ── Send now ──────────────────────────────────────────────
    public function sendNow(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
            'audience' => 'required|in:all,customers,admins,managers,pos_operators,specific',
            'specific_user_id' => 'nullable|required_if:audience,specific|exists:users,id',
            'send_sms' => 'boolean',
        ]);

        $users = $this->service->resolveAudience(
            $request->audience,
            $request->specific_user_id
        );

        $count = $this->service->sendToMany(
            $users,
            $request->title,
            $request->body,
            'custom',
            $request->url,
            (bool) $request->send_sms
        );

        return back()->with('success', "Notification sent to {$count} user(s).");
    }

    // ── Schedule ──────────────────────────────────────────────
    public function schedule(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'audience'     => 'required|in:all,customers,admins,managers,pos_operators,specific',
            'specific_user_id' => 'nullable|required_if:audience,specific|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'send_sms'     => 'boolean',
        ]);

        ScheduledNotification::create([
            'created_by'       => auth()->id(),
            'title'            => $request->title,
            'body'             => $request->body,
            'type'             => 'custom',
            'audience'         => $request->audience,
            'specific_user_id' => $request->specific_user_id,
            'send_sms'         => (bool) $request->send_sms,
            'scheduled_at'     => $request->scheduled_at,
            'url'              => $request->url,
        ]);

        return back()->with('success', 'Notification scheduled successfully.');
    }

    // ── Cancel scheduled ──────────────────────────────────────
    public function cancelScheduled(ScheduledNotification $scheduled)
    {
        if ($scheduled->status === 'pending') {
            $scheduled->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Scheduled notification cancelled.');
    }

    // ── Delete scheduled ──────────────────────────────────────
    public function destroyScheduled(ScheduledNotification $scheduled)
    {
        $scheduled->delete();
        return back()->with('success', 'Deleted.');
    }

    // ── Bell API: unread count (JSON) ─────────────────────────
    public function unreadCount()
    {
        $count = Notification::forUser(auth()->id())->unread()->count();
        return response()->json(['count' => $count]);
    }

    // ── Bell API: recent notifications (JSON) ─────────────────
    public function recent()
    {
        $notifications = Notification::forUser(auth()->id())
            ->latest()
            ->limit(10)
            ->get(['id', 'title', 'body', 'type', 'icon', 'url', 'is_read', 'created_at']);

        return response()->json($notifications);
    }

    // ── Mark single as read ───────────────────────────────────
    public function markRead(Notification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->markRead();
        return response()->json(['ok' => true]);
    }

    // ── Mark all as read ──────────────────────────────────────
    public function markAllRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
