<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\ScheduledNotification;
use App\Models\User;
use Illuminate\Support\Collection;
use Twilio\Rest\Client as TwilioClient;

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
        $icon  = Notification::$typeIcons[$type] ?? 'fas fa-bell';
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
            false
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

    // ── Twilio SMS ────────────────────────────────────────────
    public function sendSms(Notification $notification): void
    {
        $user = $notification->user;

        // Skip if user has no phone number
        if (!$user || !$user->phone) {
            return;
        }

        $sid   = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from  = config('services.twilio.from');

        // Skip if Twilio credentials are not set
        if (!$sid || !$token || !$from) {
            \Log::warning('Twilio SMS skipped — credentials not configured.');
            return;
        }

        // Normalise Kenyan number: 07XXXXXXXX → +2547XXXXXXXX
        $phone = $user->phone;
        if (str_starts_with($phone, '0')) {
            $phone = '+254' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '+')) {
            $phone = '+254' . $phone;
        }

        $smsBody = $notification->title . "\n" . $notification->body;

        try {
            $client = new TwilioClient($sid, $token);
            $message = $client->messages->create($phone, [
                'from' => $from,
                'body' => $smsBody,
            ]);

            // Optionally persist SMS delivery info if columns exist
            if (\Schema::hasColumn('notifications', 'sms_sent')) {
                $notification->update([
                    'sms_sent' => true,
                    'sms_sid'  => $message->sid,
                ]);
            }

            \Log::info("SMS sent to {$phone} | SID: {$message->sid}");

        } catch (\Exception $e) {
            \Log::error('Twilio SMS failed: ' . $e->getMessage());
        }
    }
}