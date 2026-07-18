<?php

namespace App\Models;

use App\Enums\AssetRelatedToEnum;
use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Lab404\Impersonate\Models\Impersonate;
use Laratrust\Traits\HasRolesAndPermissions;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasHashId, HasApiTokens, HasFactory, Notifiable, Impersonate, HasRolesAndPermissions;
    protected $whatsappService;

    protected $fillable = [
        'hash_id', 'nationality_id', 'name', 'email', 'password', 'type', 'student_number', 'gender', 'mobile', 'mobile_country_code',
        'date_of_birth', 'birth_place', 'father_name', 'mother_name',
        'father_occupation', 'mother_occupation', 'father_mobile', 'father_mobile_country_code', 'mother_mobile', 'mother_mobile_country_code',
        'has_previous_education', 'previous_education_details', 'identity_document_file', 'image',
        'current_address', 'marital_status', 'last_educational_certificate',
        'teaching_experience', 'locale', 'dark_mode', 'menu_collapsed'
    ];

    protected $appends = ['image_path'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts()
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_previous_education' => 'boolean',
        ];

    }//end of casts

    //atr
    public function getNameAttribute($value)
    {
        return ucfirst($value);

    }// end of getNameAttribute

    public function getImagePathAttribute()
    {
        if ($this->image) {
            return Storage::disk('public')->url('uploads/' . $this->image);
        }

        return asset('images/default.jpg');

    }// end of getImagePathAttribute

    public function getIdentityDocumentFilePathAttribute()
    {
        if ($this->identity_document_file) {
            return Storage::disk('public')->url('uploads/' . $this->identity_document_file);
        }

        return null;

    }// end of getIdentityDocumentFilePath

    //scope
    public function scopeWhenRoleId($query, $roleId)
    {
        return $query->when($roleId, function ($q) use ($roleId) {

            return $q->whereHas('roles', function ($qu) use ($roleId) {

                return $qu->where('id', $roleId);

            });

        });

    }// end of scopeWhenRoleId

    public function scopeWhenStudentOrganizationId($query, $organizationId, $status = null)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->whereHas('studentOrganizations', function ($qu) use ($organizationId) {

                $qu->where('organizations.id', $organizationId);

                $status = request()->status;

                if ($status !== null && $status !== '' && $status !== '0') {
                    $qu->where('organization_student.status', $status);
                }

                return $qu;

            });

        });

    }// end of scopeWhenStudentOrganizationId

    public function scopeWhenStudentBranchId($query, $branchId)
    {
        return $query->when($branchId, function ($q) use ($branchId) {

            return $q->whereHas('branchStudents', function ($qu) use ($branchId) {

                $qu->where('branch_student.branch_id', $branchId);

                $branchStatus = request()->branch_status;

                if ($branchStatus !== null && $branchStatus !== '' && $branchStatus !== '0') {
                    $qu->where('branch_student.status', $branchStatus);
                }

                return $qu;

            });

        });

    }// end of scopeWhenStudentBranchId

    public function scopeWhenTeacherOrganizationId($query, $organizationId, $status = null)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->whereHas('teacherOrganizations', function ($qu) use ($organizationId) {

                $qu->where('organizations.id', $organizationId);

                $status = request()->status;

                if ($status !== null && $status !== '' && $status !== '0') {
                    $qu->where('organization_teacher.status', $status);
                }

                return $qu;

            });

        });

    }// end of scopeWhenTeacherOrganizationId

    public function scopeWhenTeacherBranchId($query, $branchId)
    {
        return $query->when($branchId, function ($q) use ($branchId) {

            return $q->whereHas('teacherBranches', function ($qu) use ($branchId) {

                return $qu->where('branches.id', $branchId);

            });

        });

    }// end of scopeWhenTeacherBranchId

    public function scopeWhenExaminerOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->whereHas('examinerBranches', function ($qu) use ($organizationId) {

                return $qu->where('branches.organization_id', $organizationId);

            });

        });

    }// end of scopeWhenExaminerOrganizationId

    public function scopeWhenExaminerBranchId($query, $branchId)
    {
        return $query->when($branchId, function ($q) use ($branchId) {

            return $q->whereHas('examinerBranches', function ($qu) use ($branchId) {

                return $qu->where('branches.id', $branchId);

            });

        });

    }// end of scopeWhenExaminerBranchId

    public function scopeWhenStudentLessonId($query, $lessonId)
    {
        return $query->when($lessonId, function ($q) use ($lessonId) {

            // Get the lesson's classroom_id
            $lesson = Lesson::find($lessonId);

            if (!$lesson || !$lesson->classroom_id) {
                return $q->whereRaw('1 = 0'); // Return empty result if lesson not found or has no classroom
            }

            $branchId = request()->branch_id;

            return $q->whereHas('studentClassrooms', function ($qu) use ($lesson, $branchId) {

                $qu->where('classrooms.id', $lesson->classroom_id);

                if ($branchId !== null && $branchId !== '' && $branchId !== '0') {
                    $qu->where('branch_student.branch_id', $branchId);
                }

                return $qu;

            });

        });

    }// end of scopeWhenStudentLessonId

    public function scopeWhenStudentClassroomId($query, $classroomId)
    {
        return $query->when($classroomId, function ($q) use ($classroomId) {

            return $q->whereHas('studentClassrooms', function ($qu) use ($classroomId) {

                return $qu->where('classrooms.id', $classroomId);

            });

        });

    }// end of scopeWhenStudentClassroomId

    //rel
    public function adminOrganizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_admin', 'admin_id', 'organization_id')
            ->withPivot('type')
            ->withTimestamps();

    }// end of adminOrganizations

    public function adminBranches()
    {
        return $this->belongsToMany(Branch::class, 'branch_admin', 'admin_id', 'branch_id')
            ->withTimestamps();

    }// end of adminBranches

    public function studentOrganizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_student', 'student_id', 'organization_id')
            ->using(OrganizationStudent::class)
            ->withPivot('status')
            ->withTimestamps();

    }// end of studentOrganizations

    public function branchStudents()
    {
        return $this->hasMany(BranchStudent::class, 'student_id');

    }// end of branchStudents

    public function installments()
    {
        return $this->hasMany(Installment::class, 'student_id');

    }// end of installments

    public function studentClassrooms()
    {
        return $this->belongsToMany(Classroom::class, 'branch_student', 'student_id', 'classroom_id')
            ->withPivot('id', 'branch_id', 'curriculum_id', 'project_id', 'level_id', 'page_number')
            ->wherePivotNotNull('classroom_id')
            ->withTimestamps();

    }// end of studentClassrooms

    public function studentLessons()
    {
        return $this->hasMany(StudentLesson::class, 'student_id');

    }// end of studentLessons

    public function teacherOrganizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_teacher', 'teacher_id', 'organization_id')
            ->withPivot('status', 'hourly_rate', 'salary_type', 'fixed_salary', 'currency_id')
            ->withTimestamps();

    }// end of teacherOrganizations

    public function teacherBranches()
    {
        return $this->belongsToMany(Branch::class, 'branch_teacher', 'teacher_id', 'branch_id')
            ->withTimestamps();

    }// end of teacherBranches

    public function examinerBranches()
    {
        return $this->belongsToMany(Branch::class, 'branch_examiner', 'examiner_id', 'branch_id')
            ->withTimestamps();

    }// end of examinerBranches

    public function teacherCertificates()
    {
        return $this->hasMany(Asset::class, 'teacher_id')
            ->where('related_to', AssetRelatedToEnum::TEACHER_CERTIFICATE);

    }// end of teacherCertificates

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');

    }// end of nationality

    public function examinerAssessments()
    {
        return $this->hasMany(Assessment::class, 'examiner_id');

    }// end of examinerAssessments

    public function studentAssessments()
    {
        return $this->hasMany(Assessment::class, 'student_id');

    }// end of studentAssessments

    //fun
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->hash_id;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->whereHashId($value)->firstOrFail();

    }// end of resolveRouteBinding

    protected static function booted()
    {
        // static::created(function ($user) {
        //     $user->api_token = Str::random(60);
        //     $user->save();
        // });

    }//end of booted

}//end of model
