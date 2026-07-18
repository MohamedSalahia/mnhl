<?php

namespace App\Http\Controllers\Organization;

use App\Enums\AdminTypeEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\AdminRequest;
use App\Models\Branch;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_admins', only: ['index', 'data']),
            new Middleware('permission_with_team:create_admins', only: ['create', 'store']),
            new Middleware('permission_with_team:update_admins', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_admins', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middlewares

    public function index()
    {
        $organizationId = session('selected_organization')['id'];

        $roles = Role::query()
            ->where('organization_id', $organizationId)
            ->whereNotIn('name', UserTypeEnum::getConstants())
            ->get();

        return view('organization.admins.index', compact('roles'));

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $admins = User::query()
            ->whereHasRole(UserTypeEnum::ORGANIZATION_ADMIN)
            ->whereHas('adminOrganizations', function ($query) use ($organizationId) {
                $query->where('organizations.id', $organizationId);
            })
            ->whenRoleId(request()->role_id);

        return DataTables::of($admins)
            ->addColumn('record_select', 'organization.admins.data_table.record_select')
            ->addColumn('roles', function (User $admin) {
                return view('organization.admins.data_table.roles', compact('admin'));
            })
            ->editColumn('created_at', function (User $admin) {
                return $admin->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.admins.data_table.actions')
            ->rawColumns(['record_select', 'roles', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $organizationId = session('selected_organization')['id'];

        $roles = Role::query()
            ->where('organization_id', $organizationId)
            ->whereNotIn('name', UserTypeEnum::getConstants())
            ->get();

        return view('organization.admins.create', compact('roles'));

    }// end of create

    public function store(AdminRequest $request)
    {
        $organizationId = session('selected_organization')['id'];

        $selectedBranch = session('selected_branch');

        $admin = User::create($request->validated());

        $admin->adminOrganizations()->attach($organizationId, [
            'type' => AdminTypeEnum::ADMIN,
        ]);

        $admin->adminBranches()->attach($selectedBranch['id']);

        $admin->syncRoles([$request->role_id, UserTypeEnum::ORGANIZATION_ADMIN], $selectedBranch['team_id']);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.admins.index'),
        ]);

    }// end of store

    public function edit(User $admin)
    {
        $organizationId = session('selected_organization')['id'];

        if (!$admin->adminOrganizations()->where('organizations.id', $organizationId)->exists()) {
            abort(404);
        }

        $roles = Role::query()
            ->where('organization_id', $organizationId)
            ->whereNotIn('name', UserTypeEnum::getConstants())
            ->get();

        return view('organization.admins.edit', compact('admin', 'roles'));

    }// end of edit

    public function update(AdminRequest $request, User $admin)
    {
        $organizationId = session('selected_organization')['id'];

        $selectedBranch = session('selected_branch');

        // Verify admin belongs to the organization
        if (!$admin->adminOrganizations()->where('organizations.id', $organizationId)->exists()) {
            abort(404);
        }

        $requestData = $request->validated();

        // Remove password if empty (for updates)
        if (empty($requestData['password'])) {
            unset($requestData['password']);
        }

        // Update admin user
        $admin->update($requestData);

        $admin->syncRoles([$requestData['role_id'], UserTypeEnum::ORGANIZATION_ADMIN], $selectedBranch['team_id']);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.admins.index'),
        ]);

    }// end of update

    public function destroy(User $admin)
    {
        $organizationId = session('selected_organization')['id'];

        // Verify admin belongs to the organization
        if (!$admin->adminOrganizations()->where('organizations.id', $organizationId)->exists()) {
            abort(404);
        }

        $this->delete($admin);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $organizationId = session('selected_organization')['id'];

        foreach (json_decode(request()->record_ids) as $recordId) {

            $admin = User::FindOrFail($recordId);

            // Verify admin belongs to the organization
            if ($admin->adminOrganizations()->where('organizations.id', $organizationId)->exists()) {
                $this->delete($admin);
            }

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(User $admin)
    {
        $admin->delete();

    }// end of delete

    public function leaveImpersonate()
    {
        auth()->user()->leaveImpersonation();

        session(['locale' => auth()->user()->locale]);

        session()->forget('selected_organization');

        session()->forget('selected_branch');

        return redirect()->route('admin.home');

    }//end of leave impersonate

    public function switchLanguage(Request $request)
    {
        request()->validate([
            'locale' => 'required|in:' . implode(',', array_keys(config('localization.supportedLocales'))),
        ]);

        auth()->user()->update(['locale' => $request['locale']]);

        session(['locale' => $request['locale']]);

        return redirect()->back();

    }// end of switchLanguage

    public function toggleDarkMode()
    {
        auth()->user()->update([
            'dark_mode' => !auth()->user()->dark_mode
        ]);

    }// end of toggleDarkMode

    public function toggleMenuCollapsed()
    {
        auth()->user()->update([
            'menu_collapsed' => !auth()->user()->menu_collapsed
        ]);

    }// end of toggleMenuCollapsed

    public function switchOrganization(Organization $organization)
    {
        $user = auth()->user();

        if (!$user->adminOrganizations()->where('organizations.id', $organization->id)->exists()) {

            session()->flash('error', __('site.unauthorized_access'));
            return redirect()->back();

        }

        session(['selected_organization' => $organization]);

        session()->forget('selected_branch');

        return redirect()->route('organization.home');

    }// end of switchOrganization

    public function switchBranch(Branch $branch)
    {
        $user = auth()->user();

        // Check if user has access to this branch
        $selectedOrganization = session('selected_organization');

        if (!$selectedOrganization) {
            session()->flash('error', __('site.unauthorized_access'));
            return redirect()->back();
        }

        // Super admin can access any branch in their organization
        if ($user->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {

            if ($branch->organization_id != $selectedOrganization['id']) {

                session()->flash('error', __('site.unauthorized_access'));
                return redirect()->back();
            }

        } else {

            if (!$user->adminBranches()->where('branches.id', $branch->id)->exists()) {

                session()->flash('error', __('site.unauthorized_access'));
                return redirect()->back();

            }
        }

        session(['selected_branch' => $branch]);

        return redirect()->route('organization.home');

    }// end of switchBranch

}//end of controller
