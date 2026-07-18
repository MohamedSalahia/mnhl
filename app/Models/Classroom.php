<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classrooms';

    protected $fillable = [
        'branch_id', 'teacher_id', 'name', 'start_date', 'end_date', 'type'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    //attr

    //scope
    public function scopeWhenBranchId($query, $branchId)
    {
        return $query->when($branchId, function ($q) use ($branchId) {

            return $q->where('branch_id', $branchId);

        });

    }// end of scopeWhenBranchId

    public function scopeWhenTeacherId($query, $teacherId)
    {
        return $query->when($teacherId, function ($q) use ($teacherId) {

            $teacherId = User::keyFromHashId($teacherId);

            return $q->where('teacher_id', $teacherId);

        });

    }// end of scopeWhenTeacherId

    public function scopeWhenType($query, $type)
    {
        return $query->when($type, function ($q) use ($type) {

            return $q->where('type', $type);

        });

    }// end of scopeWhenType

    //rel
    public function branch()
    {
        return $this->belongsTo(Branch::class);

    }// end of branch

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');

    }// end of teacher

    public function students()
    {
        return $this->belongsToMany(User::class, 'branch_student', 'classroom_id', 'student_id')
            ->withPivot('id', 'branch_id', 'curriculum_id', 'project_id', 'level_id', 'page_number')
            ->wherePivotNotNull('classroom_id')
            ->withTimestamps();

    }// end of students

    //fun

}//end of model
