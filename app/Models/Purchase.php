<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_no', 'supplier_id', 'created_by', 'purchase_date',
        'payment_time', 'payment_status', 'total_amount', 'paid_amount', 'notes',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'payment_time'  => 'datetime',
        'total_amount'  => 'decimal:2',
        'paid_amount'   => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    // ── Accessors ──────────────────────────────────────────────────

    public function getBalanceAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    // ── Static Helpers ─────────────────────────────────────────────

    public static function generateInvoiceNo(): string
    {
        $year  = now()->format('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'PUR-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}