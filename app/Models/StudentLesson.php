<?php

namespace App\Models;

use App\Enums\ClassroomTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'lesson_id', 'curriculum_id', 'project_id', 'level_id',
        'attendance_status', 'time_elapsed', 'notes'
    ];

    protected $casts = [
        'attendance_status' => 'string',
    ];

    protected static function booted(): void
    {
        static::created(function (StudentLesson $studentLesson) {
            static::syncLessonTimeElapsed($studentLesson);
        });

        static::updated(function (StudentLesson $studentLesson) {
            static::syncLessonTimeElapsed($studentLesson);
        });

        static::deleted(function (StudentLesson $studentLesson) {
            // Need to sync after deletion, but lesson_id might not be accessible
            // So we'll reload the lesson from the database
            if ($studentLesson->lesson_id) {
                $lesson = Lesson::with('classroom')->find($studentLesson->lesson_id);
                if ($lesson && $lesson->classroom && $lesson->classroom->type === ClassroomTypeEnum::INDIVIDUAL) {
                    $total = static::where('lesson_id', $lesson->id)
                        ->sum('time_elapsed');
                    $lesson->update(['time_elapsed' => $total]);
                }
            }
        });
    }

    /**
     * Sync lesson time_elapsed for individual classrooms.
     */
    private static function syncLessonTimeElapsed(StudentLesson $studentLesson): void
    {
        $lesson = $studentLesson->lesson()->with('classroom')->first();

        if (!$lesson || !$lesson->classroom) {
            return;
        }

        if ($lesson->classroom->type === ClassroomTypeEnum::INDIVIDUAL) {
            $total = static::where('lesson_id', $lesson->id)
                ->sum('time_elapsed');

            // sum() returns 0 if all values are null, which is fine - we'll store 0
            // If we want null when all are null, we'd need to check for non-null values first
            $lesson->update(['time_elapsed' => $total]);
        }
    }

    //attr

    //scope
    public function scopeWhenStudentId($query, $studentId)
    {
        return $query->when($studentId, function ($q) use ($studentId) {

            return $q->where('student_id', $studentId);

        });

    }// end of scopeWhenStudentId

    public function scopeWhenLessonId($query, $lessonId)
    {
        return $query->when($lessonId, function ($q) use ($lessonId) {

            return $q->where('lesson_id', $lessonId);

        });

    }// end of scopeWhenLessonId

    public function scopeWhenAttendanceStatus($query, $attendanceStatus)
    {
        return $query->when($attendanceStatus, function ($q) use ($attendanceStatus) {

            return $q->where('attendance_status', $attendanceStatus);

        });

    }// end of scopeWhenAttendanceStatus

    //rel
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');

    }// end of student

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);

    }// end of lesson

    public function evaluationItem()
    {
        return $this->belongsTo(EvaluationItem::class);

    }// end of evaluationItem

    public function project()
    {
        return $this->belongsTo(Project::class);

    }// end of project

    //fun

}//end of model
