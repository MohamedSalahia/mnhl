<?php

namespace App\Http\Requests\Organization;

use App\Models\Classroom;
use App\Models\Curriculum;
use App\Models\Level;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentAcceptEnrollmentRequest extends FormRequest
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
                    $curriculumId = $this->input('curriculum_id');
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
            'page_number' => [
                'nullable',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value && $this->level_id) {
                        $level = Level::find($this->level_id);
                        if ($level) {
                            if ($value < $level->from_page || $value > $level->to_page) {
                                $fail(__('levels.page_number_out_of_range', [
                                    'from_page' => $level->from_page,
                                    'to_page' => $level->to_page
                                ]));
                            }
                        }
                    }
                },
            ],
            'classroom_id' => [
                'nullable',
                'exists:classrooms,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $selectedBranch = session('selected_branch');
                        $classroom = Classroom::find($value);
                        if ($classroom && $selectedBranch && isset($selectedBranch['id']) && $classroom->branch_id != $selectedBranch['id']) {
                            $fail(__('validation.classroom_not_belongs_to_branch'));
                        }
                    }
                },
            ],
            'exempted_from_fees' => 'boolean',
            'currency_id' => array_merge(
                [
                    'nullable',
                    Rule::requiredIf(fn () => ! $this->exempted_from_fees),
                ],
                $organizationId
                    ? [Rule::exists('currencies', 'id')->where('organization_id', $organizationId)]
                    : ['exists:currencies,id']
            ),
            'fees' => [
                Rule::requiredIf(fn () => ! $this->exempted_from_fees),
                'numeric',
                'min:0',
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
            'curriculum_id' => __('curricula.curriculum'),
            'project_id' => __('projects.project'),
            'level_id' => __('levels.level'),
            'page_number' => __('levels.page_number'),
            'classroom_id' => __('classrooms.classroom'),
            'exempted_from_fees' => __('students.exempted_from_fees'),
            'currency_id' => __('currencies.currency'),
            'fees' => __('subscription_types.fees'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $exempted = $this->exempted_from_fees ?? false;

        $this->merge([
            'exempted_from_fees' => $exempted,
            'currency_id' => $exempted ? null : $this->currency_id,
            'fees' => $exempted ? 0 : $this->fees,
        ]);

    }// end of prepareForValidation

}//end of request
