<?php

namespace App\Http\Requests\Admin;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GovernorateRequest extends FormRequest
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
        $rules = [
            'country_id' => 'required|exists:countries,id',
            '%name%' => ['required', Rule::unique('governorate_translations', 'name')],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $governorate = $this->route()->parameter('governorate');

            $rules['%name%'] = [
                'required',
                Rule::unique('governorate_translations', 'name')
                    ->ignore($governorate->id, 'governorate_id')
            ];

        }//end of if

        return RuleFactory::make($rules);

    }//end of rules

}//end of request
