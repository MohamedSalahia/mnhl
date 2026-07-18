<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'status',
    ];

    //rel
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);

    }// end of assessment

}//end of model
