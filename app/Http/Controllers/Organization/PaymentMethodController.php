<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\PaymentMethodRequest;
use App\Models\PaymentMethod;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class PaymentMethodController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_payment_methods', only: ['index', 'data']),
            new Middleware('permission_with_team:create_payment_methods', only: ['create', 'store']),
            new Middleware('permission_with_team:update_payment_methods', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_payment_methods', only: ['destroy']),
        ];

    }// end of middleware

    public function index()
    {
        return view('organization.payment_methods.index');

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $paymentMethods = PaymentMethod::query()
            ->with(['translations'])
            ->whenOrganizationId($organizationId);

        return DataTables::of($paymentMethods)
            ->editColumn('created_at', function (PaymentMethod $paymentMethod) {
                return $paymentMethod->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.payment_methods.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        return view('organization.payment_methods.create');

    }// end of create

    public function store(PaymentMethodRequest $request)
    {
        PaymentMethod::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.payment_methods.index'),
        ]);

    }// end of store

    public function edit(PaymentMethod $paymentMethod)
    {
        Gate::authorize('organization-payment-method', $paymentMethod);

        return view('organization.payment_methods.edit', compact('paymentMethod'));

    }// end of edit

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        Gate::authorize('organization-payment-method', $paymentMethod);

        $paymentMethod->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.payment_methods.index'),
        ]);

    }// end of update

    public function destroy(PaymentMethod $paymentMethod)
    {
        Gate::authorize('organization-payment-method', $paymentMethod);

        $paymentMethod->delete();

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

}// end of controller
