<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'code'];

    //rel
    public function currency()
    {
        return $this->belongsTo(Currency::class);

    }// end of currency

    //attr
    public function getNameAttribute($value)
    {
        return $value !== null ? ucfirst($value) : $value;

    }// end of getNameAttribute

    public function getCodeAttribute($value)
    {
        return strtoupper($value);

    }// end of getCodeAttribute

}// end of model
