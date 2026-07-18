<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use App\Traits\HasHashId;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasHashId, HasFactory, Translatable, SoftDeletes;

    protected $fillable = [
        'organization_id', 'country_id', 'governorate_id', 'area_id', 'team_id'
    ];

    protected $with = ['translations'];

    public $translatedAttributes = ['name'];

    //attr

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->where('organization_id', $organizationId);

        });

    }// end of scopeWhenOrganizationId

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
    public function organization()
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

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

    public function team()
    {
        return $this->belongsTo(Team::class);

    }// end of team

    public function admins()
    {
        return $this->belongsToMany(User::class, 'branch_admin', 'branch_id', 'admin_id')
            ->withTimestamps();

    }// end of admins

    public function students()
    {
        return $this->belongsToMany(User::class, 'branch_student', 'branch_id', 'student_id')
            ->withPivot('id', 'curriculum_id', 'project_id', 'level_id', 'page_number', 'classroom_id')
            ->withTimestamps();

    }// end of students

    public function installments()
    {
        return $this->hasMany(Installment::class);

    }// end of installments

    public function examiners()
    {
        return $this->belongsToMany(User::class, 'branch_examiner', 'branch_id', 'examiner_id')
            ->withTimestamps();

    }// end of examiners

    //fun
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->whereHashId($value)->firstOrFail();

    }// end of resolveRouteBinding

    protected static function booted()
    {
        static::created(function ($branch) {

            // Create a new team for the branch
            $teamName = 'branch-' . $branch->organization_id . '-' . $branch->id . '-' . uniqid();

            $team = Team::create([
                'name' => $teamName,
                'display_name' => $branch->name ?? 'Branch Team',
                'description' => 'Team for branch: ' . ($branch->name ?? 'N/A'),
            ]);

            // Update team_id quietly (without firing events)
            $branch->updateQuietly(['team_id' => $team->id]);

            // Fetch organization super admins and add role with team_id
            $organization = $branch->organization;

            $superAdmins = $organization->superAdmins()->get();

            foreach ($superAdmins as $superAdmin) {
                $superAdmin->addRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN, $team->id);
            }
        });

    }//end of booted

}// end of model

