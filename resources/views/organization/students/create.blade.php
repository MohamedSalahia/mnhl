@extends('layouts.organization.app')

@section('content')

@use('App\Models\Currency')
@use('App\Models\SubscriptionType')

@php
    $organizationId = session('selected_organization')['id'] ?? null;
    $subscriptionTypes = $organizationId
        ? SubscriptionType::query()
            ->whenOrganizationId($organizationId)
            ->whenYear(now()->year)
            ->orderBy('name')
            ->get()
        : collect();
    $currencies = $organizationId
        ? Currency::query()
            ->whenOrganizationId($organizationId)
            ->orderBy('id')
            ->get()
        : collect();
@endphp

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('students.students')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.students.index') }}" wire:navigate>@lang('students.students')</a></li>
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

                            <form method="post" action="{{ route('organization.students.store') }}" class="ajax-form" enctype="multipart/form-data">
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
                                            <label>@lang('users.email')</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" autocomplete="off">
                                        </div>
                                    </div>

                                    {{--gender--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.gender') <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-control select2" required>
                                                <option value="">@lang('site.choose') @lang('users.gender')</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>@lang('users.male')</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>@lang('users.female')</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{--mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mobile') <span class="text-danger">*</span></label>
                                            <input type="tel" name="mobile" class="form-control intl-tel" value="{{ old('mobile') }}" required>

                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>

                                            <input type="hidden" name="mobile_country_code" class="mobile-country-code" value="">
                                        </div>
                                    </div>

                                    {{--date_of_birth--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.date_of_birth') <span class="text-danger">*</span></label>
                                            <input type="text" name="date_of_birth" class="form-control date-picker" value="{{ old('date_of_birth') }}" required>
                                        </div>
                                    </div>

                                    {{--birth_place--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.birth_place')</label>
                                            <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place') }}">
                                        </div>
                                    </div>

                                    {{--father_name--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_name') <span class="text-danger">*</span></label>
                                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" required>
                                        </div>
                                    </div>

                                    {{--mother_name--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_name') <span class="text-danger">*</span></label>
                                            <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}" required>
                                        </div>
                                    </div>

                                    {{--father_occupation--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_occupation')</label>
                                            <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation') }}">
                                        </div>
                                    </div>

                                    {{--mother_occupation--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_occupation')</label>
                                            <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation') }}">
                                        </div>
                                    </div>

                                    {{--father_mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_mobile')</label>
                                            <input type="tel" name="father_mobile" class="form-control intl-tel" value="{{ old('father_mobile') }}">

                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>

                                            <input type="hidden" name="father_mobile_country_code" class="mobile-country-code" value="">
                                        </div>
                                    </div>

                                    {{--mother_mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_mobile')</label>
                                            <input type="tel" name="mother_mobile" class="form-control intl-tel" value="{{ old('mother_mobile') }}">

                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>

                                            <input type="hidden" name="mother_mobile_country_code" class="mobile-country-code" value="">
                                        </div>
                                    </div>

                                    {{--image--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.image')</label>
                                            <div class="input-group">
                                                <input type="file" name="image" class="form-control upload-image" id="student-image" accept="image/*" style="display: none;">
                                                <label for="student-image" class="form-control custom-file-upload file-label">@lang('site.choose_file')</label>
                                                <button class="btn btn-outline-secondary browse-file-btn" type="button">@lang('site.browse')</button>
                                            </div>
                                            <div class="mt-1">
                                                <img class="uploaded-image" src="" style="display:none; width: 100px; border-radius: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    {{--identity_document--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.identity_document_file')</label>
                                            <div class="input-group">
                                                <input type="file" name="identity_document_file" class="form-control upload-image" id="identity-document-file" accept="image/*" style="display: none;">
                                                <label for="identity-document-file" class="form-control custom-file-upload file-label">@lang('site.choose_file')</label>
                                                <button class="btn btn-outline-secondary browse-file-btn" type="button">@lang('site.browse')</button>
                                            </div>

                                            <div class="mt-1">
                                                <img class="uploaded-image" src="" style="display:none; width: 100px;">
                                            </div>
                                        </div>
                                    </div>

                                    {{--current_address--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.current_address')</label>
                                            <input type="text" name="current_address" class="form-control"/>
                                        </div>
                                    </div>

                                    {{--has_previous_education--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.has_previous_education')</label>
                                            <select name="has_previous_education" id="has-previous-education" class="form-control select2">
                                                <option value="0" {{ old('has_previous_education', '0') == '0' ? 'selected' : '' }}>@lang('site.no')</option>
                                                <option value="1" {{ old('has_previous_education') == '1' ? 'selected' : '' }}>@lang('site.yes')</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{--previous_education_details--}}
                                    <div class="col-md-12" id="previous-education-details-wrapper" style="display: none;">
                                        <div class="form-group">
                                            <label>@lang('users.previous_education_details')</label>
                                            <textarea name="previous_education_details" id="previous-education-details" class="form-control" rows="3">{{ old('previous_education_details') }}</textarea>
                                        </div>
                                    </div>

                                </div><!-- end of row -->

                                <div class="row">

                                    {{--curriculum_id--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('curricula.curriculum') <span class="text-danger">*</span></label>
                                            <select name="curriculum_id" class="form-control select2" required>
                                                <option value="">@lang('site.choose') @lang('curricula.curriculum')</option>
                                                @foreach ($curricula as $curriculum)
                                                    <option value="{{ $curriculum->id }}"
                                                            data-projects-url="{{ route('organization.curricula.projects', $curriculum) }}"
                                                    >
                                                        {{ $curriculum->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--project_id--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('projects.project') <span class="text-danger">*</span></label>
                                            <select name="project_id" class="form-control select2" disabled required>
                                                <option value="">@lang('site.choose') @lang('projects.project')</option>
                                            </select>
                                        </div>
                                    </div>

                                </div><!-- end of row -->

                                <div class="row">

                                    {{--level_id--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('levels.level') <span class="text-danger">*</span></label>
                                            <select name="level_id" id="level-id" class="form-control select2" disabled required>
                                                <option value="">@lang('site.choose') @lang('levels.level')</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{--page_number--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('levels.page_number') <span class="text-danger">*</span></label>
                                            <select name="page_number" id="page-number" class="form-control select2" disabled required>
                                                <option value="">@lang('site.choose') @lang('levels.page_number')</option>
                                            </select>
                                        </div>
                                    </div>

                                </div><!-- end of row -->

                                {{--classroom_id--}}
                                <div class="form-group">
                                    <label>@lang('classrooms.classroom')</label>
                                    <select name="classroom_id" class="form-control select2">
                                        <option value="">@lang('site.choose') @lang('classrooms.classroom')</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                                {{ $classroom->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--exempted_from_fees--}}
                                <div class="form-group">
                                    <input type="hidden" name="exempted_from_fees" value="0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="exempted_from_fees" value="1" id="exempted-from-fees"
                                            {{ old('exempted_from_fees') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="exempted-from-fees">@lang('students.exempted_from_fees')</label>
                                    </div>
                                </div>

                                <div id="student-subscription-fee-fields" style="display: {{ old('exempted_from_fees') ? 'none' : 'block' }}">

                                    {{--subscription_type_id--}}
                                    <div class="form-group">
                                        <label>@lang('subscription_types.subscription_type')</label>
                                        <select name="subscription_type_id" id="subscription-type-id" class="form-control select2">
                                            <option value="">@lang('site.choose') @lang('subscription_types.subscription_type')</option>
                                            @foreach ($subscriptionTypes as $subscriptionType)
                                                <option value="{{ $subscriptionType->id }}" data-fees="{{ $subscriptionType->fees }}" data-currency-id="{{ $subscriptionType->currency_id }}" {{ old('subscription_type_id') == $subscriptionType->id ? 'selected' : '' }}>
                                                    {{ $subscriptionType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">

                                        {{--fees--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('subscription_types.fees')</label>
                                                <input type="text" name="fees" id="student-fees" class="form-control" value="{{ old('fees') }}">
                                            </div>
                                        </div>

                                        {{--currency_id--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('currencies.currency')</label>
                                                <select name="currency_id" id="currency-id" class="form-control select2">
                                                    <option value="">@lang('site.choose') @lang('currencies.currency')</option>
                                                    @foreach ($currencies as $currency)
                                                        <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                                            {{ $currency->name }}{{ $currency->code ? ' (' . $currency->code . ')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div><!-- end of row -->

                                </div><!-- end of student-subscription-fee-fields -->

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
