<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GovernorateRequest;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\GovernorateTranslation;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class GovernorateController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_governorates', only: ['index']),
            new Middleware('permission:create_governorates', only: ['create', 'store']),
            new Middleware('permission:update_governorates', only: ['edit', 'update']),
            new Middleware('permission:delete_governorates', only: ['delete', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        $countries = Country::query()
            ->with(['translations'])
            ->get();

        return view('admin.governorates.index', compact('countries'));

    }// end of index

    public function data()
    {
        $governorates = Governorate::query()
            ->with(['translations'])
            ->addSelect([
                'name' => GovernorateTranslation::query()
                    ->select('name')
                    ->whereColumn('governorate_id', 'governorates.id')
                    ->where('locale', app()->getLocale())
                    ->take(1)
            ])
            ->withCount('areas')
            ->whenCountryId(request()->country_id);

        return DataTables::of($governorates)
            ->addColumn('record_select', 'admin.governorates.data_table.record_select')
            ->editColumn('created_at', function (Governorate $governorate) {
                return $governorate->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.governorates.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $countries = Country::all();

        return view('admin.governorates.create', compact('countries'));

    }// end of create

    public function store(GovernorateRequest $request)
    {
        Governorate::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.governorates.index')
        ]);

    }// end of store

    public function edit(Governorate $governorate)
    {
        $countries = Country::all();

        return view('admin.governorates.edit', compact('countries', 'governorate'));

    }// end of edit

    public function update(GovernorateRequest $request, Governorate $governorate)
    {
        $governorate->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('admin.governorates.index')
        ]);

    }// end of update

    public function areas(Governorate $governorate)
    {
        $areas = $governorate->areas;

        $emptyValueText = request()->empty_value_text;

        return view('admin.governorates._areas', compact('emptyValueText', 'areas'));

    }// end of areas

    public function destroy(Governorate $governorate)
    {
        $this->delete($governorate);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $governorate = Governorate::FindOrFail($recordId);

            $this->delete($governorate);

        }//end of for each

        session()->flash('success', __('site.deleted_successfully'));

        return redirect()->route('admin.governorates.index');

    }// end of bulkDelete

    private function delete(Governorate $governorate)
    {
        $governorate->delete();

    }// end of delete

}//end of controller
