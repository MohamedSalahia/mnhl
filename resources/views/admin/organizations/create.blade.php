@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('organizations.organizations')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.organizations.index') }}" wire:navigate>@lang('organizations.organizations')</a></li>
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

                            <form method="post" action="{{ route('admin.organizations.store') }}" class="ajax-form">
                                @csrf
                                @method('post')

                                {{--country_id--}}
                                <div class="form-group">
                                    <label>@lang('countries.country') <span class="text-danger">*</span></label>
                                    <select name="country_id" id="country-id" class="form-control select2" required
                                            data-governorates-base-url="{{ route('admin.countries.governorates', 0) }}">
                                        <option value="">@lang('site.choose') @lang('countries.country')</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--governorate_id--}}
                                <div class="form-group">
                                    <label>@lang('governorates.governorate') <span class="text-danger">*</span></label>
                                    <select name="governorate_id" id="governorate-id" class="form-control select2" disabled required
                                            data-areas-base-url="{{ route('admin.governorates.areas', 0) }}">
                                        <option value="">@lang('site.choose') @lang('governorates.governorate')</option>
                                    </select>
                                </div>

                                {{--area_id--}}
                                <div class="form-group">
                                    <label>@lang('areas.area') <span class="text-danger">*</span></label>
                                    <select name="area_id" id="area-id" class="form-control select2" disabled required>
                                        <option value="">@lang('site.choose') @lang('areas.area')</option>
                                    </select>
                                </div>

                                {{--name--}}
                                <div class="row">

                                    @foreach ($activeLanguages as $activeLanguage)

                                        <div class="col-md-{{ $activeLanguages->count() == 2 ? '6' : '12' }}">
                                            <div class="form-group">
                                                <label>@lang('organizations.name') (@lang('languages.' . $activeLanguage->code))@if($activeLanguage->code === 'ar')<span class="text-danger">*</span>@endif</label>
                                                <input type="text" name="{{ $activeLanguage->code }}[name]" data-error-name="{{ $activeLanguage->code }}.name"
                                                       {{ $loop->first ? 'autofocus' : '' }}
                                                       class="form-control"
                                                       value="{{ old($activeLanguage->code . '.name') }}"
                                                       {{ $activeLanguage->code === 'ar' ? 'required' : '' }}
                                                >
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                                {{--logo--}}
                                <div class="form-group">
                                    <label>@lang('organizations.logo') <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="file" name="logo" class="form-control upload-image" id="logo" accept="image/*" style="display: none;">
                                        <label for="logo" class="form-control custom-file-upload file-label">@lang('site.choose_file')</label>
                                        <button class="btn btn-outline-secondary browse-file-btn" type="button">@lang('site.browse')</button>
                                    </div>

                                    <div class="mt-1">
                                        <img class="uploaded-image" src="" style="display:none; width: 100px;">
                                    </div>
                                </div>

                                <h4>@lang('organizations.super_admin_info')</h4>

                                {{--super_admin_type--}}
                                <div class="form-group">
                                    <label>@lang('organizations.super_admin_type') <span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <div class="custom-control custom-radio me-3">
                                            <input type="radio" id="super_admin_type_new" name="super_admin_type" class="custom-control-input" value="new" {{ old('super_admin_type', 'new') == 'new' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="super_admin_type_new">@lang('organizations.new_user')</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="super_admin_type_existing" name="super_admin_type" class="custom-control-input" value="existing" {{ old('super_admin_type') == 'existing' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="super_admin_type_existing">@lang('organizations.existing_user')</label>
                                        </div>
                                    </div>
                                </div>

                                {{--existing user select--}}
                                <div id="existing-user-field" class="form-group" style="display: none;">
                                    <label>@lang('organizations.select_super_admin') <span class="text-danger">*</span></label>
                                    <select name="existing_super_admin_id" id="existing-super-admin-select" class="form-control select2" data-placeholder="@lang('organizations.choose_super_admin')">
                                        <option value="">@lang('organizations.choose_super_admin')</option>
                                        @foreach ($existingSuperAdmins as $superAdmin)
                                            <option value="{{ $superAdmin->id }}" {{ old('existing_super_admin_id') == $superAdmin->id ? 'selected' : '' }}>
                                                {{ $superAdmin->name }} ({{ $superAdmin->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--new user fields--}}
                                <div id="new-user-fields">
                                    {{--super_admin_name--}}
                                    <div class="form-group">
                                        <label>@lang('users.name') <span class="text-danger">*</span></label>
                                        <input type="text" name="super_admin_name" class="form-control" value="{{ old('super_admin_name') }}" required>
                                    </div>

                                    {{--super_admin_email--}}
                                    <div class="form-group">
                                        <label>@lang('users.email') <span class="text-danger">*</span></label>
                                        <input type="email" name="super_admin_email" class="form-control" value="{{ old('super_admin_email') }}" required>
                                    </div>

                                    <div class="row">

                                        {{--super_admin_password--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('users.password') <span class="text-danger">*</span></label>
                                                <input type="password" name="super_admin_password" class="form-control" required>
                                            </div>
                                        </div>

                                        {{--super_admin_password_confirmation--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('users.password_confirmation') <span class="text-danger">*</span></label>
                                                <input type="password" name="super_admin_password_confirmation" class="form-control" required>
                                            </div>
                                        </div>

                                    </div>
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

    @push('scripts')
        <script src="{{ asset('admin_assets/custom/js/countries.js') }}"></script>
        <script src="{{ asset('admin_assets/custom/js/organizations.js') }}"></script>
    @endpush

@endsection

