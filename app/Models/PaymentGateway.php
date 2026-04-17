<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model {
    protected $fillable = ['name','slug','is_active','credentials','mode'];
    protected $casts    = ['is_active'=>'boolean','credentials'=>'array'];
}
