<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\ScheduledNotification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    // ── Send to a single user ─────────────────────────────────
    public function send(
        int    $userId,
        string $title,
        string $body,
        string $type     = 'general',
        ?string $url     = null,
        ?string $refType = null,
        ?int   $refId    = null,
        bool   $sendSms  = false
    ): Notification {
        $icon = Notification::$typeIcons[$type] ?? 'fas fa-bell';

        $notification = Notification::create([
            'user_id'        => $userId,
            'title'          => $title,
            'body'           => $body,
            'type'           => $type,
            'icon'           => $icon,
            'url'            => $url,
            'reference_type' => $refType,
            'reference_id'   => $refId,
        ]);

        if ($sendSms) {
            $this->sendSms($notification);
        }

        return $notification;
    }

    // ── Send to multiple users ────────────────────────────────
    public function sendToMany(
        Collection $users,
        string $title,
        string $body,
        string $type    = 'general',
        ?string $url    = null,
        bool $sendSms   = false
    ): int {
        $icon = Notification::$typeIcons[$type] ?? 'fas fa-bell';
        $count = 0;

        foreach ($users as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'title'   => $title,
                'body'    => $body,
                'type'    => $type,
                'icon'    => $icon,
                'url'     => $url,
            ]);

            if ($sendSms) {
                $this->sendSms($notification);
            }

            $count++;
        }

        return $count;
    }

    // ── Send to audience from scheduled notification ──────────
    public function dispatchScheduled(ScheduledNotification $scheduled): void
    {
        $users = $this->resolveAudience($scheduled->audience, $scheduled->specific_user_id);

        $this->sendToMany(
            $users,
            $scheduled->title,
            $scheduled->body,
            $scheduled->type,
            $scheduled->url,
            $scheduled->send_sms
        );

        $scheduled->update(['status' => 'sent', 'sent_at' => now()]);
    }

    // ── Resolve audience to user collection ───────────────────
    public function resolveAudience(string $audience, ?int $specificUserId = null): Collection
    {
        return match($audience) {
            'all'           => User::where('is_active', 1)->get(),
            'customers'     => User::where('role', 'customer')->where('is_active', 1)->get(),
            'admins'        => User::where('role', 'admin')->where('is_active', 1)->get(),
            'managers'      => User::where('role', 'manager')->where('is_active', 1)->get(),
            'pos_operators' => User::where('role', 'pos_operator')->where('is_active', 1)->get(),
            'specific'      => User::where('id', $specificUserId)->get(),
            default         => collect(),
        };
    }

    // ── Auto-trigger: Order Placed ────────────────────────────
    public function notifyOrderPlaced(User $user, string $orderNumber, int $orderId): void
    {
        $this->send(
            $user->id,
            'Order Placed Successfully! 🛍️',
            "Your order #{$orderNumber} has been received and is being processed. We'll notify you once it's confirmed.",
            'order_placed',
            "/orders/{$orderId}",
            'App\Models\Order',
            $orderId,
            false // set true when Twilio is ready
        );
    }

    // ── Auto-trigger: Payment Confirmed ──────────────────────
    public function notifyPaymentConfirmed(User $user, string $orderNumber, int $orderId, string $amount): void
    {
        $this->send(
            $user->id,
            'Payment Confirmed ✅',
            "Payment of KES {$amount} for order #{$orderNumber} has been confirmed. Your order is now being prepared.",
            'payment_confirmed',
            "/orders/{$orderId}",
            'App\Models\Order',
            $orderId,
            false
        );
    }

    // ── Auto-trigger: Order Collected / Delivered ─────────────
    public function notifyOrderCollected(User $user, string $orderNumber, int $orderId): void
    {
        $this->send(
            $user->id,
            'Order Ready for Collection 📦',
            "Your order #{$orderNumber} is ready! Please collect it at your earliest convenience.",
            'order_collected',
            "/orders/{$orderId}",
            'App\Models\Order',
            $orderId,
            false
        );
    }

    // ── Auto-trigger: Thank You ───────────────────────────────
    public function notifyThankYou(User $user, string $orderNumber): void
    {
        $this->send(
            $user->id,
            'Thank You for Shopping with Us! 💜',
            "Thank you for your order #{$orderNumber} at American Beauty. We appreciate your support! Visit us again at americanbeauty.co.ke",
            'thank_you',
            null,
            null,
            null,
            false
        );
    }

    // ── Twilio SMS (stubbed — wire up when credentials are ready) ──
    public function sendSms(Notification $notification): void
    {
        // ── Uncomment and fill in when Twilio account is ready ──
        //
        // $user = $notification->user;
        // if (! $user->phone) return;
        //
        // $sid   = config('services.twilio.sid');
        // $token = config('services.twilio.token');
        // $from  = config('services.twilio.from');
        //
        // if (! $sid || ! $token || ! $from) return;
        //
        // try {
        //     $client = new \Twilio\Rest\Client($sid, $token);
        //     $message = $client->messages->create(
        //         '+254' . ltrim($user->phone, '0'),
        //         ['from' => $from, 'body' => $notification->title . "\n" . $notification->body]
        //     );
        //     $notification->update(['sms_sent' => true, 'sms_sid' => $message->sid]);
        // } catch (\Exception $e) {
        //     \Log::error('Twilio SMS failed: ' . $e->getMessage());
        // }
    }
}
