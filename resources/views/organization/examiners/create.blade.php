@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('examiners.examiners')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.examiners.index') }}" wire:navigate>@lang('examiners.examiners')</a></li>
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

                            <form method="post" action="{{ route('organization.examiners.store') }}" class="ajax-form">
                                @csrf
                                @method('post')

                                <div class="row">

                                    {{--name--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.name') <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" autofocus value="{{ old('name') }}" required>
                                        </div>
                                    </div>

                                    {{--email--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.email') <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                        </div>
                                    </div>

                                    {{--password--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.password') <span class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control" value="" required>
                                        </div>
                                    </div>

                                    {{--password_confirmation--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.password_confirmation') <span class="text-danger">*</span></label>
                                            <input type="password" name="password_confirmation" class="form-control" value="" required>
                                        </div>
                                    </div>

                                </div><!-- end of row -->

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</button>
                                </div>

                            </form><!-- end of form -->

                        </div><!-- end of card body -->

                    </div><!-- end of card -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
