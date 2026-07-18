<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\BranchRequest;
use App\Models\Area;
use App\Models\Branch;
use App\Models\BranchTranslation;
use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class BranchController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_branches', only: ['index']),
            new Middleware('permission_with_team:create_branches', only: ['create', 'store']),
            new Middleware('permission_with_team:update_branches', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_branches', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        $organizationId = session('selected_organization')['id'];

        $countries = Country::query()
            ->with(['translations'])
            ->get();

        return view('organization.branches.index', compact('countries', 'organizationId'));

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];
        $branchId = session('selected_branch')['id'] ?? null;

        $branches = Branch::query()
            ->where('organization_id', $organizationId)
            ->with(['translations', 'country', 'governorate', 'area'])
            ->whenCountryId(request()->country_id)
            ->whenGovernorateId(request()->governorate_id)
            ->whenAreaId(request()->area_id)
            ->addSelect([
                'name' => BranchTranslation::query()
                    ->select('name')
                    ->whereColumn('branch_id', 'branches.id')
                    ->where('locale', app()->getLocale())
                    ->take(1)
            ]);

        return DataTables::of($branches)
            ->addColumn('record_select', 'organization.branches.data_table.record_select')
            ->addColumn('name', function (Branch $branch) {
                return $branch->name;
            })
            ->addColumn('country', function (Branch $branch) {
                return $branch->country?->name;
            })
            ->addColumn('governorate', function (Branch $branch) {
                return $branch->governorate?->name;
            })
            ->addColumn('area', function (Branch $branch) {
                return $branch->area?->name;
            })
            ->editColumn('created_at', function (Branch $branch) {
                return $branch->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.branches.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $countries = Country::query()
            ->with(['translations'])
            ->get();

        return view('organization.branches.create', compact('countries'));

    }// end of create

    public function store(BranchRequest $request)
    {
        $organizationId = session('selected_organization')['id'];

        Branch::create($request->validated());

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.branches.index'),
        ]);

    }// end of store

    public function edit(Branch $branch)
    {
        Gate::authorize('organization-branch', $branch);

        $branch->load(['country', 'governorate', 'area']);

        $countries = Country::query()
            ->with(['translations'])
            ->get();

        $governorates = Governorate::query()
            ->with(['translations'])
            ->where('country_id', $branch->country_id)
            ->get();

        $areas = Area::query()
            ->with(['translations'])
            ->where('governorate_id', $branch->governorate_id)
            ->get();

        return view('organization.branches.edit', compact('branch', 'countries', 'governorates', 'areas'));

    }// end of edit

    public function update(BranchRequest $request, Branch $branch)
    {
        Gate::authorize('organization-branch', $branch);

        $branch->update($request->validated());

        session()->forget('selected_branch');

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.branches.index'),
        ]);

    }// end of update

    public function destroy(Branch $branch)
    {
        $organizationId = session('selected_organization')['id'];

        abort_if($branch->organization_id !== $organizationId, 404);

        $this->delete($branch);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $organizationId = session('selected_organization')['id'];

        foreach (json_decode(request()->record_ids) as $recordId) {

            $branch = Branch::FindOrFail($recordId);

            abort_if($branch->organization_id !== $organizationId, 404);

            $this->delete($branch);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(Branch $branch)
    {
        $branch->delete();

    }// end of delete

}//end of controller
