<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Governorate extends Model
{
    use HasFactory, Translatable, SoftDeletes;

    protected $fillable = ['country_id'];

    protected $with = ['translations'];

    public $translatedAttributes = ['name'];

    //attr

    //scope
    public function scopeWhenCountryId($query, $countryId)
    {
        return $query->when($countryId, function ($q) use ($countryId) {

            return $q->where('country_id', $countryId);

        });

    }// end of scopeWhenCountryId

    //rel
    public function country()
    {
        return $this->belongsTo(Country::class);

    }// end of country

    public function areas()
    {
        return $this->hasMany(Area::class);

    }// end of areas

    //fun

}//end of model
