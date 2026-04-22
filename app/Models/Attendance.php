<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'shift_id',
        'date',
        'clock_in',
        'clock_out',
        'hours_worked',
        'status',
        'note',
        'admin_override',
        'overridden_by',
    ];

    protected $casts = [
        'date'           => 'date',
        'clock_in'       => 'datetime',
        'clock_out'      => 'datetime',
        'admin_override' => 'boolean',
        'hours_worked'   => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function overriddenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'overridden_by');
    }

    // ── Helpers ───────────────────────────────────────────────

    public function getHoursFormattedAttribute(): string
    {
        if (!$this->hours_worked) return '—';
        $h = intdiv($this->hours_worked, 60);
        $m = $this->hours_worked % 60;
        return $h > 0 ? "{$h}h {$m}m" : "{$m}m";
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'present'   => 'badge-success',
            'late'      => 'badge-warning',
            'early_out' => 'badge-tango',
            'absent'    => 'badge-danger',
            'half_day'  => 'badge-purple',
            default     => 'badge-muted',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'present'   => 'Present',
            'late'      => 'Late',
            'early_out' => 'Early Out',
            'absent'    => 'Absent',
            'half_day'  => 'Half Day',
            default     => ucfirst($this->status),
        };
    }

    public function getDurationAttribute(): ?int
    {
        if (!$this->clock_in || !$this->clock_out) return null;
        return $this->clock_in->diffInMinutes($this->clock_out);
    }

    public function isClockedIn(): bool
    {
        return $this->clock_in !== null && $this->clock_out === null;
    }
}