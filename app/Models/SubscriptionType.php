<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'year', 'name', 'fees', 'currency_id', 'has_specific_date', 'start_date', 'end_date',
    ];

    //attr

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'fees' => 'decimal:3',
            'has_specific_date' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];

    }// end of casts

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);

    }// end of scopeWhenOrganizationId

    public function scopeWhenYear($query, $year)
    {
        return $query->when($year, function ($q) use ($year) {

            return $q->where('year', $year);

        });

    }// end of scopeWhenYear

    //rel
    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    public function currency()
    {
        return $this->belongsTo(Currency::class);

    }// end of currency

    //fun

}// end of model
