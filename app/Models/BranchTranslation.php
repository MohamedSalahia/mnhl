<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    //rel
    public function branch()
    {
        return $this->belongsTo(Branch::class);

    }// end of branch

    //attr
    public function getNameAttribute($value)
    {
        return ucfirst($value);

    }// end of getNameAttribute

}//end of model

