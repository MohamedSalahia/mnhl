<?php

namespace Database\Seeders;

use App\Enums\ClassroomTypeEnum;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classrooms = [
            [
                'branch_id' => 1,
                'teacher_id' => 4,
                'name' => 'حلقة القرآن الكريم - المستوى الأول',
                'type' => ClassroomTypeEnum::GROUP,
                'start_date' => now()->addDays(7)->format('Y-m-d'), // Next week
                'end_date' => now()->addDays(37)->format('Y-m-d'), // 30 days later
            ],
            [
                'branch_id' => 1,
                'teacher_id' => 4,
                'name' => 'حلقة الفقه - كتاب الطهارة',
                'type' => ClassroomTypeEnum::INDIVIDUAL,
                'start_date' => now()->addDays(10)->format('Y-m-d'),
                'end_date' => now()->addDays(40)->format('Y-m-d'),
            ],
            [
                'branch_id' => 1,
                'teacher_id' => 4,
                'name' => 'حلقة فردية - شرح متن الأجرومية',
                'type' => ClassroomTypeEnum::INDIVIDUAL,
                'start_date' => now()->addDays(4)->format('Y-m-d'),
                'end_date' => now()->addDays(18)->format('Y-m-d'),
            ],
        ];

        // Create classrooms for organization 1 and branch 1
        foreach ($classrooms as $classroom) {

            Classroom::create($classroom);

        }//end of foreach

    }//end of run

}//end of seeder
