<?php

namespace App\Http\Controllers\Organization;

use App\Enums\AssessmentStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentDeduction;
use App\Models\BranchStudent;
use App\Models\StudentLesson;
use App\Models\User;
use App\Services\AssessmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        return view('organization.assessments.index', compact('status'));

    }// end of index

    public function data(Request $request)
    {
        $selectedBranch = session('selected_branch');
        $selectedOrganization = session('selected_organization');

        $organizationId = $selectedOrganization['id'] ?? null;
        $branchId = $selectedBranch['id'] ?? null;

        $assessments = Assessment::query()
            ->with(['student', 'examiner', 'assessmentScheme'])
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
            ->addColumn('actions', 'organization.assessments.data_table.actions')
            ->rawColumns(['actions'])
            ->toJson();

    }// end of data

    public function show(Assessment $assessment)
    {
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

        return view('organization.assessments.show', compact('assessment', 'studentLessons', 'branchStudent', 'relatedStudentLessons'));

    }// end of show

    public function editExaminer(Assessment $assessment)
    {
        // Load branch to get team_id
        $assessment->load('branch');

        if (!$assessment->branch || !$assessment->branch->team_id) {
            return response()->json([
                'error' => __('assessments.examiner_team_not_found'),
            ], 400);
        }

        // Query examiners for this branch's team
        $examiners = User::query()
            ->whereHas('roles', function ($query) use ($assessment) {
                $query->where('name', UserTypeEnum::EXAMINER)
                    ->where('team_id', $assessment->branch->team_id);
            })
            ->orderBy('name')
            ->get();

        return response()->json([
            'view' => view('organization.assessments._edit_examiner', compact('assessment', 'examiners'))->render(),
        ]);

    }// end of editExaminer

    public function updateExaminer(Request $request, Assessment $assessment)
    {
        // Load branch to get team_id
        $assessment->load('branch');

        if (!$assessment->branch || !$assessment->branch->team_id) {
            return response()->json([
                'error' => __('assessments.examiner_team_not_found'),
            ], 400);
        }

        // Validate examiner_id
        $validator = Validator::make($request->all(), [
            'examiner_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($assessment) {
                    $examiner = User::find($value);
                    if (!$examiner || !$examiner->hasRole(UserTypeEnum::EXAMINER, $assessment->branch->team_id)) {
                        $fail(__('assessments.examiner_not_in_branch'));
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update examiner
        $assessment->update([
            'examiner_id' => $request->examiner_id,
        ]);

        return response()->json([
            'success_message' => __('assessments.examiner_updated_successfully'),
            'redirect_to' => route('organization.assessments.show', $assessment->id),
        ]);

    }// end of updateExaminer

    public function start(Assessment $assessment)
    {
        // Verify user is organization super admin
        if (!auth()->user()->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {
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
            'view' => view('organization.assessments._start', compact('assessment'))->render(),
        ]);

    }// end of start

    public function resume(Assessment $assessment)
    {
        // Verify user is organization super admin
        if (!auth()->user()->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {
            abort(403);
        }

        // Verify assessment is in progress or partially in progress
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
            'view' => view('organization.assessments._start', compact('assessment'))->render(),
        ]);

    }// end of resume

    public function storeDeductions(Request $request, Assessment $assessment, AssessmentService $assessmentService)
    {
        // Verify user is organization super admin
        if (!auth()->user()->hasRole(UserTypeEnum::ORGANIZATION_SUPER_ADMIN)) {
            abort(403);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'status' => [
                'required',
                'string',
                'in:' . AssessmentStatusEnum::COMPLETED . ',' . AssessmentStatusEnum::PARTIALLY_IN_PROGRESS,
            ],
            'deductions' => 'required|array',
            'deductions.*.assessment_scheme_deduction_id' => 'required|exists:assessment_scheme_deductions,id',
            'deductions.*.quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
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

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('organization.assessments.show', $assessment->id),
        ]);

    }// end of storeDeductions

}//end of controller
