<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CurrencyRequest;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_currencies', only: ['index']),
            new Middleware('permission:create_currencies', only: ['create', 'store']),
            new Middleware('permission:update_currencies', only: ['edit', 'update']),
            new Middleware('permission:delete_currencies', only: ['delete', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        return view('admin.currencies.index');

    }// end of index

    public function data()
    {
        $currencies = Currency::query()
            ->with(['translation', 'translations'])
            ->withCount('countries');

        return DataTables::of($currencies)
            ->editColumn('created_at', function (Currency $currency) {
                return $currency->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.currencies.data_table.actions')
            ->rawColumns(['actions', 'active'])
            ->toJson();

    }// end of data

    public function create()
    {
        return view('admin.currencies.create');

    }// end of create

    public function store(CurrencyRequest $request, CurrencyService $currencyService)
    {
        $currency = Currency::create($request->validated());

        if ($currencyService->setCurrencyExchangeRate($currency)) {

            session()->flash('success', __('site.added_successfully'));

            return response()->json([
                'redirect_to' => route('admin.currencies.index'),
            ]);

        } else {

            $currency->delete();

            $errors = [
                'en.code' => [__('currencies.wrong_code')]
            ];

            return response()->json(['errors' => $errors], 422);

        }//end of else

    }// end of store

    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', compact('currency'));

    }// end of edit

    public function update(CurrencyRequest $request, CurrencyService $currencyService, Currency $currency)
    {
        $currency->update($request->validated());

        if ($currencyService->setCurrencyExchangeRate($currency)) {

            session()->flash('success', __('site.added_successfully'));

            return response()->json([
                'redirect_to' => route('admin.currencies.index'),
            ]);

        } else {

            $currency->delete();

            $errors = [
                'en.code' => [__('currencies.wrong_code')]
            ];

            return response()->json(['errors' => $errors], 422);

        }//end of else

    }// end of update

    public function destroy(Currency $currency)
    {
        $this->delete($currency);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    private function delete(Currency $currency)
    {
        $currency->delete();

    }// end of delete

}//end of controller
