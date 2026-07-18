<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentSchemeDeduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'assessment_scheme_id', 'name', 'value', 'max_clicks', 'background_color', 'text_color', 'order',
    ];

    //attr

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->where('organization_id', $organizationId);

        });

    }// end of scopeWhenOrganizationId

    public function scopeWhenAssessmentSchemeId($query, $assessmentSchemeId)
    {
        return $query->when($assessmentSchemeId, function ($q) use ($assessmentSchemeId) {

            return $q->where('assessment_scheme_id', $assessmentSchemeId);

        });

    }// end of scopeWhenAssessmentSchemeId

    //rel
    public function assessmentScheme()
    {
        return $this->belongsTo(AssessmentScheme::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    //fun

}//end of model
