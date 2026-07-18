<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        $organizationId = session('selected_organization')['id'] ?? $this->organization_id;

        $rules = [
            'name' => [
                'required',
                Rule::unique('roles')->where('organization_id', $organizationId),
            ],
            'permissions' => 'required',
            'organization_id' => 'required|exists:organizations,id',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $role = $this->route()->parameter('role');

            $rules['name'] = [
                'required',
                Rule::unique('roles')->ignore($role->id)->where('organization_id', $organizationId),
            ];

        }//end of if

        return $rules;

    }//end of rules

    public function prepareForValidation()
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
        ]);

    }// end of prepareForValidation

}//end of request
