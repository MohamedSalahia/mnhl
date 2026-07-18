<?php

namespace App\Http\Requests\Organization;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $organizationId = session('selected_organization')['id'];

        $rules = [
            'country_id' => 'required|exists:countries,id',
            'governorate_id' => 'required|exists:governorates,id',
            'area_id' => 'required|exists:areas,id',
            'organization_id' => 'required|exists:organizations,id',
            '%name%' => [
                'required',
                function ($attribute, $value, $fail) use ($organizationId) {
                    $locale = str_replace(['.', 'name'], '', $attribute);
                    $locale = str_replace('translations', '', $locale);
                    $locale = trim($locale, '.');

                    $exists = \App\Models\BranchTranslation::where('name', $value)
                        ->where('locale', $locale)
                        ->whereHas('branch', function ($query) use ($organizationId) {
                            $query->where('organization_id', $organizationId);
                        })
                        ->exists();

                    if ($exists) {
                        $fail(__('validation.unique', ['attribute' => __('branches.name')]));
                    }
                }
            ],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $branch = $this->route()->parameter('branch');

            $rules['%name%'] = [
                'required',
                function ($attribute, $value, $fail) use ($organizationId, $branch) {
                    $locale = str_replace(['.', 'name'], '', $attribute);
                    $locale = str_replace('translations', '', $locale);
                    $locale = trim($locale, '.');

                    $exists = \App\Models\BranchTranslation::where('name', $value)
                        ->where('locale', $locale)
                        ->where('branch_id', '!=', $branch->id)
                        ->whereHas('branch', function ($query) use ($organizationId) {
                            $query->where('organization_id', $organizationId);
                        })
                        ->exists();

                    if ($exists) {
                        $fail(__('validation.unique', ['attribute' => __('branches.name')]));
                    }
                }
            ];

        }//end of if

        return $this->makeEnOptional(RuleFactory::make($rules));

    }//end of rules

    /** Make any en[...] field optional while keeping ar[...] required */
    private function makeEnOptional(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            if (str_starts_with($key, 'en[')) {
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
        return $this->merge([
            'organization_id' => session('selected_organization')['id'],
        ]);

    }// end of prepareForValidation

}//end of request
