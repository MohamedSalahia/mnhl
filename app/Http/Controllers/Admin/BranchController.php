<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchTranslation;
use App\Models\Country;
use App\Models\Organization;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class BranchController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_branches', only: ['index']),
        ];

    }// end of middleware

    public function index()
    {
        $organizations = Organization::query()
            ->with(['translations'])
            ->get();

        $countries = Country::query()
            ->with(['translations'])
            ->get();

        return view('admin.branches.index', compact('organizations', 'countries'));

    }// end of index

    public function data()
    {
        $branches = Branch::query()
            ->with(['translations', 'organization.translations', 'country', 'governorate', 'area'])
            ->whenOrganizationId(request()->organization_id)
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
            ->addColumn('name', function (Branch $branch) {
                return $branch->name;
            })
            ->addColumn('organization', function (Branch $branch) {
                return $branch->organization->name ?? '-';
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
            ->rawColumns([])
            ->toJson();

    }// end of data

}//end of controller

