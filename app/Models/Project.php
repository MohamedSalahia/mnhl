<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'curriculum_id', 'evaluation_model_id', 'name',
        'can_proceed_to_next_project', 'order'
    ];

    protected $casts = [
        'can_proceed_to_next_project' => 'boolean',
        'order' => 'integer',
    ];

    //attr

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->where('organization_id', $organizationId);

        });

    }// end of scopeWhenOrganizationId

    public function scopeWhenEvaluationModelId($query, $evaluationModelId)
    {
        return $query->when($evaluationModelId, function ($q) use ($evaluationModelId) {

            return $q->where('evaluation_model_id', $evaluationModelId);

        });

    }// end of scopeWhenEvaluationModelId

    public function scopeWhenCurriculumId($query, $curriculumId)
    {
        return $query->when($curriculumId, function ($q) use ($curriculumId) {

            return $q->where('curriculum_id', $curriculumId);

        });

    }// end of scopeWhenCurriculumId

    //rel
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);

    }// end of curriculum

    public function evaluationModel()
    {
        return $this->belongsTo(EvaluationModel::class);

    }// end of evaluationModel

    public function levels()
    {
        return $this->hasMany(Level::class);

    }// end of levels

    public function installments()
    {
        return $this->hasMany(Installment::class);

    }// end of installments

    //fun

}//end of model

