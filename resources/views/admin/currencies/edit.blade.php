@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('currencies.currencies')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.currencies.index') }}" wire:navigate>@lang('currencies.currencies')</a></li>
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

                            <form method="post" action="{{ route('admin.currencies.update', $currency->id) }}" class="ajax-form">
                                @csrf
                                @method('put')

                                {{--name--}}
                                <div class="row">

                                    @foreach ($activeLanguages as $activeLanguage)

                                        <div class="col-md-{{ $activeLanguages->count() == 2 ? '6' : '12' }}">
                                            <div class="form-group">
                                                <label>@lang('currencies.name') (@lang('languages.' . $activeLanguage->code))<span class="text-danger">*</span></label>
                                                <input type="text" name="{{ $activeLanguage->code }}[name]" data-error-name="{{ $activeLanguage->code }}.name"
                                                       autofocus
                                                       class="form-control"
                                                       value="{{ old('name', $currency->translate($activeLanguage->code)?->name) }}"
                                                       required
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
                                                <label>@lang('currencies.code') (@lang('languages.' . $activeLanguage->code))<span class="text-danger">*</span></label>
                                                <input type="text" name="{{ $activeLanguage->code }}[code]" data-error-name="{{ $activeLanguage->code }}.code"
                                                       autofocus
                                                       class="form-control"
                                                       value="{{ old('name', $currency->translate($activeLanguage->code)?->code) }}"
                                                       required
                                                >
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                                {{--decimal_places--}}
                                <div class="form-group">
                                    <label>@lang('currencies.decimal_places') <span class="text-danger">*</span></label>
                                    <input type="number" name="decimal_places" class="form-control" value="{{ old('decimal_places', $currency->decimal_places) }}" required>
                                </div>

                                {{--submit--}}
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.edit')</button>
                                </div>

                            </form><!-- end of form -->

                        </div><!-- end of card body -->

                    </div><!-- end of card -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
