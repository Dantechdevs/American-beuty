<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {
    protected $fillable = ['code','type','value','minimum_order','usage_limit','used_count','expires_at','is_active'];
    protected $casts    = ['is_active'=>'boolean','expires_at'=>'date','value'=>'decimal:2','minimum_order'=>'decimal:2'];

    public function isValid(float $orderTotal): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($orderTotal < $this->minimum_order) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            return round($subtotal * ($this->value / 100), 2);
        }
        return min($this->value, $subtotal);
    }
}
