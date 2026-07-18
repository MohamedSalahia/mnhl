<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Branch;
use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;

class TeacherRegistrationRequest extends FormRequest
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
        $settings = $organization ? ($organization->teacher_registration_settings ?? []) : [];

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
                function ($attribute, $value, $fail) {
                    if (!Branch::findByHashId($value)) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'name' => $this->getRule('name', $settings, 'required|string|max:255'),
            'email' => $this->getRule('email', $settings, 'required|email|unique:users'),
            'gender' => $this->getRule('gender', $settings, 'required|in:' . implode(',', GenderEnum::getConstants())),
            'mobile' => $this->getRule('mobile', $settings, 'required|string|max:20'),
            'date_of_birth' => $this->getRule('date_of_birth', $settings, 'required|date_format:Y-m-d'),
            'birth_place' => $this->getRule('birth_place', $settings, 'nullable|string|max:255'),
            'marital_status' => $this->getRule('marital_status', $settings, 'required|in:' . implode(',', MaritalStatusEnum::getConstants())),
            'nationality_id' => $this->getRule('nationality_id', $settings, 'required|exists:nationalities,id'),
            'image' => $this->getRule('image', $settings, 'required|file|mimes:jpeg,jpg,png|max:2048'),
            'identity_document_file' => $this->getRule('identity_document_file', $settings, 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048'),
            'current_address' => $this->getRule('current_address', $settings, 'nullable|string|max:500'),
            'last_educational_certificate' => $this->getRule('last_educational_certificate', $settings, 'nullable|string|max:255'),
        ];

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
            'marital_status' => __('users.marital_status'),
            'nationality_id' => __('users.nationality'),
            'identity_document_file' => __('users.identity_document_file'),
            'current_address' => __('users.current_address'),
            'last_educational_certificate' => __('users.last_educational_certificate'),
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'type' => UserTypeEnum::TEACHER,
        ]);

    }// end of prepareForValidation

}//end of request
