<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    //attr
    public function getNameAttribute($value)
    {
        return $value !== null ? ucfirst($value) : $value;

    }// end of getNameAttribute

    //rel
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);

    }// end of paymentMethod

}// end of model
