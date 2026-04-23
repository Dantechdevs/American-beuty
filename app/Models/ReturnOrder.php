<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ReturnOrder extends Model
{
    protected $fillable = [
        'return_number', 'order_id', 'order_item_id', 'product_id', 'user_id',
        'status', 'initiated_by', 'quantity', 'reason', 'description', 'photo',
        'refund_amount', 'refund_method', 'stock_restored', 'admin_notes',
        'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'stock_restored' => 'boolean',
        'reviewed_at'    => 'datetime',
        'refund_amount'  => 'decimal:2',
    ];

    const STATUSES = ['pending', 'reviewing', 'approved', 'rejected', 'refunded', 'closed'];

    const REASONS = [
        'damaged'       => 'Item Damaged',
        'wrong_item'    => 'Wrong Item Received',
        'not_as_described' => 'Not As Described',
        'missing_parts' => 'Missing Parts',
        'changed_mind'  => 'Changed Mind',
        'other'         => 'Other',
    ];

    const REFUND_METHODS = [
        'wallet'           => 'Wallet Credit',
        'original_payment' => 'Original Payment Method',
        'store_credit'     => 'Store Credit',
        'cash'             => 'Cash',
    ];

    // Relationships
    public function order()       { return $this->belongsTo(Order::class); }
    public function orderItem()   { return $this->belongsTo(OrderItem::class); }
    public function product()     { return $this->belongsTo(Product::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function reviewer()    { return $this->belongsTo(User::class, 'reviewed_by'); }

    // Scopes
    public function scopePending(Builder $q)   { return $q->where('status', 'pending'); }
    public function scopeApproved(Builder $q)  { return $q->where('status', 'approved'); }
    public function scopeRefunded(Builder $q)  { return $q->where('status', 'refunded'); }

    // Helpers
    public function isPending()   { return $this->status === 'pending'; }
    public function isApproved()  { return $this->status === 'approved'; }
    public function isRejected()  { return $this->status === 'rejected'; }
    public function isClosed()    { return $this->status === 'closed'; }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending'   => 'badge-warning',
            'reviewing' => 'badge-info',
            'approved'  => 'badge-success',
            'rejected'  => 'badge-danger',
            'refunded'  => 'badge-primary',
            'closed'    => 'badge-secondary',
            default     => 'badge-secondary',
        };
    }

    // Auto-generate return number
    protected static function booted(): void
    {
        static::creating(function ($return) {
            $return->return_number = 'RET-' . strtoupper(uniqid());
        });
    }
}