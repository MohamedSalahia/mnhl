<?php

namespace App\Http\Controllers\Organization;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\RoleRequest;
use App\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_roles', only: ['index']),
            new Middleware('permission_with_team:create_roles', only: ['create', 'store']),
            new Middleware('permission_with_team:update_roles', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_roles', only: ['delete', 'bulk_delete']),
        ];

    }// end of middlewares

    public function index()
    {
        return view('organization.roles.index');

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];

        $roles = Role::query()
            ->where('organization_id', $organizationId)
            ->whereNotIn('name', UserTypeEnum::getConstants())
            ->withCount(['users']);

        return DataTables::of($roles)
            ->addColumn('record_select', 'organization.roles.data_table.record_select')
            ->editColumn('created_at', function (Role $role) {
                return $role->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.roles.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();


    }// end of data

    public function create()
    {
        return view('organization.roles.create');

    }// end of create

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->only(['name', 'organization_id']));

        $role->givePermissions($request->permissions);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.roles.index'),
        ]);
    }// end of store

    public function edit(Role $role)
    {
        Gate::authorize('organization-role', $role);

        return view('organization.roles.edit', compact('role'));

    }// end of edit

    public function update(RoleRequest $request, Role $role)
    {
        Gate::authorize('organization-role', $role);

        $role->update($request->only(['name']));

        $role->syncPermissions($request->permissions);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.roles.index'),
        ]);

    }// end of update

    public function destroy(Role $role)
    {
        $organizationId = session('selected_organization')['id'];

        abort_if($role->organization_id !== $organizationId, 404);

        $this->delete($role);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $organizationId = session('selected_organization')['id'];

        foreach (json_decode(request()->record_ids) as $recordId) {

            $role = Role::FindOrFail($recordId);

            abort_if($role->organization_id !== $organizationId, 404);

            $this->delete($role);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(Role $role)
    {
        $role->delete();

    }// end of delete

}//end of controller
