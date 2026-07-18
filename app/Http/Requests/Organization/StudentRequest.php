<?php

namespace App\Http\Requests\Organization;

use App\Enums\GenderEnum;
use App\Enums\UserTypeEnum;
use App\Models\Classroom;
use App\Models\Curriculum;
use App\Models\Level;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
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

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'gender' => 'required|in:' . implode(',', GenderEnum::getConstants()),
            'mobile' => 'required|string|max:20',
            'mobile_country_code' => 'nullable|string|max:10',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'birth_location' => 'nullable|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'father_mobile' => 'nullable|string|max:20',
            'father_mobile_country_code' => 'nullable|string|max:10',
            'mother_mobile' => 'nullable|string|max:20',
            'mother_mobile_country_code' => 'nullable|string|max:10',
            'has_previous_education' => 'nullable|boolean',
            'previous_education_details' => 'required_if:has_previous_education,1|nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'identity_document_file' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'current_address' => 'nullable|string|max:500',
            'curriculum_id' => [
                'nullable',
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
                'nullable',
                'exists:projects,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId && $value) {
                        $project = Project::find($value);
                        if ($project && $project->organization_id != $organizationId) {
                            $fail(__('validation.project_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'level_id' => [
                'nullable',
                'exists:levels,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId && $value) {
                        $level = Level::find($value);
                        if ($level && $level->organization_id != $organizationId) {
                            $fail(__('validation.level_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'page_number' => [
                'nullable',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value && request()->level_id) {
                        $level = Level::find(request()->level_id);
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
                'required',
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
                    Rule::requiredIf(fn () => ! $this->boolean('exempted_from_fees')),
                ],
                $organizationId
                    ? [Rule::exists('currencies', 'id')->where('organization_id', $organizationId)]
                    : ['exists:currencies,id']
            ),
            'fees' => [
                Rule::requiredIf(fn () => ! $this->boolean('exempted_from_fees')),
                'numeric',
                'min:0',
            ],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $student = $this->route()->parameter('student');

            $rules['email'] = 'nullable|email|unique:users,email,' . $student->id;
        }

        return $rules;

    }//end of rules

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'birth_location' => __('users.birth_location'),
            'father_name' => __('users.father_name'),
            'mother_name' => __('users.mother_name'),
            'father_occupation' => __('users.father_occupation'),
            'mother_occupation' => __('users.mother_occupation'),
            'father_mobile' => __('users.father_mobile'),
            'father_mobile_country_code' => __('users.father_mobile_country_code'),
            'mother_mobile' => __('users.mother_mobile'),
            'mother_mobile_country_code' => __('users.mother_mobile_country_code'),
            'has_previous_education' => __('users.has_previous_education'),
            'previous_education_details' => __('users.previous_education_details'),
            'identity_document_file' => __('users.identity_document'),
            'curriculum_id' => __('curricula.curriculum'),
            'project_id' => __('projects.project'),
            'level_id' => __('levels.level'),
            'page_number' => __('levels.pages'),
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
            'has_previous_education' => $this->has_previous_education ?? false,
            'previous_education_details' => $this->has_previous_education ? $this->previous_education_details : null,
            'type' => UserTypeEnum::STUDENT,
            'exempted_from_fees' => $exempted,
            'currency_id' => $exempted ? null : $this->currency_id,
            'fees' => $exempted ? 0 : $this->fees,
        ]);

    }// end of prepareForValidation


}//end of request
