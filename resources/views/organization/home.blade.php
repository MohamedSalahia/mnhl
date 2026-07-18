@php use App\Enums\AssessmentStatusEnum; @endphp
@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('site.home')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">@lang('site.home')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">

            {{-- Stats Cards --}}
            <div class="row">

                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-25">@lang('assessments.assessment_requests')</h6>
                                <h2 class="font-weight-bolder mb-0">{{ $pendingCount }}</h2>
                                <a href="{{ route('organization.assessments.index', ['status' => AssessmentStatusEnum::PENDING]) }}" class="text-primary small">@lang('site.show_all')</a>
                            </div>
                            <div class="avatar bg-light-warning p-75">
                                <div class="avatar-content"><i data-feather="clock" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-25">@lang('assessments.in_progress_assessments')</h6>
                                <h2 class="font-weight-bolder mb-0">{{ $inProgressCount }}</h2>
                                <a href="{{ route('organization.assessments.index', ['status' => AssessmentStatusEnum::IN_PROGRESS]) }}" class="text-primary small">@lang('site.show_all')</a>
                            </div>
                            <div class="avatar bg-light-info p-75">
                                <div class="avatar-content"><i data-feather="loader" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-25">@lang('assessments.completed_assessments')</h6>
                                <h2 class="font-weight-bolder mb-0">{{ $completedCount }}</h2>
                                <a href="{{ route('organization.assessments.index', ['status' => AssessmentStatusEnum::COMPLETED]) }}" class="text-primary small">@lang('site.show_all')</a>
                            </div>
                            <div class="avatar bg-light-success p-75">
                                <div class="avatar-content"><i data-feather="check-circle" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-25">@lang('lessons.lessons') @lang('site.today')</h6>
                                <h2 class="font-weight-bolder mb-0">{{ $todayLessonsCount }}</h2>
                                <a href="{{ route('organization.lessons.index') }}" class="text-primary small">@lang('site.show_all')</a>
                            </div>
                            <div class="avatar bg-light-primary p-75">
                                <div class="avatar-content"><i data-feather="book-open" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- end stats row -->

            {{-- Pending Assessments --}}
            @if($pendingAssessments->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">@lang('assessments.assessment_requests')</h4>
                            <a href="{{ route('organization.assessments.index', ['status' => AssessmentStatusEnum::PENDING]) }}" wire:navigate class="btn btn-sm btn-outline-primary">@lang('site.show_all')</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>@lang('assessments.student')</th>
                                            <th>@lang('assessments.examiner')</th>
                                            <th>@lang('assessments.assessment_scheme')</th>
                                            <th>@lang('assessments.created_at')</th>
                                            <th>@lang('site.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingAssessments as $assessment)
                                            <tr>
                                                <td>{{ $assessment->student->name ?? '-' }}</td>
                                                <td>{{ $assessment->examiner->name ?? '-' }}</td>
                                                <td>{{ $assessment->assessmentScheme->name ?? '-' }}</td>
                                                <td>{{ $assessment->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="{{ route('organization.assessments.show', $assessment->id) }}" wire:navigate class="btn btn-primary btn-sm">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                    @if(auth()->user()->hasRole(\App\Enums\UserTypeEnum::ORGANIZATION_SUPER_ADMIN))
                                                        <button type="button"
                                                            class="btn btn-success btn-sm ajax-btn"
                                                            data-url="{{ route('organization.assessments.start', $assessment->id) }}"
                                                            data-modal-title="@lang('assessments.start_assessment')">
                                                            <i data-feather="play"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div><!-- end content body -->

    </div><!-- end content wrapper -->

@endsection

@push('scripts')
    <script>
        $(function () {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endpush
