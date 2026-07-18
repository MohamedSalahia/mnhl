<?php

namespace App\Http\Requests\Organization;

use App\Models\AssessmentScheme;
use App\Models\Curriculum;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LevelRequest extends FormRequest
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

        $projectId = $this->project_id ?? null;

        $rules = [
            'organization_id' => 'required|exists:organizations,id',
            'project_id' => [
                'required',
                'exists:projects,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId) {
                        $project = \App\Models\Project::find($value);
                        if ($project && $project->organization_id != $organizationId) {
                            $fail(__('validation.project_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'assessment_scheme_id' => [
                'required',
                'exists:assessment_schemes,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId) {
                        $assessmentScheme = AssessmentScheme::find($value);
                        if ($assessmentScheme && $assessmentScheme->organization_id != $organizationId) {
                            $fail(__('validation.assessment_scheme_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('levels')->where(function ($query) use ($projectId) {
                    return $query->where('project_id', $projectId);
                }),
            ],
            'from_page' => ['required', 'integer', 'min:1', 'lt:to_page'],
            'to_page' => [
                'required', 'integer', 'min:1', 'gt:from_page',
                function ($attribute, $value, $fail) use ($projectId) {

                    if ($projectId) {
                        $project = Project::with('curriculum')->find($projectId);

                        if ($project && $project->curriculum && $project->curriculum->book_number_of_pages) {

                            if ($value > $project->curriculum->book_number_of_pages) {

                                $fail(__('levels.to_page_exceeds_curriculum_pages', [
                                    'curriculum' => $project->curriculum->name,
                                    'max_pages' => $project->curriculum->book_number_of_pages
                                ]));
                            }
                        }
                    }
                },
            ],
            'min_passing_score' => 'required|integer|min:0|max:1000',
            'max_score' => 'required|integer|min:0|max:1000|gte:min_passing_score',
            'additional_curricula' => 'nullable|array',
            'additional_curricula.*.curriculum_id' => [
                'nullable',
                'exists:curricula,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId && $value) {
                        $curriculum = Curriculum::find($value);
                        if ($curriculum && $curriculum->organization_id != $organizationId) {
                            $fail(__('curricula.curriculum_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'additional_curricula.*.from_page' => 'nullable|integer|min:1',
            'additional_curricula.*.to_page' => [
                'nullable',
                'integer',
                'min:1',
                'gt:additional_curricula.*.from_page',
                function ($attribute, $value, $fail) {
                    // Extract the index from the attribute (e.g., "additional_curricula.0.to_page" -> 0)
                    preg_match('/additional_curricula\.(\d+)\.to_page/', $attribute, $matches);
                    $index = $matches[1] ?? null;

                    if ($index !== null && $this->has("additional_curricula.{$index}.curriculum_id")) {
                        $curriculumId = $this->input("additional_curricula.{$index}.curriculum_id");

                        if ($curriculumId) {
                            $curriculum = Curriculum::find($curriculumId);

                            if ($curriculum && $curriculum->book_number_of_pages) {
                                if ($value > $curriculum->book_number_of_pages) {
                                    $fail(__('curricula.to_page_exceeds_curriculum_pages', [
                                        'curriculum' => $curriculum->name,
                                        'max_pages' => $curriculum->book_number_of_pages
                                    ]));
                                }
                            }
                        }
                    }
                },
            ],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $level = $this->route()->parameter('level');
            $projectId = $this->project_id ?? $level->project_id ?? null;

            $rules['name'] = [
                'required', 'string', 'max:255',
                Rule::unique('levels')->ignore($level->id)->where(function ($query) use ($projectId) {
                    return $query->where('project_id', $projectId);
                }),
            ];

        }//end of if

        return $rules;

    }//end of rules

    public function attributes()
    {
        return [
            'name' => __('levels.name'),
            'from_page' => __('levels.from_page'),
            'to_page' => __('levels.to_page'),
            'project_id' => __('projects.project'),
            'assessment_scheme_id' => __('levels.assessment_scheme'),
            'min_passing_score' => __('levels.min_passing_score'),
            'max_score' => __('levels.max_score'),
            'additional_curricula.*.curriculum_id' => __('curricula.curriculum'),
            'additional_curricula.*.from_page' => __('levels.from_page'),
            'additional_curricula.*.to_page' => __('levels.to_page'),
        ];

    }// end of attributes

    public function prepareForValidation()
    {
        $level = $this->route()->parameter('level');

        $this->merge([
            'organization_id' => session('selected_organization')['id'],
            'project_id' => $level ? $level->project_id : $this->project_id,
        ]);

    }// end of prepareForValidation

}//end of request

