<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','phone','password','role','avatar','is_active'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'is_active' => 'boolean'];

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function orders() { return $this->hasMany(Order::class); }
    public function wishlist() { return $this->hasMany(Wishlist::class); }
    public function cart() { return $this->hasMany(Cart::class); }
    public function addresses() { return $this->hasMany(Address::class); }
}
