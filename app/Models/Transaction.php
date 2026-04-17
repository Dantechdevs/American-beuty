<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $fillable = ['order_id','gateway','transaction_id','amount','currency','status','payload'];
    protected $casts    = ['payload'=>'array','amount'=>'decimal:2'];
    public function order() { return $this->belongsTo(Order::class); }
}
