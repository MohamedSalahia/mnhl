<?php

namespace App\Services;

use App\Enums\AssessmentStatusEnum;
use App\Models\Assessment;
use App\Models\BranchStudent;
use App\Models\Level;
use App\Models\Project;

class AssessmentService
{
    /**
     * Handle student progression after assessment completion.
     *
     * @param Assessment $assessment
     * @return void
     */
    public function handleProgression(Assessment $assessment): void
    {
        // Only process completed assessments
        if ($assessment->status !== AssessmentStatusEnum::COMPLETED) {
            return;
        }

        // Check if student passed the assessment
        if (!$this->studentPassed($assessment)) {
            return;
        }

        // Get the student's branch record
        $branchStudent = $this->getBranchStudent($assessment);

        if (!$branchStudent) {
            return;
        }

        // Try to progress to next level
        $nextLevel = $this->getNextLevel($assessment);

        if ($nextLevel) {
            $this->progressToNextLevel($branchStudent, $nextLevel);
            return;
        }

        // No next level - check if can proceed to next project
        $this->tryProgressToNextProject($assessment, $branchStudent);

    }// end of handleProgression

    /**
     * Check if student passed the assessment.
     *
     * @param Assessment $assessment
     * @return bool
     */
    protected function studentPassed(Assessment $assessment): bool
    {
        if (!$assessment->level || $assessment->score === null) {
            return false;
        }

        return $assessment->score >= $assessment->level->min_passing_score;

    }// end of studentPassed
    
    protected function getBranchStudent(Assessment $assessment): ?BranchStudent
    {
        return BranchStudent::query()
            ->whenStudentId($assessment->student_id)
            ->whenBranchId($assessment->branch_id)
            ->whenProjectId($assessment->project_id)
            ->whenCurriculumId($assessment->curriculum_id)
            ->whenLevelId($assessment->level_id)
            ->first();

    }// end of getBranchStudent

    /**
     * Get the next level in the current project.
     *
     * @param Assessment $assessment
     * @return Level|null
     */
    protected function getNextLevel(Assessment $assessment): ?Level
    {
        if (!$assessment->level || !$assessment->project_id) {
            return null;
        }

        return Level::query()
            ->where('project_id', $assessment->project_id)
            ->where('order', '>', $assessment->level->order)
            ->orderBy('order', 'asc')
            ->first();

    }// end of getNextLevel

    /**
     * Progress student to the next level.
     *
     * @param BranchStudent $branchStudent
     * @param Level $nextLevel
     * @return void
     */
    protected function progressToNextLevel(BranchStudent $branchStudent, Level $nextLevel): void
    {
        BranchStudent::query()
            ->where('id', $branchStudent->id)
            ->update(['level_id' => $nextLevel->id]);

    }// end of progressToNextLevel

    /**
     * Try to progress student to the next project.
     *
     * @param Assessment $assessment
     * @param BranchStudent $branchStudent
     * @return void
     */
    protected function tryProgressToNextProject(Assessment $assessment, BranchStudent $branchStudent): void
    {
        // Check if current project allows progression
        $currentProject = Project::find($assessment->project_id);

        if (!$currentProject || !$currentProject->can_proceed_to_next_project) {
            return;
        }

        // Find the next project
        $nextProject = Project::query()
            ->where('curriculum_id', $currentProject->curriculum_id)
            ->where('order', '>', $currentProject->order)
            ->orderBy('order', 'asc')
            ->first();

        if (!$nextProject) {
            return;
        }

        // Get the first level of the next project
        $firstLevel = Level::query()
            ->where('project_id', $nextProject->id)
            ->orderBy('order', 'asc')
            ->first();

        // Update student's project and level
        BranchStudent::query()
            ->where('id', $branchStudent->id)
            ->update([
                'project_id' => $nextProject->id,
                'level_id' => $firstLevel?->id,
            ]);

    }// end of tryProgressToNextProject

} // end of class
