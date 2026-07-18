<?php

namespace App\Models;

use App\Enums\AdminTypeEnum;
use App\Traits\HasHashId;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Organization extends Model
{
    use HasHashId, HasFactory, Translatable, SoftDeletes;

    protected $fillable = [
        'country_id', 'governorate_id', 'area_id', 'logo', 'students_count', 'teachers_count', 'examiners_count', 'student_registration_settings', 'teacher_registration_settings'
    ];

    protected $with = ['translations'];

    public $translatedAttributes = ['name'];

    protected $casts = [
        'student_registration_settings' => 'array',
        'teacher_registration_settings' => 'array',
    ];

    //attr
    public function getLogoPathAttribute()
    {
        return Storage::disk('public')->url('uploads/' . $this->logo);

    }// end of getLogoPathAttribute

    public function getLogoBase64Attribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        $path = storage_path('app/public/uploads/' . $this->logo);

        if (!file_exists($path)) {
            return null;
        }

        $mime = mime_content_type($path);
        $data = base64_encode(file_get_contents($path));

        return 'data:' . $mime . ';base64,' . $data;

    }// end of getLogoBase64Attribute

    //scope
    public function scopeWhenCountryId($query, $countryId)
    {
        return $query->when($countryId, function ($q) use ($countryId) {

            return $q->where('country_id', $countryId);

        });

    }// end of scopeWhenCountryId

    public function scopeWhenGovernorateId($query, $governorateId)
    {
        return $query->when($governorateId, function ($q) use ($governorateId) {

            return $q->where('governorate_id', $governorateId);

        });

    }// end of scopeWhenGovernorateId

    public function scopeWhenAreaId($query, $areaId)
    {
        return $query->when($areaId, function ($q) use ($areaId) {

            return $q->where('area_id', $areaId);

        });

    }// end of scopeWhenAreaId

    //rel
    public function country()
    {
        return $this->belongsTo(Country::class);

    }// end of country

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);

    }// end of governorate

    public function area()
    {
        return $this->belongsTo(Area::class);

    }// end of area

    public function branches()
    {
        return $this->hasMany(Branch::class);

    }// end of branches

    public function superAdmins()
    {
        return $this->belongsToMany(User::class, 'organization_admin', 'organization_id', 'admin_id')
            ->wherePivot('type', AdminTypeEnum::SUPER_ADMIN)
            ->withPivot('type')
            ->withTimestamps();

    }// end of superAdmins

    public function admins()
    {
        return $this->belongsToMany(User::class, 'organization_admin', 'organization_id', 'admin_id')
            ->withPivot('type')
            ->withTimestamps();

    }// end of admins

    public function students()
    {
        return $this->belongsToMany(User::class, 'organization_student', 'organization_id', 'student_id')
            ->using(OrganizationStudent::class)
            ->withPivot('status')
            ->withTimestamps();

    }// end of students

    public function installments()
    {
        return $this->hasMany(Installment::class);

    }// end of installments

    //fun
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->whereHashId($value)->firstOrFail();

    }// end of resolveRouteBinding

}// end of model

