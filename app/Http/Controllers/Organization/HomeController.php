<?php

namespace App\Http\Controllers\Organization;

use App\Enums\AssessmentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Lesson;

class HomeController extends Controller
{
    public function index()
    {
        $organizationId = session('selected_organization')['id'] ?? null;
        $branchId = session('selected_branch')['id'] ?? null;

        $pendingCount = Assessment::query()
            ->whenOrganizationId($organizationId)
            ->whenBranchId($branchId)
            ->whenStatus(AssessmentStatusEnum::PENDING)
            ->count();

        $inProgressCount = Assessment::query()
            ->whenOrganizationId($organizationId)
            ->whenBranchId($branchId)
            ->whenStatus(AssessmentStatusEnum::IN_PROGRESS)
            ->count();

        $completedCount = Assessment::query()
            ->whenOrganizationId($organizationId)
            ->whenBranchId($branchId)
            ->whenStatus(AssessmentStatusEnum::COMPLETED)
            ->count();

        $todayLessonsCount = Lesson::query()
            ->whenOrganizationId($organizationId)
            ->whenBranchId($branchId)
            ->whereDate('date', today())
            ->count();

        $pendingAssessments = Assessment::query()
            ->with(['student', 'examiner', 'assessmentScheme'])
            ->whenOrganizationId($organizationId)
            ->whenBranchId($branchId)
            ->whenStatus(AssessmentStatusEnum::PENDING)
            ->latest()
            ->take(8)
            ->get();

        return view('organization.home', compact(
            'pendingCount',
            'inProgressCount',
            'completedCount',
            'todayLessonsCount',
            'pendingAssessments'
        ));

    }// end of index

}//end of controller
