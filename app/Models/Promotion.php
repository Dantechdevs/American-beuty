<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'applies_to',
        'applies_to_id',
        'minimum_order',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'value'         => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'is_active'     => 'boolean',
        'starts_at'     => 'datetime',
        'ends_at'       => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class, 'applies_to_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'applies_to_id');
    }

    // ── Status Helpers ─────────────────────────────────────────

    public function isRunning(): bool
    {
        if (!$this->is_active) return false;
        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->ends_at   && $now->gt($this->ends_at))   return false;
        return true;
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'inactive';
        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) return 'scheduled';
        if ($this->ends_at   && $now->gt($this->ends_at))   return 'expired';
        return 'running';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'running'   => 'badge-success',
            'scheduled' => 'badge-warning',
            'expired'   => 'badge-danger',
            default     => 'badge-muted',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'running'   => 'Running',
            'scheduled' => 'Scheduled',
            'expired'   => 'Expired',
            default     => 'Inactive',
        };
    }

    // ── Discount Calculator ────────────────────────────────────

    public function calculateDiscount(float $price): float
    {
        if ($this->type === 'percent') {
            return round($price * ($this->value / 100), 2);
        }
        return min((float) $this->value, $price);
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRunning($query)
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now));
    }
}