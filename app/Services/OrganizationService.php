<?php

namespace App\Services;

use App\Enums\AdminTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Branch;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class OrganizationService
{
    public function storeOrganization($request)
    {
        $requestData = $request->validated();

        // Handle logo upload
        if ($request->file('logo')) {
            $requestData['logo'] = $request->file('logo')->hashName();
            $request->file('logo')->store('uploads', 'public');
        }

        // Create organization
        $organization = Organization::create($requestData);

        if ($request->super_admin_type === 'existing') {

            $superAdmin = User::findOrFail($request->existing_super_admin_id);

        } else {

            $superAdminData = [
                'name' => $request->super_admin_name,
                'email' => $request->super_admin_email,
                'password' => $request->super_admin_password,
                'type' => UserTypeEnum::ORGANIZATION_SUPER_ADMIN,
            ];

            $superAdmin = User::create($superAdminData);

            $superAdmin->addRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN);
        }

        // Attach super admin to organization via pivot table
        $organization->admins()->attach($superAdmin->id, [
            'type' => AdminTypeEnum::SUPER_ADMIN,
        ]);

        $this->createMainBranch($organization);

        return $organization;

    }// end of create

    public function update($request, Organization $organization): Organization
    {
        $requestData = $request->validated();

        if ($request->file('logo')) {
            Storage::disk('public')->delete('uploads/' . $organization->logo);
            $requestData['logo'] = $request->file('logo')->hashName();
            $request->file('logo')->store('uploads', 'public');
        }

        $organization->update($requestData);

        return $organization;

    }// end of update

    public function delete(Organization $organization): void
    {
        if ($organization->logo) {
            Storage::disk('public')->delete('uploads/' . $organization->logo);
        }

        $organization->delete();

    }// end of delete

    private function createMainBranch(Organization $organization): Branch
    {
        $branchData = [
            'organization_id' => $organization->id,
            'country_id' => $organization->country_id,
            'governorate_id' => $organization->governorate_id,
            'area_id' => $organization->area_id,
            'ar' => [
                'name' => 'الفرع الرئيسي'
            ],
            'en' => [
                'name' => 'Main Branch'
            ],
        ];

        return Branch::create($branchData);

    }// end of createMainBranch

}//end of service

