<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledNotification extends Model
{
    protected $fillable = [
        'created_by', 'title', 'body', 'type', 'icon', 'url',
        'audience', 'specific_user_id', 'send_sms',
        'scheduled_at', 'status', 'sent_at', 'error',
    ];

    protected $casts = [
        'send_sms'     => 'boolean',
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function specificUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'specific_user_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function audienceLabel(): string
    {
        return match($this->audience) {
            'all'          => 'All Users',
            'customers'    => 'Customers',
            'admins'       => 'Admins',
            'managers'     => 'Managers',
            'pos_operators'=> 'POS Operators',
            'specific'     => $this->specificUser?->name ?? 'Specific User',
            default        => ucfirst($this->audience),
        };
    }
}
