<?php

namespace App\Http\Controllers\Organization;

use App\Enums\TeacherSalaryCalculationTypeEnum;
use App\Enums\TeacherSalaryTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\TeacherSalaryRequest;
use App\Models\Lesson;
use App\Models\PaymentMethod;
use App\Models\TeacherSalary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class TeacherSalaryController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_teacher_salaries',   only: ['index', 'data']),
            new Middleware('permission_with_team:create_teacher_salaries', only: ['create', 'store']),
            new Middleware('permission_with_team:update_teacher_salaries', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_teacher_salaries', only: ['destroy']),
        ];

    }// end of middleware

    public function create(Request $request)
    {
        if (! $request->ajax()) abort(404);

        $teacherHash = $request->query('teacher');
        $teacher     = User::query()->where('hash_id', $teacherHash)->firstOrFail();

        Gate::authorize('organization-teacher', $teacher);

        $organizationId = session('selected_organization')['id'] ?? null;
        $paymentMethods = PaymentMethod::query()
            ->whenOrganizationId($organizationId)
            ->get()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);

        $types = TeacherSalaryTypeEnum::cases();

        // Currency from teacher's salary settings
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $organizationId)
            ->first();
        $currency = $organizationTeacher?->pivot->currency_id
            ? \App\Models\Currency::find($organizationTeacher->pivot->currency_id)
            : null;

        $currentPeriod = now()->format('Y-m');

        return response()->json([
            'view' => view('organization.teacher_salaries._create',
                compact('teacher', 'paymentMethods', 'types', 'currency', 'currentPeriod'))->render(),
        ]);

    }// end of create

    public function store(TeacherSalaryRequest $request)
    {
        $teacher = User::query()->findOrFail($request->teacher_id);
        Gate::authorize('organization-teacher', $teacher);

        $sessionOrganizationId = session('selected_organization')['id'] ?? null;
        $sessionBranchId       = session('selected_branch')['id'] ?? null;

        if (
            $sessionOrganizationId === null || $sessionBranchId === null
            || (int) $request->organization_id !== (int) $sessionOrganizationId
            || (int) $request->branch_id       !== (int) $sessionBranchId
        ) {
            abort(403);
        }

        // Resolve period (year + month) — must come before validation
        $periodRaw    = $request->period ?: now()->format('Y-m');
        [$periodYear, $periodMonth] = array_map('intval', explode('-', $periodRaw));

        // Carry-forward: shift advance to next month's period
        $carryForward = (bool) $request->carry_forward;
        if ($carryForward && $request->type === TeacherSalaryTypeEnum::ADVANCE->value) {
            $next        = Carbon::createFromDate($periodYear, $periodMonth, 1)->addMonth();
            $periodYear  = $next->year;
            $periodMonth = $next->month;
        }

        // Check payment doesn't exceed period's remaining
        if ($request->type === TeacherSalaryTypeEnum::PAYMENT->value) {
            $remaining = $this->getRemaining($teacher, $sessionOrganizationId, $periodYear, $periodMonth);

            if ((float) $request->amount > $remaining) {
                return response()->json([
                    'errors' => [
                        'amount' => [__('teacher_salaries.payment_exceeds_remaining', [
                            'remaining' => number_format($remaining, 2),
                        ])],
                    ],
                ], 422);
            }
        }

        TeacherSalary::create(array_merge(
            $request->safe()->only([
                'organization_id', 'branch_id', 'teacher_id',
                'type', 'amount', 'payment_method_id', 'notes', 'date',
            ]),
            [
                'period_year'    => $periodYear,
                'period_month'   => $periodMonth,
                'carry_forward'  => $carryForward,
            ]
        ));

        return response()->json([
            'success_message'          => __('teacher_salaries.transaction_added'),
            'reload_ajax_data_wrapper' => true,
        ]);

    }// end of store

    public function edit(Request $request, TeacherSalary $teacherSalary)
    {
        if (! $request->ajax()) abort(404);

        $this->authorizeTransaction($teacherSalary);

        $organizationId = session('selected_organization')['id'] ?? $teacherSalary->organization_id;
        $paymentMethods = PaymentMethod::query()
            ->whenOrganizationId($organizationId)
            ->get()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);

        // Currency from teacher's salary settings
        $teacher = $teacherSalary->teacher;
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $organizationId)
            ->first();
        $currency = $organizationTeacher?->pivot->currency_id
            ? \App\Models\Currency::find($organizationTeacher->pivot->currency_id)
            : null;

        $currentPeriod = $teacherSalary->period_year && $teacherSalary->period_month
            ? sprintf('%04d-%02d', $teacherSalary->period_year, $teacherSalary->period_month)
            : now()->format('Y-m');

        return response()->json([
            'view' => view('organization.teacher_salaries._edit',
                ['transaction' => $teacherSalary, 'paymentMethods' => $paymentMethods, 'currency' => $currency, 'currentPeriod' => $currentPeriod])->render(),
        ]);

    }// end of edit

    public function update(TeacherSalaryRequest $request, TeacherSalary $teacherSalary)
    {
        $this->authorizeTransaction($teacherSalary);

        // Check payment doesn't exceed period's remaining (excluding current record's amount)
        if ($teacherSalary->getRawOriginal('type') === TeacherSalaryTypeEnum::PAYMENT->value) {
            $organizationId = session('selected_organization')['id'] ?? null;
            $teacher        = User::find($teacherSalary->teacher_id);

            $periodYear  = (int) $teacherSalary->period_year  ?: null;
            $periodMonth = (int) $teacherSalary->period_month ?: null;

            // Remaining + current record amount = max allowed for this payment
            $remaining    = $this->getRemaining($teacher, $organizationId, $periodYear, $periodMonth);
            $maxAllowed   = $remaining + (float) $teacherSalary->amount;

            if ((float) $request->amount > $maxAllowed) {
                return response()->json([
                    'errors' => [
                        'amount' => [__('teacher_salaries.payment_exceeds_remaining', [
                            'remaining' => number_format($maxAllowed, 2),
                        ])],
                    ],
                ], 422);
            }
        }

        $data = $request->safe()->only(['amount', 'payment_method_id', 'notes', 'date']);

        if ($request->period) {
            [$periodYear, $periodMonth] = array_map('intval', explode('-', $request->period));
            $data['period_year']  = $periodYear;
            $data['period_month'] = $periodMonth;
        }

        $teacherSalary->update($data);

        return response()->json([
            'success_message'          => __('site.updated_successfully'),
            'reload_ajax_data_wrapper' => true,
        ]);

    }// end of update

    public function destroy(TeacherSalary $teacherSalary)
    {
        $this->authorizeTransaction($teacherSalary);

        $teacherSalary->delete();

        return response()->json([
            'success_message'         => __('site.deleted_successfully'),
            'reload_ajax_data_wrapper' => true,
        ]);

    }// end of destroy

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Calculate the remaining amount for a specific period (year+month).
     * If period is null, falls back to all-time global remaining.
     */
    private function getRemaining(
        User $teacher,
        ?int $organizationId,
        ?int $periodYear  = null,
        ?int $periodMonth = null
    ): float {
        // Salary settings
        $organizationTeacher = $teacher->teacherOrganizations()
            ->where('organizations.id', $organizationId)
            ->first();

        $salaryType  = TeacherSalaryCalculationTypeEnum::tryFrom(
                           $organizationTeacher?->pivot->salary_type ?? 'hourly'
                       ) ?? TeacherSalaryCalculationTypeEnum::HOURLY;
        $hourlyRate  = (float) ($organizationTeacher?->pivot->hourly_rate  ?? 0);
        $fixedSalary = (float) ($organizationTeacher?->pivot->fixed_salary ?? 0);

        // Base salary — scoped to period if provided
        if ($salaryType === TeacherSalaryCalculationTypeEnum::HOURLY) {
            $query = Lesson::query()
                ->where('teacher_id', $teacher->id)
                ->whenOrganizationId($organizationId);

            if ($periodYear && $periodMonth) {
                $query->whereYear('date', $periodYear)->whereMonth('date', $periodMonth);
            }

            $baseSalary = round(((int) $query->sum('time_elapsed')) / 60 * $hourlyRate, 2);
        } else {
            $baseSalary = $fixedSalary; // fixed is the same every month
        }

        // Transactions — scoped to period if provided
        $txQuery = TeacherSalary::query()
            ->where('teacher_id', $teacher->id)
            ->whenOrganizationId($organizationId);

        if ($periodYear && $periodMonth) {
            $txQuery->where('period_year', $periodYear)->where('period_month', $periodMonth);
        }

        $transactions    = $txQuery->get();
        $totalBonuses    = (float) $transactions->filter(fn($t) => $t->getRawOriginal('type') === 'bonus')->sum('amount');
        $totalDeductions = (float) $transactions->filter(fn($t) => $t->getRawOriginal('type') === 'deduction')->sum('amount');
        $totalAdvances   = (float) $transactions->filter(fn($t) => $t->getRawOriginal('type') === 'advance')->sum('amount');
        $totalPaid       = (float) $transactions->filter(fn($t) => $t->getRawOriginal('type') === 'payment')->sum('amount');

        $netSalary = round($baseSalary + $totalBonuses - $totalDeductions, 2);

        return round($netSalary - $totalAdvances - $totalPaid, 2);

    }// end of getRemaining

    private function authorizeTransaction(TeacherSalary $transaction): void
    {
        $organizationId = session('selected_organization')['id'] ?? null;
        $branchId       = session('selected_branch')['id']       ?? null;

        if (
            (int) $transaction->organization_id !== (int) $organizationId
            || (int) $transaction->branch_id    !== (int) $branchId
        ) {
            abort(403);
        }

    }// end of authorizeTransaction

}// end of controller
