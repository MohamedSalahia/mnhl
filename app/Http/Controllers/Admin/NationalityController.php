<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NationalityRequest;
use App\Models\Nationality;
use App\Models\NationalityTranslation;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class NationalityController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_nationalities', only: ['index']),
            new Middleware('permission:create_nationalities', only: ['create', 'store']),
            new Middleware('permission:update_nationalities', only: ['edit', 'update']),
            new Middleware('permission:delete_nationalities', only: ['delete', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        return view('admin.nationalities.index');

    }// end of index

    public function data()
    {
        $nationalities = Nationality::query()
            ->addSelect([
                'name' => NationalityTranslation::select('name')
                    ->whereColumn('nationality_id', 'nationalities.id')
                    ->where('locale', app()->getLocale())
                    ->take(1),
            ]);

        return DataTables::of($nationalities)
            ->addColumn('record_select', 'admin.nationalities.data_table.record_select')
            ->editColumn('created_at', function (Nationality $nationality) {
                return $nationality->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.nationalities.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        return view('admin.nationalities.create');

    }// end of create

    public function store(NationalityRequest $request)
    {
        Nationality::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.nationalities.index')
        ]);

    }// end of store

    public function edit(Nationality $nationality)
    {
        return view('admin.nationalities.edit', compact('nationality'));

    }// end of edit

    public function update(NationalityRequest $request, Nationality $nationality)
    {
        $nationality->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('admin.nationalities.index')
        ]);

    }// end of update

    public function destroy(Nationality $nationality)
    {
        $this->delete($nationality);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $nationality = Nationality::FindOrFail($recordId);

            $this->delete($nationality);

        }//end of for each

        session()->flash('success', __('site.deleted_successfully'));

        return redirect()->route('admin.nationalities.index');

    }// end of bulkDelete

    private function delete(Nationality $nationality)
    {
        $nationality->delete();

    }// end of delete

}//end of controller

