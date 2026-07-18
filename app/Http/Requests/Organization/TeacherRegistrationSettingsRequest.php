<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRegistrationSettingsRequest extends FormRequest
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
            'marital_status' => 'nullable|boolean',
            'nationality_id' => 'nullable|boolean',
            'image' => 'nullable|boolean',
            'identity_document_file' => 'nullable|boolean',
            'current_address' => 'nullable|boolean',
            'last_educational_certificate' => 'nullable|boolean',
        ];
    }

}//end of request
