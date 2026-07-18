@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('nationalities.nationalities')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.nationalities.index') }}" wire:navigate>@lang('nationalities.nationalities')</a></li>
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

                            <form method="post" action="{{ route('admin.nationalities.store') }}" class="ajax-form">
                                @csrf
                                @method('post')

                                {{--name--}}
                                <div class="row">

                                    @foreach ($activeLanguages as $activeLanguage)

                                        <div class="col-md-{{ $activeLanguages->count() == 2 ? '6' : '12' }}">
                                            <div class="form-group">
                                                <label>@lang('nationalities.name') (@lang('languages.' . $activeLanguage->code))<span class="text-danger">*</span></label>
                                                <input type="text" name="{{ $activeLanguage->code }}[name]" data-error-name="{{ $activeLanguage->code }}.name"
                                                       {{ $loop->first ? 'autofocus' : '' }}
                                                       class="form-control"
                                                       value="{{ old('name') }}"
                                                       required
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

