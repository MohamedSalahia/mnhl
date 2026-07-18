<?php

namespace Database\Seeders;

use App\Enums\CurriculumTypeEnum;
use App\Models\AssessmentScheme;
use App\Models\Curriculum;
use App\Models\Level;
use App\Models\Project;
use Illuminate\Database\Seeder;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all();

        // Get assessment schemes grouped by organization
        $assessmentSchemesByOrg = AssessmentScheme::all()->groupBy('organization_id');

        foreach ($projects as $project) {

            // Get assessment schemes for this organization
            $orgAssessmentSchemes = $assessmentSchemesByOrg->get($project->organization_id, collect());
            $defaultAssessmentSchemeId = $orgAssessmentSchemes->first()?->id;

            $levelsData = [
                [
                    'organization_id' => $project->organization_id,
                    'project_id' => $project->id,
                    'assessment_scheme_id' => $defaultAssessmentSchemeId,
                    'name' => 'المستوى الأول',
                    'order' => 1,
                    'from_page' => 1,
                    'to_page' => 20,
                    'min_passing_score' => 60,
                    'max_score' => 100,
                ],
                [
                    'organization_id' => $project->organization_id,
                    'project_id' => $project->id,
                    'assessment_scheme_id' => $defaultAssessmentSchemeId,
                    'name' => 'المستوى الثاني',
                    'order' => 2,
                    'from_page' => 21,
                    'to_page' => 40,
                    'min_passing_score' => 60,
                    'max_score' => 100,
                ],
                [
                    'organization_id' => $project->organization_id,
                    'project_id' => $project->id,
                    'assessment_scheme_id' => $defaultAssessmentSchemeId,
                    'name' => 'المستوى الثالث',
                    'order' => 3,
                    'from_page' => 41,
                    'to_page' => 60,
                    'min_passing_score' => 60,
                    'max_score' => 100,
                ],
                [
                    'organization_id' => $project->organization_id,
                    'project_id' => $project->id,
                    'assessment_scheme_id' => $defaultAssessmentSchemeId,
                    'name' => 'المستوى الرابع',
                    'order' => 4,
                    'from_page' => 61,
                    'to_page' => 80,
                    'min_passing_score' => 60,
                    'max_score' => 100,
                ],
                [
                    'organization_id' => $project->organization_id,
                    'project_id' => $project->id,
                    'assessment_scheme_id' => $defaultAssessmentSchemeId,
                    'name' => 'المستوى الخامس',
                    'order' => 5,
                    'from_page' => 81,
                    'to_page' => 100,
                    'min_passing_score' => 60,
                    'max_score' => 100,
                ],
            ];

            foreach ($levelsData as $levelData) {
                $level = Level::create($levelData);

                // Attach additional curricula to this level
                $additionalCurricula = Curriculum::query()
                    ->where('organization_id', $level->organization_id)
                    ->where('curriculum_type', CurriculumTypeEnum::ADDITIONAL)
                    ->inRandomOrder()
                    ->take(rand(1, 2)) // Attach 1 or 2 additional curricula randomly
                    ->get();

                foreach ($additionalCurricula as $index => $curriculum) {
                    $level->attachedCurricula()->attach($curriculum->id, [
                        'from_page' => $level->from_page + ($index * 10) + 1, // Offset pages for each curriculum
                        'to_page' => min($level->to_page, $level->from_page + ($index * 10) + 15), // Ensure within level range
                    ]);
                }
            }
            
        }//end of foreach

    }//end of run

}//end of seeder
