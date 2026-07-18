<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectBasicInformationRequest extends FormRequest
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
        $organizationId = session('selected_organization')['id'] ?? $this->organization_id;

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
            'curriculum_id' => [
                'required',
                'exists:curricula,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId) {
                        $curriculum = \App\Models\Curriculum::find($value);
                        if ($curriculum && $curriculum->organization_id != $organizationId) {
                            $fail(__('validation.curriculum_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects')->where('organization_id', $organizationId),
            ],
            'can_proceed_to_next_project' => 'nullable|boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $project = $this->route()->parameter('project');

            $rules['name'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('projects')->ignore($project->id)->where('organization_id', $organizationId),
            ];

        }//end of if

        return $rules;

    }//end of rules

    public function prepareForValidation()
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
            'can_proceed_to_next_project' => $this->has('can_proceed_to_next_project') ? true : false,
        ]);

    }// end of prepareForValidation

}//end of request

