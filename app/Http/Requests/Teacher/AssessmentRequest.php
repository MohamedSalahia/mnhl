<?php

namespace App\Http\Requests\Teacher;

use App\Enums\AssessmentStatusEnum;
use App\Models\Assessment;
use App\Models\BranchStudent;
use App\Models\Project;
use App\Models\StudentLesson;
use Illuminate\Foundation\Http\FormRequest;

class AssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'student_lesson_id' => [
                'required',
                'exists:student_lessons,id',
                $this->validateDuplicateAssessment(),
            ],
            'examiner_id' => [
                'required',
                'exists:users,id',
                $this->validateExaminerId(),
            ],
        ];

    }//end of rules

    /**
     * Validate that an assessment doesn't already exist for this student.
     *
     * @return \Closure
     */
    private function validateDuplicateAssessment()
    {
        return function ($attribute, $value, $fail) {
            $studentLesson = StudentLesson::with('lesson.branch')->find($value);

            if (!$studentLesson || !$studentLesson->lesson) {
                return;
            }

            // Get assessment_scheme_id from student's project
            $branchStudent = BranchStudent::query()
                ->where('student_id', $studentLesson->student_id)
                ->where('branch_id', $studentLesson->lesson->branch_id)
                ->where('classroom_id', $studentLesson->lesson->classroom_id)
                ->first();

            $assessmentSchemeId = null;

            if ($branchStudent && $branchStudent->project_id) {
                $project = Project::find($branchStudent->project_id);
                $assessmentSchemeId = $project->assessment_scheme_id ?? null;
            }

            // Check if assessment already exists for this student with the same assessment scheme
            if ($assessmentSchemeId) {
                $existingAssessment = Assessment::where('student_id', $studentLesson->student_id)
                    ->where('assessment_scheme_id', $assessmentSchemeId)
                    ->whereIn('status', [AssessmentStatusEnum::PENDING, AssessmentStatusEnum::IN_PROGRESS])
                    ->first();

                if ($existingAssessment) {
                    $fail(__('assessments.already_exists'));
                }
            }
        };
    }

    /**
     * Validate that the examiner has the examiner role for this branch's team.
     *
     * @return \Closure
     */
    private function validateExaminerId()
    {
        return function ($attribute, $value, $fail) {
            $studentLessonId = $this->input('student_lesson_id');

            if (!$studentLessonId) {
                return;
            }

            $studentLesson = StudentLesson::with('lesson.branch')->find($studentLessonId);

            if (!$studentLesson || !$studentLesson->lesson || !$studentLesson->lesson->branch) {
                return;
            }

            $teamId = $studentLesson->lesson->branch->team_id;

            if (!$teamId) {
                $fail(__('assessments.examiner_team_not_found'));
                return;
            }

            $examiner = \App\Models\User::query()
                ->where('id', $value)
                ->whereHas('roles', function ($query) use ($teamId) {
                    $query->where('name', 'examiner')
                        ->where('team_id', $teamId);
                })
                ->first();

            if (!$examiner) {
                $fail(__('assessments.examiner_not_in_branch'));
            }
        };
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'student_lesson_id' => __('lessons.student_lesson'),
            'examiner_id' => __('assessments.examiner'),
        ];

    }//end of attributes

}//end of request
