<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\FundRequest;
use App\Models\Fund;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class FundController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_financial_transactions', only: ['index']),
            new Middleware('permission_with_team:create_financial_transactions', only: ['create', 'store']),
            new Middleware('permission_with_team:update_financial_transactions', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_financial_transactions', only: ['destroy']),
        ];

    }// end of middleware

    public function create()
    {
        return response()->json([
            'view' => view('organization.funds._create')->render(),
        ]);

    }// end of create

    public function store(FundRequest $request)
    {
        $organizationId = session('selected_organization')['id'] ?? null;

        Fund::create([
            'organization_id' => $organizationId,
            'name'            => $request->name,
        ]);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'reload_table' => '#financial-transactions-table',
        ]);

    }// end of store

    public function edit(Fund $fund)
    {
        Gate::authorize('organization-fund', $fund);

        return response()->json([
            'view' => view('organization.funds._edit', compact('fund'))->render(),
        ]);

    }// end of edit

    public function update(FundRequest $request, Fund $fund)
    {
        Gate::authorize('organization-fund', $fund);

        $fund->update(['name' => $request->name]);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'reload_table' => '#financial-transactions-table',
        ]);

    }// end of update

    public function destroy(Fund $fund)
    {
        Gate::authorize('organization-fund', $fund);

        $fund->delete();

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

}// end of controller
