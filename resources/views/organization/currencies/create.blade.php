@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('currencies.currencies')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.currencies.index') }}" wire:navigate>@lang('currencies.currencies')</a></li>
                                <li class="breadcrumb-item active">@lang('site.create')</li>
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

                            <form method="post" action="{{ route('organization.currencies.store') }}" class="ajax-form">
                                @csrf
                                @method('post')

                                {{--name--}}
                                <div class="row">

                                    @foreach ($activeLanguages as $activeLanguage)

                                        <div class="col-md-{{ $activeLanguages->count() == 2 ? '6' : '12' }}">
                                            <div class="form-group">
                                                <label>@lang('currencies.name') (@lang('languages.' . $activeLanguage->code))@if($activeLanguage->code === 'ar')<span class="text-danger">*</span>@endif</label>
                                                <input type="text" name="{{ $activeLanguage->code }}[name]" data-error-name="{{ $activeLanguage->code }}.name"
                                                       autofocus
                                                       class="form-control"
                                                       value="{{ old($activeLanguage->code . '.name') }}"
                                                       {{ $activeLanguage->code === 'ar' ? 'required' : '' }}
                                                       autocomplete="off"
                                                >
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                                {{--code--}}
                                <div class="row">

                                    @foreach ($activeLanguages as $activeLanguage)

                                        <div class="col-md-{{ $activeLanguages->count() == 2 ? '6' : '12' }}">
                                            <div class="form-group">
                                                <label>@lang('currencies.code') (@lang('languages.' . $activeLanguage->code))@if($activeLanguage->code === 'ar')<span class="text-danger">*</span>@endif</label>
                                                <input type="text" name="{{ $activeLanguage->code }}[code]" data-error-name="{{ $activeLanguage->code }}.code"
                                                       class="form-control"
                                                       value="{{ old($activeLanguage->code . '.code') }}"
                                                       {{ $activeLanguage->code === 'ar' ? 'required' : '' }}
                                                       autocomplete="off"
                                                >
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</button>
                                </div>

                            </form><!-- end of form -->

                        </div><!-- end  card body-->

                    </div><!-- end of card -->

                </div><!-- end of row -->

            </div><!-- end of col -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
