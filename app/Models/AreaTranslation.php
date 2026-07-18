<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

}//end of model
