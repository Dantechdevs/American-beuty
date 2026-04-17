<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number','user_id','coupon_id','status',
        'first_name','last_name','email','phone',
        'address_line_1','address_line_2','city','county','country',
        'subtotal','shipping','discount','tax','total',
        'payment_method','payment_status','notes','paid_at',
    ];
    protected $casts = [
        'subtotal'  => 'decimal:2',
        'shipping'  => 'decimal:2',
        'discount'  => 'decimal:2',
        'tax'       => 'decimal:2',
        'total'     => 'decimal:2',
        'paid_at'   => 'datetime',
    ];

    public function user()         { return $this->belongsTo(User::class); }
    public function items()        { return $this->hasMany(OrderItem::class); }
    public function coupon()       { return $this->belongsTo(Coupon::class); }
    public function mpesa()        { return $this->hasOne(MpesaTransaction::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending'    => 'warning',
            'processing' => 'info',
            'shipped'    => 'primary',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $model->order_number = 'AB-' . strtoupper(Str::random(8));
            }
        });
    }
}
