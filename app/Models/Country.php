<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Country extends Model
{
    use HasFactory, Translatable, SoftDeletes;

    protected $fillable = [
        'flag', 'code', 'default'
    ];

    protected $with = ['translations'];

    public $translatedAttributes = ['name'];

    //attr
    public function getFlagPathAttribute()
    {
        return Storage::disk('public')->url('uploads/' . $this->flag);

    }// end of getFlagPathAttribute

    //scope

    //rel
    public function timezone()
    {
        return $this->belongsTo(Timezone::class);

    }// end of timezone

    public function governorates()
    {
        return $this->hasMany(Governorate::class);

    }// end of governorates

    public function areas()
    {
        return $this->hasManyThrough(Area::class, Governorate::class);

    }// end of areas

}// end of model
