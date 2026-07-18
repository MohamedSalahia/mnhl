<?php

namespace App\Http\Controllers\Organization;

use App\Enums\BranchStudentStatusEnum;
use App\Enums\CurriculumTypeEnum;
use App\Enums\OrganizationStudentStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\StudentBranchEnrollementRequest;
use App\Http\Requests\Organization\StudentExtraProjectEnrollmentRequest;
use App\Http\Requests\Organization\StudentAcceptEnrollmentRequest;
use App\Http\Requests\Organization\StudentRequest;
use App\Models\Branch;
use App\Models\BranchStudent;
use App\Models\Classroom;
use App\Models\Currency;
use App\Models\Curriculum;
use App\Models\Installment;
use App\Models\Project;
use App\Models\SubscriptionType;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class StudentController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_students', only: ['index', 'data', 'show', 'details', 'lessons', 'installments', 'confirmAcceptEnrollment', 'confirmRejectEnrollment', 'createExtraProjectEnrollment']),
            new Middleware('permission_with_team:create_students', only: ['create', 'store']),
            new Middleware('permission_with_team:update_students', only: ['edit', 'update', 'acceptBranchEnrollment', 'rejectBranchEnrollment', 'toggleBranchStudentStatus', 'storeExtraProjectEnrollment', 'editBranchEnrollment', 'updateBranchEnrollment']),
            new Middleware('permission_with_team:delete_students', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middlewares

    public function index()
    {
        return view('organization.students.index');

    }// end of index

    public function data()
    {
        $students = User::query()
            ->whereHasRole(UserTypeEnum::STUDENT)
            ->whenStudentOrganizationId(session('selected_organization')['id'] ?? null)
            ->whenStudentBranchId(session('selected_branch')['id'] ?? null)
            ->whenStudentClassroomId(request()->classroom_id);

        return DataTables::of($students)
            ->addColumn('record_select', 'organization.students.data_table.record_select')
            ->editColumn('gender', function (User $student) {
                return $student->gender == 'male' ? __('users.male') : __('users.female');
            })
            ->editColumn('mobile', 'organization.students.data_table.mobile')
            ->editColumn('created_at', function (User $student) {
                return $student->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.students.data_table.actions')
            ->rawColumns(['record_select', 'mobile', 'actions'])
            ->toJson();

    }// end of data

    public function show(User $student)
    {
        $selectedOrganization = session('selected_organization');

        // Check if student belongs to organization and get status
        $organizationStudent = $student->studentOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        $isPending = $organizationStudent && $organizationStudent->pivot->status === OrganizationStudentStatusEnum::PENDING;

        $academicContext = $this->branchAcademicSectionContext($student);

        return view('organization.students.show', array_merge(
            compact('student', 'isPending', 'organizationStudent'),
            $academicContext
        ));

    }// end of show

    public function details(User $student)
    {
        $selectedOrganization = session('selected_organization');

        // Check if student belongs to organization and get status
        $organizationStudent = $student->studentOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        $isPending = $organizationStudent && $organizationStudent->pivot->status === OrganizationStudentStatusEnum::PENDING;

        $academicContext = $this->branchAcademicSectionContext($student);

        return view('organization.students._details', array_merge(
            compact('student', 'isPending', 'organizationStudent'),
            $academicContext
        ));

    }// end of details

    public function lessons(User $student)
    {
        $selectedOrganization = session('selected_organization');
        $selectedBranch = session('selected_branch');

        // Check if student belongs to organization and get status
        $organizationStudent = $student->studentOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        $isPending = $organizationStudent && $organizationStudent->pivot->status === OrganizationStudentStatusEnum::PENDING;

        // Load student lessons with relationships, filtered by branch if selected
        $studentLessons = $student->studentLessons()
            ->with(['lesson.classroom', 'lesson.branch', 'lesson.teacher', 'lesson.lessonEvaluationItems.evaluationItem'])
            ->when($selectedBranch && isset($selectedBranch['id']), function ($query) use ($selectedBranch) {
                return $query->whereHas('lesson', function ($q) use ($selectedBranch) {
                    $q->where('branch_id', $selectedBranch['id']);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('organization.students._lessons', compact('student', 'isPending', 'organizationStudent', 'studentLessons'));

    }// end of lessons

    public function installments(User $student)
    {
        $selectedOrganization = session('selected_organization');
        $selectedBranch = session('selected_branch');

        $organizationStudent = $student->studentOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        $isPending = $organizationStudent && $organizationStudent->pivot->status === OrganizationStudentStatusEnum::PENDING;

        $branchStudents = collect();

        if ($selectedBranch && isset($selectedBranch['id'])) {

            $branchStudents = BranchStudent::query()
                ->with(['project', 'currency'])
                ->whenStudentId($student->id)
                ->whenBranchId($selectedBranch['id'])
                ->get();

        }//end of if

        $branchStudentsByProject = $branchStudents->groupBy('project_id');

        $installmentsByProjectId = [];

        if ($selectedBranch && isset($selectedBranch['id'])) {

            foreach ($branchStudentsByProject as $projectId => $_rows) {

                if ($projectId === null || $projectId === '') {

                    $installmentsByProjectId[$projectId] = collect();

                    continue;

                }//end of if

                $installmentsByProjectId[$projectId] = Installment::query()
                    ->with(['project', 'paymentMethod'])
                    ->whenStudentId($student->id)
                    ->whenBranchId($selectedBranch['id'])
                    ->whenProjectId($projectId)
                    ->orderByDesc('created_at')
                    ->get();

            }//end of foreach

        }//end of if

        return view('organization.students._installments', compact(
            'student',
            'isPending',
            'organizationStudent',
            'branchStudentsByProject',
            'installmentsByProjectId',
        ));

    }// end of installments

    public function confirmAcceptEnrollment(User $student)
    {
        $selectedOrganization = session('selected_organization');
        $selectedBranch = session('selected_branch');

        // Verify student is pending
        $organizationStudent = $student->studentOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        if (!$organizationStudent || $organizationStudent->pivot->status !== OrganizationStudentStatusEnum::PENDING) {
            abort(403, __('students.enrollment_not_pending'));
        }

        $curricula = Curriculum::query()
            ->whenOrganizationId($selectedOrganization['id'] ?? null)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        $classrooms = Classroom::query()
            ->whenBranchId($selectedBranch['id'] ?? null)
            ->get();

        $organizationId = $selectedOrganization['id'] ?? null;

        $subscriptionTypes = $organizationId
            ? SubscriptionType::query()
                ->whenOrganizationId($organizationId)
                ->whenYear(now()->year)
                ->orderBy('name')
                ->get()
            : collect();

        $currencies = $organizationId
            ? Currency::query()
                ->whenOrganizationId($organizationId)
                ->orderBy('id')
                ->get()
            : collect();

        return response()->json([
            'view' => view('organization.students.branch_enrollment._confirm_accept', compact(
                'student',
                'curricula',
                'classrooms',
                'subscriptionTypes',
                'currencies',
            ))->render(),
        ]);

    }// end of confirmAcceptEnrollment

    public function confirmRejectEnrollment(User $student)
    {
        $selectedOrganization = session('selected_organization');

        // Verify student is pending
        $organizationStudent = $student->studentOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        if (!$organizationStudent || $organizationStudent->pivot->status !== OrganizationStudentStatusEnum::PENDING) {
            abort(403, __('students.enrollment_not_pending'));
        }

        return response()->json([
            'view' => view('organization.students.branch_enrollment._confirm_reject', compact('student'))->render(),
        ]);

    }// end of confirmRejectEnrollment

    public function acceptBranchEnrollment(StudentAcceptEnrollmentRequest $request, User $student)
    {
        $selectedBranch = session('selected_branch');

        $selectedOrganization = session('selected_organization');

        // Verify student is pending
        $organizationStudent = $student->studentOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        if (!$organizationStudent || $organizationStudent->pivot->status !== OrganizationStudentStatusEnum::PENDING) {
            abort(403, __('students.enrollment_not_pending'));
        }

        // Update organization_student status to ACTIVE
        $student->studentOrganizations()->updateExistingPivot(
            $selectedOrganization['id'],
            ['status' => OrganizationStudentStatusEnum::ACTIVE]
        );

        // Assign student number if not already set
        if (! $student->student_number) {
            $yearMonth = now()->format('Ym');
            $count = User::query()
                ->where('type', UserTypeEnum::STUDENT)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();
            $student->update(['student_number' => $yearMonth . str_pad($count, 4, '0', STR_PAD_LEFT)]);
        }

        // Branch enrollment for selected branch (replaces other branches; one line per branch from form)
        if ($selectedBranch && isset($selectedBranch['id'])) {

            $exempted = $request->boolean('exempted_from_fees');
            $feesAmount = $exempted ? 0.0 : (float) $request->fees;

            $pivotData = [
                'curriculum_id' => $request->curriculum_id,
                'project_id' => $request->project_id,
                'level_id' => $request->level_id,
                'page_number' => $request->page_number,
                'classroom_id' => $request->classroom_id,
                'exempted_from_fees' => $exempted,
                'currency_id' => $exempted ? null : $request->currency_id,
                'fees' => $feesAmount,
            ];

            if ($exempted) {

                $pivotData['paid_fees'] = 0.0;
                $pivotData['remaining_fees'] = 0.0;

            }//end of if

            $this->replaceStudentBranchEnrollment(
                $student,
                $selectedBranch['id'],
                array_filter($pivotData, fn ($value) => ! is_null($value))
            );
        }

        // Sync student role with branch team_id
        if ($selectedBranch && isset($selectedBranch['team_id'])) {
            $student->syncRoles([UserTypeEnum::STUDENT], $selectedBranch['team_id']);
        }

        session()->flash('success', __('students.enrollment_accepted_successfully'));

        return response()->json([
            'redirect_to' => route('organization.students.index'),
        ]);

    }// end of acceptBranchEnrollment

    public function rejectBranchEnrollment(User $student)
    {
        $selectedOrganization = session('selected_organization');

        // Verify student is pending
        $organizationStudent = $student->studentOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        if (!$organizationStudent || $organizationStudent->pivot->status !== OrganizationStudentStatusEnum::PENDING) {
            abort(403, __('students.enrollment_not_pending'));
        }

        // Update organization_student status to INACTIVE
        $student->studentOrganizations()->updateExistingPivot(
            $selectedOrganization['id'],
            ['status' => OrganizationStudentStatusEnum::INACTIVE]
        );

        session()->flash('success', __('students.enrollment_rejected_successfully'));

        return response()->json([
            'redirect_to' => route('organization.students.index'),
        ]);

    }// end of rejectBranchEnrollment

    public function create()
    {

        $selectedBranch = session('selected_branch');

        $curricula = Curriculum::query()
            ->whenOrganizationId(session('selected_organization')['id'])
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        $classrooms = Classroom::query()
            ->whenBranchId($selectedBranch['id'] ?? null)
            ->get();

        return view('organization.students.create', compact('curricula', 'classrooms'));

    }// end of create

    public function createExtraProjectEnrollment(User $student)
    {
        Gate::authorize('organization-student', $student);

        $selectedBranch = session('selected_branch');
        $selectedOrganization = session('selected_organization');

        if (!$selectedBranch || !isset($selectedBranch['id'])) {

            return response()->json([
                'view' => '<div class="alert alert-warning mb-0">'.e(__('students.branch_not_selected')).'</div>',
            ]);

        }//end of if

        $organizationId = $selectedOrganization['id'] ?? null;

        $curricula = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        $classrooms = Classroom::query()
            ->whenBranchId($selectedBranch['id'])
            ->get();

        $subscriptionTypes = $organizationId
            ? SubscriptionType::query()
                ->whenOrganizationId($organizationId)
                ->whenYear(now()->year)
                ->orderBy('name')
                ->get()
            : collect();

        $currencies = $organizationId
            ? Currency::query()
                ->whenOrganizationId($organizationId)
                ->orderBy('id')
                ->get()
            : collect();

        return response()->json([
            'view' => view('organization.students.extra_project_enrollment_form', compact(
                'student',
                'curricula',
                'classrooms',
                'subscriptionTypes',
                'currencies',
            ))->render(),
        ]);

    }// end of createExtraProjectEnrollment

    public function storeExtraProjectEnrollment(StudentExtraProjectEnrollmentRequest $request, User $student)
    {
        $selectedBranch = session('selected_branch');

        if (!$selectedBranch || !isset($selectedBranch['id'])) {

            return response()->json([
                'message' => __('students.branch_not_selected'),
                'errors' => [
                    'project_id' => [__('students.branch_not_selected')],
                ],
            ], 422);

        }//end of if

        $exempted = $request->boolean('exempted_from_fees');
        $feesAmount = $exempted ? 0.0 : (float) $request->fees;

        $pivotData = [
            'curriculum_id' => $request->curriculum_id,
            'project_id' => $request->project_id,
            'level_id' => $request->level_id,
            'page_number' => $request->page_number,
            'classroom_id' => $request->classroom_id,
            'exempted_from_fees' => $exempted,
            'currency_id' => $exempted ? null : $request->currency_id,
            'fees' => $feesAmount,
        ];

        if ($exempted) {

            $pivotData['paid_fees'] = 0.0;
            $pivotData['remaining_fees'] = 0.0;

        }//end of if

        $this->addStudentBranchEnrollment(
            $student,
            (int) $selectedBranch['id'],
            array_filter($pivotData, fn ($value) => ! is_null($value))
        );

        session()->flash('success', __('students.extra_project_enrollment_success'));

        return response()->json([
            'redirect_to' => route('organization.students.show', $student->hash_id),
            'refresh' => true,
        ]);

    }// end of storeExtraProjectEnrollment

    public function editBranchEnrollment(User $student)
    {
        Gate::authorize('organization-student', $student);

        $selectedBranch = session('selected_branch');
        $selectedOrganization = session('selected_organization');

        if (!$selectedBranch || ! isset($selectedBranch['id'])) {

            return response()->json([
                'view' => '<div class="alert alert-warning mb-0">'.e(__('students.branch_not_selected')).'</div>',
            ]);

        }//end of if

        $branchStudent = $this->resolveBranchStudentForEnrollmentQuery($student);

        if (! $branchStudent) {

            abort(404);

        }//end of if

        if (! $this->branchEnrollmentAllowedForEditing($branchStudent, $student->id, (int) $selectedBranch['id'])) {

            abort(403, __('students.branch_enrollment_cannot_edit_with_installments'));

        }//end of if

        $branchStudent->load(['curriculum', 'project', 'level', 'classroom', 'currency']);

        $organizationId = $selectedOrganization['id'] ?? null;

        $curricula = Curriculum::query()
            ->whenOrganizationId($organizationId)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        $classrooms = Classroom::query()
            ->whenBranchId($selectedBranch['id'])
            ->get();

        $subscriptionTypes = $organizationId
            ? SubscriptionType::query()
                ->whenOrganizationId($organizationId)
                ->whenYear(now()->year)
                ->orderBy('name')
                ->get()
            : collect();

        $currencies = $organizationId
            ? Currency::query()
                ->whenOrganizationId($organizationId)
                ->orderBy('id')
                ->get()
            : collect();

        $projects = collect();
        $levels = collect();

        if ($branchStudent->curriculum_id) {

            $curriculum = Curriculum::find($branchStudent->curriculum_id);

            if ($curriculum) {

                $projects = $curriculum->projects;

            }//end of if

        }//end of if

        if ($branchStudent->project_id) {

            $project = Project::find($branchStudent->project_id);

            if ($project) {

                $levels = $project->levels()->orderBy('order')->get();

            }//end of if

        }//end of if

        return response()->json([
            'view' => view('organization.students.branch_enrollment._edit', compact(
                'student',
                'branchStudent',
                'curricula',
                'classrooms',
                'subscriptionTypes',
                'currencies',
                'projects',
                'levels',
            ))->render(),
        ]);

    }// end of editBranchEnrollment

    public function updateBranchEnrollment(StudentBranchEnrollementRequest $request, User $student)
    {
        Gate::authorize('organization-student', $student);

        $selectedBranch = session('selected_branch');

        if (!$selectedBranch || ! isset($selectedBranch['id'])) {

            return response()->json([
                'message' => __('students.branch_not_selected'),
                'errors' => [
                    'project_id' => [__('students.branch_not_selected')],
                ],
            ], 422);

        }//end of if

        $branchStudent = $request->enrollmentBranchStudent();

        if (! $branchStudent) {

            abort(404);

        }//end of if

        if (! $this->branchEnrollmentAllowedForEditing($branchStudent, $student->id, (int) $selectedBranch['id'])) {

            abort(403, __('students.branch_enrollment_cannot_edit_with_installments'));

        }//end of if

        $exempted = $request->exempted_from_fees ?? false;
        $feesAmount = $exempted ? 0.0 : (float) $request->fees;

        $pivotData = [
            'curriculum_id' => $request->curriculum_id,
            'project_id' => $request->project_id,
            'level_id' => $request->level_id,
            'page_number' => $request->page_number,
            'classroom_id' => $request->classroom_id,
            'exempted_from_fees' => $exempted,
            'currency_id' => $exempted ? null : $request->currency_id,
            'fees' => $feesAmount,
        ];

        if ($exempted) {

            $pivotData['paid_fees'] = 0.0;
            $pivotData['remaining_fees'] = 0.0;

        }//end of if

        $branchStudent->update($pivotData);

        if ($branchStudent->project_id) {

            Installment::syncBranchStudentFeesForContext(
                $student->id,
                (int) $selectedBranch['id'],
                (int) $branchStudent->project_id
            );

        }//end of if

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.students.show', $student->hash_id),
            'refresh' => true,
        ]);

    }// end of updateBranchEnrollment

    public function store(StudentRequest $request)
    {
        $selectedBranch = session('selected_branch');

        $selectedOrganization = session('selected_organization');

        $requestData = $request->validated();

        if ($request->file('image')) {
            $requestData['image'] = $request->file('image')->hashName();
            $request->file('image')->store('uploads', 'public');
        }

        if ($request->file('identity_document_file')) {
            $requestData['identity_document_file'] = $request->file('identity_document_file')->hashName();
            $request->file('identity_document_file')->store('uploads', 'public');
        }

        $student = User::create($requestData);

        // Generate sequential student number in format YYYYMMNNNN
        $yearMonth = now()->format('Ym');
        $count = User::query()
            ->where('type', UserTypeEnum::STUDENT)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $student->student_number = $yearMonth . str_pad($count, 4, '0', STR_PAD_LEFT);
        $student->save();

        if ($selectedOrganization && isset($selectedOrganization['id'])) {

            $student->studentOrganizations()->sync([
                $selectedOrganization['id'] => ['status' => OrganizationStudentStatusEnum::ACTIVE]
            ]);

        }

        // Branch enrollment for selected branch (replaces other branches; one line per branch from form)
        if ($selectedBranch && isset($selectedBranch['id'])) {

            $exempted = $request->boolean('exempted_from_fees');
            $feesAmount = $exempted ? 0.0 : (float)$request->fees;

            $pivotData = [
                'curriculum_id' => $request->curriculum_id,
                'project_id' => $request->project_id,
                'level_id' => $request->level_id,
                'page_number' => $request->page_number,
                'classroom_id' => $request->classroom_id,
                'exempted_from_fees' => $exempted,
                'currency_id' => $exempted ? null : $request->currency_id,
                'fees' => $feesAmount,
            ];

            if ($exempted) {

                $pivotData['paid_fees'] = 0.0;
                $pivotData['remaining_fees'] = 0.0;

            }//end of if

            $this->replaceStudentBranchEnrollment(
                $student,
                $selectedBranch['id'],
                array_filter($pivotData, fn($value) => !is_null($value))
            );

        }

        $student->syncRoles([UserTypeEnum::STUDENT], $selectedBranch['team_id']);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.students.index'),
        ]);

    }// end of store

    public function edit(User $student)
    {
        Gate::authorize('organization-student', $student);

        $selectedBranch = session('selected_branch');
        $selectedOrganization = session('selected_organization');

        $curricula = Curriculum::query()
            ->whenOrganizationId($selectedOrganization['id'] ?? null)
            ->whenType(CurriculumTypeEnum::MAIN)
            ->get();

        $classrooms = Classroom::query()
            ->whenBranchId($selectedBranch['id'] ?? null)
            ->get();

        // Get current pivot data for the selected branch
        $currentPivot = null;
        $projects = collect();
        $levels = collect();

        if ($selectedBranch && isset($selectedBranch['id'])) {
            $branchStudent = $student->branchStudents()
                ->where('branch_id', $selectedBranch['id'])
                ->first();

            if ($branchStudent) {
                $currentPivot = $branchStudent;

                // Fetch projects if curriculum is selected
                if ($currentPivot && $currentPivot->curriculum_id) {
                    $curriculum = Curriculum::find($currentPivot->curriculum_id);
                    if ($curriculum) {
                        $projects = $curriculum->projects;
                    }
                }

                // Fetch levels if project is selected
                if ($currentPivot && $currentPivot->project_id) {
                    $project = \App\Models\Project::find($currentPivot->project_id);
                    if ($project) {
                        $levels = $project->levels()->orderBy('order')->get();
                    }
                }
            }
        }

        return view('organization.students.edit', compact('student', 'curricula', 'classrooms', 'currentPivot', 'projects', 'levels'));

    }// end of edit

    public function update(StudentRequest $request, User $student)
    {
        Gate::authorize('organization-student', $student);

        $selectedBranch = session('selected_branch');

        $selectedOrganization = session('selected_organization');

        $requestData = $request->validated();

        if ($request->file('image')) {
            if ($student->image) {
                Storage::disk('public')->delete('uploads/' . $student->image);
            }
            $requestData['image'] = $request->file('image')->hashName();
            $request->file('image')->store('uploads', 'public');
        }

        if ($request->file('identity_document_file')) {
            Storage::disk('public')->delete('uploads/' . $student->identity_document_file);
            $requestData['identity_document_file'] = $request->file('identity_document_file')->hashName();
            $request->file('identity_document_file')->store('uploads', 'public');
        }

        // Update student
        $student->update($requestData);

        if ($selectedOrganization && isset($selectedOrganization['id'])) {
            $student->studentOrganizations()->syncWithoutDetaching([$selectedOrganization['id']]);
        }

        if ($selectedBranch && isset($selectedBranch['id'])) {

            $exempted = $request->boolean('exempted_from_fees');
            $feesAmount = $exempted ? 0.0 : (float)$request->fees;

            $pivotData = [
                'curriculum_id' => $request->curriculum_id,
                'project_id' => $request->project_id,
                'level_id' => $request->level_id,
                'page_number' => $request->page_number,
                'classroom_id' => $request->classroom_id,
                'exempted_from_fees' => $exempted,
                'currency_id' => $exempted ? null : $request->currency_id,
                'fees' => $feesAmount,
            ];

            if ($exempted) {

                $pivotData['paid_fees'] = 0.0;
                $pivotData['remaining_fees'] = 0.0;

            }//end of if

            $this->updateStudentBranchEnrollment(
                $student,
                $selectedBranch['id'],
                array_filter($pivotData, fn($value) => !is_null($value))
            );

        }

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.students.index'),
        ]);

    }// end of update

    public function destroy(User $student)
    {
        Gate::authorize('organization-student', $student);

        $this->delete($student);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $selectedBranch = session('selected_branch');

        foreach (json_decode(request()->record_ids) as $recordId) {
            $student = User::findOrFail($recordId);

            // Verify student belongs to the branch
            if ($student->hasRole(UserTypeEnum::STUDENT, $selectedBranch['team_id'])) {
                $this->delete($student);
            }
        }

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(User $student)
    {
        $student->delete();

    }// end of delete

    /**
     * Replace all branch_student rows for other branches, then set a single enrollment line for $branchId.
     */
    private function replaceStudentBranchEnrollment(User $student, int $branchId, array $data): void
    {
        BranchStudent::query()
            ->where('student_id', $student->id)
            ->where('branch_id', '!=', $branchId)
            ->delete();

        BranchStudent::query()
            ->where('student_id', $student->id)
            ->where('branch_id', $branchId)
            ->delete();

        BranchStudent::create(array_merge([
            'student_id' => $student->id,
            'branch_id' => $branchId,
        ], $data));

        if (isset($data['project_id']) && $data['project_id'] !== null && $data['project_id'] !== '') {

            Installment::syncBranchStudentFeesForContext(
                $student->id,
                $branchId,
                (int)$data['project_id']
            );

        }//end of if

    }// end of replaceStudentBranchEnrollment

    /**
     * Replace all branch_student rows for $branchId with one row from $data (other branches unchanged).
     */
    private function updateStudentBranchEnrollment(User $student, int $branchId, array $data): void
    {
        BranchStudent::query()
            ->where('student_id', $student->id)
            ->where('branch_id', $branchId)
            ->delete();

        BranchStudent::create(array_merge([
            'student_id' => $student->id,
            'branch_id' => $branchId,
        ], $data));

        if (isset($data['project_id']) && $data['project_id'] !== null && $data['project_id'] !== '') {

            Installment::syncBranchStudentFeesForContext(
                $student->id,
                $branchId,
                (int)$data['project_id']
            );

        }//end of if

    }// end of updateStudentBranchEnrollment

    /**
     * @return array{branchStudents: \Illuminate\Support\Collection, latestBranchStudent: ?BranchStudent, branchStudentAcademicEditable: array<int, bool>}
     */
    private function branchAcademicSectionContext(User $student): array
    {
        $selectedBranch = session('selected_branch');
        $branchStudents = collect();
        $latestBranchStudent = null;
        $branchStudentAcademicEditable = [];

        if (! $selectedBranch || ! isset($selectedBranch['id'])) {

            return compact('branchStudents', 'latestBranchStudent', 'branchStudentAcademicEditable');

        }//end of if

        $branchId = (int) $selectedBranch['id'];

        $branchStudents = $student->branchStudents()
            ->with(['curriculum', 'project', 'level', 'classroom', 'currency'])
            ->whenBranchId($branchId)
            ->latest('id')
            ->get();

        $latestBranchStudent = $branchStudents->first();

        $projectsWithInstallments = Installment::query()
            ->whenStudentId($student->id)
            ->whenBranchId($branchId)
            ->whereNotNull('project_id')
            ->distinct()
            ->pluck('project_id');

        $hasNullProjectInstallments = Installment::query()
            ->whenStudentId($student->id)
            ->whenBranchId($branchId)
            ->whereNull('project_id')
            ->exists();

        foreach ($branchStudents as $branchStudent) {

            $hasInstallmentsForRow = ($branchStudent->project_id === null && $hasNullProjectInstallments)
                || ($branchStudent->project_id !== null && $projectsWithInstallments->contains($branchStudent->project_id));

            $branchStudentAcademicEditable[$branchStudent->id] = $branchStudent->exempted_from_fees
                || ! $hasInstallmentsForRow;

        }//end of foreach

        return compact('branchStudents', 'latestBranchStudent', 'branchStudentAcademicEditable');

    }// end of branchAcademicSectionContext

    private function resolveBranchStudentForEnrollmentQuery(User $student): ?BranchStudent
    {
        $selectedBranch = session('selected_branch');

        if (! $selectedBranch || ! isset($selectedBranch['id'])) {

            return null;

        }//end of if

        $raw = request()->query('project_id');

        if ($raw === null || $raw === '') {

            $projectId = null;

        } else {

            $projectId = (int) $raw;

        }//end of else

        $query = BranchStudent::query()
            ->where('student_id', $student->id)
            ->where('branch_id', (int) $selectedBranch['id']);

        if ($projectId === null) {

            $query->whereNull('project_id');

        } else {

            $query->where('project_id', $projectId);

        }//end of else

        return $query->first();

    }// end of resolveBranchStudentForEnrollmentQuery

    private function branchEnrollmentAllowedForEditing(BranchStudent $branchStudent, int $studentId, int $branchId): bool
    {
        if ($branchStudent->exempted_from_fees) {

            return true;

        }//end of if

        $query = Installment::query()
            ->whenStudentId($studentId)
            ->whenBranchId($branchId);

        if ($branchStudent->project_id === null) {

            $query->whereNull('project_id');

        } else {

            $query->where('project_id', $branchStudent->project_id);

        }//end of else

        return ! $query->exists();

    }// end of branchEnrollmentAllowedForEditing

    /**
     * Add a branch_student row without removing existing enrollments (multi-project per branch).
     */
    private function addStudentBranchEnrollment(User $student, int $branchId, array $data): void
    {
        BranchStudent::create(array_merge([
            'student_id' => $student->id,
            'branch_id' => $branchId,
        ], $data));

        if (isset($data['project_id']) && $data['project_id'] !== null && $data['project_id'] !== '') {

            Installment::syncBranchStudentFeesForContext(
                $student->id,
                $branchId,
                (int) $data['project_id']
            );

        }//end of if

    }// end of addStudentBranchEnrollment

    public function toggleBranchStudentStatus(User $student, BranchStudent $branchStudent)
    {
        $selectedOrganization = session('selected_organization');
        $selectedBranch = session('selected_branch');

        if (!$selectedBranch || !isset($selectedBranch['id'])) {

            return $this->branchStudentStatusAjaxError(__('students.branch_not_selected'));

        }//end of if

        if ($branchStudent->student_id !== $student->id) {

            return $this->branchStudentStatusAjaxError(__('students.invalid_branch_student_context'));

        }//end of if

        if ($branchStudent->branch_id !== (int)$selectedBranch['id']) {

            return $this->branchStudentStatusAjaxError(__('students.invalid_branch_student_context'));

        }//end of if

        $branchBelongsToOrg = Branch::query()
            ->where('id', $branchStudent->branch_id)
            ->whenOrganizationId($selectedOrganization['id'] ?? null)
            ->exists();

        if (!$branchBelongsToOrg) {

            return $this->branchStudentStatusAjaxError(__('students.invalid_branch_student_context'));

        }//end of if

        $current = $branchStudent->status ?? BranchStudentStatusEnum::ACTIVE;

        $newStatus = $current === BranchStudentStatusEnum::ACTIVE
            ? BranchStudentStatusEnum::INACTIVE
            : BranchStudentStatusEnum::ACTIVE;

        $branchStudent->update(['status' => $newStatus]);

        return response()->json([
            'redirect_to' => route('organization.students.show', $student->hash_id),
            'refresh' => true,
        ]);

    }// end of toggleBranchStudentStatus

    private function branchStudentStatusAjaxError(string $message)
    {
        return response()->json([
            'message' => $message,
            'errors' => [
                'toggle' => [$message],
            ],
        ], 422);

    }// end of branchStudentStatusAjaxError

} //end of controller
