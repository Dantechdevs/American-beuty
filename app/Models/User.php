<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
        'role', 'avatar', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    // ── Role checks ────────────────────────────────────────────

    public function isAdmin(): bool       { return $this->role === 'admin'; }
    public function isManager(): bool     { return $this->role === 'manager'; }
    public function isPosOperator(): bool { return $this->role === 'pos_operator'; }
    public function isDelivery(): bool    { return $this->role === 'delivery'; }
    public function isCustomer(): bool    { return $this->role === 'customer'; }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'pos_operator', 'delivery']);
    }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'        => 'Administrator',
            'manager'      => 'Manager',
            'pos_operator' => 'POS Operator',
            'delivery'     => 'Delivery Personnel',
            'customer'     => 'Customer',
            default        => ucfirst($this->role),
        };
    }

    public function getRoleBadgeAttribute(): string
    {
        return match($this->role) {
            'admin'        => 'badge-purple',
            'manager'      => 'badge-gold',
            'pos_operator' => 'badge-info',
            'delivery'     => 'badge-tango',
            'customer'     => 'badge-pink',
            default        => 'badge-muted',
        };
    }

    // ── Relationships ──────────────────────────────────────────

    public function orders()    { return $this->hasMany(Order::class); }
    public function wishlist()  { return $this->hasMany(Wishlist::class); }
    public function cart()      { return $this->hasMany(Cart::class); }
    public function addresses() { return $this->hasMany(Address::class); }
}