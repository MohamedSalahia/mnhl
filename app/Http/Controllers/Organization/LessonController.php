<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;
use Yajra\DataTables\DataTables;

class LessonController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission_with_team:read_lessons', only: ['index', 'data', 'show', 'downloadReport']),
        ];

    }// end of middlewares

    public function index()
    {
        $teacher = null;

        if (request()->has('teacher_id')) {
            $teacher = User::findByHashId(request()->teacher_id);
        }

        return view('organization.lessons.index', compact('teacher'));

    }// end of index

    public function data()
    {
        $lessons = Lesson::query()
            ->with(['classroom', 'branch', 'studentLessons', 'teacher'])
            ->whenOrganizationId(session('selected_organization')['id'] ?? null)
            ->whenBranchId(session('selected_branch')['id'] ?? null)
            ->whenTeacherId(request()->teacher_id)
            ->whenClassroomId(request()->classroom_id)
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
            ->editColumn('teacher_id', function (Lesson $lesson) {
                return $lesson->teacher->name ?? '-';
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
            ->addColumn('actions', 'organization.lessons.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function show(Lesson $lesson)
    {
        $lesson->load([
            'classroom',
            'classroom.students',
            'studentLessons.student',
            'lessonEvaluationItems.evaluationItem',
            'teacher',
            'branch',
            'organization'
        ]);

        return view('organization.lessons.show', compact('lesson'));

    }// end of show

    public function downloadReport(Lesson $lesson)
    {
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
            'organization.lessons.report',
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

}//end of controller
