<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Curriculum extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'branch_id', 'name', 'book_name', 'book_file', 'book_number_of_pages', 'curriculum_type'
    ];

    protected $casts = [
        'book_number_of_pages' => 'integer',
    ];

    //attr
    public function getBookFilePathAttribute()
    {
        return $this->book_file ? Storage::disk('public')->url('uploads/' . $this->book_file) : null;

    }// end of getBookFilePathAttribute

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

    public function scopeWhenType($query, $type)
    {
        return $query->when($type, function ($q) use ($type) {

            return $q->where('curriculum_type', $type);

        });

    }// end of scopeWhenType

    //rel
    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    public function branch()
    {
        return $this->belongsTo(Branch::class);

    }// end of branch

    public function projects()
    {
        return $this->hasMany(Project::class);

    }// end of projects

    //fun

}//end of model

