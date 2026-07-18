<?php

namespace App\Http\Requests\Teacher;

use App\Enums\AttendanceStatusEnum;
use App\Models\BranchStudent;
use App\Models\Curriculum;
use App\Models\Level;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StudentLessonRequest extends FormRequest
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
        $organizationId = session('selected_organization')['id'] ?? null;

        return [
            'attendance_status' => [
                'required',
                'in:' . implode(',', AttendanceStatusEnum::getConstants()),
            ],
            'time_elapsed' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
            'curriculum_id' => [
                'required',
                'exists:curricula,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId && $value) {
                        $curriculum = Curriculum::find($value);
                        if ($curriculum && $curriculum->organization_id != $organizationId) {
                            $fail(__('validation.curriculum_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'project_id' => [
                'required',
                'exists:projects,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if (!$value) {
                        return;
                    }

                    $project = Project::find($value);
                    if (!$project) {
                        return;
                    }

                    // Check if project belongs to organization
                    if ($organizationId && $project->organization_id != $organizationId) {
                        $fail(__('validation.project_not_belongs_to_organization'));
                        return;
                    }

                    // Always check if project belongs to selected curriculum (required relationship)
                    $curriculumId = $this->curriculum_id;

                    if ($curriculumId && $project->curriculum_id != $curriculumId) {
                        $fail(__('projects.project_not_belongs_to_curriculum'));
                    }
                },
            ],
            'level_id' => [
                'required',
                'exists:levels,id',
                function ($attribute, $value, $fail) use ($organizationId) {

                    if ($organizationId && $value) {

                        $level = Level::find($value);

                        if ($level && $level->organization_id != $organizationId) {
                            $fail(__('validation.level_not_belongs_to_organization'));
                        }

                        // Check if level belongs to selected project
                        if ($this->project_id && $level && $level->project_id != $this->project_id) {
                            $fail(__('validation.level_not_belongs_to_project'));
                        }
                    }
                },
            ],
        ];

    }//end of rules

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'attendance_status' => __('lessons.attendance_status'),
            'time_elapsed' => __('lessons.time_elapsed'),
            'notes' => __('lessons.notes'),
            'evaluation_item_id' => __('evaluation_items.evaluation_item'),
            'curriculum_id' => __('curricula.curriculum'),
            'project_id' => __('projects.project'),
            'level_id' => __('levels.level'),
        ];

    }//end of attributes

    protected function prepareForValidation()
    {
        $studentLesson = $this->route('studentLesson');

        if ($studentLesson && $studentLesson->lesson) {

            $lesson = $studentLesson->lesson;

            if ($lesson->branch_id && $lesson->classroom_id) {

                $branchStudent = BranchStudent::query()
                    ->where('student_id', $studentLesson->student_id)
                    ->where('branch_id', $lesson->branch_id)
                    ->where('classroom_id', $lesson->classroom_id)
                    ->first();

                if ($branchStudent) {

                    $this->merge([
                        'curriculum_id' => $branchStudent->curriculum_id,
                        'project_id' => $branchStudent->project_id,
                        'level_id' => $branchStudent->level_id,
                    ]);

                }//end of if

            }//end of if

        }//end of if

    }//end of prepareForValidation

}//end of request
