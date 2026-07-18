<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Models\Country;
use App\Models\CountryTranslation;
use App\Models\Currency;
use App\Models\Timezone;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class CountryController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_countries', only: ['index']),
            new Middleware('permission:create_countries', only: ['create', 'store']),
            new Middleware('permission:update_countries', only: ['edit', 'update', 'toggleActive']),
            new Middleware('permission:delete_countries', only: ['delete', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        return view('admin.countries.index');

    }// end of index

    public function data()
    {
        $countries = Country::query()
            ->with(['translations', 'currency'])
            ->withCount('governorates', 'areas')
            ->addSelect([
                'name' => CountryTranslation::query()
                    ->select('name')
                    ->whereColumn('country_id', 'countries.id')
                    ->where('locale', app()->getLocale())
                    ->take(1)
            ]);

        return DataTables::of($countries)
            ->addColumn('record_select', 'admin.countries.data_table.record_select')
            ->addColumn('currency', function (Country $country) {
                return $country->currency->code;
            })
            ->addColumn('tax_percent', function (Country $country) {
                return number_format($country->tax_percent, 2) . ' %';
            })
            ->editColumn('created_at', function (Country $country) {
                return $country->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.countries.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $currencies = Currency::query()
            ->with(['translations'])
            ->get();

        return view('admin.countries.create', compact('currencies'));

    }// end of create

    public function store(CountryRequest $request)
    {
        $requestData = $request->validated();

        if ($request->flag) {
            //Storage::disk('public')->delete('uploads/' . $->flag);
            $requestData['flag'] = $request->flag->hashName();
            $request->flag->store('uploads', 'public');
        }

        Country::create($requestData);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.countries.index')
        ]);

    }// end of store

    public function edit(Country $country)
    {
        $currencies = Currency::query()
            ->with(['translations'])
            ->get();

        return view('admin.countries.edit', compact('currencies', 'country'));

    }// end of edit

    public function update(CountryRequest $request, Country $country)
    {
        $requestData = $request->validated();

        if ($request->flag) {
            Storage::disk('public')->delete('uploads/' . $country->flag);
            $requestData['flag'] = $request->flag->hashName();
            $request->flag->store('uploads', 'public');
        }

        $country->update($requestData);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('admin.countries.index')
        ]);

    }// end of update

    public function governorates(Country $country)
    {
        $governorates = $country->governorates;

        $emptyValueText = request()->empty_value_text;

        return view('admin.countries._governorates', compact('emptyValueText', 'governorates'));

    }// end of governorates

    public function destroy(Country $country)
    {
        $this->delete($country);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $country = Country::FindOrFail($recordId);

            $this->delete($country);

        }//end of for each

        session()->flash('success', __('site.deleted_successfully'));

        return redirect()->route('admin.countries.index');

    }// end of bulkDelete

    private function delete(Country $country)
    {
        $country->delete();

    }// end of delete

}//end of controller
