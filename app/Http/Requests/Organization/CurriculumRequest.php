<?php

namespace App\Http\Requests\Organization;

use App\Enums\CurriculumTypeEnum;
use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;

class CurriculumRequest extends FormRequest
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
        $organizationId = session('selected_organization')['id'] ?? null;

        $rules = [
            'organization_id' => 'required|exists:organizations,id',
            'branch_id' => [
                'required',
                'exists:branches,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId) {
                        $branch = Branch::find($value);
                        if ($branch && $branch->organization_id != $organizationId) {
                            $fail(__('branches.branch_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'name' => 'required|string|max:255',
            'book_name' => 'required|string|max:255',
            'book_file' => 'required|file|mimes:pdf|max:20480',
            'book_number_of_pages' => 'nullable|integer|min:1',
            'curriculum_type' => ['required', 'in:' . implode(',', CurriculumTypeEnum::getConstants())],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            // Book file is optional on update
            $rules['book_file'] = 'nullable|file|mimes:pdf|max:20480';
        }//end of if

        return $rules;

    }//end of rules

    public function prepareForValidation()
    {
        $curriculum = $this->route()->parameter('curriculum');

        $this->merge([
            'organization_id' => session('selected_organization')['id'],
            'branch_id' => session('selected_branch')['id'],
        ]);

    }// end of prepareForValidation

    public function attributes()
    {
        return [
            'name' => __('curricula.name'),
            'branch_id' => __('branches.branch'),
            'book_name' => __('curricula.book_name'),
            'book_file' => __('curricula.book_file'),
            'book_number_of_pages' => __('curricula.book_number_of_pages'),
            'curriculum_type' => __('curricula.curriculum_type'),
        ];

    }//end of attributes

}//end of request

