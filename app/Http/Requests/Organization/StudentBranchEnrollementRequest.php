<?php

namespace App\Http\Requests\Organization;

use App\Models\BranchStudent;
use App\Models\Classroom;
use App\Models\Curriculum;
use App\Models\Level;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StudentBranchEnrollementRequest extends FormRequest
{
    private ?BranchStudent $enrollmentBranchStudent = null;

    private bool $enrollmentBranchStudentResolved = false;

    public function enrollmentBranchStudent(): ?BranchStudent
    {
        if ($this->enrollmentBranchStudentResolved) {

            return $this->enrollmentBranchStudent;

        }//end of if

        $this->enrollmentBranchStudentResolved = true;

        /** @var User|null $student */
        $student = $this->route('student');
        $branchId = session('selected_branch')['id'] ?? null;

        if ($student === null || $branchId === null) {

            $this->enrollmentBranchStudent = null;

            return null;

        }//end of if

        $raw = $this->query('project_id');

        if ($raw === null || $raw === '') {

            $projectId = null;

        } else {

            $projectId = (int) $raw;

        }//end of else

        $query = BranchStudent::query()
            ->where('student_id', $student->id)
            ->where('branch_id', (int) $branchId);

        if ($projectId === null) {

            $query->whereNull('project_id');

        } else {

            $query->where('project_id', $projectId);

        }//end of else

        $this->enrollmentBranchStudent = $query->first();

        return $this->enrollmentBranchStudent;

    }// end of enrollmentBranchStudent

    public function authorize(): bool
    {
        /** @var User|null $student */
        $student = $this->route('student');
        $branchStudent = $this->enrollmentBranchStudent();

        if ($student === null || $branchStudent === null) {

            return false;

        }//end of if

        return Gate::allows('organization-student', $student)
            && $branchStudent->student_id === $student->id;

    }// end of authorize

    public function rules(): array
    {
        $organizationId = session('selected_organization')['id'] ?? null;

        /** @var User $student */
        $student = $this->route('student');
        $branchStudent = $this->enrollmentBranchStudent();
        $branchId = session('selected_branch')['id'] ?? null;

        return [
            'curriculum_id' => [
                'required',
                'exists:curricula,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId && $value) {
                        $curriculum = Curriculum::find($value);
                        if ($curriculum && $curriculum->organization_id != $organizationId) {
                            $fail(__('validation.curriculum_not_belongs_to_organization'));
                        }
                    }
                },
            ],
            'project_id' => [
                'required',
                'exists:projects,id',
                function ($attribute, $value, $fail) use ($organizationId, $student, $branchId, $branchStudent) {
                    if (! $value) {

                        return;

                    }//end of if

                    $project = Project::find($value);
                    if (! $project) {

                        return;

                    }//end of if

                    if ($organizationId && $project->organization_id != $organizationId) {
                        $fail(__('validation.project_not_belongs_to_organization'));

                        return;

                    }//end of if

                    $curriculumId = $this->curriculum_id;
                    if ($curriculumId && $project->curriculum_id != $curriculumId) {
                        $fail(__('projects.project_not_belongs_to_curriculum'));

                        return;

                    }//end of if

                    if ($branchId && $student) {
                        $duplicateProject = BranchStudent::query()
                            ->where('student_id', $student->id)
                            ->where('branch_id', $branchId)
                            ->where('project_id', $value)
                            ->where('id', '!=', $branchStudent->id)
                            ->exists();

                        if ($duplicateProject) {
                            $fail(__('students.already_enrolled_in_project'));
                        }
                    }
                },
            ],
            'level_id' => [
                'required',
                'exists:levels,id',
                function ($attribute, $value, $fail) use ($organizationId) {
                    if ($organizationId && $value) {
                        $level = Level::find($value);
                        if ($level && $level->organization_id != $organizationId) {
                            $fail(__('validation.level_not_belongs_to_organization'));
                        }
                        if ($this->project_id && $level && $level->project_id != $this->project_id) {
                            $fail(__('validation.level_not_belongs_to_project'));
                        }
                    }
                },
            ],
            'page_number' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value && $this->level_id) {
                        $level = Level::find($this->level_id);
                        if ($level) {
                            if ($value < $level->from_page || $value > $level->to_page) {
                                $fail(__('levels.page_number_out_of_range', [
                                    'from_page' => $level->from_page,
                                    'to_page' => $level->to_page,
                                ]));
                            }
                        }
                    }
                },
            ],
            'classroom_id' => [
                'nullable',
                'exists:classrooms,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $selectedBranch = session('selected_branch');
                        $classroom = Classroom::find($value);
                        if ($classroom && $selectedBranch && isset($selectedBranch['id']) && $classroom->branch_id != $selectedBranch['id']) {
                            $fail(__('validation.classroom_not_belongs_to_branch'));
                        }
                    }
                },
                function ($attribute, $value, $fail) use ($student, $branchId, $branchStudent) {
                    if (! $value || ! $branchId || ! $student) {

                        return;

                    }//end of if

                    $duplicateClassroom = BranchStudent::query()
                        ->where('student_id', $student->id)
                        ->where('branch_id', $branchId)
                        ->where('classroom_id', $value)
                        ->where('id', '!=', $branchStudent->id)
                        ->exists();

                    if ($duplicateClassroom) {
                        $fail(__('students.branch_classroom_enrollment_exists'));
                    }
                },
            ],
            'exempted_from_fees' => 'boolean',
            'currency_id' => array_merge(
                [
                    'nullable',
                    Rule::requiredIf(fn () => ! $this->exempted_from_fees),
                ],
                $organizationId
                    ? [Rule::exists('currencies', 'id')->where('organization_id', $organizationId)]
                    : ['exists:currencies,id']
            ),
            'fees' => [
                Rule::requiredIf(fn () => ! $this->exempted_from_fees),
                'numeric',
                'min:0',
            ],
        ];

    }// end of rules

    public function attributes(): array
    {
        return [
            'curriculum_id' => __('curricula.curriculum'),
            'project_id' => __('projects.project'),
            'level_id' => __('levels.level'),
            'page_number' => __('levels.page_number'),
            'classroom_id' => __('classrooms.classroom'),
            'exempted_from_fees' => __('students.exempted_from_fees'),
            'currency_id' => __('currencies.currency'),
            'fees' => __('subscription_types.fees'),
        ];

    }// end of attributes

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! (session('selected_branch')['id'] ?? null)) {
                $validator->errors()->add('project_id', __('students.branch_not_selected'));
            }
        });

    }// end of withValidator

    protected function prepareForValidation(): void
    {
        $exempted = $this->exempted_from_fees ?? false;

        $this->merge([
            'exempted_from_fees' => $exempted,
            'currency_id' => $exempted ? null : $this->currency_id,
            'fees' => $exempted ? 0 : $this->fees,
        ]);

    }// end of prepareForValidation

}//end of request
