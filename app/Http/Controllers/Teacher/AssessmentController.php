<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\AssessmentStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\AssessmentRequest;
use App\Http\Requests\Teacher\AssessmentStoreDeductionRequest;
use App\Models\Assessment;
use App\Models\AssessmentDeduction;
use App\Models\BranchStudent;
use App\Models\Level;
use App\Models\Project;
use App\Models\StudentLesson;
use App\Models\User;
use App\Services\AssessmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AssessmentController extends Controller
{
    public function create(Request $request)
    {
        $studentLessonId = $request->query('student_lesson_id');

        if (!$studentLessonId) {
            abort(400, 'Missing required parameter: student_lesson_id');
        }

        $studentLesson = StudentLesson::with([
            'lesson.branch',
            'student',
        ])->findOrFail($studentLessonId);

        // Verify teacher owns this lesson
        if (!$studentLesson->lesson || $studentLesson->lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        // Get branch and team_id
        $branch = $studentLesson->lesson->branch;
        $teamId = $branch->team_id ?? null;

        if (!$teamId) {
            abort(400, 'Team not found for this branch');
        }

        // Get assessment_scheme_id from student's level
        $branchStudent = BranchStudent::where('student_id', $studentLesson->student_id)
            ->where('branch_id', $studentLesson->lesson->branch_id)
            ->where('classroom_id', $studentLesson->lesson->classroom_id)
            ->first();

        $assessmentSchemeId = null;

        if ($branchStudent && $branchStudent->level_id) {
            $level = Level::find($branchStudent->level_id);
            $assessmentSchemeId = $level->assessment_scheme_id ?? null;
        }

        $examiners = User::query()
            ->whereHas('roles', function ($query) use ($teamId) {
                $query->where('name', 'examiner')
                    ->where('team_id', $teamId);
            })
            ->get();

        return response()->json([
            'view' => view('teacher.assessments._create', compact('studentLesson', 'examiners', 'assessmentSchemeId'))->render(),
        ]);

    }// end of create

    public function store(AssessmentRequest $request)
    {
        $studentLesson = StudentLesson::query()
            ->with('lesson.branch')
            ->findOrFail($request->student_lesson_id);

        // Verify teacher owns this lesson
        if (!$studentLesson->lesson || $studentLesson->lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        // Get assessment_scheme_id from student's level
        $branchStudent = BranchStudent::query()
            ->where('student_id', $studentLesson->student_id)
            ->where('branch_id', $studentLesson->lesson->branch_id)
            ->where('classroom_id', $studentLesson->lesson->classroom_id)
            ->first();

        $assessmentSchemeId = null;
        $curriculumId = null;
        $projectId = null;
        $levelId = null;

        if ($branchStudent) {
            $curriculumId = $branchStudent->curriculum_id;
            $projectId = $branchStudent->project_id;
            $levelId = $branchStudent->level_id;

            if ($levelId) {
                $level = Level::find($levelId);
                $assessmentSchemeId = $level->assessment_scheme_id ?? null;
            }
        }

        // Get organization_id and branch_id from lesson
        $branch = $studentLesson->lesson->branch;
        $organizationId = $branch->organization_id ?? null;
        $branchId = $studentLesson->lesson->branch_id;

        // Create assessment with pending status
        $assessment = Assessment::create([
            'student_id' => $studentLesson->student_id,
            'examiner_id' => $request->examiner_id,
            'assessment_scheme_id' => $assessmentSchemeId,
            'organization_id' => $organizationId,
            'branch_id' => $branchId,
            'curriculum_id' => $curriculumId,
            'project_id' => $projectId,
            'level_id' => $levelId,
            'status' => AssessmentStatusEnum::PENDING,
        ]);

        session()->flash('success', __('assessments.assessment_created'));

        return response()->json([
            'redirect_to' => route('teacher.assessments.show', $assessment->id),
        ]);

    }// end of store

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        return view('teacher.assessments.index', compact('status'));

    }// end of index

    public function data(Request $request)
    {
        $selectedBranch = session('selected_branch');
        $selectedOrganization = session('selected_organization');

        $organizationId = $selectedOrganization['id'] ?? null;
        $branchId = $selectedBranch['id'] ?? null;

        $assessments = Assessment::query()
            ->with(['student', 'examiner', 'assessmentScheme'])
            ->when(!auth()->user()->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN), function ($q) {
                $q->whenExaminerId(auth()->user()->id);
            })
            ->whenOrganizationId($organizationId)
            ->whenBranchId($branchId)
            ->whenStatus(request()->status ?? 'pending');

        return DataTables::of($assessments)
            ->editColumn('student_id', function (Assessment $assessment) {
                return $assessment->student->name ?? '-';
            })
            ->editColumn('examiner_id', function (Assessment $assessment) {
                return $assessment->examiner->name ?? '-';
            })
            ->editColumn('assessment_scheme_id', function (Assessment $assessment) {
                return $assessment->assessmentScheme->name ?? '-';
            })
            ->editColumn('status', function (Assessment $assessment) {
                $statusLabels = [
                    AssessmentStatusEnum::PENDING => __('assessments.status_pending'),
                    AssessmentStatusEnum::IN_PROGRESS => __('assessments.status_in_progress'),
                    AssessmentStatusEnum::PARTIALLY_IN_PROGRESS => __('assessments.status_partially_in_progress'),
                    AssessmentStatusEnum::COMPLETED => __('assessments.status_completed'),
                ];

                return $statusLabels[$assessment->status] ?? $assessment->status;
            })
            ->editColumn('notes', function (Assessment $assessment) {
                return $assessment->notes ?? '-';
            })
            ->editColumn('created_at', function (Assessment $assessment) {
                return $assessment->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'teacher.assessments.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function show(Assessment $assessment)
    {
        // Verify examiner owns this assessment or user is organization super admin
        if ($assessment->examiner_id != auth()->id() && !auth()->user()->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {
            abort(403);
        }

        // Load assessment with relationships
        $assessment->load([
            'student',
            'examiner',
            'assessmentScheme',
            'branch.translations',
            'organization',
            'curriculum',
            'project',
            'level',
            'statuses' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        // Load student's branch info (project, level, page_number)
        $branchStudent = null;
        if ($assessment->branch_id) {
            $branchStudent = BranchStudent::query()
                ->with(['project', 'level', 'curriculum', 'classroom'])
                ->whenStudentId($assessment->student_id)
                ->whenBranchId($assessment->branch_id)
                ->whenProjectId($assessment->project_id)
                ->whenCurriculumId($assessment->curriculum_id)
                ->whenLevelId($assessment->level_id)
                ->first();
        }

        // Load student's lessons for this branch
        $studentLessons = StudentLesson::query()
            ->where('student_id', $assessment->student_id)
            ->whereHas('lesson', function ($q) use ($assessment) {
                $q->where('branch_id', $assessment->branch_id);
            })
            ->with([
                'lesson.classroom',
                'lesson.lessonEvaluationItems' => function ($q) use ($assessment) {
                    $q->where('student_id', $assessment->student_id)->orderBy('page_number');
                },
                'lesson.lessonEvaluationItems.evaluationItem'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Load lessons and evaluation items related to assessment's curriculum, project, level
        $relatedStudentLessons = collect();

        if ($assessment->curriculum_id || $assessment->project_id || $assessment->level_id) {
            $relatedStudentLessons = StudentLesson::query()
                ->where('student_id', $assessment->student_id)
                ->when($assessment->curriculum_id, function ($q) use ($assessment) {
                    $q->where('curriculum_id', $assessment->curriculum_id);
                })
                ->when($assessment->project_id, function ($q) use ($assessment) {
                    $q->where('project_id', $assessment->project_id);
                })
                ->when($assessment->level_id, function ($q) use ($assessment) {
                    $q->where('level_id', $assessment->level_id);
                })
                ->whereHas('lesson', function ($q) use ($assessment) {
                    $q->where('branch_id', $assessment->branch_id);
                })
                ->with([
                    'lesson.classroom',
                    'lesson.lessonEvaluationItems' => function ($q) use ($assessment) {
                        $q->where('student_id', $assessment->student_id)->orderBy('page_number');
                    },
                    'lesson.lessonEvaluationItems.evaluationItem'
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('teacher.assessments.show', compact('assessment', 'studentLessons', 'branchStudent', 'relatedStudentLessons'));

    }// end of show

    public function start(Assessment $assessment)
    {
        // Verify examiner owns this assessment or user is organization super admin
        if ($assessment->examiner_id != auth()->id() && !auth()->user()->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {
            abort(403);
        }

        // Verify assessment is pending
        if ($assessment->status !== AssessmentStatusEnum::PENDING) {
            abort(400, __('assessments.only_pending_assessments_can_be_started'));
        }

        // Load assessment with scheme and deductions
        $assessment->load([
            'assessmentScheme.deductions' => function ($query) {
                $query->orderBy('order');
            },
            'assessmentDeductions'
        ]);

        $assessment->update(['status' => AssessmentStatusEnum::IN_PROGRESS]);

        return response()->json([
            'view' => view('teacher.assessments._start', compact('assessment'))->render(),
        ]);

    }// end of start

    public function resume(Assessment $assessment)
    {
        // Verify examiner owns this assessment or user is organization super admin
        if ($assessment->examiner_id != auth()->id() && !auth()->user()->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {
            abort(403);
        }

        // Verify assessment is partially in progress
        if (!in_array($assessment->status, [AssessmentStatusEnum::IN_PROGRESS, AssessmentStatusEnum::PARTIALLY_IN_PROGRESS])) {
            abort(400, __('assessments.only_partially_in_progress_assessments_can_be_resumed'));
        }

        // Load assessment with scheme and deductions
        $assessment->load([
            'assessmentScheme.deductions' => function ($query) {
                $query->orderBy('order');
            },
            'assessmentDeductions'
        ]);

        return response()->json([
            'view' => view('teacher.assessments._start', compact('assessment'))->render(),
        ]);

    }// end of resume

    public function storeDeductions(AssessmentStoreDeductionRequest $request, Assessment $assessment, AssessmentService $assessmentService)
    {
        // Verify examiner owns this assessment or user is organization super admin
        if ($assessment->examiner_id != auth()->id() && !auth()->user()->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            // Delete existing deductions
            AssessmentDeduction::where('assessment_id', $assessment->id)->delete();

            // Create new deduction records
            foreach ($request->deductions as $deductionData) {
                if (isset($deductionData['quantity']) && $deductionData['quantity'] > 0) {
                    AssessmentDeduction::create([
                        'assessment_id' => $assessment->id,
                        'assessment_scheme_deduction_id' => $deductionData['assessment_scheme_deduction_id'],
                        'quantity' => $deductionData['quantity'],
                        'organization_id' => $assessment->organization_id,
                        'branch_id' => $assessment->branch_id,
                    ]);
                }
            }

            // Calculate score based on deductions
            $score = null;

            if ($assessment->level && $assessment->level->max_score) {
                $totalDeductions = 0;

                // Load created deductions with their scheme deductions to get values
                $createdDeductions = AssessmentDeduction::where('assessment_id', $assessment->id)
                    ->with('assessmentSchemeDeduction')
                    ->get();

                foreach ($createdDeductions as $deduction) {
                    if ($deduction->assessmentSchemeDeduction) {
                        $totalDeductions += $deduction->quantity * $deduction->assessmentSchemeDeduction->value;
                    }
                }

                $score = max(0, $assessment->level->max_score - $totalDeductions);
            }

            // Update assessment status, score, and notes based on user selection
            $assessment->update([
                'status' => $request->status,
                'score' => $score,
                'notes' => $request->notes,
            ]);

            // Handle student progression
            $assessmentService->handleProgression($assessment);

            DB::commit();

            session()->flash('success', __('site.updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('teacher.assessments.show', $assessment->id),
        ]);

    }// end of storeDeductions

}//end of controller
