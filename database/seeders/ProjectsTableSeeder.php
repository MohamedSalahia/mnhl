<?php

namespace Database\Seeders;

use App\Enums\CurriculumTypeEnum;
use App\Models\Curriculum;
use App\Models\EvaluationModel;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $projectTemplates = [
            [
                'name' => 'المشروع الأول',
                'can_proceed_to_next_project' => true,
                'order' => 1,
            ],
            [
                'name' => 'المشروع الثاني',
                'can_proceed_to_next_project' => true,
                'order' => 2,
            ],
            [
                'name' => 'المشروع الثالث',
                'can_proceed_to_next_project' => false,
                'order' => 3,
            ],
            [
                'name' => 'المشروع الرابع',
                'can_proceed_to_next_project' => true,
                'order' => 4,
            ],
        ];

        $mainCurriculum = Curriculum::query()
            ->where('organization_id', 1)
            ->where('curriculum_type', CurriculumTypeEnum::MAIN)
            ->first();

        foreach ($projectTemplates as $template) {

            $evaluationModel = EvaluationModel::query()
                ->where('organization_id', 1)
                ->inRandomOrder()
                ->first();

            Project::create([
                'organization_id' => 1,
                'curriculum_id' => $mainCurriculum->id,
                'evaluation_model_id' => $evaluationModel->id,
                'name' => $template['name'],
                'can_proceed_to_next_project' => $template['can_proceed_to_next_project'],
                'order' => $template['order'],
            ]);

        }//end of foreach

    }//end of run

}//end of seeder

