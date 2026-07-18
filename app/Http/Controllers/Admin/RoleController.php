<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:read_roles', only: ['index']),
            new Middleware('permission:create_roles', only: ['create', 'store']),
            new Middleware('permission:update_roles', only: ['edit', 'update']),
            new Middleware('permission:delete_roles', only: ['delete', 'bulk_delete']),
        ];

    }// end of middlewares

    public function index()
    {
        return view('admin.roles.index');

    }// end of index

    public function data()
    {
        $roles = Role::query()
            ->withCount(['users'])
            ->whereNotIn('name', UserTypeEnum::getConstants())
            ->whereNull('organization_id');

        return DataTables::of($roles)
            ->addColumn('record_select', 'admin.roles.data_table.record_select')
            ->editColumn('created_at', function (Role $role) {
                return $role->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.roles.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();


    }// end of data

    public function create()
    {
        return view('admin.roles.create');

    }// end of create

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->only(['name']));

        $role->givePermissions($request->permissions);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.roles.index'),
        ]);
    }// end of store

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));

    }// end of edit

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->only(['name']));

        $role->syncPermissions($request->permissions);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('admin.roles.index'),
        ]);

    }// end of update

    public function destroy(Role $role)
    {
        $this->delete($role);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $role = Role::FindOrFail($recordId);
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
