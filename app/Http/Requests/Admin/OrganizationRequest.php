<?php

namespace App\Http\Requests\Admin;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'country_id' => 'required|exists:countries,id',
            'governorate_id' => 'required|exists:governorates,id',
            'area_id' => 'required|exists:areas,id',
            '%name%' => ['required', Rule::unique('organization_translations', 'name')],
            'logo' => 'required|image|max:10240',
            'super_admin_type' => 'required|in:new,existing',
        ];

        // Conditional validation based on super_admin_type
        if ($this->input('super_admin_type') === 'existing') {
            $rules['existing_super_admin_id'] = 'required|exists:users,id';
        } else {
            $rules['super_admin_name'] = 'required|string|max:255';
            $rules['super_admin_email'] = 'required|email|unique:users,email';
            $rules['super_admin_password'] = 'required|confirmed|min:8';
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $organization = $this->route()->parameter('organization');

            $rules['%name%'] = [
                'required',
                Rule::unique('organization_translations', 'name')
                    ->ignore($organization->id, 'organization_id')
            ];

            $rules['logo'] = ['sometimes', 'nullable', 'image', 'max:2048'];

            // Remove super_admin validation for update requests
            unset($rules['super_admin_type']);
            unset($rules['existing_super_admin_id']);
            unset($rules['super_admin_name']);
            unset($rules['super_admin_email']);
            unset($rules['super_admin_password']);

        }

        return $this->makeEnOptional(RuleFactory::make($rules));

    }//end of rules

    private function makeEnOptional(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            // RuleFactory returns dot-notation keys e.g. en.name
            // Only touch non-Arabic translation fields — skip other fields
            if (preg_match('/^[a-z]{2}\./', $key) && !str_starts_with($key, 'ar.')) {
                $rules[$key] = array_merge(
                    ['nullable'],
                    array_filter((array) $rule, fn($r) => $r !== 'required')
                );
            }
        }
        return $rules;
    }

}//end of request

