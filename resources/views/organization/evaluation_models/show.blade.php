@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">{{ $evaluationModel->name }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.evaluation_models.index') }}" wire:navigate>@lang('evaluation_models.evaluation_models')</a></li>
                                <li class="breadcrumb-item active">{{ $evaluationModel->name }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of content header -->

        <div class="content-body">

            <!-- Evaluation Model Info Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">
                                <i data-feather="clipboard" class="mr-50"></i>
                                @lang('evaluation_models.evaluation_model')
                            </h4>
                        </div>
                        <div class="card-body pt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="font-weight-bold mr-1">@lang('evaluation_models.name'):</span>
                                        <span>{{ $evaluationModel->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="font-weight-bold mr-1">@lang('site.created_at'):</span>
                                        <span>{{ $evaluationModel->created_at->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluation Items Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-1">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title mb-0">
                                    @lang('evaluation_items.evaluation_items')
                                </h4>
                                <span class="badge badge-pill badge-primary ml-1">{{ $evaluationModel->evaluationItems->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @forelse($evaluationModel->evaluationItems as $index => $item)
                                <div class="d-flex align-items-center justify-content-between p-1 {{ !$loop->last ? 'border-bottom' : '' }}"
                                     id="evaluation-item-{{ $item->id }}">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        {{-- Order Number --}}
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-light mr-1"
                                             style="width: 32px; height: 32px; min-width: 32px;">
                                            <span class="font-weight-bold text-secondary small">{{ $index + 1 }}</span>
                                        </div>

                                        {{-- Color Badge Preview --}}
                                        <span class="badge py-50 px-1 mr-1"
                                              style="background-color: {{ $item->background_color }}; color: {{ $item->text_color }}; min-width: 100px; font-size: 0.9rem;">
                                            {{ $item->name }}
                                        </span>

                                        {{-- Pass/Fail Indicator --}}
                                        @if($item->pass)
                                            <span class="badge badge-light-success">
                                                <i data-feather="check-circle" style="width: 14px; height: 14px;"></i>
                                            </span>
                                        @else
                                            <span class="badge badge-light-secondary">
                                                <i data-feather="minus-circle" style="width: 14px; height: 14px;"></i>
                                            </span>
                                        @endif
                                    </div>
                                    
                                </div>
                            @empty
                                <div class="p-2 text-center">
                                    <div class="mb-1">
                                        <i data-feather="inbox" style="width: 48px; height: 48px;" class="text-muted"></i>
                                    </div>
                                    <p class="text-muted mb-0">@lang('site.no_data_found')</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('organization.evaluation_models.index') }}" wire:navigate class="btn btn-secondary">
                        <i data-feather="arrow-left"></i>
                        @lang('site.back')
                    </a>
                </div>
            </div>

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection


