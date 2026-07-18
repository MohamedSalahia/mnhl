<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Enums\UserTypeEnum;
use App\Models\Branch;
use App\Models\Classroom;
use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;

class StudentRegistrationRequest extends FormRequest
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
        $organizationHashId = $this->input('organization_id');
        $organization = $organizationHashId ? Organization::findByHashId($organizationHashId) : null;
        $settings = $organization ? ($organization->student_registration_settings ?? []) : [];

        $rules = [
            'organization_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Organization::findByHashId($value)) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'branch_id' => [
                'required',
                function ($attribute, $value, $fail) use ($organizationHashId, $organization) {
                    $branch = Branch::findByHashId($value);
                    
                    if (!$branch) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                        return;
                    }
                    
                    // Check if branch belongs to the organization
                    if ($organization && $branch->organization_id !== $organization->id) {
                        $fail(__('branches.branch_not_belongs_to_organization'));
                    }
                },
            ],
            'name' => $this->getRule('name', $settings, 'required|string|max:255'),
            'email' => $this->getRule('email', $settings, 'required|email|unique:users'),
            'gender' => $this->getRule('gender', $settings, 'required|in:' . implode(',', GenderEnum::getConstants())),
            'mobile' => $this->getRule('mobile', $settings, 'required|string|max:20'),
            'mobile_country_code' => 'nullable|string|max:10',
            'date_of_birth' => $this->getRule('date_of_birth', $settings, 'required|date_format:Y-m-d'),
            'birth_place' => $this->getRule('birth_place', $settings, 'nullable|string|max:255'),
            'father_name' => $this->getRule('father_name', $settings, 'required|string|max:255'),
            'mother_name' => $this->getRule('mother_name', $settings, 'required|string|max:255'),
            'father_occupation' => $this->getRule('father_occupation', $settings, 'required|string|max:255'),
            'mother_occupation' => $this->getRule('mother_occupation', $settings, 'required|string|max:255'),
            'father_mobile' => $this->getRule('father_mobile', $settings, 'required|string|max:20'),
            'father_mobile_country_code' => 'nullable|string|max:10',
            'mother_mobile' => $this->getRule('mother_mobile', $settings, 'required|string|max:20'),
            'mother_mobile_country_code' => 'nullable|string|max:10',
            'has_previous_education' => $this->getRule('has_previous_education', $settings, 'required|boolean'),
            'previous_education_details' => $this->getRule('previous_education_details', $settings, 'required|string|max:1000'),
            'image' => $this->getRule('image', $settings, 'required|file|mimes:jpeg,jpg,png|max:2048'),
            'identity_document_file' => $this->getRule('identity_document_file', $settings, 'required|file|mimes:jpeg,jpg,png,pdf|max:2048'),
            'current_address' => $this->getRule('current_address', $settings, 'required|string|max:500'),
            'classroom_id' => [
                'nullable',
                'exists:classrooms,id',
                function ($attribute, $value, $fail) use ($organizationHashId) {
                    if ($value) {
                        $branchHashId = $this->input('branch_id');
                        $branch = $branchHashId ? Branch::findByHashId($branchHashId) : null;
                        if ($branch) {
                            $classroom = Classroom::find($value);
                            if ($classroom && $classroom->branch_id != $branch->id) {
                                $fail(__('validation.classroom_not_belongs_to_branch'));
                            }
                        }
                    }
                },
            ],
            'type' => 'required|in:' . implode(',', UserTypeEnum::getConstants()),
        ];

        // Handle previous_education_details conditional requirement
        if (isset($settings['previous_education_details']) && $settings['previous_education_details']) {
            $rules['previous_education_details'] = 'required_if:has_previous_education,1|string|max:1000';
        } elseif (isset($settings['has_previous_education']) && $settings['has_previous_education']) {
            $rules['previous_education_details'] = 'required_if:has_previous_education,1|nullable|string|max:1000';
        } else {
            $rules['previous_education_details'] = 'nullable|string|max:1000';
        }

        return $rules;

    }//end of rules

    /**
     * Get validation rule based on settings
     *
     * @param string $field
     * @param array $settings
     * @param string $requiredRule
     * @return string
     */
    private function getRule($field, $settings, $requiredRule)
    {
        if (isset($settings[$field]) && $settings[$field]) {
            return $requiredRule;
        }

        // Convert required rule to nullable
        $nullableRule = str_replace('required|', 'nullable|', $requiredRule);
        $nullableRule = str_replace('required', 'nullable', $nullableRule);

        return $nullableRule;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'birth_place' => __('users.birth_place'),
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
            'identity_document_file' => __('users.identity_document_file'),
            'classroom_id' => __('classrooms.classroom'),
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'has_previous_education' => $this->has_previous_education ?? false,
            'previous_education_details' => $this->has_previous_education ? $this->previous_education_details : null,
            'type' => UserTypeEnum::STUDENT,
        ]);

    }// end of prepareForValidation

}//end of request

