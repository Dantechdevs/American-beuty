<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use LogsActivity;

    protected $fillable = [
        'order_number','user_id','coupon_id','status',
        'source','served_by',
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

    // -- Spatie Activity Log -------------------------------------------
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'payment_status', 'payment_method', 'total'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Order \"{$this->order_number}\" was {$eventName}");
    }

    // -- Relationships -------------------------------------------------
    public function user()         { return $this->belongsTo(User::class); }
    public function items()        { return $this->hasMany(OrderItem::class); }
    public function coupon()       { return $this->belongsTo(Coupon::class); }
    public function mpesa()        { return $this->hasOne(MpesaTransaction::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
    public function servedBy()     { return $this->belongsTo(User::class, 'served_by'); }

    // -- Helpers -------------------------------------------------------
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