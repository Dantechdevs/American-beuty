<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_minutes',
        'is_active',
    ];

    protected $casts = [
        'start_time'    => 'datetime:H:i',
        'end_time'      => 'datetime:H:i',
        'grace_minutes' => 'integer',
        'is_active'     => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // ── Helpers ───────────────────────────────────────────────

    public function getDurationAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) return '—';
        $start = \Carbon\Carbon::parse($this->start_time);
        $end   = \Carbon\Carbon::parse($this->end_time);
        $mins  = $start->diffInMinutes($end);
        $h     = intdiv($mins, 60);
        $m     = $mins % 60;
        return $m > 0 ? "{$h}h {$m}m" : "{$h}h";
    }

    public function getScheduleAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) return '—';
        $start = \Carbon\Carbon::parse($this->start_time)->format('g:i A');
        $end   = \Carbon\Carbon::parse($this->end_time)->format('g:i A');
        return "{$start} – {$end}";
    }

    public function getLateThresholdAttribute(): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($this->start_time)
                             ->addMinutes($this->grace_minutes ?? 0);
    }

    public function isLate(\Carbon\Carbon $clockIn): bool
    {
        return $clockIn->gt($this->late_threshold);
    }
}