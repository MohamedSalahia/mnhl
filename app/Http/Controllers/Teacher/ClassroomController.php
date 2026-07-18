<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\ClassroomTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Yajra\DataTables\DataTables;

class ClassroomController extends Controller
{
    public function index()
    {
        return view('teacher.classrooms.index');

    }// end of index

    public function data()
    {
        $selectedBranch = session('selected_branch');

        $classrooms = Classroom::query()
            ->with(['teacher', 'branch'])
            ->whenTeacherId(auth()->user()->hash_id)
            ->whenBranchId($selectedBranch['id'] ?? null)
            ->whenType(request()->type);

        return DataTables::of($classrooms)
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
            ->addColumn('actions', 'teacher.classrooms.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function show(Classroom $classroom)
    {
        // Verify the classroom belongs to the authenticated teacher
        if ($classroom->teacher_id != auth()->id()) {
            abort(403);
        }

        $classroom->load(['teacher', 'branch', 'students']);

        return view('teacher.classrooms.show', compact('classroom'));

    }// end of show

}//end of controller
