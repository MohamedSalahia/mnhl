<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'project_id', 'assessment_scheme_id', 'name', 'order', 'from_page', 'to_page', 'min_passing_score', 'max_score'
    ];

    //attr

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->where('organization_id', $organizationId);

        });

    }// end of scopeWhenOrganizationId

    public function scopeWhenProjectId($query, $projectId)
    {
        return $query->when($projectId, function ($q) use ($projectId) {

            return $q->where('project_id', $projectId);

        });

    }// end of scopeWhenProjectId

    //rel
    public function project()
    {
        return $this->belongsTo(Project::class);

    }// end of project

    public function assessmentScheme()
    {
        return $this->belongsTo(AssessmentScheme::class);

    }// end of assessmentScheme

    public function attachedCurricula()
    {
        return $this->belongsToMany(Curriculum::class, 'level_additional_curriculum')
            ->withPivot('from_page', 'to_page')
            ->withTimestamps();

    }// end of attachedCurricula

    //fun

}//end of model

