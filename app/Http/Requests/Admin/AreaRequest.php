<?php

namespace App\Http\Requests\Admin;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AreaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'governorate_id' => 'required|exists:governorates,id',
            '%name%' => ['required', Rule::unique('area_translations', 'name')],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $area = $this->route()->parameter('area');

            $rules['%name%'] = [
                'required',
                Rule::unique('area_translations', 'name')
                    ->ignore($area->id, 'area_id')
            ];

        }//end of if

        return RuleFactory::make($rules);

    }//end of rules

}//end of request
