<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes, Translatable;

    protected $fillable = ['organization_id'];

    protected $with = ['translations'];

    public $translatedAttributes = ['name'];

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
