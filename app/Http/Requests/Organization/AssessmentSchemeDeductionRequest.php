<?php

namespace App\Http\Requests\Organization;

use App\Models\AssessmentScheme;
use Illuminate\Foundation\Http\FormRequest;

class AssessmentSchemeDeductionRequest extends FormRequest
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
        $organizationId = session('selected_organization')['id'];

        $rules = [
            'organization_id' => 'required|exists:organizations,id',
            'assessment_scheme_id' => [
                'required',
                'exists:assessment_schemes,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    $assessmentScheme = AssessmentScheme::find($value);
                    if ($assessmentScheme && $assessmentScheme->organization_id != $organizationId) {
                        $fail(__('validation.assessment_scheme_not_belongs_to_organization'));
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'value' => 'required|integer|min:0',
            'max_clicks' => 'nullable|integer|min:0',
            'background_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
        ];

        return $rules;

    }//end of rules

    public function prepareForValidation()
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
            'max_clicks'      => $this->max_clicks !== '' ? $this->max_clicks : null,
        ]);

    }// end of prepareForValidation

}//end of request
