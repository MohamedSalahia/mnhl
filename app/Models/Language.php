<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'country_flag_code', 'active'];

    //attr

    //scope
    public function scopeActive($query)
    {
        return $query->where('active', 1);

    }// end of active

    //rel

    //fun

}//end of model
