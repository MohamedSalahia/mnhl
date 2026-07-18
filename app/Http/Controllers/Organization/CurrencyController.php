<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\CurrencyRequest;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_currencies', only: ['index', 'data']),
            new Middleware('permission_with_team:create_currencies', only: ['create', 'store']),
            new Middleware('permission_with_team:update_currencies', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_currencies', only: ['destroy']),
        ];

    }// end of middleware

    public function index()
    {
        return view('organization.currencies.index');

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $currencies = Currency::query()
            ->with(['translations'])
            ->whenOrganizationId($organizationId);

        return DataTables::of($currencies)
            ->editColumn('created_at', function (Currency $currency) {
                return $currency->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.currencies.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        return view('organization.currencies.create');

    }// end of create

    public function store(CurrencyRequest $request, CurrencyService $currencyService)
    {
        $currency = Currency::create($request->validated());


        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.currencies.index'),
        ]);

    }// end of store

    public function edit(Currency $currency)
    {
        Gate::authorize('organization-currency', $currency);

        return view('organization.currencies.edit', compact('currency'));

    }// end of edit

    public function update(CurrencyRequest $request, CurrencyService $currencyService, Currency $currency)
    {
        Gate::authorize('organization-currency', $currency);

        $currency->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.currencies.index'),
        ]);

    }// end of update

    public function destroy(Currency $currency)
    {
        Gate::authorize('organization-currency', $currency);

        $currency->delete();

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

}// end of controller
