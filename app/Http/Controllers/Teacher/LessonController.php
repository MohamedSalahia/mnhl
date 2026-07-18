<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\ClassroomTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\LessonRequest;
use App\Models\BranchStudent;
use App\Models\EvaluationItem;
use App\Models\Lesson;
use App\Models\StudentLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;
use Yajra\DataTables\DataTables;

class LessonController extends Controller
{
    public function index()
    {
        return view('teacher.lessons.index');

    }// end of index

    public function data()
    {
        $selectedBranch = session('selected_branch');

        $lessons = Lesson::query()
            ->with(['classroom', 'branch', 'studentLessons'])
            ->whenTeacherId(auth()->user()->hash_id)
            ->whenBranchId($selectedBranch['id'] ?? null)
            ->whenDateRange(request()->date_range);

        return DataTables::of($lessons)
            ->editColumn('date', function (Lesson $lesson) {
                return $lesson->date->format('Y-m-d');
            })
            ->editColumn('classroom_id', function (Lesson $lesson) {
                return $lesson->classroom->name ?? '-';
            })
            ->editColumn('branch_id', function (Lesson $lesson) {
                return $lesson->branch->name ?? '-';
            })
            ->addColumn('students_count', function (Lesson $lesson) {
                return $lesson->studentLessons->count();
            })
            ->addColumn('time_elapsed', function (Lesson $lesson) {
                if ($lesson->time_elapsed !== null) {
                    return $lesson->time_elapsed . ' ' . __('site.minutes');
                }
                return '-';
            })
            ->addColumn('actions', 'teacher.lessons.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function store(LessonRequest $request)
    {
        $lesson = Lesson::create($request->validated());

        $lesson->load('classroom.students');


        if ($lesson->classroom && $lesson->classroom->students) {

            foreach ($lesson->classroom->students as $student) {

                // Get curriculum_id, project_id, level_id from BranchStudent
                $branchStudent = null;

                if ($lesson->branch_id && $lesson->classroom_id) {

                    $branchStudent = BranchStudent::query()
                        ->where('student_id', $student->id)
                        ->where('branch_id', $lesson->branch_id)
                        ->where('classroom_id', $lesson->classroom_id)
                        ->first();


                }

                StudentLesson::create([
                    'student_id' => $student->id,
                    'lesson_id' => $lesson->id,
                    'curriculum_id' => $branchStudent->curriculum_id ?? null,
                    'project_id' => $branchStudent->project_id ?? null,
                    'level_id' => $branchStudent->level_id ?? null,
                    'attendance_status' => null,
                ]);

            }//end of foreach

        }//end of if

        return response()->json([
            'redirect_to' => route('teacher.lessons.show', $lesson->id),
        ]);

    }// end of store

    public function show(Lesson $lesson)
    {
        // Verify the lesson belongs to the authenticated teacher
        if ($lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        $lesson->load([
            'classroom',
            'classroom.students',
            'studentLessons.student',
            'studentLessons.evaluationItem',
            'lessonEvaluationItems.evaluationItem',
            'teacher',
            'branch',
            'organization'
        ]);

        // Load evaluation items for the organization
        $evaluationItems = EvaluationItem::where('organization_id', $lesson->organization_id)
            ->orderBy('order')
            ->get();

        return view('teacher.lessons.show', compact('lesson', 'evaluationItems'));

    }// end of show

    public function downloadReport(Lesson $lesson)
    {
        // Verify the lesson belongs to the authenticated teacher
        if ($lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        $lesson->load([
            'classroom',
            'classroom.students',
            'studentLessons.student',
            'studentLessons.evaluationItem',
            'studentLessons.project',
            'lessonEvaluationItems.evaluationItem',
            'teacher',
            'branch',
            'organization'
        ]);

        // Build sequential number map for students within this organization
        $studentSequentialNumbers = [];
        if ($lesson->organization_id) {
            $orgStudents = DB::table('organization_student')
                ->where('organization_id', $lesson->organization_id)
                ->orderBy('created_at')
                ->orderBy('student_id')
                ->pluck('student_id');
            foreach ($orgStudents as $index => $sid) {
                $studentSequentialNumbers[$sid] = $index + 1;
            }
        }

        $pdf = LaravelMpdf::loadView(
            'teacher.lessons.report',
            compact('lesson', 'studentSequentialNumbers'),
            [],
            [
                'mode' => 'utf-8',
                'format' => 'A4',
                'autoArabic' => true,
                'title' => 'Lesson Report - ' . $lesson->date->format('Y-m-d'),
                'custom_font_dir' => base_path('/resources/fonts/'),
                'custom_font_data' => [
                    'cairo' => [
                        'R' => 'Din-Regular.ttf',
                        'B' => 'Din-Bold.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                ],
            ]
        );

        $pdf->getMpdf()->autoScriptToLang = true;
        $pdf->getMpdf()->autoLangToFont = true;

        $filename = 'lesson-report-' . $lesson->date->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);

    }// end of downloadReport

    public function editTimeElapsed(Lesson $lesson)
    {
        // Verify the lesson belongs to the authenticated teacher
        if ($lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        // Ensure classroom is group type
        if (!$lesson->classroom || $lesson->classroom->type !== ClassroomTypeEnum::GROUP) {
            abort(403);
        }

        // Ensure all students have been evaluated
        if (!$lesson->canEditTimeElapsed()) {
            abort(403);
        }

        return response()->json([
            'view' => view('teacher.lessons.edit._time_elapsed', compact('lesson'))->render(),
        ]);

    }// end of editTimeElapsed

    public function updateTimeElapsed(Request $request, Lesson $lesson)
    {
        // Verify the lesson belongs to the authenticated teacher
        if ($lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        // Ensure classroom is group type
        if (!$lesson->classroom || $lesson->classroom->type !== ClassroomTypeEnum::GROUP) {
            abort(403);
        }

        // Ensure all students have been evaluated
        if (!$lesson->canEditTimeElapsed()) {
            abort(403);
        }

        $validated = $request->validate([
            'time_elapsed' => ['required', 'integer', 'min:0'],
        ]);

        $lesson->update(['time_elapsed' => $validated['time_elapsed']]);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('teacher.lessons.show', $lesson->id),
        ]);

    }// end of updateTimeElapsed

}//end of controller
