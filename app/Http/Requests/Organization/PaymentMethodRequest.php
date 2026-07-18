<?php

namespace App\Http\Requests\Organization;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentMethodRequest extends FormRequest
{
    public function authorize()
    {
        return true;

    }// end of authorize

    public function rules()
    {
        $organizationId = session('selected_organization')['id'];

        $rules = [
            'organization_id' => 'required|exists:organizations,id',
            '%name%' => [
                'required',
                Rule::unique('payment_method_translations', 'name')->where(function ($query) use ($organizationId) {
                    $query->whereIn('payment_method_id', function ($q) use ($organizationId) {
                        $q->select('id')
                            ->from('payment_methods')
                            ->where('organization_id', $organizationId);
                    });
                }),
            ],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $paymentMethod = $this->route()->parameter('payment_method');

            $rules['%name%'] = [
                'required',
                Rule::unique('payment_method_translations', 'name')
                    ->where(function ($query) use ($organizationId) {
                        $query->whereIn('payment_method_id', function ($q) use ($organizationId) {
                            $q->select('id')
                                ->from('payment_methods')
                                ->where('organization_id', $organizationId);
                        });
                    })
                    ->ignore($paymentMethod->id, 'payment_method_id'),
            ];

        }// end of if

        return $this->makeEnOptional(RuleFactory::make($rules));

    }// end of rules

    private function makeEnOptional(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            // RuleFactory returns dot-notation keys e.g. en.name, ar.name
            // Only touch non-Arabic translation fields — skip organization_id etc.
            if (preg_match('/^[a-z]{2}\./', $key) && !str_starts_with($key, 'ar.')) {
                $rules[$key] = array_merge(
                    ['nullable'],
                    array_filter((array) $rule, fn($r) => $r !== 'required')
                );
            }
        }
        return $rules;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
        ]);

    }// end of prepareForValidation

}// end of request
