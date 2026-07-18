@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('settings.general')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('settings.settings')</li>
                                <li class="breadcrumb-item active">@lang('settings.general')</li>
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

                            <form method="post" action="{{ route('admin.settings.general_data') }}" class="ajax-form">
                                @csrf
                                @method('post')

                                {{--logo--}}
                                <div class="form-group">
                                    <label>@lang('settings.logo')</label>
                                    <input type="file" name="logo" class="form-control load-image">
                                    <img src="{{ Storage::url('uploads/' . setting('logo')) }}" class="loaded-image" alt="" style="display: {{ setting('logo') ? 'block' : 'none' }}; width: 100px; margin: 10px 0;">
                                </div>

                                {{--fav_icon--}}
                                <div class="form-group">
                                    <label>@lang('settings.fav_icon')</label>
                                    <input type="file" name="fav_icon" class="form-control load-image">
                                    <img src="{{ Storage::url('uploads/' . setting('fav_icon')) }}" class="loaded-image" alt="" style="display: {{ setting('fav_icon') ? 'block' : 'none' }}; width: 50px; margin: 10px 0;">
                                </div>

                                {{--title--}}
                                <div class="form-group">
                                    <label>@lang('settings.title')</label>
                                    <input type="text" name="title" class="form-control" value="{{ setting('title') }}">
                                </div>

                                {{--description--}}
                                <div class="form-group">
                                    <label>@lang('settings.description')</label>
                                    <textarea name="description" class="form-control">{{ setting('description') }}</textarea>
                                </div>

                                {{--keywords--}}
                                <div class="form-group">
                                    <label>@lang('settings.keywords')</label>
                                    <input type="text" name="keywords" class="form-control" value="{{ setting('keywords') }}">
                                </div>

                                {{--email--}}
                                <div class="form-group">
                                    <label>@lang('users.email')</label>
                                    <input type="text" name="email" class="form-control" value="{{ setting('email') }}">
                                </div>

                                {{--submit--}}
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.update')</button>
                                </div>

                            </form><!-- end of form -->

                        </div><!-- end of card body -->

                    </div><!-- end of card -->

                </div><!-- end of row -->

            </div><!-- end of col -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
