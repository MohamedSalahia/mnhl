<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'evaluation_model_id', 'name', 'background_color', 'text_color', 'pass', 'order'
    ];

    protected $casts = [
        'pass' => 'boolean',
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


    //rel
    public function evaluationModel()
    {
        return $this->belongsTo(EvaluationModel::class);
    }

    public function lessonEvaluationItems()
    {
        return $this->hasMany(LessonEvaluationItem::class);
    }

    public function studentLessons()
    {
        return $this->hasMany(StudentLesson::class);
    }

    //fun

}//end of model

