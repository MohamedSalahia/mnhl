<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\Traits\BelongsToThrough;

class Area extends Model
{
    use HasFactory, Translatable, BelongsToThrough, SoftDeletes;

    protected $fillable = ['governorate_id'];

    protected $with = ['translations'];

    public $translatedAttributes = ['name'];

    //attr

    //scope
    public function scopeWhenCountryId($query, $countryId)
    {
        return $query->when($countryId, function ($q) use ($countryId) {

            return $q->whereHas('country', function ($qu) use ($countryId) {

                return $qu->where('countries.id', $countryId);

            });

        });

    }// end of scopeWhenCountryId

    public function scopeWhenGovernorateId($query, $governorateId)
    {
        return $query->when($governorateId, function ($q) use ($governorateId) {

            return $q->where('governorate_id', $governorateId);

        });

    }// end of scopeWhenGovernorateId

    //rel
    public function country()
    {
        return $this->belongsToThrough(Country::class, Governorate::class);

    }// end of country

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);

    }// end of governorate

    //fun

}//end of model
