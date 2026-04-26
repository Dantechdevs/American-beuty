<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'assigned_by',
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
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'service_price'    => 'decimal:2',
        'deposit_amount'   => 'decimal:2',
        'confirmed_at'     => 'datetime',
        'completed_at'     => 'datetime',
        'cancelled_at'     => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopePending($query)   { return $query->where('status', 'pending'); }
    public function scopeConfirmed($query) { return $query->where('status', 'confirmed'); }
    public function scopeCancelled($query) { return $query->where('status', 'cancelled'); }
    public function scopeCompleted($query) { return $query->where('status', 'completed'); }
    public function scopeToday($query)     { return $query->whereDate('appointment_date', today()); }
    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', today())
                     ->orderBy('appointment_date')
                     ->orderBy('appointment_time');
    }

    // ── Helpers ────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'confirmed'  => 'badge-success',
            'cancelled'  => 'badge-danger',
            'completed'  => 'badge-purple',
            default      => 'badge-warning',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'confirmed'  => 'Confirmed',
            'cancelled'  => 'Cancelled',
            'completed'  => 'Completed',
            default      => 'Pending',
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