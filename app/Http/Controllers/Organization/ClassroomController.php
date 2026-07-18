<?php

namespace App\Http\Controllers\Organization;

use App\Enums\ClassroomTypeEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\ClassroomRequest;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class ClassroomController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_classrooms', only: ['index', 'data', 'show']),
            new Middleware('permission_with_team:create_classrooms', only: ['create', 'store']),
            new Middleware('permission_with_team:update_classrooms', only: ['edit', 'update']),
            new Middleware('permission_with_team:delete_classrooms', only: ['destroy', 'bulkDelete']),
        ];

    }// end of middleware

    public function index()
    {
        return view('organization.classrooms.index');

    }// end of index

    public function data()
    {
        $selectedBranch = session('selected_branch');
        $selectedOrganization = session('selected_organization');

        $classrooms = Classroom::query()
            ->with(['teacher', 'branch'])
            ->whenBranchId($selectedBranch['id'] ?? null)
            ->whenTeacherId(request()->teacher_id)
            ->whenType(request()->type);

        return DataTables::of($classrooms)
            ->addColumn('record_select', 'organization.classrooms.data_table.record_select')
            ->editColumn('teacher_id', function (Classroom $classroom) {
                return $classroom->teacher->name ?? '';
            })
            ->editColumn('type', function (Classroom $classroom) {
                return $classroom->type == ClassroomTypeEnum::INDIVIDUAL
                    ? __('classrooms.individual')
                    : __('classrooms.group');
            })
            ->editColumn('start_date', function (Classroom $classroom) {
                return $classroom->start_date->format('Y-m-d');
            })
            ->editColumn('end_date', function (Classroom $classroom) {
                return $classroom->end_date->format('Y-m-d');
            })
            ->editColumn('created_at', function (Classroom $classroom) {
                return $classroom->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'organization.classrooms.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        $selectedBranch = session('selected_branch');

        $selectedOrganization = session('selected_organization');

        $teachers = User::query()
            ->whereHasRole(UserTypeEnum::TEACHER)
            ->whenTeacherOrganizationId($selectedOrganization['id'] ?? null)
            ->whenTeacherBranchId($selectedBranch['id'] ?? null)
            ->get();

        return view('organization.classrooms.create', compact('teachers'));

    }// end of create

    public function store(ClassroomRequest $request)
    {
        $selectedBranch = session('selected_branch');

        $requestData = $request->validated();
        $requestData['branch_id'] = $selectedBranch['id'];

        Classroom::create($requestData);

        session()->flash('success', __('site.added_successfully'));

        return response()->json([
            'redirect_to' => route('organization.classrooms.index'),
        ]);

    }// end of store

    public function show(Classroom $classroom)
    {
        Gate::authorize('organization-classroom', $classroom);

        $classroom->load(['teacher', 'branch']);

        return view('organization.classrooms.show', compact('classroom'));

    }// end of show

    public function details(Classroom $classroom)
    {
        Gate::authorize('organization-classroom', $classroom);

        $classroom->load(['teacher', 'branch']);

        return view('organization.classrooms._details', compact('classroom'));

    }// end of details

    public function students(Classroom $classroom)
    {
        Gate::authorize('organization-classroom', $classroom);

        $classroom->load(['teacher', 'branch']);

        return view('organization.classrooms._students', compact('classroom'));

    }// end of students

    public function lessons(Classroom $classroom)
    {
        Gate::authorize('organization-classroom', $classroom);

        $classroom->load(['teacher', 'branch']);

        return view('organization.classrooms._lessons', compact('classroom'));

    }// end of lessons

    public function edit(Classroom $classroom)
    {
        Gate::authorize('organization-classroom', $classroom);

        $selectedBranch = session('selected_branch');
        $selectedOrganization = session('selected_organization');

        $teachers = User::query()
            ->whereHasRole(UserTypeEnum::TEACHER)
            ->whenTeacherOrganizationId($selectedOrganization['id'] ?? null)
            ->whenTeacherBranchId($selectedBranch['id'] ?? null)
            ->get();

        return view('organization.classrooms.edit', compact('classroom', 'teachers'));

    }// end of edit

    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        Gate::authorize('organization-classroom', $classroom);

        $classroom->update($request->validated());

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.classrooms.index'),
        ]);

    }// end of update

    public function destroy(Classroom $classroom)
    {
        Gate::authorize('organization-classroom', $classroom);

        $this->delete($classroom);

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of destroy

    public function bulkDelete()
    {
        $selectedBranch = session('selected_branch');

        foreach (json_decode(request()->record_ids) as $recordId) {
            $classroom = Classroom::findOrFail($recordId);

            Gate::authorize('organization-classroom', $classroom);

            $this->delete($classroom);
        }

        return response()->json([
            'success_message' => __('site.deleted_successfully'),
        ]);

    }// end of bulkDelete

    private function delete(Classroom $classroom)
    {
        $classroom->delete();

    }// end of delete

}//end of controller
