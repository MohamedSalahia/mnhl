<?php

namespace App\Models;

use App\Enums\OrganizationStudentStatusEnum;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationStudent extends Pivot
{
    protected $table = 'organization_student';

    protected $fillable = [
        'organization_id',
        'student_id',
        'status',
    ];

    //rel
    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');

    }// end of student

}//end of model
