@php
    use App\Models\Project;
    use App\Enums\ClassroomTypeEnum;

    // Group student lessons by project_id
    $lessonsByProject = $studentLessons->groupBy('project_id');

    // Calculate totals (only for individual classrooms)
    $overallTotalMinutes = 0;
    $projectTotals = [];

    foreach ($lessonsByProject as $projectId => $lessons) {

        $projectTotalMinutes = $lessons->filter(function($studentLesson) {
            $lesson = $studentLesson->lesson;
            return $lesson && $lesson->classroom && $lesson->classroom->type === ClassroomTypeEnum::INDIVIDUAL;
        })->sum('time_elapsed') ?? 0;

        $projectTotals[$projectId] = $projectTotalMinutes;

        $overallTotalMinutes += $projectTotalMinutes;

    }//end of foreach
@endphp

<div class="card">
    <div class="card-body">

        @if($studentLessons->count() > 0)
            <!-- Lessons Grouped by Project -->
            @foreach($lessonsByProject as $projectId => $lessons)
                @php
                    $project = $projectId ? Project::find($projectId) : null;
                    $projectTotalMinutes = $projectTotals[$projectId] ?? 0;
                    $projectHours = floor($projectTotalMinutes / 60);
                    $projectMinutes = $projectTotalMinutes % 60;

                    // Check if any lesson in this project has an individual classroom
                    $hasIndividualClassroom = $lessons->filter(function($studentLesson) {
                        $lesson = $studentLesson->lesson;
                        return $lesson && $lesson->classroom && $lesson->classroom->type === ClassroomTypeEnum::INDIVIDUAL;
                    })->count() > 0;
                @endphp

                <div class="card mb-3" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">

                    <!-- Project Header -->
                    <div class="card-header" style="background: linear-gradient(135deg, #25B15D 0%, #1a8a47 100%); color: #fff; padding: 1rem 1.5rem; border-bottom: none;">

                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="mr-2" style="flex-shrink: 0;">
                                    <i data-feather="folder" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0" style="color: #fff; font-weight: 600;">
                                        {{ $project ? $project->name : __('lessons.no_project') }}
                                    </h5>
                                    <small style="opacity: 0.9; color: #fff;">
                                        {{ $lessons->count() }} {{ __('lessons.lesson') }}
                                    </small>
                                </div>
                            </div>

                            @if($hasIndividualClassroom)
                                <div class="text-right ml-3" style="flex-shrink: 0;">
                                    @if($projectTotalMinutes > 0)
                                        <div style="font-size: 0.9rem; opacity: 0.95;">
                                            @if($projectHours > 0 && $projectMinutes > 0)
                                                <strong>{{ $projectHours }}</strong> {{ __('site.hours') }} <strong>{{ $projectMinutes }}</strong> {{ __('site.minutes') }}
                                            @elseif($projectHours > 0)
                                                <strong>{{ $projectHours }}</strong> {{ __('site.hours') }}
                                            @else
                                                <strong>{{ $projectMinutes }}</strong> {{ __('site.minutes') }}
                                            @endif
                                        </div>
                                    @else
                                        <div style="font-size: 0.9rem; opacity: 0.7;">
                                            {{ __('site.no_time_recorded') }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Lessons List -->
                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="padding: 0.75rem 1rem; font-weight: 600; border-bottom: 2px solid #e0e0e0;">@lang('lessons.date')</th>
                                    <th style="padding: 0.75rem 1rem; font-weight: 600; border-bottom: 2px solid #e0e0e0;">@lang('classrooms.classroom')</th>
                                    <th style="padding: 0.75rem 1rem; font-weight: 600; border-bottom: 2px solid #e0e0e0;">@lang('branches.branch')</th>
                                    <th style="padding: 0.75rem 1rem; font-weight: 600; border-bottom: 2px solid #e0e0e0;">@lang('lessons.attendance_status')</th>
                                    @if($hasIndividualClassroom)
                                        <th style="padding: 0.75rem 1rem; font-weight: 600; border-bottom: 2px solid #e0e0e0;">@lang('lessons.time_elapsed')</th>
                                    @endif
                                    <th style="padding: 0.75rem 1rem; font-weight: 600; border-bottom: 2px solid #e0e0e0; max-width: 300px; min-width: 200px;">@lang('evaluation_items.evaluation_items')</th>
                                    <th style="padding: 0.75rem 1rem; font-weight: 600; border-bottom: 2px solid #e0e0e0; width: 10%;">@lang('site.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lessons as $studentLesson)
                                    @php
                                        $lesson = $studentLesson->lesson;
                                    @endphp
                                    @if($lesson)
                                        <tr>
                                            <td style="padding: 0.75rem 1rem; vertical-align: middle;">
                                                {{ $lesson->date ? $lesson->date->format('Y-m-d') : '-' }}
                                            </td>
                                            <td style="padding: 0.75rem 1rem; vertical-align: middle;">
                                                {{ $lesson->classroom ? $lesson->classroom->name : '-' }}
                                            </td>
                                            <td style="padding: 0.75rem 1rem; vertical-align: middle;">
                                                {{ $lesson->branch ? $lesson->branch->name : '-' }}
                                            </td>
                                            <td style="padding: 0.75rem 1rem; vertical-align: middle;">
                                                @if($studentLesson->attendance_status)
                                                    @php
                                                        $statusClass = 'attendance-' . strtolower($studentLesson->attendance_status);
                                                    @endphp
                                                    <span class="attendance-badge {{ $statusClass }}">
                                                            @lang('lessons.' . strtolower($studentLesson->attendance_status))
                                                        </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            @if($hasIndividualClassroom)
                                                <td style="padding: 0.75rem 1rem; vertical-align: middle;">
                                                    @if($lesson->classroom && $lesson->classroom->type === ClassroomTypeEnum::INDIVIDUAL)
                                                        @if($studentLesson->time_elapsed !== null)
                                                            {{ $studentLesson->time_elapsed }} {{ __('site.minutes') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td style="padding: 0.75rem 1rem; vertical-align: middle; max-width: 300px;">
                                                @php
                                                    $studentLessonEvaluationItems = $lesson->lessonEvaluationItems->where('student_id', $studentLesson->student_id)->sortBy('page_number');
                                                @endphp
                                                @if($studentLessonEvaluationItems->count() > 0)
                                                    <div class="evaluation-items-wrapper">
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($studentLessonEvaluationItems as $lessonEvalItem)
                                                                @if($lessonEvalItem->evaluationItem)
                                                                    <span class="evaluation-badge"
                                                                          style="background-color: {{ $lessonEvalItem->evaluationItem->background_color ?? '#6c757d' }}; color: {{ $lessonEvalItem->evaluationItem->text_color ?? '#fff' }};"
                                                                          title="{{ $lessonEvalItem->evaluationItem->name }} ({{ __('levels.page') }}: {{ $lessonEvalItem->page_number }})"
                                                                    >
                                                                        {{ $lessonEvalItem->page_number }}: {{ $lessonEvalItem->evaluationItem->name }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td style="padding: 0.75rem 1rem; vertical-align: middle;">
                                                <a href="{{ route('organization.lessons.show', $lesson->id) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   wire:navigate>
                                                    <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-primary mb-0 p-1">
                <div class="d-flex align-items-center">
                    <i data-feather="info" class="mr-2" style="width: 18px; height: 18px;"></i>
                    <span>@lang('students.no_lessons_found')</span>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .attendance-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .attendance-present {
        background-color: #d4edda;
        color: #155724;
    }

    .attendance-absent {
        background-color: #f8d7da;
        color: #721c24;
    }

    .attendance-late {
        background-color: #fff3cd;
        color: #856404;
    }

    .evaluation-items-wrapper {
        max-width: 100%;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .evaluation-items-wrapper .d-flex {
        flex-wrap: wrap;
        gap: 0.5rem;
        width: 100%;
    }

    .evaluation-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .card-header i[data-feather] {
        color: #fff !important;
        stroke: #fff !important;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    $(function () {
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
