<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $organizationId = session('selected_organization')['id'];

        $rules = [
            'organization_id' => 'required|exists:organizations,id',
            'year' => 'required|integer|min:1900|max:2100',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subscription_types', 'name')
                    ->where(fn ($query) => $query->where('organization_id', $organizationId)->where('year', $this->year)),
            ],
            'fees' => 'required|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'has_specific_date' => 'required|boolean',
            'start_date' => [
                'nullable',
                'date',
                Rule::requiredIf(fn () => $this->has_specific_date ?? false),
            ],
            'end_date' => [
                'nullable',
                'date',
                Rule::requiredIf(fn () => $this->has_specific_date ?? false),
                'after_or_equal:start_date',
            ],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $subscriptionType = $this->route()->parameter('subscription_type');

            $rules['name'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('subscription_types', 'name')
                    ->where(fn ($query) => $query->where('organization_id', $organizationId)->where('year', $this->year))
                    ->ignore($subscriptionType->id),
            ];

        }// end of if

        return $rules;

    }// end of rules

    public function prepareForValidation()
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
            'has_specific_date' => (bool) ($this->has_specific_date ?? false),
        ]);

    }// end of prepareForValidation

}// end of request
