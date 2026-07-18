<?php

namespace App\Http\Requests\Organization;

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'gender' => 'required|in:' . implode(',', GenderEnum::getConstants()),
            'mobile' => 'required|string|max:20',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'birth_place' => 'nullable|string|max:255',
            'marital_status' => 'required|in:' . implode(',', MaritalStatusEnum::getConstants()),
            'nationality_id' => 'required|exists:nationalities,id',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'identity_document_file' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'current_address' => 'nullable|string|max:500',
            'last_educational_certificate' => 'nullable|string|max:255',
            'teacher_certificate_ids' => 'nullable|array|min:1',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $teacher = $this->route()->parameter('teacher');
            $rules['email'] = 'required|email|unique:users,email,' . $teacher->id;
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
        $data = [
            'type' => UserTypeEnum::TEACHER,
        ];

        if ($this->filled('teacher_certificate_ids')) {
            $decodedIds = json_decode($this->teacher_certificate_ids, true);
            $data['teacher_certificate_ids'] = is_array($decodedIds) ? $decodedIds : [];
        }

        return $this->merge($data);

    }// end of prepareForValidation


}//end of request

