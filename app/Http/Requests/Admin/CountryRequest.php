<?php

namespace App\Http\Requests\Admin;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            '%name%' => ['required', Rule::unique('country_translations', 'name')],
            'code' => 'required|unique:countries,code',
            'mobile_digits' => 'required|integer',
            'flag' => 'required|image:allow_svg|max:2048',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $country = $this->route()->parameter('country');

            $rules['%name%'] = [
                'required',
                Rule::unique('country_translations', 'name')
                    ->ignore($country->id, 'country_id')
            ];

            $rules['code'] = [
                'required',
                Rule::unique('countries', 'code')
                    ->ignore($country->id, 'id')
            ];

            $rules['flag'] = ['sometimes', 'nullable'];

        }//end of if

        return RuleFactory::make($rules);

    }//end of rules

}//end of request
