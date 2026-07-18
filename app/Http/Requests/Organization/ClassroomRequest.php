<?php

namespace App\Http\Requests\Organization;

use App\Enums\ClassroomTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
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
            'teacher_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'type' => 'required|in:' . implode(',', ClassroomTypeEnum::getConstants()),
        ];

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
            'teacher_id' => __('classrooms.teacher'),
            'name' => __('classrooms.name'),
            'start_date' => __('classrooms.start_date'),
            'end_date' => __('classrooms.end_date'),
            'type' => __('classrooms.type'),
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $selectedBranch = session('selected_branch');
            $selectedOrganization = session('selected_organization');

            if ($this->filled('teacher_id')) {
                $teacher = \App\Models\User::find($this->teacher_id);
                
                if ($teacher) {
                    $belongsToOrganization = $teacher->teacherOrganizations()
                        ->where('organizations.id', $selectedOrganization['id'] ?? null)
                        ->exists();
                    
                    $belongsToBranch = $teacher->teacherBranches()
                        ->where('branches.id', $selectedBranch['id'] ?? null)
                        ->exists();

                    if (!$belongsToOrganization || !$belongsToBranch) {
                        $validator->errors()->add('teacher_id', __('validation.exists', ['attribute' => __('classrooms.teacher')]));
                    }
                }
            }
        });
    }// end of withValidator

}//end of request
