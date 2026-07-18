<?php

namespace Database\Seeders;

use App\Models\EvaluationItem;
use App\Models\EvaluationModel;
use Illuminate\Database\Seeder;

class EvaluationItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $evaluationModels = EvaluationModel::all();

        if ($evaluationModels->isEmpty()) {
            $this->command->warn('No evaluation models found. Please run EvaluationModelsTableSeeder first.');
            return;
        }

        $evaluationItemTemplates = [
            [
                'name' => 'ممتاز',
                'background_color' => '#28a745',
                'text_color' => '#ffffff',
                'pass' => true,
                'order' => 1,
            ],
            [
                'name' => 'جيد جداً',
                'background_color' => '#17a2b8',
                'text_color' => '#ffffff',
                'pass' => true,
                'order' => 2,
            ],
            [
                'name' => 'جيد',
                'background_color' => '#ffc107',
                'text_color' => '#000000',
                'pass' => true,
                'order' => 3,
            ],
            [
                'name' => 'مقبول',
                'background_color' => '#fd7e14',
                'text_color' => '#ffffff',
                'pass' => false,
                'order' => 4,
            ],
            [
                'name' => 'ضعيف',
                'background_color' => '#dc3545',
                'text_color' => '#ffffff',
                'pass' => false,
                'order' => 5,
            ],
        ];

        foreach ($evaluationModels as $evaluationModel) {
            foreach ($evaluationItemTemplates as $template) {
                EvaluationItem::create([
                    'organization_id' => $evaluationModel->organization_id,
                    'evaluation_model_id' => $evaluationModel->id,
                    'name' => $template['name'],
                    'background_color' => $template['background_color'],
                    'text_color' => $template['text_color'],
                    'pass' => $template['pass'],
                    'order' => $template['order'],
                ]);
            }
        }

    }//end of run

}//end of seeder

