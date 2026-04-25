<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_phone',
        'client_email',
        'service_name',
        'service_category',
        'service_price',
        'service_duration',
        'appointment_date',
        'appointment_time',
        'notes',
        'status',
        'deposit_amount',
        'mpesa_code',
        'payment_status',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'service_price'    => 'decimal:2',
        'deposit_amount'   => 'decimal:2',
    ];

    // ── Scopes ──────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', today())
                     ->orderBy('appointment_date')
                     ->orderBy('appointment_time');
    }

    // ── Helpers ─────────────────────────────────────────────

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'confirmed'  => '#3DB54A',
            'cancelled'  => '#C8359D',
            'completed'  => '#7B2FBE',
            default      => '#f4b942',  // pending
        };
    }

    public function getStatusBgColorAttribute(): string
    {
        return match($this->status) {
            'confirmed'  => '#E0F5E3',
            'cancelled'  => '#FCE4EC',
            'completed'  => '#EDE0F8',
            default      => '#FFF8E7',  // pending
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'KSh ' . number_format($this->service_price, 0);
    }

    public function getFormattedDepositAttribute(): string
    {
        return 'KSh ' . number_format($this->deposit_amount, 0);
    }

    public function getAppointmentDateTimeAttribute(): string
    {
        return $this->appointment_date->format('M d, Y') . ' at ' . $this->appointment_time;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isUpcoming(): bool
    {
        return $this->appointment_date->isFuture() || $this->appointment_date->isToday();
    }
}