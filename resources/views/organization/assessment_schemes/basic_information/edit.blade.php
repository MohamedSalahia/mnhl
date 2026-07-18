@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('assessment_schemes.assessment_schemes')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.assessment_schemes.index') }}" wire:navigate>@lang('assessment_schemes.assessment_schemes')</a></li>
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

                            <form method="post" action="{{ route('organization.assessment_schemes.basic_information.update', $assessmentScheme->id) }}" class="ajax-form">
                                @csrf
                                @method('put')

                                {{--name--}}
                                <div class="form-group">
                                    <label>@lang('assessment_schemes.name')<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $assessmentScheme->name }}" autofocus required>
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
