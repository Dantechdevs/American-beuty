<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class StockAlert extends Model
{
    protected $fillable = [
        'product_id',
        'low_stock_threshold',
        'is_active',
        'alert_type',        // 'low_stock' | 'out_of_stock' | 'expiry'
        'notified_at',       // last time alert was sent
        'resolved_at',       // when stock was restocked
        'triggered_by',      // 'purchase' | 'sale' | 'damage' | 'manual'
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'low_stock_threshold'=> 'integer',
        'notified_at'        => 'datetime',
        'resolved_at'        => 'datetime',
    ];

    // ── Relationships ──────────────────────────────

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // ── Accessors / Helpers ────────────────────────

    public function isTriggered(): bool
    {
        $stock = $this->product?->stock_quantity ?? 0;
        return $stock <= $this->low_stock_threshold;
    }

    public function isResolved(): bool
    {
        return !is_null($this->resolved_at);
    }

    public function markNotified(): void
    {
        $this->update(['notified_at' => Carbon::now()]);
    }

    public function markResolved(): void
    {
        $this->update([
            'resolved_at' => Carbon::now(),
            'is_active'   => false,
        ]);
    }
}