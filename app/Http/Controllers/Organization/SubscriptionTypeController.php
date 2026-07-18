<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\SubscriptionTypeRequest;
use App\Models\Currency;
use App\Models\SubscriptionType;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class SubscriptionTypeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_subscription_types', only: ['index', 'data']),
            new Middleware('permission_with_team:create_subscription_types', only: ['create', 'store']),
            new Middleware('permission_with_team:update_subscription_types', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_subscription_types', only: ['destroy']),
        ];

    }// end of middleware

    public function index()
    {
        return view('organization.subscription_types.index');

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $subscriptionTypes = SubscriptionType::query()
            ->whenOrganizationId($organizationId)
            ->whenYear(now()->year);

        return DataTables::of($subscriptionTypes)
            ->editColumn('fees', function (SubscriptionType $subscriptionType) {
                return number_format($subscriptionType->fees, 2);
            })
            ->editColumn('has_specific_date', function (SubscriptionType $subscriptionType) {
                return $subscriptionType->has_specific_date ? __('site.yes') : __('site.no');
            })
            ->editColumn('start_date', function (SubscriptionType $subscriptionType) {
                return $subscriptionType->start_date?->format('Y-m-d') ?? '-';
            })
            ->editColumn('end_date', function (SubscriptionType $subscriptionType) {
                return $subscriptionType->end_date?->format('Y-m-d') ?? '-';
            })
            ->editColumn('created_at', function (SubscriptionType $subscriptionType) {
                return $subscriptionType->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.subscription_types.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $organizationId = session('selected_organization')['id'] ?? null;

        $currencies = $organizationId
            ? Currency::query()->whenOrganizationId($organizationId)->orderBy('id')->get()
            : collect();

        return view('organization.subscription_types.create', compact('currencies'));

    }// end of create

    public function store(SubscriptionTypeRequest $request)
    {
        SubscriptionType::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.subscription_types.index'),
        ]);

    }// end of store

    public function edit(SubscriptionType $subscriptionType)
    {
        Gate::authorize('organization-subscription-type', $subscriptionType);

        $organizationId = session('selected_organization')['id'] ?? null;

        $currencies = $organizationId
            ? Currency::query()->whenOrganizationId($organizationId)->orderBy('id')->get()
            : collect();

        return view('organization.subscription_types.edit', compact('subscriptionType', 'currencies'));

    }// end of edit

    public function update(SubscriptionTypeRequest $request, SubscriptionType $subscriptionType)
    {
        Gate::authorize('organization-subscription-type', $subscriptionType);

        $subscriptionType->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.subscription_types.index'),
        ]);

    }// end of update

    public function destroy(SubscriptionType $subscriptionType)
    {
        Gate::authorize('organization-subscription-type', $subscriptionType);

        $subscriptionType->delete();

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

}// end of controller
