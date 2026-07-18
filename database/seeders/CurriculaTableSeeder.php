<?php

namespace Database\Seeders;

use App\Enums\CurriculumTypeEnum;
use App\Models\Curriculum;
use Illuminate\Database\Seeder;

class CurriculaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curriculumTemplates = [
            [
                'name' => 'المنهج الأساسي للقرآن الكريم',
                'book_name' => 'كتاب القرآن الكريم',
                'book_number_of_pages' => 120,
                'curriculum_type' => CurriculumTypeEnum::MAIN,
            ],
            [
                'name' => 'المنهج الأساسي للغة العربية',
                'book_name' => 'كتاب اللغة العربية - المستوى الأول',
                'book_number_of_pages' => 150,
                'curriculum_type' => CurriculumTypeEnum::MAIN,
            ],
            [
                'name' => 'المنهج الإضافي للأنشطة',
                'book_name' => 'كتاب الأنشطة التربوية',
                'book_number_of_pages' => 80,
                'curriculum_type' => CurriculumTypeEnum::ADDITIONAL,
            ],
            [
                'name' => 'المنهج الإضافي للتربية الإسلامية',
                'book_name' => 'كتاب التربية الإسلامية',
                'book_number_of_pages' => 100,
                'curriculum_type' => CurriculumTypeEnum::ADDITIONAL,
            ],
        ];

        foreach ($curriculumTemplates as $template) {
            Curriculum::create([
                'organization_id' => 1,
                'branch_id' => 1,
                'name' => $template['name'],
                'book_name' => $template['book_name'],
                'book_number_of_pages' => $template['book_number_of_pages'],
                'curriculum_type' => $template['curriculum_type'],
            ]);
        }

    }//end of run

}//end of seeder

