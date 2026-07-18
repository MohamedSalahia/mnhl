<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\OrganizationStudentStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Branch;
use App\Models\BranchStudent;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            [
                'name' => 'طالب 1',
                'email' => 'student1@branch1.com',
                'password' => 'password',
                'type' => UserTypeEnum::STUDENT,
                'mobile' => '1280000001',
                'mobile_country_code' => '+20',
                'gender' => GenderEnum::MALE,
                'date_of_birth' => '2010-05-15',
                'birth_place' => 'القاهرة',
                'father_name' => 'محمد علي',
                'mother_name' => 'فاطمة أحمد',
                'father_occupation' => 'مهندس',
                'mother_occupation' => 'معلمة',
                'father_mobile' => '1280001001',
                'father_mobile_country_code' => '+20',
                'mother_mobile' => '1280002001',
                'mother_mobile_country_code' => '+20',
                'current_address' => 'القاهرة، مصر',
                'has_previous_education' => false,
                'organization' => [
                    'id' => 1,
                    'branch' => [
                        'id' => 1,
                        'curriculum_id' => 1,
                        'project_id' => 1,
                        'level_id' => 1,
                        'page_number' => 5,
                        'classroom_id' => 1,
                    ],
                ],
            ],

            [
                'name' => 'طالب 2',
                'email' => 'student2@branch1.com',
                'password' => 'password',
                'type' => UserTypeEnum::STUDENT,
                'mobile' => '1280000002',
                'mobile_country_code' => '+20',
                'gender' => GenderEnum::FEMALE,
                'date_of_birth' => '2011-08-20',
                'birth_place' => 'الإسكندرية',
                'father_name' => 'أحمد حسن',
                'mother_name' => 'سارة محمود',
                'father_occupation' => 'طبيب',
                'mother_occupation' => 'ممرضة',
                'father_mobile' => '1280001002',
                'father_mobile_country_code' => '+20',
                'mother_mobile' => '1280002002',
                'mother_mobile_country_code' => '+20',
                'current_address' => 'الإسكندرية، مصر',
                'has_previous_education' => true,
                'previous_education_details' => 'درست في مدرسة النور الابتدائية',
                'organization' => [
                    'id' => 1,
                    'branch' => [
                        'id' => 1,
                        'curriculum_id' => 1,
                        'project_id' => 1,
                        'level_id' => 1,
                        'page_number' => 10,
                        'classroom_id' => 1,
                    ],
                ],
            ],

            [
                'name' => 'طالب 3',
                'email' => 'student3@branch1.com',
                'password' => 'password',
                'type' => UserTypeEnum::STUDENT,
                'mobile' => '1280000003',
                'mobile_country_code' => '+20',
                'gender' => GenderEnum::MALE,
                'date_of_birth' => '2009-12-10',
                'birth_place' => 'الجيزة',
                'father_name' => 'خالد إبراهيم',
                'mother_name' => 'نورا سعيد',
                'father_occupation' => 'معلم',
                'mother_occupation' => 'ربة منزل',
                'father_mobile' => '1280001003',
                'father_mobile_country_code' => '+20',
                'mother_mobile' => '1280002003',
                'mother_mobile_country_code' => '+20',
                'current_address' => 'الجيزة، مصر',
                'has_previous_education' => false,
                'organization' => [
                    'id' => 1,
                    'branch' => [
                        'id' => 1,
                        'curriculum_id' => 1,
                        'project_id' => 1,
                        'level_id' => 1,
                        'page_number' => 15,
                        'classroom_id' => 2,
                    ],
                ],
            ],

            [
                'name' => 'طالب 4',
                'email' => 'student4@branch1.com',
                'password' => 'password',
                'type' => UserTypeEnum::STUDENT,
                'mobile' => '1280000004',
                'mobile_country_code' => '+20',
                'gender' => GenderEnum::FEMALE,
                'date_of_birth' => '2012-03-25',
                'birth_place' => 'المنصورة',
                'father_name' => 'محمود علي',
                'mother_name' => 'ليلى أحمد',
                'father_occupation' => 'محاسب',
                'mother_occupation' => 'محاسبة',
                'father_mobile' => '1280001004',
                'father_mobile_country_code' => '+20',
                'mother_mobile' => '1280002004',
                'mother_mobile_country_code' => '+20',
                'current_address' => 'المنصورة، مصر',
                'has_previous_education' => false,
                'organization' => [
                    'id' => 1,
                    'branch' => [
                        'id' => 1,
                        'curriculum_id' => 1,
                        'project_id' => 1,
                        'level_id' => 1,
                        'page_number' => 8,
                        'classroom_id' => 2,
                    ],
                ],
            ],
            [
                'name' => 'طالب 5',
                'email' => 'student5@branch1.com',
                'password' => 'password',
                'type' => UserTypeEnum::STUDENT,
                'mobile' => '1280000005',
                'mobile_country_code' => '+20',
                'gender' => GenderEnum::MALE,
                'date_of_birth' => '2010-11-30',
                'birth_place' => 'طنطا',
                'father_name' => 'أحمد فؤاد',
                'mother_name' => 'منى حسن',
                'father_occupation' => 'مهندس',
                'mother_occupation' => 'معلمة',
                'father_mobile' => '1280001005',
                'father_mobile_country_code' => '+20',
                'mother_mobile' => '1280002005',
                'mother_mobile_country_code' => '+20',
                'current_address' => 'طنطا، مصر',
                'has_previous_education' => true,
                'previous_education_details' => 'درست في مدرسة الفتح الابتدائية',
                'organization' => [
                    'id' => 1,
                    'branch' => [
                        'id' => 1,
                        'curriculum_id' => 1,
                        'project_id' => 1,
                        'level_id' => 1,
                        'page_number' => 12,
                        'classroom_id' => 1,
                    ],
                ],
            ],
        ];

        foreach ($students as $student) {


            $newStudent = User::create(collect($student)->except(['organization'])->toArray());

            $organizationId = $student['organization']['id'];

            $branchId = $student['organization']['branch']['id'];

            $pivotData = [
                'curriculum_id' => $student['organization']['branch']['curriculum_id'],
                'project_id' => $student['organization']['branch']['project_id'],
                'level_id' => $student['organization']['branch']['level_id'],
                'page_number' => $student['organization']['branch']['page_number'],
                'classroom_id' => $student['organization']['branch']['classroom_id'],
            ];

            $newStudent->studentOrganizations()->attach($organizationId, [
                'status' => OrganizationStudentStatusEnum::ACTIVE
            ]);

            BranchStudent::query()
                ->whenStudentId($newStudent->id)
                ->whenBranchId($branchId)
                ->delete();

            BranchStudent::create(array_merge([
                'student_id' => $newStudent->id,
                'branch_id' => $branchId,
            ], array_filter($pivotData, fn ($value) => !is_null($value))));

            $branch = Branch::find($branchId);

            if ($branch->team_id) {

                $newStudent->addRole(UserTypeEnum::STUDENT, $branch->team_id);

            } else {

                $newStudent->addRole(UserTypeEnum::STUDENT);

            }

        }//end of foreach

    }//end of run

}//end of seeder

