<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['organization_id', 'name'];

    //attr

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->where('organization_id', $organizationId);

        });

    }// end of scopeWhenOrganizationId

    //rel
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function evaluationItems()
    {
        return $this->hasMany(EvaluationItem::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    //fun

}//end of model

