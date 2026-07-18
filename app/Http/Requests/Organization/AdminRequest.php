<?php

namespace App\Http\Requests\Organization;

use App\Enums\UserTypeEnum;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role_id' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($organizationId) {

                    if ($organizationId) {

                        $role = Role::find($value);

                        if ($role && $role->organization_id != $organizationId) {
                            $fail(__('validation.role_not_belongs_to_organization'));
                        }

                    }
                },
            ],
            'type' => 'required|in:' . implode(',', UserTypeEnum::getConstants()),
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $admin = $this->route()->parameter('admin');

            $rules['email'] = 'required|email|unique:users,id,' . $admin->id;
            $rules['password'] = 'nullable|confirmed';

        }//end of if

        return $rules;

    }//end of rules

    public function prepareForValidation()
    {
        return $this->merge([
            'type' => UserTypeEnum::ORGANIZATION_ADMIN,
        ]);

    }// end of prepareForValidation

}//end of request
