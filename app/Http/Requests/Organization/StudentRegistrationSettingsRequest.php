<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class StudentRegistrationSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'gender' => 'nullable|boolean',
            'mobile' => 'nullable|boolean',
            'date_of_birth' => 'nullable|boolean',
            'birth_place' => 'nullable|boolean',
            'father_name' => 'nullable|boolean',
            'mother_name' => 'nullable|boolean',
            'father_occupation' => 'nullable|boolean',
            'mother_occupation' => 'nullable|boolean',
            'father_mobile' => 'nullable|boolean',
            'mother_mobile' => 'nullable|boolean',
            'image' => 'nullable|boolean',
            'identity_document_file' => 'nullable|boolean',
            'current_address' => 'nullable|boolean',
            'has_previous_education' => 'nullable|boolean',
            'previous_education_details' => 'nullable|boolean',
        ];
    }

}//end of request

