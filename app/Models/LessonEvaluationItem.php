<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonEvaluationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id', 'student_id', 'page_number', 'evaluation_item_id'
    ];

    //attr

    //scope
    public function scopeWhenLessonId($query, $lessonId)
    {
        return $query->when($lessonId, function ($q) use ($lessonId) {

            return $q->where('lesson_id', $lessonId);

        });

    }// end of scopeWhenLessonId

    public function scopeWhenPageNumber($query, $pageNumber)
    {
        return $query->when($pageNumber, function ($q) use ($pageNumber) {

            return $q->where('page_number', $pageNumber);

        });

    }// end of scopeWhenPageNumber

    public function scopeWhenStudentId($query, $studentId)
    {
        return $query->when($studentId, function ($q) use ($studentId) {

            return $q->where('student_id', $studentId);

        });

    }// end of scopeWhenStudentId

    //rel
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);

    }// end of lesson

    public function evaluationItem()
    {
        return $this->belongsTo(EvaluationItem::class);

    }// end of evaluationItem

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');

    }// end of student

    //fun

}//end of model
