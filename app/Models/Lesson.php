<?php

namespace App\Models;

use App\Enums\ClassroomTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'branch_id', 'teacher_id', 'classroom_id', 'date', 'time_elapsed'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    //attr

    //scope
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

    public function scopeWhenClassroomId($query, $classroomId)
    {
        return $query->when($classroomId, function ($q) use ($classroomId) {

            return $q->where('classroom_id', $classroomId);

        });

    }// end of scopeWhenClassroomId

    public function scopeWhenTeacherId($query, $teacherId)
    {
        return $query->when($teacherId, function ($q) use ($teacherId) {

            $teacherId = User::keyFromHashId($teacherId);

            return $q->where('teacher_id', $teacherId);

        });

    }// end of scopeWhenTeacherId

    public function scopeWhenDateRange($query, $dateRange)
    {
        return $query->when($dateRange && isset($dateRange['from']) && isset($dateRange['to']), function ($q) use ($dateRange) {

            return $q->whereBetween('date', [$dateRange['from'], $dateRange['to']]);

        });

    }// end of scopeWhenDateRange

    //rel
    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    public function branch()
    {
        return $this->belongsTo(Branch::class);

    }// end of branch

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');

    }// end of teacher

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);

    }// end of classroom

    public function lessonEvaluationItems()
    {
        return $this->hasMany(LessonEvaluationItem::class);

    }// end of lessonEvaluationItems

    public function studentLessons()
    {
        return $this->hasMany(StudentLesson::class);

    }// end of studentLessons

    //fun
    public function canEditTimeElapsed(): bool
    {
        if (!$this->classroom || $this->classroom->type !== \App\Enums\ClassroomTypeEnum::GROUP) {
            return false;
        }

        $studentCount = $this->studentLessons()->count();

        if ($studentCount === 0) {
            return false;
        }

        $evaluatedCount = $this->studentLessons()->whereNotNull('attendance_status')->count();

        return $evaluatedCount === $studentCount;

    }// end of canEditTimeElapsed

    public function canDownloadReport(): bool
    {
        if (!$this->classroom) {
            return false;
        }

        $studentCount = $this->studentLessons()->count();

        if ($studentCount === 0) {
            return false;
        }

        $evaluatedCount = $this->studentLessons()->whereNotNull('attendance_status')->count();

        if ($evaluatedCount !== $studentCount) {
            return false;
        }

        if ($this->classroom->type === ClassroomTypeEnum::GROUP) {
            return $this->time_elapsed !== null;
        }

        return true; // individual: all evaluated

    }// end of canDownloadReport

}//end of model
