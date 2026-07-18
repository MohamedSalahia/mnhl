<?php

namespace Database\Seeders;

use App\Models\AssessmentScheme;
use App\Models\AssessmentSchemeDeduction;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class AssessmentSchemeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = Organization::all();

        foreach ($organizations as $organization) {
            // Create assessment schemes for each organization
            $schemes = [
                [
                    'name' => 'سلم التقييم الأساسي',
                    'deductions' => [
                        [
                            'name' => 'خطأ إملائي',
                            'value' => 1,
                            'max_clicks' => 3,
                            'background_color' => '#fef3c7',
                            'text_color' => '#92400e',
                            'order' => 1,
                        ],
                        [
                            'name' => 'خطأ نحوي',
                            'value' => 2,
                            'max_clicks' => 2,
                            'background_color' => '#fed7aa',
                            'text_color' => '#9a3412',
                            'order' => 2,
                        ],
                        [
                            'name' => 'خطأ في الإجابة',
                            'value' => 3,
                            'max_clicks' => 1,
                            'background_color' => '#fecaca',
                            'text_color' => '#991b1b',
                            'order' => 3,
                        ],
                        [
                            'name' => 'عدم الإجابة',
                            'value' => 5,
                            'max_clicks' => 1,
                            'background_color' => '#fee2e2',
                            'text_color' => '#7f1d1d',
                            'order' => 4,
                        ],
                        [
                            'name' => 'خطأ في الحساب',
                            'value' => 2,
                            'max_clicks' => 2,
                            'background_color' => '#fde68a',
                            'text_color' => '#78350f',
                            'order' => 5,
                        ],
                    ],
                ],
                [
                    'name' => 'سلم التقييم المتقدم',
                    'deductions' => [
                        [
                            'name' => 'خطأ بسيط',
                            'value' => 1,
                            'max_clicks' => 3,
                            'background_color' => '#dbeafe',
                            'text_color' => '#1e40af',
                            'order' => 1,
                        ],
                        [
                            'name' => 'خطأ في الفهم',
                            'value' => 3,
                            'max_clicks' => 2,
                            'background_color' => '#fecaca',
                            'text_color' => '#991b1b',
                            'order' => 2,
                        ],
                        [
                            'name' => 'خطأ في المنهجية',
                            'value' => 4,
                            'max_clicks' => 1,
                            'background_color' => '#fca5a5',
                            'text_color' => '#7f1d1d',
                            'order' => 3,
                        ],
                        [
                            'name' => 'خطأ كبير',
                            'value' => 5,
                            'max_clicks' => 1,
                            'background_color' => '#dc2626',
                            'text_color' => '#ffffff',
                            'order' => 4,
                        ],
                        [
                            'name' => 'عدم الإجابة',
                            'value' => 10,
                            'max_clicks' => 1,
                            'background_color' => '#991b1b',
                            'text_color' => '#ffffff',
                            'order' => 5,
                        ],
                    ],
                ],
            ];

            foreach ($schemes as $schemeData) {
                $scheme = AssessmentScheme::create([
                    'organization_id' => $organization->id,
                    'name' => $schemeData['name'],
                ]);

                // Create deductions for this scheme
                foreach ($schemeData['deductions'] as $deductionData) {
                    AssessmentSchemeDeduction::create([
                        'organization_id' => $organization->id,
                        'assessment_scheme_id' => $scheme->id,
                        'name' => $deductionData['name'],
                        'value' => $deductionData['value'],
                        'max_clicks' => $deductionData['max_clicks'],
                        'background_color' => $deductionData['background_color'],
                        'text_color' => $deductionData['text_color'],
                        'order' => $deductionData['order'],
                    ]);
                }
            }
        }
    }
}
