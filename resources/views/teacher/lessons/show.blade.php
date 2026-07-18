@php
    use App\Enums\AttendanceStatusEnum;
    use App\Enums\ClassroomTypeEnum;
    use App\Helpers\PhoneHelper;
    use App\Models\Project;
@endphp

@extends('layouts.teacher.app')

@section('title')
    @lang('lessons.lesson') - {{ $lesson->name ?? $lesson->date->format('Y-m-d') }}
@endsection

@section('content')

    <style>
        .lesson-hero {
            background: linear-gradient(135deg, #25B15D 0%, #1e8f4a 100%);
            border-radius: 12px;
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(37, 177, 93, 0.3);
        }

        .lesson-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }

        .lesson-hero-content {
            position: relative;
            z-index: 1;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .hero-stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hero-stat-card .stat-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-stat-card .stat-icon i,
        .hero-stat-card .stat-icon svg {
            width: 20px;
            height: 20px;
            color: white !important;
            stroke: white !important;
        }

        .hero-stat-card .stat-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }

        .hero-stat-card .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .hero-stat-card .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .students-table-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
        }

        .students-table-card .card-header {
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 1.25rem 1.5rem;
        }

        .students-table-card .card-title {
            color: #2d3748;
            font-weight: 600;
            margin: 0;
        }

        .students-table-card .table {
            margin-bottom: 0;
        }

        .students-table-card .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .students-table-card .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .students-table-card .table tbody td:nth-child(5) {
            max-width: 300px;
            min-width: 200px;
        }

        .students-table-card .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .attendance-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            color: #fff;
            white-space: nowrap;
            margin: 0.125rem 0;
            flex-shrink: 0;
            max-width: 100%;
            box-sizing: border-box;
        }

        .evaluation-badge:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: all 0.2s ease;
        }

        @media (max-width: 768px) {
            .lesson-hero {
                padding: 1.5rem 1rem;
            }

            .hero-stats {
                grid-template-columns: 1fr;
            }
        }

        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">
                            @lang('lessons.lesson')
                        </h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('teacher.home') }}">@lang('site.home')</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('teacher.classrooms.index') }}">@lang('classrooms.classrooms')</a>
                                </li>
                                @if($lesson->classroom)
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('teacher.classrooms.show', $lesson->classroom->id) }}">{{ $lesson->classroom->name }}</a>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active">{{ $lesson->name ?? $lesson->date->format('Y-m-d') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">

            <!-- Lesson Hero Section -->
            <div class="row">

                <div class="col-12">

                    <div class="lesson-hero">

                        <div class="lesson-hero-content">
                            @if($lesson->description)
                                <p class="text-white mb-4" style="opacity: 0.95; font-size: 1.1rem;">
                                    {{ $lesson->description }}
                                </p>
                            @endif

                            <div class="hero-stats">
                                <div class="hero-stat-card">
                                    <div class="stat-icon">
                                        <i data-feather="calendar"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ $lesson->date->format('Y-m-d') }}</div>
                                        <div class="stat-label">@lang('lessons.date')</div>
                                    </div>
                                </div>

                                @if($lesson->classroom)
                                    <div class="hero-stat-card">
                                        <div class="stat-icon">
                                            <i data-feather="users"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $lesson->classroom->name }}</div>
                                            <div class="stat-label">@lang('classrooms.classroom')</div>
                                        </div>
                                    </div>
                                @endif

                                @if($lesson->studentLessons)
                                    <div class="hero-stat-card">
                                        <div class="stat-icon">
                                            <i data-feather="user-check"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $lesson->studentLessons->count() }}</div>
                                            <div class="stat-label">@lang('students.students')</div>
                                        </div>
                                    </div>
                                @endif

                                @if($lesson->studentLessons)
                                    @php
                                        $presentCount = $lesson->studentLessons->where('attendance_status', AttendanceStatusEnum::PRESENT)->count();
                                    @endphp
                                    <div class="hero-stat-card">
                                        <div class="stat-icon">
                                            <i data-feather="check-circle"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $presentCount }}</div>
                                            <div class="stat-label">@lang('lessons.present')</div>
                                        </div>
                                    </div>
                                @endif

                                @if($lesson->classroom && $lesson->time_elapsed !== null)
                                    <div class="hero-stat-card">
                                        <div class="stat-icon">
                                            <i data-feather="clock"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $lesson->time_elapsed }}</div>
                                            <div class="stat-label">@lang('lessons.time_elapsed')</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table Section -->
            <div class="row">

                <div class="col-12">

                    <div class="card students-table-card">

                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i data-feather="users" class="mr-50"></i>
                                @lang('students.students')
                            </h4>
                            <div class="d-flex gap-2">
                                @if($lesson->classroom && $lesson->classroom->type === ClassroomTypeEnum::GROUP)
                                    @if($lesson->time_elapsed === null)
                                        @if($lesson->canEditTimeElapsed())
                                            <button type="button" class="btn btn-primary ajax-modal"
                                                    data-url="{{ route('teacher.lessons.edit_time_elapsed', $lesson) }}"
                                                    data-modal-title="@lang('lessons.add_time_elapsed')">
                                                <i data-feather="clock"></i>
                                                @lang('lessons.add_time_elapsed')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary" disabled style="opacity: 0.5; cursor: not-allowed;">
                                                <i data-feather="clock"></i>
                                                @lang('lessons.add_time_elapsed')
                                            </button>
                                        @endif
                                    @endif
                                @endif

                                @if($lesson->canDownloadReport())

                                    <a href="{{ route('teacher.lessons.download_report', $lesson) }}" class="btn btn-success" target="_blank">
                                        <i data-feather="file-text"></i>
                                        @lang('lessons.create_report')
                                    </a>

                                @else

                                    <button type="button" class="btn btn-success" disabled style="opacity: 0.5; cursor: not-allowed;">
                                        <i data-feather="file-text"></i>
                                        @lang('lessons.create_report')
                                    </button>

                                @endif
                            </div>
                        </div>

                        <div class="card-body">

                            @if($lesson->studentLessons && $lesson->studentLessons->count() > 0)

                                <div class="table-responsive">
                                    <table class="table table-striped mt-2">
                                        <thead>
                                        <tr>
                                            <th>@lang('users.name')</th>
                                            <th>@lang('users.email')</th>
                                            <th>@lang('users.mobile')</th>
                                            <th>@lang('lessons.attendance_status')</th>
                                            <th style="max-width: 300px; min-width: 200px;">@lang('evaluation_items.evaluation_items')</th>
                                            <th>@lang('site.actions')</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @foreach($lesson->studentLessons as $studentLesson)

                                            <tr>
                                                <td>{{ $studentLesson->student->name }}</td>
                                                <td>{{ $studentLesson->student->email ?? 'N/A' }}</td>
                                                <td class="mobile">
                                                    @if($studentLesson->student->mobile_country_code && $studentLesson->student->mobile)
                                                        @php
                                                            $countryCode = PhoneHelper::getCountryCodeFromDialCode($studentLesson->student->mobile_country_code);
                                                        @endphp
                                                        @if($countryCode)
                                                            <i class="flag-icon flag-icon-{{ $countryCode }}"></i>
                                                        @endif
                                                        {{ $studentLesson->student->mobile_country_code }} {{ $studentLesson->student->mobile }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($studentLesson->attendance_status)
                                                        @php
                                                            $statusClass = 'attendance-' . $studentLesson->attendance_status;
                                                        @endphp
                                                        <span class="attendance-badge {{ $statusClass }}">
                                                            @lang('lessons.' . $studentLesson->attendance_status)
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
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
                                                <td>
                                                    <a href="" class="btn btn-primary btn-sm ajax-modal"
                                                       data-url="{{ route('teacher.student_lessons.edit', $studentLesson->id) }}"
                                                       data-modal-title="@lang('lessons.evaluate')"
                                                       data-modal-size-class="modal-lg"
                                                    >
                                                        <i data-feather="check-circle"></i>
                                                        @lang('lessons.evaluate')
                                                    </a>
                                                </td>

                                            </tr>

                                        @endforeach

                                        </tbody>

                                    </table>
                                </div>
                            @else
                                <div class="alert alert-primary text-center p-1 mt-1">
                                    <i data-feather="info" class="mr-50"></i>
                                    @lang('site.no_data_found')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
