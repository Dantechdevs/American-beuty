<?php

namespace App\Console\Commands;

use App\Models\ScheduledNotification;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class ProcessScheduledNotifications extends Command
{
    protected $signature   = 'notifications:process';
    protected $description = 'Send scheduled notifications that are due';

    public function handle(NotificationService $service): void
    {
        $due = ScheduledNotification::where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($due->isEmpty()) {
            $this->info('No notifications due.');
            return;
        }

        foreach ($due as $scheduled) {
            try {
                $service->dispatchScheduled($scheduled);
                $this->info("Sent: {$scheduled->title} → {$scheduled->audienceLabel()}");
            } catch (\Throwable $e) {
                $scheduled->update(['status' => 'failed', 'error' => $e->getMessage()]);
                $this->error("Failed: {$scheduled->title} — {$e->getMessage()}");
            }
        }
    }
}