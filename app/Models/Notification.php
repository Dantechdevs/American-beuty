<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body', 'type', 'icon', 'url',
        'reference_type', 'reference_id',
        'is_read', 'read_at', 'sms_sent', 'sms_sid',
    ];

    protected $casts = [
        'is_read'  => 'boolean',
        'sms_sent' => 'boolean',
        'read_at'  => 'datetime',
    ];

    // ── Type icons map ────────────────────────────────────────
    public static array $typeIcons = [
        'order_placed'       => 'fas fa-bag-shopping',
        'payment_confirmed'  => 'fas fa-circle-check',
        'order_collected'    => 'fas fa-box-open',
        'thank_you'          => 'fas fa-heart',
        'general'            => 'fas fa-bell',
        'custom'             => 'fas fa-bullhorn',
    ];

    public static array $typeColors = [
        'order_placed'       => 'purple',
        'payment_confirmed'  => 'green',
        'order_collected'    => 'tango',
        'thank_you'          => 'pink',
        'general'            => 'muted',
        'custom'             => 'purple',
    ];

    // ── Relationships ─────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ── Helpers ───────────────────────────────────────────────
    public function markRead(): void
    {
        if (! $this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }

    public function getIconAttribute($value): string
    {
        return $value ?: (static::$typeIcons[$this->type] ?? 'fas fa-bell');
    }
}
