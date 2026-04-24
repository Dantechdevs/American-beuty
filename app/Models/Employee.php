<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name', 'email', 'phone', 'pin',
        'role', 'photo', 'shift_id',
        'is_active', 'joined_date',
    ];

    protected $hidden = ['pin'];

    protected $casts = [
        'joined_date' => 'date',
        'is_active'   => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function todayAttendance()
    {
        return $this->hasOne(Attendance::class)
                    ->whereDate('date', today());
    }

    // ── Helpers ────────────────────────────────────────────────

    public function hasLoginAccount(): bool
    {
        return !is_null($this->user_id);
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        return strtoupper(
            collect($words)->take(2)->map(fn($w) => $w[0])->implode('')
        );
    }

    public function isCurrentlyClockedIn(): bool
    {
        return $this->attendances()
                    ->whereDate('date', today())
                    ->whereNotNull('clock_in')
                    ->whereNull('clock_out')
                    ->exists();
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'cashier'       => 'Cashier',
            'beautician'    => 'Beautician',
            'receptionist'  => 'Receptionist',
            'manager'       => 'Manager',
            'cleaner'       => 'Cleaner',
            'pos_operator'  => 'POS Operator',
            'delivery'      => 'Delivery Personnel',
            default         => ucfirst($this->role),
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role)
         {
            'cashier'       => 'badge-info',
            'beautician'    => 'badge-pink',
            'receptionist'  => 'badge-purple',
            'manager'       => 'badge-gold',
            'cleaner'       => 'badge-muted',
            'pos_operator'  => 'badge-info',
            'delivery'      => 'badge-tango',
            default         => 'badge-muted',       
        };               
    }
}