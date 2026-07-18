<?php

namespace App\Http\Requests\Organization;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class ExaminerRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'type' => 'required|in:' . implode(',', UserTypeEnum::getConstants()),
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $examiner = $this->route()->parameter('examiner');

            $rules['email'] = 'required|email|unique:users,id,' . $examiner->id;
            $rules['password'] = 'nullable|confirmed';

        }//end of if

        return $rules;

    }//end of rules

    public function prepareForValidation()
    {
        return $this->merge([
            'type' => UserTypeEnum::EXAMINER,
        ]);

    }// end of prepareForValidation

}//end of request
