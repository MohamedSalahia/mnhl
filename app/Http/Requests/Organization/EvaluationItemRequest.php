<?php

namespace App\Http\Requests\Organization;

use App\Models\EvaluationModel;
use Illuminate\Foundation\Http\FormRequest;

class EvaluationItemRequest extends FormRequest
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
            'evaluation_model_id' => [
                'required',
                'exists:evaluation_models,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    $evaluationModel = EvaluationModel::find($value);
                    if ($evaluationModel && $evaluationModel->organization_id != $organizationId) {
                        $fail(__('validation.evaluation_model_not_belongs_to_organization'));
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'background_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'pass' => 'nullable|boolean',
        ];

        return $rules;

    }//end of rules

    public function prepareForValidation()
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
            'pass' => $this->has('pass') ? true : false,
        ]);

    }// end of prepareForValidation

}//end of request

