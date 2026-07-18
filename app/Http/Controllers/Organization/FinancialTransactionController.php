<?php

namespace App\Http\Controllers\Organization;

use App\Enums\FinancialTransactionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\FinancialTransactionRequest;
use App\Models\Currency;
use App\Models\FinancialTransaction;
use App\Models\Fund;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class FinancialTransactionController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_financial_transactions', only: ['index', 'data']),
            new Middleware('permission_with_team:create_financial_transactions', only: ['create', 'store']),
            new Middleware('permission_with_team:update_financial_transactions', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_financial_transactions', only: ['destroy']),
        ];

    }// end of middleware

    public function index()
    {
        $organizationId = session('selected_organization')['id'] ?? null;

        $funds      = Fund::whenOrganizationId($organizationId)->orderBy('name')->get();
        $currencies = Currency::whenOrganizationId($organizationId)->orderBy('id')->get();

        // Summary totals
        $baseQuery = FinancialTransaction::whenOrganizationId($organizationId)
            ->whenBranchId(session('selected_branch')['id'] ?? null);

        $totalIncome  = (clone $baseQuery)->where('type', FinancialTransactionTypeEnum::INCOME)->sum('amount');
        $totalExpense = (clone $baseQuery)->where('type', FinancialTransactionTypeEnum::EXPENSE)->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        return view('organization.financial_transactions.index', compact(
            'funds', 'currencies', 'totalIncome', 'totalExpense', 'balance'
        ));

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'] ?? null;
        $branchId       = session('selected_branch')['id'] ?? null;

        $transactions = FinancialTransaction::query()
            ->with(['currency', 'fund'])
            ->whenOrganizationId($organizationId)
            ->whenBranchId($branchId)
            ->when(request('type'),        fn($q) => $q->where('type', request('type')))
            ->when(request('fund_id'),     fn($q) => $q->where('fund_id', request('fund_id')))
            ->when(request('currency_id'), fn($q) => $q->where('currency_id', request('currency_id')));

        return DataTables::of($transactions)
            ->editColumn('type', function (FinancialTransaction $t) {
                $label = $t->type === FinancialTransactionTypeEnum::INCOME
                    ? __('financial_transactions.income')
                    : __('financial_transactions.expense');
                $badge = $t->type === FinancialTransactionTypeEnum::INCOME
                    ? 'badge-light-success'
                    : 'badge-light-danger';
                return '<span class="badge ' . $badge . '">' . $label . '</span>';
            })
            ->editColumn('date', function (FinancialTransaction $t) {
                return $t->date?->format('Y-m-d') ?? '—';
            })
            ->editColumn('amount', function (FinancialTransaction $t) {
                $currency = $t->currency?->name ?? '';
                return number_format($t->amount, 3) . ($currency ? ' <small class="text-muted">' . $currency . '</small>' : '');
            })
            ->addColumn('fund_name', function (FinancialTransaction $t) {
                return $t->fund?->name ?? '—';
            })
            ->addColumn('actions', 'organization.financial_transactions.data_table.actions')
            ->rawColumns(['type', 'amount', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $organizationId = session('selected_organization')['id'] ?? null;

        $funds      = Fund::whenOrganizationId($organizationId)->orderBy('name')->get();
        $currencies = Currency::whenOrganizationId($organizationId)->orderBy('id')->get();
        $today      = now()->format('Y-m-d');

        return view('organization.financial_transactions.create', compact('funds', 'currencies', 'today'));

    }// end of create

    public function store(FinancialTransactionRequest $request)
    {
        $organizationId = session('selected_organization')['id'] ?? null;
        $branchId       = session('selected_branch')['id'] ?? null;

        FinancialTransaction::create(array_merge($request->validated(), [
            'organization_id' => $organizationId,
            'branch_id'       => $branchId,
        ]));

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.financial_transactions.index'),
        ]);

    }// end of store

    public function edit(FinancialTransaction $financialTransaction)
    {
        Gate::authorize('organization-financial-transaction', $financialTransaction);

        $organizationId = session('selected_organization')['id'] ?? null;

        $funds      = Fund::whenOrganizationId($organizationId)->orderBy('name')->get();
        $currencies = Currency::whenOrganizationId($organizationId)->orderBy('id')->get();

        return view('organization.financial_transactions.edit', compact('financialTransaction', 'funds', 'currencies'));

    }// end of edit

    public function update(FinancialTransactionRequest $request, FinancialTransaction $financialTransaction)
    {
        Gate::authorize('organization-financial-transaction', $financialTransaction);

        $financialTransaction->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.financial_transactions.index'),
        ]);

    }// end of update

    public function destroy(FinancialTransaction $financialTransaction)
    {
        Gate::authorize('organization-financial-transaction', $financialTransaction);

        $financialTransaction->delete();

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

}// end of controller
