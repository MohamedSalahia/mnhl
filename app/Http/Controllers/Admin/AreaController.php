<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AreaRequest;
use App\Models\Area;
use App\Models\AreaTranslation;
use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class AreaController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_areas', only: ['index']),
            new Middleware('permission:create_areas', only: ['create', 'store']),
            new Middleware('permission:update_areas', only: ['edit', 'update']),
            new Middleware('permission:delete_areas', only: ['delete', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        $countries = Country::query()
            ->with(['translations'])
            ->get();

        return view('admin.areas.index', compact('countries'));

    }// end of index

    public function data()
    {
        $areas = Area::query()
            ->with(['country', 'country.translations', 'governorate', 'governorate.translations', 'translations'])
            ->addSelect([
                'name' => AreaTranslation::select('name')
                    ->whereColumn('area_id', 'areas.id')
                    ->where('locale', app()->getLocale())
                    ->take(1),
            ])
            ->whenCountryId(request()->country_id)
            ->whenGovernorateId(request()->governorate_id);

        return DataTables::of($areas)
            ->addColumn('record_select', 'admin.areas.data_table.record_select')
            ->addColumn('country', function (Area $area) {
                return $area->country?->name;
            })
            ->addColumn('governorate', function (Area $area) {
                return $area->governorate->name;
            })
            ->editColumn('created_at', function (Area $area) {
                return $area->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.areas.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $countries = Country::all();

        return view('admin.areas.create', compact('countries'));

    }// end of create

    public function store(AreaRequest $request)
    {
        Area::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.areas.index')
        ]);

    }// end of store

    public function edit(Area $area)
    {
        $area->load(['governorate', 'country']);

        $countries = Country::all();

        $governorates = Governorate::query()
            ->with(['translations'])
            ->where('country_id', $area->country->id)
            ->get();

        return view('admin.areas.edit', compact('countries', 'governorates', 'area'));

    }// end of edit

    public function update(AreaRequest $request, Area $area)
    {
        $area->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('admin.areas.index')
        ]);

    }// end of update

    public function destroy(Area $area)
    {
        $this->delete($area);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $area = Area::FindOrFail($recordId);

            $this->delete($area);

        }//end of for each

        session()->flash('success', __('site.deleted_successfully'));

        return redirect()->route('admin.areas.index');

    }// end of bulkDelete

    private function delete(Area $area)
    {
        $area->delete();

    }// end of delete

}//end of controller
