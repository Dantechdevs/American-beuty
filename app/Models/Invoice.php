<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number','order_id','user_id',
        'client_name','client_phone','client_email','client_address',
        'subtotal','discount','tax','total',
        'status','payment_method','notes',
        'invoice_date','due_date','paid_at','created_by','served_by',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'tax'          => 'decimal:2',
        'total'        => 'decimal:2',
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'paid_at'      => 'datetime',
    ];

    public function items()    { return $this->hasMany(InvoiceItem::class)->orderBy('sort_order'); }
    public function order()    { return $this->belongsTo(Order::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }
    public function servedBy() { return $this->belongsTo(\App\Models\Employee::class, 'served_by'); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'paid'      => 'success',
            'sent'      => 'info',
            'cancelled' => 'danger',
            default     => 'warning',
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->invoice_number)) {
                $model->invoice_number = 'INV-' . strtoupper(Str::random(8));
            }
        });
    }
}
