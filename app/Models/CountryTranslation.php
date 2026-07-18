<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    //attr
    public function getNameAttribute($value)
    {
        return ucfirst($value);

    }// end of getNameAttribute

}//end of model
