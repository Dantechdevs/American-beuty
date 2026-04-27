<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockAdjustment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'product_id',
        'created_by',
        'type',
        'quantity',
        'direction',
        'stock_before',
        'stock_after',
        'note',
        'reference_type',
        'reference_id',
    ];

    // -- Spatie Activity Log -------------------------------------------
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'type', 'quantity', 'direction', 'stock_before', 'stock_after', 'note'])
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Stock adjustment ({$this->getTypeLabel()}: {$this->direction} {$this->quantity} units) was {$eventName}");
    }

    // -- Relationships -------------------------------------------------
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    // -- Helpers -------------------------------------------------------
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'purchase'      => 'Purchase',
            'pos_sale'      => 'POS Sale',
            'online_sale'   => 'Online Sale',
            'manual_add'    => 'Manual Add',
            'manual_deduct' => 'Manual Deduct',
            'damaged'       => 'Damaged',
            'expired'       => 'Expired',
            default         => ucfirst($this->type),
        };
    }

    public function getTypeBadgeClass(): string
    {
        return match($this->type) {
            'purchase'      => 'badge-purple',
            'pos_sale'      => 'badge-info',
            'online_sale'   => 'badge-info',
            'manual_add'    => 'badge-success',
            'manual_deduct' => 'badge-warning',
            'damaged'       => 'badge-danger',
            'expired'       => 'badge-tango',
            default         => 'badge-muted',
        };
    }

    public function getTypeIconClass(): string
    {
        return match($this->type) {
            'purchase'      => 'fa-truck',
            'pos_sale'      => 'fa-cash-register',
            'online_sale'   => 'fa-globe',
            'manual_add'    => 'fa-plus-circle',
            'manual_deduct' => 'fa-minus-circle',
            'damaged'       => 'fa-box-archive',
            'expired'       => 'fa-clock',
            default         => 'fa-circle',
        };
    }

    // -- Scopes --------------------------------------------------------
    public function scopeIn($query)
    {
        return $query->where('direction', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('direction', 'out');
    }

    public function scopeDamaged($query)
    {
        return $query->where('type', 'damaged');
    }

    public function scopeExpired($query)
    {
        return $query->where('type', 'expired');
    }
}