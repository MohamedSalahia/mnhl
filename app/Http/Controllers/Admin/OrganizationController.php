<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrganizationRequest;
use App\Models\Area;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\Organization;
use App\Models\OrganizationTranslation;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class OrganizationController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_organizations', only: ['index', 'show']),
            new Middleware('permission:create_organizations', only: ['create', 'store']),
            new Middleware('permission:update_organizations', only: ['edit', 'update']),
            new Middleware('permission:delete_organizations', only: ['delete', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        $countries = Country::query()
            ->with(['translations'])
            ->get();

        return view('admin.organizations.index', compact('countries'));

    }// end of index

    public function data()
    {
        $organizations = Organization::query()
            ->with(['translations', 'country', 'governorate', 'area'])
            ->withCount('branches')
            ->whenCountryId(request()->country_id)
            ->whenGovernorateId(request()->governorate_id)
            ->whenAreaId(request()->area_id)
            ->addSelect([
                'name' => OrganizationTranslation::query()
                    ->select('name')
                    ->whereColumn('organization_id', 'organizations.id')
                    ->where('locale', app()->getLocale())
                    ->take(1)
            ]);

        return DataTables::of($organizations)
            ->addColumn('record_select', 'admin.organizations.data_table.record_select')
            ->addColumn('logo', function (Organization $organization) {
                return '<img src="' . $organization->logo_path . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;" />';
            })
            ->addColumn('country', function (Organization $organization) {
                return $organization->country?->name;
            })
            ->addColumn('governorate', function (Organization $organization) {
                return $organization->governorate?->name;
            })
            ->addColumn('area', function (Organization $organization) {
                return $organization->area?->name;
            })
            ->addColumn('branches_count', function (Organization $organization) {
                return $organization->branches_count;
            })
            ->editColumn('created_at', function (Organization $organization) {
                return $organization->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.organizations.data_table.actions')
            ->rawColumns(['record_select', 'logo', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $countries = Country::all();

        $existingSuperAdmins = User::query()
            ->whereHasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)
            ->select('id', 'name', 'email')
            ->get();

        return view('admin.organizations.create', compact('countries', 'existingSuperAdmins'));

    }// end of create

    public function store(OrganizationRequest $request, OrganizationService $organizationService)
    {
        $organizationService->storeOrganization($request);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.organizations.index')
        ]);

    }// end of store

    public function show(Organization $organization)
    {
        $organization->load([
            'branches.translations',
            'branches.country',
            'branches.governorate',
            'branches.area',
            'translations',
            'country',
            'governorate',
            'area',
        ]);

        return view('admin.organizations.show', compact('organization'));

    }// end of show

    public function edit(Organization $organization)
    {
        $organization->load(['country', 'governorate', 'area']);

        $countries = Country::all();

        $governorates = Governorate::query()
            ->with(['translations'])
            ->where('country_id', $organization->country_id)
            ->get();

        $areas = Area::query()
            ->with(['translations'])
            ->where('governorate_id', $organization->governorate_id)
            ->get();

        return view('admin.organizations.edit', compact('organization', 'countries', 'governorates', 'areas'));

    }// end of edit

    public function update(OrganizationRequest $request, Organization $organization, OrganizationService $organizationService)
    {
        $organizationService->update($request, $organization);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('admin.organizations.index')
        ]);

    }// end of update

    public function destroy(Organization $organization, OrganizationService $organizationService)
    {
        $this->delete($organization, $organizationService);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete(OrganizationService $organizationService)
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $organization = Organization::FindOrFail($recordId);

            $this->delete($organization, $organizationService);

        }//end of for each

        session()->flash('success', __('site.deleted_successfully'));

        return redirect()->route('admin.organizations.index');

    }// end of bulkDelete

    private function delete(Organization $organization, OrganizationService $organizationService)
    {
        $organizationService->delete($organization);

    }// end of delete

    public function impersonate(Organization $organization)
    {
        $organizationSuperAdmin = $organization->superAdmins()->first();

        if (!$organizationSuperAdmin) {
            session()->flash('error', __('site.organization_super_admin_not_found'));
            return redirect()->back();
        }

        auth()->user()->impersonate($organizationSuperAdmin);

        return redirect()->route('organization.home');

    }// end of impersonate

}//end of controller

