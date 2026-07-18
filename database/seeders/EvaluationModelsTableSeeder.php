<?php

namespace Database\Seeders;

use App\Models\EvaluationModel;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class EvaluationModelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = Organization::all();

        if ($organizations->isEmpty()) {
            $this->command->warn('No organizations found. Please run OrganizationsTableSeeder first.');
            return;
        }

        $evaluationModelTemplates = [
            [
                'name' => 'نموذج التقييم الأساسي',
            ],
            [
                'name' => 'نموذج التقييم المتقدم',
            ],
            [
                'name' => 'نموذج التقييم الشامل',
            ],
        ];

        foreach ($organizations as $organization) {
            foreach ($evaluationModelTemplates as $template) {
                EvaluationModel::create([
                    'organization_id' => $organization->id,
                    'name' => $template['name'],
                ]);
            }
        }

    }//end of run

}//end of seeder

