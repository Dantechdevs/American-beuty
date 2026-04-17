<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model {
    protected $fillable = [
        'order_id','merchant_request_id','checkout_request_id',
        'phone_number','amount','mpesa_receipt_number',
        'transaction_date','status','result_description','raw_response',
    ];
    protected $casts = ['raw_response'=>'array','amount'=>'decimal:2'];
    public function order() { return $this->belongsTo(Order::class); }
}
