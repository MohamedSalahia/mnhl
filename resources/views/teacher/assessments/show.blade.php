@php
    use App\Enums\AssessmentStatusEnum;
@endphp

@extends('layouts.teacher.app')

@section('title')
    @lang('assessments.assessment') - {{ $assessment->student->name ?? '' }}
@endsection

@section('content')

    <div class="content-wrapper" id="assessments-show">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">
                            @lang('assessments.assessment_details')
                        </h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('teacher.home') }}">@lang('site.home')</a>
                                </li>
                                <li class="breadcrumb-item">
                                    @php
                                        $statusLabels = [
                                            AssessmentStatusEnum::PENDING => __('assessments.assessment_requests'),
                                            AssessmentStatusEnum::IN_PROGRESS => __('assessments.in_progress_assessments'),
                                            AssessmentStatusEnum::PARTIALLY_IN_PROGRESS => __('assessments.partially_in_progress_assessments'),
                                            AssessmentStatusEnum::COMPLETED => __('assessments.completed_assessments'),
                                        ];
                                        $breadcrumbLabel = $statusLabels[$assessment->status] ?? __('assessments.assessments');
                                    @endphp
                                    <a href="{{ route('teacher.assessments.index', ['status' => $assessment->status]) }}">{{ $breadcrumbLabel }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ $assessment->student->name ?? '' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">

            <!-- Unified Hero: Student + Assessment Details -->
            <div class="row">
                <div class="col-12">
                    <div class="assessment-hero">
                        <div class="assessment-hero-content">

                            {{-- Top: Student profile + assessment meta --}}
                            <div class="hero-top">
                                <img src="{{ $assessment->student->image_path }}" alt="{{ $assessment->student->name }}" class="student-avatar">
                                <div class="hero-info">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="student-name">{{ $assessment->student->name }}</div>
                                        @if(in_array($assessment->status, [AssessmentStatusEnum::PENDING, AssessmentStatusEnum::IN_PROGRESS, AssessmentStatusEnum::PARTIALLY_IN_PROGRESS]))
                                            @php
                                                $buttonLabel = $assessment->status === AssessmentStatusEnum::PENDING
                                                    ? __('assessments.start_assessment')
                                                    : __('assessments.continue_assessment');
                                                $buttonIcon = $assessment->status === AssessmentStatusEnum::PENDING
                                                    ? 'play-circle'
                                                    : 'edit-3';
                                                $buttonRoute = $assessment->status === AssessmentStatusEnum::PENDING
                                                    ? route('teacher.assessments.start', $assessment->id)
                                                    : route('teacher.assessments.resume', $assessment->id);
                                            @endphp
                                            <a href="#" class="btn btn-light ajax-modal"
                                               data-url="{{ $buttonRoute }}"
                                               data-modal-title="{{ $buttonLabel }}"
                                               style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: white; font-weight: 600;">
                                                <i data-feather="{{ $buttonIcon }}"></i>
                                                {{ $buttonLabel }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="contact-badges">
                                        @if($assessment->student->email)
                                            <span class="contact-badge">
                                                <i data-feather="mail"></i>
                                                {{ $assessment->student->email }}
                                            </span>
                                        @endif
                                        @if($assessment->student->mobile)
                                            <span class="contact-badge" style="direction: ltr;">
                                                <i data-feather="phone"></i>
                                                @if($assessment->student->mobile_country_code)
                                                    {{ $assessment->student->mobile_country_code }}
                                                @endif
                                                {{ $assessment->student->mobile }}
                                            </span>
                                        @endif
                                    </div>

                                    @php
                                        $statusLabels = [
                                            AssessmentStatusEnum::PENDING => __('assessments.status_pending'),
                                            AssessmentStatusEnum::IN_PROGRESS => __('assessments.status_in_progress'),
                                            AssessmentStatusEnum::PARTIALLY_IN_PROGRESS => __('assessments.status_partially_in_progress'),
                                            AssessmentStatusEnum::COMPLETED => __('assessments.status_completed'),
                                        ];
                                    @endphp

                                    <div class="hero-meta-row">
                                        <div class="hero-meta-item">
                                            <div class="meta-icon"><i data-feather="clipboard"></i></div>
                                            <div class="meta-text">
                                                <span class="meta-label">@lang('assessments.assessment_scheme')</span>
                                                <span class="meta-value">{{ $assessment->assessmentScheme->name ?? __('site.not_set') }}</span>
                                            </div>
                                        </div>
                                        <div class="hero-meta-item">
                                            <div class="meta-icon"><i data-feather="user-check"></i></div>
                                            <div class="meta-text">
                                                <span class="meta-label">@lang('assessments.examiner')</span>
                                                <span class="meta-value">{{ $assessment->examiner->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="hero-meta-item">
                                            <div class="meta-icon"><i data-feather="info"></i></div>
                                            <div class="meta-text">
                                                <span class="meta-label">@lang('assessments.status')</span>
                                                <span class="meta-value">{{ $statusLabels[$assessment->status] ?? $assessment->status }}</span>
                                            </div>
                                        </div>
                                        <div class="hero-meta-item">
                                            <div class="meta-icon"><i data-feather="calendar"></i></div>
                                            <div class="meta-text">
                                                <span class="meta-label">@lang('assessments.created_at')</span>
                                                <span class="meta-value">{{ $assessment->created_at->format('Y-m-d') }}</span>
                                            </div>
                                        </div>
                                        <div class="hero-meta-item">
                                            <div class="meta-icon"><i data-feather="book-open"></i></div>
                                            <div class="meta-text">
                                                <span class="meta-label">@lang('assessments.total_lessons')</span>
                                                <span class="meta-value">{{ $relatedStudentLessons ? $relatedStudentLessons->count() : 0 }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($assessment->notes)
                                        <div class="assessment-notes-banner">
                                            <div class="notes-content">
                                                <div class="notes-label">@lang('assessments.notes')</div>
                                                <div class="notes-text">{{ $assessment->notes }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <hr class="hero-divider">

                            {{-- Bottom: Branch / Project / Level / Curriculum / Page stat cards --}}
                            <div class="hero-bottom">
                                <div class="hero-stats">
                                    @if($assessment->branch)
                                        <div class="hero-stat-card">
                                            <div class="stat-icon"><i data-feather="map-pin"></i></div>
                                            <div class="stat-content">
                                                <div class="stat-value">{{ $assessment->branch->name }}</div>
                                                <div class="stat-label">@lang('branches.branch')</div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($assessment->curriculum)
                                        <div class="hero-stat-card">
                                            <div class="stat-icon"><i data-feather="book"></i></div>
                                            <div class="stat-content">
                                                <div class="stat-value">{{ $assessment->curriculum->name }}</div>
                                                <div class="stat-label">@lang('curricula.curriculum')</div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($assessment->project)
                                        <div class="hero-stat-card">
                                            <div class="stat-icon"><i data-feather="folder"></i></div>
                                            <div class="stat-content">
                                                <div class="stat-value">{{ $assessment->project->name }}</div>
                                                <div class="stat-label">@lang('projects.project')</div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($assessment->level)
                                        <div class="hero-stat-card">
                                            <div class="stat-icon"><i data-feather="layers"></i></div>
                                            <div class="stat-content">
                                                <div class="stat-value">{{ $assessment->level->name }}</div>
                                                <div class="stat-label">@lang('levels.level')</div>
                                            </div>
                                        </div>

                                        <div class="hero-stat-card">
                                            <div class="stat-icon"><i data-feather="target"></i></div>
                                            <div class="stat-content">
                                                <div class="stat-value">{{ $assessment->level->max_score }}</div>
                                                <div class="stat-label">@lang('levels.max_score')</div>
                                            </div>
                                        </div>

                                        <div class="hero-stat-card">
                                            <div class="stat-icon"><i data-feather="check-circle"></i></div>
                                            <div class="stat-content">
                                                <div class="stat-value">{{ $assessment->level->min_passing_score }}</div>
                                                <div class="stat-label">@lang('levels.min_passing_score')</div>
                                            </div>
                                        </div>

                                        @if($assessment->score !== null)
                                            <div class="hero-stat-card">
                                                <div class="stat-icon"><i data-feather="award"></i></div>
                                                <div class="stat-content">
                                                    <div class="stat-value">{{ $assessment->score }}</div>
                                                    <div class="stat-label">@lang('assessments.student_score')</div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    @if($branchStudent && $branchStudent->classroom)
                                        <div class="hero-stat-card">
                                            <div class="stat-icon"><i data-feather="home"></i></div>
                                            <div class="stat-content">
                                                <div class="stat-value">{{ $branchStudent->classroom->name }}</div>
                                                <div class="stat-label">@lang('classrooms.classroom')</div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($branchStudent && $branchStudent->page_number !== null)
                                        <div class="hero-stat-card">
                                            <div class="stat-icon"><i data-feather="bookmark"></i></div>
                                            <div class="stat-content">
                                                <div class="stat-value">
                                                    <span class="page-number-chip">
                                                        <i data-feather="book-open"></i>
                                                        {{ $branchStudent->page_number }}
                                                    </span>
                                                </div>
                                                <div class="stat-label">@lang('levels.page_number')</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Lessons and Evaluation Items -->
            @if($relatedStudentLessons && $relatedStudentLessons->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card students-table-card my-1">
                            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    <i data-feather="target" class="mr-50"></i>
                                    @lang('assessments.related_lessons')
                                    @if($assessment->curriculum || $assessment->project || $assessment->level)
                                        <small class="text-muted ml-2">
                                            (@if($assessment->curriculum){{ $assessment->curriculum->name }}@endif
                                            @if($assessment->project) / {{ $assessment->project->name }}@endif
                                            @if($assessment->level) / {{ $assessment->level->name }}@endif)
                                        </small>
                                    @endif
                                </h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mt-2">
                                        <thead>
                                        <tr>
                                            <th>@lang('lessons.date')</th>
                                            <th>@lang('classrooms.classroom')</th>
                                            <th>@lang('lessons.attendance_status')</th>
                                            <th style="max-width: 300px; min-width: 200px;">@lang('evaluation_items.evaluation_items')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($relatedStudentLessons as $studentLesson)
                                            @if($studentLesson->lesson)
                                                <tr>
                                                    <td>{{ $studentLesson->lesson->date->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if($studentLesson->lesson->classroom)
                                                            {{ $studentLesson->lesson->classroom->name }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($studentLesson->attendance_status)
                                                            <span class="attendance-badge attendance-{{ $studentLesson->attendance_status }}">
                                                                @lang('lessons.' . $studentLesson->attendance_status)
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $studentLessonEvaluationItems = $studentLesson->lesson->lessonEvaluationItems->sortBy('page_number');
                                                        @endphp
                                                        @if($studentLessonEvaluationItems->count() > 0)
                                                            <div class="evaluation-items-wrapper">
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    @foreach($studentLessonEvaluationItems as $lessonEvalItem)
                                                                        @if($lessonEvalItem->evaluationItem)
                                                                            <span class="evaluation-badge"
                                                                                  style="background-color: {{ $lessonEvalItem->evaluationItem->background_color ?? '#6c757d' }}; color: {{ $lessonEvalItem->evaluationItem->text_color ?? '#fff' }};"
                                                                                  title="{{ $lessonEvalItem->evaluationItem->name }} ({{ __('levels.page') }}: {{ $lessonEvalItem->page_number }})">
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
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status History Timeline -->
            <div class="row">
                <div class="col-12">
                    <div class="card students-table-card my-1">
                        <div class="card-header border-bottom">
                            <h4 class="card-title mb-0">
                                <i data-feather="clock" class="mr-50"></i>
                                @lang('assessments.status_history')
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="status-timeline">
                                @php
                                    $allStatuses = [
                                        AssessmentStatusEnum::PENDING => [
                                            'bg' => '#ffc107',
                                            'text' => '#856404',
                                            'badge_bg' => '#fff3cd',
                                            'badge_text' => '#856404',
                                            'icon' => 'clock',
                                            'label' => __('assessments.status_pending')
                                        ],
                                        AssessmentStatusEnum::IN_PROGRESS => [
                                            'bg' => '#0d6efd',
                                            'text' => '#084298',
                                            'badge_bg' => '#cfe2ff',
                                            'badge_text' => '#084298',
                                            'icon' => 'play-circle',
                                            'label' => __('assessments.status_in_progress')
                                        ],
                                        AssessmentStatusEnum::PARTIALLY_IN_PROGRESS => [
                                            'bg' => '#ffc107',
                                            'text' => '#856404',
                                            'badge_bg' => '#fff3cd',
                                            'badge_text' => '#856404',
                                            'icon' => 'clock',
                                            'label' => __('assessments.status_partially_in_progress')
                                        ],
                                        AssessmentStatusEnum::COMPLETED => [
                                            'bg' => '#198754',
                                            'text' => '#0f5132',
                                            'badge_bg' => '#d1e7dd',
                                            'badge_text' => '#0f5132',
                                            'icon' => 'check-circle',
                                            'label' => __('assessments.status_completed')
                                        ],
                                    ];

                                    // Create a map of status to its record for quick lookup
                                    $statusRecords = [];
                                    if ($assessment->statuses) {
                                        foreach ($assessment->statuses as $statusRecord) {
                                            $statusRecords[$statusRecord->status] = $statusRecord;
                                        }
                                    }

                                    $currentStatus = $assessment->status;
                                @endphp

                                @foreach($allStatuses as $statusKey => $config)
                                    @php
                                        $isActive = $statusKey === $currentStatus;
                                        $hasRecord = isset($statusRecords[$statusKey]);
                                        $statusRecord = $hasRecord ? $statusRecords[$statusKey] : null;

                                        // Skip PARTIALLY_IN_PROGRESS if it doesn't exist in assessment_statuses (unless it's the current status)
                                        if ($statusKey === AssessmentStatusEnum::PARTIALLY_IN_PROGRESS && !$hasRecord && !$isActive) {
                                            continue;
                                        }

                                        // Status should not be dimmed if it's active OR has a record in assessment_statuses
                                        $shouldBeActive = $isActive || $hasRecord;
                                    @endphp
                                    <div class="timeline-item {{ $shouldBeActive ? 'active' : 'dimmed' }}">
                                        <div class="timeline-marker">
                                            <div class="timeline-dot" style="background-color: {{ $shouldBeActive ? $config['bg'] : '#e9ecef' }};">
                                                <i data-feather="{{ $config['icon'] }}"></i>
                                            </div>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <div class="timeline-status-badge" style="background-color: {{ $shouldBeActive ? $config['badge_bg'] : '#e9ecef' }}; color: {{ $shouldBeActive ? $config['badge_text'] : '#adb5bd' }};">
                                                    <i data-feather="{{ $config['icon'] }}"></i>
                                                    {{ $config['label'] }}
                                                </div>
                                                @if($hasRecord && $statusRecord)
                                                    <div class="timeline-date-wrapper">
                                                        <i data-feather="calendar"></i>
                                                        <span>
                                                            {{ $statusRecord->created_at->translatedFormat('Y-m-d') }}
                                                            <span class="ml-2">•</span>
                                                            <span class="ml-2">{{ $statusRecord->created_at->translatedFormat('g:i A') }}</span>
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="timeline-date-wrapper" style="opacity: 0.5;">
                                                        <i data-feather="calendar"></i>
                                                        <span>-</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
