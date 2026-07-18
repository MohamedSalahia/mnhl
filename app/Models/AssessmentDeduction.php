<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentDeduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assessment_id', 'assessment_scheme_deduction_id', 'quantity', 'organization_id', 'branch_id'
    ];

    //attr

    //scope

    //rel
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);

    }// end of assessment

    public function assessmentSchemeDeduction()
    {
        return $this->belongsTo(AssessmentSchemeDeduction::class, 'assessment_scheme_deduction_id');

    }// end of assessmentSchemeDeduction

    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    public function branch()
    {
        return $this->belongsTo(Branch::class);

    }// end of branch

    //fun

}//end of model
