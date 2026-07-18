<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;
use App\Enums\OrganizationTeacherStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Branch;
use App\Models\Nationality;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organization = Organization::find(1);

        $branch = Branch::find(1);

        $nationality = Nationality::first();

        $teacherTemplates = [
            [
                'gender' => GenderEnum::MALE,
                'date_of_birth' => '1985-03-15',
                'birth_place' => 'القاهرة',
                'marital_status' => MaritalStatusEnum::MARRIED,
                'current_address' => 'القاهرة، مصر',
                'last_educational_certificate' => 'بكالوريوس في التربية',
                'teaching_experience' => '5 سنوات',
            ],
            [
                'gender' => GenderEnum::FEMALE,
                'date_of_birth' => '1990-07-20',
                'birth_place' => 'الإسكندرية',
                'marital_status' => MaritalStatusEnum::SINGLE,
                'current_address' => 'الإسكندرية، مصر',
                'last_educational_certificate' => 'بكالوريوس في الآداب',
                'teaching_experience' => '3 سنوات',
            ],
            [
                'gender' => GenderEnum::MALE,
                'date_of_birth' => '1988-11-10',
                'birth_place' => 'الجيزة',
                'marital_status' => MaritalStatusEnum::MARRIED,
                'current_address' => 'الجيزة، مصر',
                'last_educational_certificate' => 'ماجستير في التربية',
                'teaching_experience' => '7 سنوات',
            ],
            [
                'gender' => GenderEnum::FEMALE,
                'date_of_birth' => '1992-04-25',
                'birth_place' => 'المنصورة',
                'marital_status' => MaritalStatusEnum::SINGLE,
                'current_address' => 'المنصورة، مصر',
                'last_educational_certificate' => 'بكالوريوس في العلوم',
                'teaching_experience' => '2 سنوات',
            ],
            [
                'gender' => GenderEnum::MALE,
                'date_of_birth' => '1987-09-30',
                'birth_place' => 'طنطا',
                'marital_status' => MaritalStatusEnum::MARRIED,
                'current_address' => 'طنطا، مصر',
                'last_educational_certificate' => 'بكالوريوس في التربية',
                'teaching_experience' => '6 سنوات',
            ],
            [
                'gender' => GenderEnum::FEMALE,
                'date_of_birth' => '1991-02-14',
                'birth_place' => 'أسيوط',
                'marital_status' => MaritalStatusEnum::MARRIED,
                'current_address' => 'أسيوط، مصر',
                'last_educational_certificate' => 'بكالوريوس في الآداب',
                'teaching_experience' => '4 سنوات',
            ],
            [
                'gender' => GenderEnum::MALE,
                'date_of_birth' => '1986-08-22',
                'birth_place' => 'المنيا',
                'marital_status' => MaritalStatusEnum::SINGLE,
                'current_address' => 'المنيا، مصر',
                'last_educational_certificate' => 'بكالوريوس في التربية',
                'teaching_experience' => '5 سنوات',
            ],
            [
                'gender' => GenderEnum::FEMALE,
                'date_of_birth' => '1993-01-18',
                'birth_place' => 'سوهاج',
                'marital_status' => MaritalStatusEnum::SINGLE,
                'current_address' => 'سوهاج، مصر',
                'last_educational_certificate' => 'بكالوريوس في العلوم',
                'teaching_experience' => '1 سنة',
            ],
        ];

        $teacherCounter = 1;

        foreach ($teacherTemplates as $template) {

            $teacherData = array_merge($template, [
                'name' => 'معلم ' . $teacherCounter,
                'email' => 'teacher' . $teacherCounter . '@org1.com',
                'password' => bcrypt('password'),
                'type' => UserTypeEnum::TEACHER,
                'mobile' => '12' . str_pad($teacherCounter + 100, 8, '0', STR_PAD_LEFT),
                'mobile_country_code' => '+20',
                'nationality_id' => $nationality->id,
            ]);

            $teacher = User::create($teacherData);

            $teacher->teacherOrganizations()->attach($organization->id, [
                'status' => OrganizationTeacherStatusEnum::ACTIVE
            ]);

            $teacher->teacherBranches()->attach($branch->id);

            if ($branch->team_id) {

                $teacher->addRole(UserTypeEnum::TEACHER, $branch->team_id);

                $teacher->addRole(UserTypeEnum::EXAMINER, $branch->team_id);

                $teacher->examinerBranches()->attach($branch->id);

            } else {

                $teacher->addRole(UserTypeEnum::TEACHER);

            }

            $teacherCounter++;

        }//end of foreach

    }//end of run

}//end of seeder

