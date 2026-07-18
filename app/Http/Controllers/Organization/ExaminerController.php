<?php

namespace App\Http\Controllers\Organization;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\ExaminerRequest;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\DataTables;

class ExaminerController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_examiners', only: ['index', 'data']),
            new Middleware('permission_with_team:create_examiners', only: ['create', 'store']),
            new Middleware('permission_with_team:update_examiners', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_examiners', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middlewares

    public function index()
    {
        return view('organization.examiners.index');

    }// end of index

    public function data()
    {
        $organizationId = session('selected_organization')['id'];
        $selectedBranch = session('selected_branch');

        $examiners = User::query()
            ->whereHasRole(UserTypeEnum::EXAMINER)
            ->whenExaminerOrganizationId($organizationId)
            ->whenExaminerBranchId($selectedBranch['id'] ?? null);

        return DataTables::of($examiners)
            ->addColumn('record_select', function (User $examiner) {
                return view('organization.examiners.data_table.record_select', ['id' => $examiner->id])->render();
            })
            ->editColumn('created_at', function (User $examiner) {
                return $examiner->created_at->format('Y-m-d');
            })
            ->addColumn('actions', function (User $examiner) use ($selectedBranch) {
                $hasTeacherRole = $examiner->hasRole(UserTypeEnum::TEACHER, $selectedBranch['team_id'] ?? null);
                return view('organization.examiners.data_table.actions', ['id' => $examiner->id, 'hasTeacherRole' => $hasTeacherRole])->render();
            })
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        return view('organization.examiners.create');

    }// end of create

    public function store(ExaminerRequest $request)
    {
        $selectedBranch = session('selected_branch');

        $examiner = User::create($request->validated());

        $examiner->examinerBranches()->attach($selectedBranch['id']);

        $examiner->syncRoles([UserTypeEnum::EXAMINER], $selectedBranch['team_id']);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.examiners.index'),
        ]);

    }// end of store

    public function edit(User $examiner)
    {
        $organizationId = session('selected_organization')['id'];
        $selectedBranch = session('selected_branch');

        // Verify examiner belongs to the branch
        if (!$examiner->examinerBranches()->where('branches.id', $selectedBranch['id'])->exists()) {
            abort(404);
        }

        return view('organization.examiners.edit', compact('examiner'));

    }// end of edit

    public function update(ExaminerRequest $request, User $examiner)
    {
        $organizationId = session('selected_organization')['id'];
        $selectedBranch = session('selected_branch');

        // Verify examiner belongs to the branch
        if (!$examiner->examinerBranches()->where('branches.id', $selectedBranch['id'])->exists()) {
            abort(404);
        }

        $requestData = $request->validated();

        // Remove password if empty (for updates)
        if (empty($requestData['password'])) {
            unset($requestData['password']);
        }

        // Update examiner user
        $examiner->update($requestData);

        $examiner->syncRoles([UserTypeEnum::EXAMINER], $selectedBranch['team_id']);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.examiners.index'),
        ]);

    }// end of update

    public function destroy(User $examiner)
    {
        $selectedBranch = session('selected_branch');

        // Verify examiner belongs to the branch
        if (!$examiner->examinerBranches()->where('branches.id', $selectedBranch['id'])->exists()) {
            abort(404);
        }

        $this->delete($examiner);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $selectedBranch = session('selected_branch');

        foreach (json_decode(request()->record_ids) as $recordId) {

            $examiner = User::findOrFail($recordId);

            // Verify examiner belongs to the branch
            if ($examiner->examinerBranches()->where('branches.id', $selectedBranch['id'])->exists()) {
                $this->delete($examiner);
            }

        }//end of for each

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(User $examiner)
    {
        $examiner->delete();

    }// end of delete

}//end of controller
