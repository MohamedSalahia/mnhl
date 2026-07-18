<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
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
            new Middleware('permission:read_admins', only: ['index']),
            new Middleware('permission:create_admins', only: ['create', 'store']),
            new Middleware('permission:update_admins', only: ['edit', 'update']),
            new Middleware('permission:delete_admins', only: ['delete', 'bulk_delete']),
        ];

    }// end of middlewares

    public function index()
    {
        $roles = Role::query()
            ->whereNotIn('name', ['super_admin', 'admin'])
            ->get();

        return view('admin.admins.index', compact('roles'));

    }// end of index

    public function data()
    {
        $admins = User::query()
            ->whereHasRole('admin')
            ->whenRoleId(request()->role_id);

        return DataTables::of($admins)
            ->addColumn('record_select', 'admin.admins.data_table.record_select')
            ->addColumn('roles', function (User $admin) {
                return view('admin.admins.data_table.roles', compact('admin'));
            })
            ->editColumn('created_at', function (User $admin) {
                return $admin->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.admins.data_table.actions')
            ->rawColumns(['record_select', 'roles', 'actions'])
            ->toJson();


    }// end of data

    public function create()
    {
        $roles = Role::whereNotIn('name', ['super_admin', 'admin'])->get();

        return view('admin.admins.create', compact('roles'));

    }// end of create

    public function store(AdminRequest $request)
    {
        $admin = User::create($request->validated());

        $admin->addRoles(['admin', $request->role_id]);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('admin.admins.index'),
        ]);

    }// end of store

    public function edit(User $admin)
    {
        $roles = Role::whereNotIn('name', ['super_admin', 'admin'])->get();

        return view('admin.admins.edit', compact('admin', 'roles'));

    }// end of edit

    public function update(AdminRequest $request, User $admin)
    {
        $admin->update($request->validated());

        $admin->syncRoles(['admin', $request->role_id]);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('admin.admins.index'),
        ]);

    }// end of update

    public function destroy(User $admin)
    {
        $this->delete($admin);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);


    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $admin = User::FindOrFail($recordId);
            $this->delete($admin);

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(User $admin)
    {
        $admin->delete();

    }// end of delete

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

}//end of controller
