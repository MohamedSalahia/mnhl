<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\InstallmentRequest;
use App\Models\Installment;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class InstallmentController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_installments', only: ['index', 'data']),
            new Middleware('permission_with_team:create_installments', only: ['create', 'store']),
            new Middleware('permission_with_team:update_installments', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_installments', only: ['destroy']),
        ];

    }// end of middleware

    public function index()
    {
        return view('organization.installments.index');

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'] ?? null;
        $branchId = session('selected_branch')['id'] ?? null;

        $installments = Installment::query()
            ->with([
                'student',
                'project',
                'paymentMethod',
            ]);

        if ($organizationId !== null && $branchId !== null) {

            $installments->where('organization_id', $organizationId)->where('branch_id', $branchId);

        } else {

            $installments->whereRaw('0 = 1');

        }//end of if

        return DataTables::of($installments)
            ->addColumn('student_name', function (Installment $installment) {
                return $installment->student?->name ?? '—';
            })
            ->addColumn('project_name', function (Installment $installment) {
                return $installment->project?->name ?? '—';
            })
            ->addColumn('payment_method_name', function (Installment $installment) {
                return $installment->paymentMethod?->name ?? '—';
            })
            ->addColumn('actions', function (Installment $installment) {
                return view('organization.installments.data_table.actions', compact('installment'));
            })
            ->filterColumn('student_name', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {

                    return $q->where('name', 'like', '%' . $keyword . '%');

                });
            })
            ->filterColumn('project_name', function ($query, $keyword) {
                $query->whereHas('project', function ($q) use ($keyword) {

                    return $q->where('name', 'like', '%' . $keyword . '%');

                });
            })
            ->filterColumn('payment_method_name', function ($query, $keyword) {
                $query->whereHas('paymentMethod', function ($q) use ($keyword) {

                    return $q->whereHas('translations', function ($q2) use ($keyword) {

                        return $q2->where('name', 'like', '%' . $keyword . '%')
                            ->where('locale', app()->getLocale());

                    });

                });
            })
            ->filterColumn('actions', function ($query, $keyword) {
            })
            ->editColumn('amount', function (Installment $installment) {
                return number_format((float) $installment->amount, 2);
            })
            ->editColumn('created_at', function (Installment $installment) {
                return $installment->created_at->format('Y-m-d');
            })
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function create(Request $request)
    {
        if (! $request->ajax()) {

            abort(404);

        }//end of if

        $studentHash = $request->query('student');
        $projectId = $request->query('project_id');

        $student = User::query()->where('hash_id', $studentHash)->firstOrFail();
        $project = Project::query()->findOrFail($projectId);

        Gate::authorize('organization-student', $student);
        Gate::authorize('organization-project', $project);

        $branchId = session('selected_branch')['id'] ?? null;

        if ($branchId === null) {

            abort(403);

        }//end of if

        if (! InstallmentRequest::enrollmentAllowsInstallments($student->id, $branchId, $project->id)) {

            abort(403);

        }//end of if

        $organizationId = session('selected_organization')['id'] ?? null;
        $paymentMethods = PaymentMethod::query()
            ->whenOrganizationId($organizationId)
            ->get()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);

        return response()->json([
            'view' => view('organization.installments._create', compact('student', 'project', 'paymentMethods'))->render(),
        ]);

    }// end of create

    public function store(InstallmentRequest $request)
    {
        $student = User::query()->findOrFail($request->student_id);
        $project = Project::query()->findOrFail($request->project_id);

        Gate::authorize('organization-student', $student);
        Gate::authorize('organization-project', $project);

        $sessionOrganizationId = session('selected_organization')['id'] ?? null;
        $sessionBranchId = session('selected_branch')['id'] ?? null;

        if (
            $sessionOrganizationId === null
            || $sessionBranchId === null
            || (int) $request->organization_id !== (int) $sessionOrganizationId
            || (int) $request->branch_id !== (int) $sessionBranchId
        ) {

            abort(403);

        }//end of if

        Installment::create($request->safe()->only([
            'organization_id',
            'branch_id',
            'student_id',
            'project_id',
            'payment_method_id',
            'amount',
        ]));

        return response()->json([
            'success_message' => __('installments.added_successfully'),
            'reload_ajax_data_wrapper' => true,
        ]);

    }// end of store

    public function edit(Request $request, Installment $installment)
    {
        if (! $request->ajax()) {

            abort(404);

        }//end of if

        Gate::authorize('organization-installment', $installment);

        if (! InstallmentRequest::enrollmentAllowsInstallments($installment->student_id, $installment->branch_id, $installment->project_id)) {

            abort(403);

        }//end of if

        $organizationId = session('selected_organization')['id'] ?? $installment->organization_id;
        $paymentMethods = PaymentMethod::query()
            ->whenOrganizationId($organizationId)
            ->get()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);

        return response()->json([
            'view' => view('organization.installments._edit', compact('installment', 'paymentMethods'))->render(),
        ]);

    }// end of edit

    public function update(InstallmentRequest $request, Installment $installment)
    {
        Gate::authorize('organization-installment', $installment);

        $installment->update([
            'amount' => $request->amount,
            'payment_method_id' => $request->payment_method_id,
        ]);

        return response()->json([
            'success_message' => __('site.updated_successfully'),
            'reload_ajax_data_wrapper' => true,
        ]);

    }// end of update

    public function destroy(Installment $installment)
    {
        Gate::authorize('organization-installment', $installment);

        $installment->delete();

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
            'reload_ajax_data_wrapper' => true,
        ]);

    }// end of destroy

}// end of controller
