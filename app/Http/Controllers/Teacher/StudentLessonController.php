<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\AttendanceStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StudentLessonRequest;
use App\Models\Assessment;
use App\Models\BranchStudent;
use App\Models\EvaluationItem;
use App\Models\LessonEvaluationItem;
use App\Models\Level;
use App\Models\Project;
use App\Models\StudentLesson;
use Illuminate\Http\Request;

class StudentLessonController extends Controller
{
    public function edit(StudentLesson $studentLesson)
    {
        $studentLesson->load([
            'lesson',
            'student',
            'evaluationItem',
            'lesson.classroom.students',
            'lesson.branch'
        ]);

        if (!$studentLesson->lesson || $studentLesson->lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        // Get evaluation items for the project and page_number from branch_student pivot table
        $evaluationItems = collect();
        $pageNumber = null;
        $levelToPage = null;

        if ($studentLesson->lesson->branch_id && $studentLesson->lesson->classroom_id) {

            $branchStudent = BranchStudent::where('student_id', $studentLesson->student_id)
                ->where('branch_id', $studentLesson->lesson->branch_id)
                ->where('classroom_id', $studentLesson->lesson->classroom_id)
                ->first();

            if ($branchStudent) {
                // Get page_number from branch_student pivot table
                $pageNumber = $branchStudent->page_number ?? null;

                // Get level's to_page if level_id exists
                if ($branchStudent->level_id) {
                    $level = Level::find($branchStudent->level_id);
                    if ($level) {
                        $levelToPage = $level->to_page;
                    }
                }

                // Get evaluation items if project_id exists
                if ($branchStudent->project_id) {

                    $project = Project::query()
                        ->with('evaluationModel.evaluationItems')
                        ->find($branchStudent->project_id);

                    if ($project && $project->evaluationModel) {

                        $evaluationItems = $project->evaluationModel->evaluationItems()
                            ->orderBy('order')
                            ->get();

                    }//end of if

                }//end of if

            }//end of if

        }//end of if

        // Load existing lesson evaluation items for this lesson and student
        $lessonEvaluationItems = LessonEvaluationItem::query()
            ->where('lesson_id', $studentLesson->lesson_id)
            ->where('student_id', $studentLesson->student_id)
            ->with('evaluationItem')
            ->orderBy('page_number')
            ->get();

        // Check for existing assessment
        $existingAssessment = null;
        $assessmentSchemeId = null;

        if ($studentLesson->lesson->branch_id && $studentLesson->lesson->classroom_id) {
            $branchStudent = BranchStudent::where('student_id', $studentLesson->student_id)
                ->where('branch_id', $studentLesson->lesson->branch_id)
                ->where('classroom_id', $studentLesson->lesson->classroom_id)
                ->first();

            if ($branchStudent && $branchStudent->project_id) {
                $project = Project::find($branchStudent->project_id);
                $assessmentSchemeId = $project->assessment_scheme_id ?? null;

                if ($assessmentSchemeId) {
                    $existingAssessment = Assessment::where('student_id', $studentLesson->student_id)
                        ->where('assessment_scheme_id', $assessmentSchemeId)
                        ->with('examiner')
                        ->first();
                }
            }
        }

        return response()->json([
            'view' => view('teacher.student_lessons._edit', compact('studentLesson', 'evaluationItems', 'pageNumber', 'lessonEvaluationItems', 'levelToPage', 'existingAssessment'))->render(),
        ]);

    }// end of show

    public function update(StudentLessonRequest $request, StudentLesson $studentLesson)
    {
        $studentLesson->load('lesson');

        if (!$studentLesson->lesson || $studentLesson->lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        // If attendance status is absent, delete all lesson evaluation items for this lesson and student
        if (isset($validated['attendance_status']) && $validated['attendance_status'] === AttendanceStatusEnum::ABSENT) {

            LessonEvaluationItem::query()
                ->where('lesson_id', $studentLesson->lesson_id)
                ->where('student_id', $studentLesson->student_id)
                ->delete();
            
        }

        $studentLesson->update($validated);

        session()->flash('success', __('site.updated_successfully'));

        return response()->json([
            'redirect_to' => route('teacher.lessons.show', $studentLesson->lesson),
        ]);

    }// end of update

    public function selectEvaluationItem(Request $request, StudentLesson $studentLesson)
    {
        $studentLesson->load('lesson');

        if (!$studentLesson->lesson || $studentLesson->lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'evaluation_item_id' => [
                'required',
                'exists:evaluation_items,id',
            ],
            'page_number' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        $evaluationItemId = $validated['evaluation_item_id'];

        $lesson = $studentLesson->lesson;
        $student = $studentLesson->student;

        // Get current page_number from BranchStudent pivot table
        $branchStudent = BranchStudent::query()
            ->where('student_id', $studentLesson->student_id)
            ->where('branch_id', $lesson->branch_id)
            ->where('classroom_id', $lesson->classroom_id)
            ->first();

        if (!$branchStudent) {

            return response()->json([
                'error' => __('Branch student record not found'),
            ], 404);
        }

        // Get current page number - use provided page_number if available, otherwise use branch_student page_number
        // This allows frontend to specify the page number when badge shows a different value (e.g., after deselect)
        $currentPageNumber = $validated['page_number'] ?? ($branchStudent->page_number ?? 1);

        // If a specific page_number was provided, update BranchStudent to match it
        if (isset($validated['page_number'])) {
            BranchStudent::query()
                ->where('student_id', $studentLesson->student_id)
                ->where('branch_id', $lesson->branch_id)
                ->where('classroom_id', $lesson->classroom_id)
                ->update(['page_number' => $currentPageNumber]);
        }

        // Load level to check to_page limit
        $level = null;
        $reachedEndOfLevel = false;
        if ($branchStudent->level_id) {
            $level = Level::find($branchStudent->level_id);
            if ($level && $level->to_page !== null && $currentPageNumber >= $level->to_page) {
                $reachedEndOfLevel = true;
            }
        }

        // Load the selected evaluation item to check pass status
        $evaluationItemModel = EvaluationItem::find($evaluationItemId);
        $isFileLocked = $evaluationItemModel && $evaluationItemModel->pass === false;

        // Create or update LessonEvaluationItem (unique constraint on lesson_id + student_id + page_number)
        $lessonEvaluationItem = LessonEvaluationItem::updateOrCreate(
            [
                'lesson_id' => $lesson->id,
                'student_id' => $studentLesson->student_id,
                'page_number' => $currentPageNumber,
            ],
            [
                'evaluation_item_id' => $evaluationItemId,
            ]
        );

        // Increment page_number in BranchStudent pivot table only if:
        // - Not reached end of level AND
        // - The evaluation item is not a "fail" (pass = false means file is locked, page stays the same)
        if ($reachedEndOfLevel || $isFileLocked) {
            // Don't increment - either reached end of level or file is locked (weak evaluation)
            $newPageNumber = $currentPageNumber;
        } else {
            $newPageNumber = $currentPageNumber + 1;

            BranchStudent::query()
                ->where('student_id', $studentLesson->student_id)
                ->where('branch_id', $lesson->branch_id)
                ->where('classroom_id', $lesson->classroom_id)
                ->update(['page_number' => $newPageNumber]);
        }

        // Load the evaluation item with its name
        $lessonEvaluationItem->load('evaluationItem');

        return response()->json([
            'success' => true,
            'page_number' => $currentPageNumber,
            'new_page_number' => $newPageNumber,
            'reached_end_of_level' => $reachedEndOfLevel,
            'file_locked' => $isFileLocked,
            'evaluation_item' => [
                'id' => $lessonEvaluationItem->evaluationItem->id,
                'name' => $lessonEvaluationItem->evaluationItem->name,
            ],
        ]);

    }// end of selectEvaluationItem

    public function deselectEvaluationItem(Request $request, StudentLesson $studentLesson)
    {
        $studentLesson->load('lesson');

        if (!$studentLesson->lesson || $studentLesson->lesson->teacher_id != auth()->id()) {
            abort(403);
        }

        $lesson = $studentLesson->lesson;

        // Get the last LessonEvaluationItem for this lesson and student (ordered by page_number DESC)
        $lastLessonEvaluationItem = LessonEvaluationItem::query()
            ->where('lesson_id', $lesson->id)
            ->where('student_id', $studentLesson->student_id)
            ->orderBy('page_number', 'DESC')
            ->first();

        if (!$lastLessonEvaluationItem) {
            return response()->json([
                'error' => __('No evaluation item to remove'),
            ], 404);
        }

        $pageNumberToRemove = $lastLessonEvaluationItem->page_number;

        // Delete the last lesson evaluation item
        $lastLessonEvaluationItem->delete();

        // Get BranchStudent pivot table record
        $branchStudent = BranchStudent::query()
            ->where('student_id', $studentLesson->student_id)
            ->where('branch_id', $lesson->branch_id)
            ->where('classroom_id', $lesson->classroom_id)
            ->first();

        if (!$branchStudent) {
            return response()->json([
                'error' => __('Branch student record not found'),
            ], 404);
        }

        // Decrement page_number in BranchStudent pivot table (minimum value is 1)
        $currentPageNumber = $branchStudent->page_number ?? 1;
        $newPageNumber = max(1, $currentPageNumber - 1);

        BranchStudent::query()
            ->where('student_id', $studentLesson->student_id)
            ->where('branch_id', $lesson->branch_id)
            ->where('classroom_id', $lesson->classroom_id)
            ->update(['page_number' => $newPageNumber]);

        return response()->json([
            'success' => true,
            'removed_page_number' => $pageNumberToRemove,
            'new_page_number' => $newPageNumber,
        ]);

    }// end of deselectEvaluationItem

}//end of controller
