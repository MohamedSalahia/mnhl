<?php

namespace App\Models;

use App\Enums\AssessmentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assessment_scheme_id', 'examiner_id', 'student_id', 'organization_id', 'branch_id', 'status',
        'curriculum_id', 'project_id', 'level_id', 'score', 'notes'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    //attr

    //scope
    public function scopeWhenAssessmentSchemeId($query, $assessmentSchemeId)
    {
        return $query->when($assessmentSchemeId, function ($q) use ($assessmentSchemeId) {

            return $q->where('assessment_scheme_id', $assessmentSchemeId);

        });

    }// end of scopeWhenAssessmentSchemeId

    public function scopeWhenExaminerId($query, $examinerId)
    {
        return $query->when($examinerId, function ($q) use ($examinerId) {

            return $q->where('examiner_id', $examinerId);

        });

    }// end of scopeWhenExaminerId

    public function scopeWhenStudentId($query, $studentId)
    {
        return $query->when($studentId, function ($q) use ($studentId) {

            return $q->where('student_id', $studentId);

        });

    }// end of scopeWhenStudentId

    public function scopeWhenStatus($query, $status)
    {
        return $query->when($status, function ($q) use ($status) {

            if ($status === AssessmentStatusEnum::IN_PROGRESS) {
                return $q->whereIn('status', [AssessmentStatusEnum::IN_PROGRESS, AssessmentStatusEnum::PARTIALLY_IN_PROGRESS]);

            }

            return $q->where('status', $status);

        });

    }// end of scopeWhenStatus

    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->where('organization_id', $organizationId);

        });

    }// end of scopeWhenOrganizationId

    public function scopeWhenBranchId($query, $branchId)
    {
        return $query->when($branchId, function ($q) use ($branchId) {

            return $q->where('branch_id', $branchId);

        });

    }// end of scopeWhenBranchId

    //rel
    public function assessmentScheme()
    {
        return $this->belongsTo(AssessmentScheme::class);

    }// end of assessmentScheme

    public function examiner()
    {
        return $this->belongsTo(User::class, 'examiner_id');

    }// end of examiner

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');

    }// end of student

    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    public function branch()
    {
        return $this->belongsTo(Branch::class);

    }// end of branch

    public function statuses()
    {
        return $this->hasMany(AssessmentStatus::class);

    }// end of statuses

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);

    }// end of curriculum

    public function project()
    {
        return $this->belongsTo(Project::class);

    }// end of project

    public function level()
    {
        return $this->belongsTo(Level::class);

    }// end of level

    public function assessmentDeductions()
    {
        return $this->hasMany(AssessmentDeduction::class);

    }// end of assessmentDeductions

    public function deductions()
    {
        return $this->hasManyThrough(
            AssessmentSchemeDeduction::class,
            AssessmentDeduction::class,
            'assessment_id',
            'id',
            'id',
            'assessment_scheme_deduction_id'
        );

    }// end of deductions

    //fun

    protected static function boot()
    {
        parent::boot();

        static::created(function ($assessment) {
            // Log initial status when assessment is created
            AssessmentStatus::create([
                'assessment_id' => $assessment->id,
                'status' => $assessment->status,
                'created_at' => now(),
            ]);
        });

        static::updated(function ($assessment) {
            // Log status change if status was modified
            if ($assessment->isDirty('status')) {
                AssessmentStatus::create([
                    'assessment_id' => $assessment->id,
                    'status' => $assessment->status,
                    'created_at' => now(),
                ]);
            }
        });

    }// end of boot

}//end of model
