<?php

namespace App\Http\Requests\Admin;

use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NationalityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            '%name%' => ['required', Rule::unique('nationality_translations', 'name')],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $nationality = $this->route()->parameter('nationality');

            $rules['%name%'] = [
                'required',
                Rule::unique('nationality_translations', 'name')
                    ->ignore($nationality->id, 'nationality_id')
            ];

        }//end of if

        return RuleFactory::make($rules);

    }//end of rules

}//end of request

