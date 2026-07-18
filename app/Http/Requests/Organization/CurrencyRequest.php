<?php

namespace App\Http\Requests\Organization;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyRequest extends FormRequest
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
            '%name%' => [
                'required',
                Rule::unique('currency_translations', 'name')->where(function ($query) use ($organizationId) {
                    $query->whereIn('currency_id', function ($q) use ($organizationId) {
                        $q->select('id')
                            ->from('currencies')
                            ->where('organization_id', $organizationId);
                    });
                }),
            ],
            '%code%' => [
                'required',
                Rule::unique('currency_translations', 'code')->where(function ($query) use ($organizationId) {
                    $query->whereIn('currency_id', function ($q) use ($organizationId) {
                        $q->select('id')
                            ->from('currencies')
                            ->where('organization_id', $organizationId);
                    });
                }),
            ],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $currency = $this->route()->parameter('currency');

            $rules['%name%'] = [
                'required',
                Rule::unique('currency_translations', 'name')
                    ->where(function ($query) use ($organizationId) {
                        $query->whereIn('currency_id', function ($q) use ($organizationId) {
                            $q->select('id')
                                ->from('currencies')
                                ->where('organization_id', $organizationId);
                        });
                    })
                    ->ignore($currency->id, 'currency_id'),
            ];

            $rules['%code%'] = [
                'required',
                Rule::unique('currency_translations', 'code')
                    ->where(function ($query) use ($organizationId) {
                        $query->whereIn('currency_id', function ($q) use ($organizationId) {
                            $q->select('id')
                                ->from('currencies')
                                ->where('organization_id', $organizationId);
                        });
                    })
                    ->ignore($currency->id, 'currency_id'),
            ];

        }// end of if

        return $this->makeEnOptional(RuleFactory::make($rules));

    }// end of rules

    private function makeEnOptional(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            // RuleFactory returns dot-notation keys e.g. en.name, ar.code
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

    public function prepareForValidation()
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'],
        ]);

    }// end of prepareForValidation

}// end of request
