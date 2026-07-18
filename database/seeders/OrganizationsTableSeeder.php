<?php

namespace Database\Seeders;

use App\Enums\AdminTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Branch;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            [
                'country_id' => 1,
                'governorate_id' => 1,
                'area_id' => 1,
                'logo' => null,
                'students_count' => 100,
                'teachers_count' => 10,
                'examiners_count' => 5,
                'ar' => ['name' => 'منظمة تعليمية الأولى'],
                'en' => ['name' => 'First Educational Organization'],
                'super_admin' => [
                    'name' => 'Organization 1 Super Admin',
                    'email' => 'org1_super_admin@app.com',
                    'password' => 'password',
                ],
            ],
            [
                'country_id' => 1,
                'governorate_id' => 1,
                'area_id' => 2,
                'logo' => null,
                'students_count' => 150,
                'teachers_count' => 15,
                'examiners_count' => 7,
                'ar' => ['name' => 'منظمة تعليمية الثانية'],
                'en' => ['name' => 'Second Educational Organization'],
                'super_admin' => [
                    'name' => 'Organization 2 Super Admin',
                    'email' => 'org2_super_admin@app.com',
                    'password' => 'password',
                ],
            ],
        ];

        foreach ($organizations as $orgData) {

            $superAdminData = $orgData['super_admin'];

            unset($orgData['super_admin']);

            $organization = Organization::create($orgData);

            $superAdmin = User::create([
                'name' => $superAdminData['name'],
                'email' => $superAdminData['email'],
                'password' => bcrypt($superAdminData['password']),
                'type' => UserTypeEnum::ORGANIZATION_SUPER_ADMIN,
            ]);

            //attach superadmin to organization

            $superAdmin->addRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN);

            // Attach superadmin to organization via organization_admin table
            $organization->admins()->attach($superAdmin->id, ['type' => AdminTypeEnum::SUPER_ADMIN]);

            // Create main branch for the organization
            Branch::create([
                'organization_id' => $organization->id,
                'country_id' => $orgData['country_id'],
                'governorate_id' => $orgData['governorate_id'],
                'area_id' => $orgData['area_id'],
                'ar' => ['name' => 'الفرع الرئيسي'],
                'en' => ['name' => 'Main Branch'],
            ]);

        }//end of for each

    }//end of run

}//end of seeder

