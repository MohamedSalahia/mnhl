<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory, Translatable;

    protected $fillable = ['organization_id'];

    protected $with = ['translations'];

    public $translatedAttributes = ['name', 'code'];

    //attr

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);

    }// end of scopeWhenOrganizationId

    //rel
    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    //fun

}// end of model
