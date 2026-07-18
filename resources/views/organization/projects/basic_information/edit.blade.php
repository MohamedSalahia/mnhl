@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('projects.projects')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.projects.index') }}" wire:navigate>@lang('projects.projects')</a></li>
                                <li class="breadcrumb-item active">@lang('site.edit')</li>
                            </ol>

                        </div><!-- end of breadcrumb -->
                    </div>
                </div><!-- end of row -->

            </div><!-- end of content header -->

        </div><!-- end of content header -->

        <div class="content-body">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">

                            <form method="post" action="{{ route('organization.projects.basic_information.update', $project->id) }}" class="ajax-form">
                                @csrf
                                @method('put')

                                {{--name--}}
                                <div class="form-group">
                                    <label>@lang('projects.name')<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $project->name }}" autofocus required>
                                </div>

                                {{--evaluation_model_id--}}
                                <div class="form-group">
                                    <label>@lang('projects.evaluation_model') <span class="text-danger">*</span></label>
                                    <select name="evaluation_model_id" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('evaluation_models.evaluation_model')</option>
                                        @foreach($evaluationModels as $evaluationModel)
                                            <option value="{{ $evaluationModel->id }}" {{ $project->evaluation_model_id == $evaluationModel->id ? 'selected' : '' }}>{{ $evaluationModel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--curriculum_id--}}
                                <div class="form-group">
                                    <label>@lang('curricula.curriculum') <span class="text-danger">*</span></label>
                                    <select name="curriculum_id" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('curricula.curriculum')</option>
                                        @foreach($mainCurriculums as $curriculum)
                                            <option value="{{ $curriculum->id }}" {{ $project->curriculum_id == $curriculum->id ? 'selected' : '' }}>{{ $curriculum->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--can_proceed_to_next_project--}}
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="can_proceed_to_next_project" class="custom-control-input" id="can_proceed_to_next_project" value="1" {{ $project->can_proceed_to_next_project ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="can_proceed_to_next_project">@lang('projects.can_proceed_to_next_project')</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="arrow-right"></i> @lang('site.next')</button>
                                </div>

                            </form><!-- end of form -->

                        </div><!-- end of card body -->

                    </div><!-- end of card -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection

