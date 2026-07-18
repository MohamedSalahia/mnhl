<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nationality extends Model
{
    use HasFactory, Translatable, SoftDeletes;

    protected $fillable = [];

    protected $with = ['translations'];

    public $translatedAttributes = ['name'];

    //attr

    //scope

    //rel

    //fun

}//end of model

