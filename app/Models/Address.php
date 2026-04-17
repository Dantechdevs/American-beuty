<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Address extends Model {
    protected $fillable = ['user_id','first_name','last_name','phone','email','address_line_1','address_line_2','city','county','country','is_default'];
    protected $casts    = ['is_default'=>'boolean'];
    public function user() { return $this->belongsTo(User::class); }
    public function getFullNameAttribute(): string { return "{$this->first_name} {$this->last_name}"; }
}
