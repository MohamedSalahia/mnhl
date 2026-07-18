<?php

namespace App\Http\Requests\Teacher;

use App\Enums\AssessmentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class AssessmentStoreDeductionRequest extends FormRequest
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
        return [
            'status' => [
                'required',
                'string',
                'in:' . AssessmentStatusEnum::COMPLETED . ',' . AssessmentStatusEnum::PARTIALLY_IN_PROGRESS,
            ],
            'deductions' => 'required|array',
            'deductions.*.assessment_scheme_deduction_id' => 'required|exists:assessment_scheme_deductions,id',
            'deductions.*.quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
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
            'status' => __('assessments.status'),
            'deductions' => __('assessment_scheme_deductions.assessment_scheme_deductions'),
            'deductions.*.assessment_scheme_deduction_id' => __('assessment_scheme_deductions.assessment_scheme_deduction'),
            'deductions.*.quantity' => __('assessments.quantity'),
            'notes' => __('assessments.notes'),
        ];

    }//end of attributes

}//end of request
