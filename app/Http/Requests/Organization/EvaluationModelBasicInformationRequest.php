<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EvaluationModelBasicInformationRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('evaluation_models')->where('organization_id', $organizationId),
            ],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $evaluationModel = $this->route()->parameter('evaluation_model');

            $rules['name'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('evaluation_models')->ignore($evaluationModel->id)->where('organization_id', $organizationId),
            ];

        }//end of if

        return $rules;

    }//end of rules

    public function prepareForValidation()
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
        ]);

    }// end of prepareForValidation

}//end of request

