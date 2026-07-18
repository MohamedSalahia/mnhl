<?php

namespace App\Http\Requests\Teacher;

use App\Models\Classroom;
use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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

        $branchId = session('selected_branch')['id'] ?? null;

        return [
            'organization_id' => [
                'required',
                'exists:organizations,id',
            ],
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],
            'teacher_id' => [
                'required',
                'exists:users,id',
            ],
            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                function ($attribute, $value, $fail) use ($branchId) {
                    if ($value && $branchId) {
                        $classroom = Classroom::find($value);
                        if ($classroom && $classroom->branch_id != $branchId) {
                            $fail(__('validation.classroom_not_belongs_to_branch'));
                        }
                    }
                },
            ],
            'date' => 'required|date',
        ];

    }//end of rules

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'classroom_id' => __('classrooms.classroom'),
            'project_id' => __('projects.project'),
            'level_id' => __('levels.level'),
            'date' => __('lessons.date'),
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $organizationId = session('selected_organization')['id'] ?? null;

        $branchId = session('selected_branch')['id'] ?? null;

        $teacherId = auth()->user()->id;

        $this->merge([
            'organization_id' => $organizationId,
            'branch_id' => $branchId,
            'teacher_id' => $teacherId,
            'date' => now()->format('Y-m-d'),
        ]);

    }//end of prepareForValidation

}//end of request
