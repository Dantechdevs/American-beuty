<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'gateway',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'amount'  => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Order::class,
            'id',       // orders.id
            'id',       // users.id
            'order_id', // transactions.order_id
            'user_id'   // orders.user_id
        );
    }
}