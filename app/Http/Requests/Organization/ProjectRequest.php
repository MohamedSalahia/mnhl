<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'organization_id' => 'required|exists:organizations,id',
            'evaluation_model_id' => [
                'required',
                'exists:evaluation_models,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId) {
                        $evaluationModel = \App\Models\EvaluationModel::find($value);
                        if ($evaluationModel && $evaluationModel->organization_id != $organizationId) {
                            $fail(__('validation.evaluation_model_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'name' => 'required|string|max:255',
            'can_proceed_to_next_project' => 'nullable|boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            // No additional rules needed for update
        }//end of if

        return $rules;

    }//end of rules

    protected function prepareForValidation()
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
            'can_proceed_to_next_project' => $this->has('can_proceed_to_next_project') ? true : false,
        ]);
    }

}//end of request

