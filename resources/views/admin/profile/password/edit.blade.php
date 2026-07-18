@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('users.profile')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('users.profile')</li>
                                <li class="breadcrumb-item active">@lang('users.change_password')</li>
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

                            <form method="post" action="{{ route('admin.profile.password.update') }}" class="ajax-form">
                                @csrf
                                @method('put')

                                {{--old_password--}}
                                <div class="form-group">
                                    <label>@lang('users.old_password')</label>
                                    <input type="password" name="old_password" class="form-control" value="" required>
                                </div>

                                {{--password--}}
                                <div class="form-group">
                                    <label>@lang('users.password')</label>
                                    <input type="password" name="password" class="form-control" value="" required>
                                </div>

                                {{--password_confirmation--}}
                                <div class="form-group">
                                    <label>@lang('users.password_confirmation')</label>
                                    <input type="password" name="password_confirmation" class="form-control" value="" required>
                                </div>

                                {{--submit--}}
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.edit')</button>
                                </div>

                            </form><!-- end of form -->

                        </div><!-- end of card body -->

                    </div><!-- end of card -->

                </div><!-- end of col 12 -->

            </div><!-- end of row -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection