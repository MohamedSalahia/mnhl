@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">{{ $project->name }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.projects.index') }}" wire:navigate>@lang('projects.projects')</a></li>
                                <li class="breadcrumb-item active">{{ $project->name }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of content header -->

        <div class="content-body">

            <!-- Project Info Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i data-feather="briefcase" class="mr-50"></i>
                                @lang('projects.project')
                            </h4>
                            <div>
                                <a href="{{ route('organization.projects.basic_information.edit', $project) }}" wire:navigate
                                   class="btn btn-sm btn-outline-primary"
                                   title="@lang('site.edit')">
                                    <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                    <span class="d-none d-sm-inline ml-25">@lang('site.edit')</span>
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="font-weight-bold mr-1">@lang('projects.name'):</span>
                                        <span>{{ $project->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="font-weight-bold mr-1">@lang('projects.evaluation_model'):</span>
                                        <span>{{ $project->evaluationModel->name ?? '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="font-weight-bold mr-1">@lang('curricula.curriculum'):</span>
                                        <span>{{ $project->curriculum->name ?? '' }}</span>
                                        @if($project->curriculum)
                                            @php
                                                $isMain = $project->curriculum->curriculum_type === \App\Enums\CurriculumTypeEnum::MAIN;
                                                $badgeClass = $isMain ? 'badge-primary' : 'badge-warning';
                                                $label = $isMain ? __('curricula.main') : __('curricula.additional');
                                            @endphp
                                            <span class="badge badge-pill {{ $badgeClass }} ml-1">{{ $label }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="font-weight-bold mr-1">@lang('projects.can_proceed_to_next_project'):</span>
                                        <span>{{ $project->can_proceed_to_next_project ? __('site.yes') : __('site.no') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="font-weight-bold mr-1">@lang('site.created_at'):</span>
                                        <span>{{ $project->created_at->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Levels Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-1">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title mb-0">
                                    @lang('levels.levels')
                                </h4>
                                <span class="badge badge-pill badge-primary ml-1">{{ $project->levels->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">#</th>
                                        <th>@lang('levels.level')</th>
                                        <th class="text-center" style="width: 120px;">@lang('levels.pages')</th>
                                        <th>@lang('curricula.additional_curricula')</th>
                                        <th class="text-center" style="width: 100px;">@lang('site.actions')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($project->levels as $index => $level)
                                        <tr id="level-{{ $level->id }}">
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-light"
                                                     style="width: 32px; height: 32px; min-width: 32px;">
                                                    <span class="font-weight-bold text-secondary small">{{ $index + 1 }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                    <span class="badge badge-light-primary py-50 px-1">
                                                        {{ $level->name }}
                                                    </span>
                                            </td>
                                            <td class="text-center">
                                                    <span class="badge badge-light-secondary">
                                                        {{ $level->from_page }}-{{ $level->to_page }}
                                                    </span>
                                            </td>
                                            <td>
                                                @if($level->attachedCurricula->count() > 0)
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($level->attachedCurricula as $curriculum)
                                                            <span class="badge badge-light-warning"
                                                                  title="{{ $curriculum->name }} ({{ $curriculum->pivot->from_page }}-{{ $curriculum->pivot->to_page }})">
                                                                <i data-feather="book-open" class="mr-25" style="width: 12px; height: 12px;"></i>
                                                                {{ Str::limit($curriculum->name, 30) }}
                                                                <span class="text-muted ml-25">({{ $curriculum->pivot->from_page }}-{{ $curriculum->pivot->to_page }})</span>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted small">@lang('site.none')</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-outline-primary ajax-modal"
                                                   data-url="{{ route('organization.levels.edit', ['level' => $level->id, 'redirect_to' => route('organization.projects.show', $project)]) }}"
                                                   data-modal-title="@lang('site.edit') @lang('levels.level')"
                                                   title="@lang('site.edit')"
                                                >
                                                    <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="mb-1">
                                                    <i data-feather="inbox" style="width: 48px; height: 48px;" class="text-muted"></i>
                                                </div>
                                                <p class="text-muted mb-0">@lang('site.no_data_found')</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('organization.projects.index') }}" wire:navigate class="btn btn-primary">
                        <i data-feather="arrow-left"></i>
                        @lang('site.back')
                    </a>
                </div>
            </div>

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection

