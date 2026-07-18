<?php

namespace App\Http\Controllers\Organization;

use App\Enums\OrganizationTeacherStatusEnum;
use App\Enums\UserTypeEnum;
use App\Enums\TeacherSalaryCalculationTypeEnum;
use App\Enums\TeacherSalaryTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\TeacherAcceptEnrollmentRequest;
use App\Http\Requests\Organization\TeacherRequest;
use App\Models\Asset;
use App\Models\Currency;
use App\Models\Lesson;
use App\Models\TeacherSalary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class TeacherController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_teachers', only: ['index', 'data', 'show', 'confirmAcceptEnrollment', 'confirmRejectEnrollment']),
            new Middleware('permission_with_team:create_teachers', only: ['create', 'store']),
            new Middleware('permission_with_team:update_teachers', only: ['edit', 'update', 'acceptEnrollment', 'rejectEnrollment', 'toggleExaminer']),
            new Middleware('permission_with_team:delete_teachers', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middlewares

    public function index()
    {
        return view('organization.teachers.index');

    }// end of index

    public function data()
    {
        $selectedBranch = session('selected_branch');

        $selectedOrganization = session('selected_organization');

        $teachers = User::query()
            ->whereHasRole(UserTypeEnum::TEACHER)
            ->whenTeacherOrganizationId($selectedOrganization['id'] ?? null)
            ->whenTeacherBranchId($selectedBranch['id'] ?? null);

        return DataTables::of($teachers)
            ->addColumn('record_select', 'organization.teachers.data_table.record_select')
            ->editColumn('gender', function (User $teacher) {
                return $teacher->gender == 'male' ? __('users.male') : __('users.female');
            })
            ->editColumn('mobile', 'organization.teachers.data_table.mobile')
            ->editColumn('created_at', function (User $teacher) {
                return $teacher->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.teachers.data_table.actions')
            ->rawColumns(['record_select', 'mobile', 'actions'])
            ->toJson();

    }// end of data

    public function show(User $teacher)
    {
        $selectedOrganization = session('selected_organization');
        $selectedBranch = session('selected_branch');

        // Load relationships
        $teacher->load('teacherCertificates', 'nationality');

        // Check if teacher belongs to organization and get status
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        $isPending = $organizationTeacher && $organizationTeacher->pivot->status === OrganizationTeacherStatusEnum::PENDING;

        // Check if teacher has examiner role
        $isExaminer = $selectedBranch && isset($selectedBranch['team_id'])
            ? $teacher->hasRole(UserTypeEnum::EXAMINER, $selectedBranch['team_id'])
            : false;

        return view('organization.teachers.show', compact('teacher', 'isPending', 'organizationTeacher', 'isExaminer'));

    }// end of show

    public function details(User $teacher)
    {
        $selectedOrganization = session('selected_organization');
        $selectedBranch = session('selected_branch');

        // Load relationships
        $teacher->load('teacherCertificates', 'nationality');

        // Check if teacher belongs to organization and get status
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        $isPending = $organizationTeacher && $organizationTeacher->pivot->status === OrganizationTeacherStatusEnum::PENDING;

        // Check if teacher has examiner role
        $isExaminer = $selectedBranch && isset($selectedBranch['team_id'])
            ? $teacher->hasRole(UserTypeEnum::EXAMINER, $selectedBranch['team_id'])
            : false;

        return view('organization.teachers._details', compact('teacher', 'isPending', 'organizationTeacher', 'isExaminer'));

    }// end of details

    public function lessons(User $teacher)
    {
        $selectedOrganization = session('selected_organization');
        $selectedBranch = session('selected_branch');

        // Load relationships
        $teacher->load('teacherCertificates', 'nationality');

        // Check if teacher belongs to organization and get status
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        $isPending = $organizationTeacher && $organizationTeacher->pivot->status === OrganizationTeacherStatusEnum::PENDING;

        // Check if teacher has examiner role
        $isExaminer = $selectedBranch && isset($selectedBranch['team_id'])
            ? $teacher->hasRole(UserTypeEnum::EXAMINER, $selectedBranch['team_id'])
            : false;

        return view('organization.teachers._lessons', compact('teacher', 'isPending', 'organizationTeacher', 'isExaminer'));

    }// end of lessons

    public function salaries(User $teacher)
    {
        $selectedOrganization = session('selected_organization');
        $organizationId       = $selectedOrganization['id'] ?? null;

        // ── Salary settings ──────────────────────────────────────────────────
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $organizationId)
            ->first();

        $salaryType  = TeacherSalaryCalculationTypeEnum::tryFrom($organizationTeacher?->pivot->salary_type ?? 'hourly')
                       ?? TeacherSalaryCalculationTypeEnum::HOURLY;
        $hourlyRate  = (float) ($organizationTeacher?->pivot->hourly_rate  ?? 0);
        $fixedSalary = (float) ($organizationTeacher?->pivot->fixed_salary ?? 0);
        $currency    = $organizationTeacher?->pivot->currency_id
                       ? Currency::find($organizationTeacher->pivot->currency_id)
                       : null;

        // ── All transactions (all branches under this org) ───────────────────
        $transactions = TeacherSalary::query()
            ->with('paymentMethod')
            ->where('teacher_id', $teacher->id)
            ->whenOrganizationId($organizationId)
            ->latest()
            ->get();

        // ── Collect all distinct periods (from transactions + lessons) ────────
        $txPeriods = $transactions
            ->whereNotNull('period_year')
            ->map(fn($t) => ['year' => (int) $t->period_year, 'month' => (int) $t->period_month])
            ->unique(fn($p) => $p['year'] * 100 + $p['month']);

        $lessonPeriods = Lesson::query()
            ->where('teacher_id', $teacher->id)
            ->whenOrganizationId($organizationId)
            ->whereNotNull('time_elapsed')
            ->selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->distinct()
            ->get()
            ->map(fn($l) => ['year' => (int) $l->year, 'month' => (int) $l->month]);

        $periods = $txPeriods->merge($lessonPeriods)
            ->unique(fn($p) => $p['year'] * 100 + $p['month'])
            ->sortByDesc(fn($p) => $p['year'] * 100 + $p['month'])
            ->values();

        // If no periods yet, show current month
        if ($periods->isEmpty()) {
            $periods = collect([['year' => (int) now()->year, 'month' => (int) now()->month]]);
        }

        // ── Build monthly rows ────────────────────────────────────────────────
        $monthlyRows = $periods->map(function ($period) use (
            $teacher, $organizationId, $transactions,
            $salaryType, $hourlyRate, $fixedSalary
        ) {
            $year  = $period['year'];
            $month = $period['month'];

            // Base salary for this month
            if ($salaryType === TeacherSalaryCalculationTypeEnum::HOURLY) {
                $minutes  = (int) Lesson::query()
                    ->where('teacher_id', $teacher->id)
                    ->whenOrganizationId($organizationId)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->sum('time_elapsed');
                $base = round(($minutes / 60) * $hourlyRate, 2);
                $hours = round($minutes / 60, 2);
            } else {
                $base  = $fixedSalary;
                $hours = null;
            }

            // Transactions for this period
            $periodTx = $transactions->filter(
                fn($t) => (int) $t->period_year === $year && (int) $t->period_month === $month
            );

            $bonuses    = (float) $periodTx->filter(fn($t) => $t->getRawOriginal('type') === 'bonus')->sum('amount');
            $deductions = (float) $periodTx->filter(fn($t) => $t->getRawOriginal('type') === 'deduction')->sum('amount');
            $advances   = (float) $periodTx->filter(fn($t) => $t->getRawOriginal('type') === 'advance')->sum('amount');
            $paid       = (float) $periodTx->filter(fn($t) => $t->getRawOriginal('type') === 'payment')->sum('amount');

            $net       = round($base + $bonuses - $deductions, 2);
            $remaining = round($net - $advances - $paid, 2);

            return compact('year', 'month', 'base', 'hours', 'bonuses', 'deductions', 'advances', 'paid', 'net', 'remaining', 'periodTx');
        });

        // ── Grand totals ──────────────────────────────────────────────────────
        $grandNet       = round($monthlyRows->sum('net'), 2);
        $grandAdvances  = round($monthlyRows->sum('advances'), 2);
        $grandPaid      = round($monthlyRows->sum('paid'), 2);
        $grandRemaining = round($grandNet - $grandAdvances - $grandPaid, 2);

        return view('organization.teachers._salaries', compact(
            'teacher',
            'transactions',
            'salaryType',
            'hourlyRate',
            'fixedSalary',
            'currency',
            'monthlyRows',
            'grandNet',
            'grandAdvances',
            'grandPaid',
            'grandRemaining',
        ));

    }// end of salaries


    public function editSalarySettings(User $teacher)
    {
        Gate::authorize('organization-teacher', $teacher);

        $selectedOrganization = session('selected_organization');
        $organizationTeacher  = $teacher->teacherOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        $hourlyRate  = (float) ($organizationTeacher?->pivot->hourly_rate  ?? 0);
        $fixedSalary = (float) ($organizationTeacher?->pivot->fixed_salary ?? 0);
        $salaryType  = $organizationTeacher?->pivot->salary_type ?? 'hourly';
        $currencyId  = $organizationTeacher?->pivot->currency_id;

        $currencies = Currency::query()
            ->whenOrganizationId($selectedOrganization['id'] ?? null)
            ->orderBy('id')
            ->get();

        return response()->json([
            'view' => view('organization.teachers._edit_salary_settings',
                compact('teacher', 'hourlyRate', 'fixedSalary', 'salaryType', 'currencyId', 'currencies'))->render(),
        ]);

    }// end of editSalarySettings

    public function updateSalarySettings(\Illuminate\Http\Request $request, User $teacher)
    {
        Gate::authorize('organization-teacher', $teacher);

        $request->validate([
            'salary_type'  => ['required', 'in:hourly,fixed'],
            'hourly_rate'  => ['required_if:salary_type,hourly', 'nullable', 'numeric', 'min:0'],
            'fixed_salary' => ['required_if:salary_type,fixed',  'nullable', 'numeric', 'min:0'],
            'currency_id'  => ['nullable', 'exists:currencies,id'],
        ]);

        $organizationId = session('selected_organization')['id'] ?? null;

        $teacher->teacherOrganizations()->updateExistingPivot($organizationId, [
            'salary_type'  => $request->salary_type,
            'hourly_rate'  => $request->salary_type === 'hourly' ? $request->hourly_rate : 0,
            'fixed_salary' => $request->salary_type === 'fixed'  ? $request->fixed_salary : 0,
            'currency_id'  => $request->currency_id ?: null,
        ]);

        return response()->json([
            'success_message'         => __('teacher_salaries.salary_settings_updated'),
            'reload_ajax_data_wrapper' => true,
        ]);

    }// end of updateSalarySettings

    public function confirmAcceptEnrollment(User $teacher)
    {
        $selectedOrganization = session('selected_organization');

        // Verify teacher is pending
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        if (!$organizationTeacher || $organizationTeacher->pivot->status !== OrganizationTeacherStatusEnum::PENDING) {
            abort(403, __('teachers.enrollment_not_pending'));
        }

        return response()->json([
            'view' => view('organization.teachers._confirm_accept_enrollment', compact('teacher'))->render(),
        ]);

    }// end of confirmAcceptEnrollment

    public function confirmRejectEnrollment(User $teacher)
    {
        $selectedOrganization = session('selected_organization');

        // Verify teacher is pending
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        if (!$organizationTeacher || $organizationTeacher->pivot->status !== OrganizationTeacherStatusEnum::PENDING) {
            abort(403, __('teachers.enrollment_not_pending'));
        }

        return response()->json([
            'view' => view('organization.teachers._confirm_reject_enrollment', compact('teacher'))->render(),
        ]);

    }// end of confirmRejectEnrollment

    public function acceptEnrollment(TeacherAcceptEnrollmentRequest $request, User $teacher)
    {
        $selectedBranch = session('selected_branch');

        $selectedOrganization = session('selected_organization');

        // Verify teacher is pending
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        if (!$organizationTeacher || $organizationTeacher->pivot->status !== OrganizationTeacherStatusEnum::PENDING) {
            abort(403, __('teachers.enrollment_not_pending'));
        }

        // Update organization_teacher status to ACTIVE
        $teacher->teacherOrganizations()->updateExistingPivot(
            $selectedOrganization['id'],
            ['status' => OrganizationTeacherStatusEnum::ACTIVE]
        );

        // Sync branch relationship
        if ($selectedBranch && isset($selectedBranch['id'])) {
            $teacher->teacherBranches()->sync([$selectedBranch['id']]);
        }

        // Sync teacher role with branch team_id
        if ($selectedBranch && isset($selectedBranch['team_id'])) {
            $teacher->syncRoles([UserTypeEnum::TEACHER], $selectedBranch['team_id']);
        }

        session()->flash('success', __('teachers.enrollment_accepted_successfully'));

        return response()->json([
            'redirect_to' => route('organization.teachers.index'),
        ]);

    }// end of acceptEnrollment

    public function rejectEnrollment(User $teacher)
    {
        $selectedOrganization = session('selected_organization');

        // Verify teacher is pending
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $selectedOrganization['id'] ?? null)
            ->first();

        if (!$organizationTeacher || $organizationTeacher->pivot->status !== OrganizationTeacherStatusEnum::PENDING) {
            abort(403, __('teachers.enrollment_not_pending'));
        }

        // Update organization_teacher status to INACTIVE
        $teacher->teacherOrganizations()->updateExistingPivot(
            $selectedOrganization['id'],
            ['status' => OrganizationTeacherStatusEnum::INACTIVE]
        );

        session()->flash('success', __('teachers.enrollment_rejected_successfully'));

        return response()->json([
            'redirect_to' => route('organization.teachers.index'),
        ]);

    }// end of rejectEnrollment

    public function create()
    {
        $nationalities = \App\Models\Nationality::query()
            ->with(['translations'])
            ->get();

        return view('organization.teachers.create', compact('nationalities'));

    }// end of create

    public function store(TeacherRequest $request)
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

        $teacher = User::create($requestData);

        // Sync organization relationship
        if ($selectedOrganization && isset($selectedOrganization['id'])) {
            $teacher->teacherOrganizations()->sync([$selectedOrganization['id']]);
        }

        // Sync branch relationship
        if ($selectedBranch && isset($selectedBranch['id'])) {
            $teacher->teacherBranches()->sync([$selectedBranch['id']]);
        }

        $teacher->syncRoles([UserTypeEnum::TEACHER], $selectedBranch['team_id']);

        // Update teacher certificate assets
        if ($request->filled('teacher_certificate_ids') && is_array($request->teacher_certificate_ids) && !empty($request->teacher_certificate_ids)) {
            Asset::whereIn('id', $request->teacher_certificate_ids)
                ->update(['teacher_id' => $teacher->id]);
        }

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.teachers.index'),
        ]);

    }// end of store

    public function edit(User $teacher)
    {
        Gate::authorize('organization-teacher', $teacher);

        $teacher->load('teacherCertificates');

        $nationalities = \App\Models\Nationality::query()
            ->with(['translations'])
            ->get();

        return view('organization.teachers.edit', compact('teacher', 'nationalities'));

    }// end of edit

    public function update(TeacherRequest $request, User $teacher)
    {
        Gate::authorize('organization-teacher', $teacher);

        $selectedBranch = session('selected_branch');

        $selectedOrganization = session('selected_organization');

        $requestData = $request->validated();

        if ($request->file('image')) {
            if ($teacher->image) {
                Storage::disk('public')->delete('uploads/' . $teacher->image);
            }
            $requestData['image'] = $request->file('image')->hashName();
            $request->file('image')->store('uploads', 'public');
        }

        if ($request->file('identity_document_file')) {
            Storage::disk('public')->delete('uploads/' . $teacher->identity_document_file);
            $requestData['identity_document_file'] = $request->file('identity_document_file')->hashName();
            $request->file('identity_document_file')->store('uploads', 'public');
        }

        // Update teacher
        $teacher->update($requestData);

        if ($selectedOrganization && isset($selectedOrganization['id'])) {
            $teacher->teacherOrganizations()->syncWithoutDetaching([$selectedOrganization['id']]);
        }

        if ($selectedBranch && isset($selectedBranch['id'])) {
            $teacher->teacherBranches()->syncWithoutDetaching([$selectedBranch['id']]);
        }

        // Update teacher certificate assets
        if ($request->filled('teacher_certificate_ids') && is_array($request->teacher_certificate_ids) && !empty($request->teacher_certificate_ids)) {
            // First, remove teacher_id from certificates that are no longer selected
            Asset::where('teacher_id', $teacher->id)
                ->where('related_to', \App\Enums\AssetRelatedToEnum::TEACHER_CERTIFICATE)
                ->whereNotIn('id', $request->teacher_certificate_ids)
                ->update(['teacher_id' => null]);

            // Then, update the selected certificates to link to this teacher
            Asset::whereIn('id', $request->teacher_certificate_ids)
                ->update(['teacher_id' => $teacher->id]);
        } else {
            // If no certificates are selected, remove all teacher certificates
            Asset::where('teacher_id', $teacher->id)
                ->where('related_to', \App\Enums\AssetRelatedToEnum::TEACHER_CERTIFICATE)
                ->update(['teacher_id' => null]);
        }

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.teachers.index'),
        ]);

    }// end of update

    public function destroy(User $teacher)
    {
        Gate::authorize('organization-teacher', $teacher);

        $this->delete($teacher);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $selectedBranch = session('selected_branch');

        foreach (json_decode(request()->record_ids) as $recordId) {
            $teacher = User::findOrFail($recordId);

            // Verify teacher belongs to the branch
            if ($teacher->hasRole(UserTypeEnum::TEACHER, $selectedBranch['team_id'])) {
                $this->delete($teacher);
            }
        }

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    public function toggleExaminer(User $teacher)
    {
        $selectedBranch = session('selected_branch');

        if (!$selectedBranch || !isset($selectedBranch['team_id'])) {
            return response()->json([
                'success' => false,
                'message' => __('teachers.branch_not_selected'),
            ], 400);
        }

        // Check if teacher has examiner role
        $isExaminer = $teacher->hasRole(UserTypeEnum::EXAMINER, $selectedBranch['team_id']);

        if ($isExaminer) {

            // Remove examiner role, keep teacher role
            $teacher->syncRoles([UserTypeEnum::TEACHER], $selectedBranch['team_id']);
            $teacher->examinerBranches()->detach($selectedBranch['id']);
            $message = __('teachers.examiner_removed');

        } else {

            // Add examiner role along with teacher role
            $teacher->syncRoles([UserTypeEnum::TEACHER, UserTypeEnum::EXAMINER], $selectedBranch['team_id']);
            $teacher->examinerBranches()->attach($selectedBranch['id']);
            $message = __('teachers.examiner_assigned');

        }//end of else

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_examiner' => !$isExaminer,
        ]);

    }// end of toggleExaminer

    public function impersonate(User $teacher)
    {
        $user = auth()->user();
        if (!$user->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN) && !$user->hasRole(UserTypeEnum::SUPER_ADMIN)) {
            abort(403);
        }

        $selectedOrganization = session('selected_organization');
        $selectedBranch = session('selected_branch');

        auth()->user()->impersonate($teacher);

        session(['selected_organization' => $selectedOrganization]);
        session(['selected_branch' => $selectedBranch]);

        return redirect()->route('teacher.home');

    }// end of impersonate

    private function delete(User $teacher)
    {
        $teacher->delete();

    }// end of delete

}//end of controller

