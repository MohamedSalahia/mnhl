<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchStudent extends Model
{
    protected $table = 'branch_student';

    protected $fillable = [
        'branch_id', 'student_id', 'status', 'curriculum_id', 'project_id', 'currency_id', 'level_id', 'page_number', 'classroom_id',
        'exempted_from_fees', 'fees', 'paid_fees', 'remaining_fees',
    ];

    protected function casts(): array
    {
        return [
            'exempted_from_fees' => 'boolean',
            'fees' => 'decimal:3',
            'paid_fees' => 'decimal:3',
            'remaining_fees' => 'decimal:3',
        ];

    }// end of casts

    //scope
    public function scopeWhenStudentId($query, $studentId)
    {
        return $query->when($studentId, function ($q) use ($studentId) {

            return $q->where('student_id', $studentId);

        });

    }// end of scopeWhenStudentId

    public function scopeWhenBranchId($query, $branchId)
    {
        return $query->when($branchId, function ($q) use ($branchId) {

            return $q->where('branch_id', $branchId);

        });

    }// end of scopeWhenBranchId

    public function scopeWhenClassroomId($query, $classroomId)
    {
        return $query->when($classroomId, function ($q) use ($classroomId) {

            return $q->where('classroom_id', $classroomId);

        });

    }// end of scopeWhenClassroomId

    public function scopeWhenProjectId($query, $projectId)
    {
        return $query->when($projectId, function ($q) use ($projectId) {

            return $q->where('project_id', $projectId);

        });

    }// end of scopeWhenProjectId

    public function scopeWhenCurriculumId($query, $curriculumId)
    {
        return $query->when($curriculumId, function ($q) use ($curriculumId) {

            return $q->where('curriculum_id', $curriculumId);

        });

    }// end of scopeWhenCurriculumId

    public function scopeWhenLevelId($query, $levelId)
    {
        return $query->when($levelId, function ($q) use ($levelId) {

            return $q->where('level_id', $levelId);

        });

    }// end of scopeWhenLevelId

    //rel
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);

    } // end of branch

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');

    } // end of student

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);

    } // end of curriculum

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);

    } // end of project

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);

    } // end of level

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);

    } // end of classroom

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);

    } // end of currency

} //end of model
