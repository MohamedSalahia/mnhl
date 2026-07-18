@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('settings.student_registration')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}">@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('settings.settings')</li>
                                <li class="breadcrumb-item active">@lang('settings.student_registration')</li>
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

                            <form method="post" action="{{ route('organization.settings.student_registration.store') }}" class="ajax-form">
                                @csrf
                                @method('post')

                                <p class="mb-2">@lang('settings.select_required_fields')</p>

                                <div class="row">

                                    {{--email--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="email" id="email" value="1" {{ old('email', $organization->student_registration_settings['email'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="email">@lang('users.email')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--gender--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="gender" id="gender" value="1" {{ old('gender', $organization->student_registration_settings['gender'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="gender">@lang('users.gender')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="mobile" id="mobile" value="1" {{ old('mobile', $organization->student_registration_settings['mobile'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="mobile">@lang('users.mobile')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--date_of_birth--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="date_of_birth" id="date_of_birth" value="1" {{ old('date_of_birth', $organization->student_registration_settings['date_of_birth'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="date_of_birth">@lang('users.date_of_birth')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--birth_place--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="birth_place" id="birth_place" value="1" {{ old('birth_place', $organization->student_registration_settings['birth_place'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="birth_place">@lang('users.birth_place')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--father_name--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="father_name" id="father_name" value="1" {{ old('father_name', $organization->student_registration_settings['father_name'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="father_name">@lang('users.father_name')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--mother_name--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="mother_name" id="mother_name" value="1" {{ old('mother_name', $organization->student_registration_settings['mother_name'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="mother_name">@lang('users.mother_name')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--father_occupation--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="father_occupation" id="father_occupation" value="1" {{ old('father_occupation', $organization->student_registration_settings['father_occupation'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="father_occupation">@lang('users.father_occupation')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--mother_occupation--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="mother_occupation" id="mother_occupation" value="1" {{ old('mother_occupation', $organization->student_registration_settings['mother_occupation'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="mother_occupation">@lang('users.mother_occupation')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--father_mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="father_mobile" id="father_mobile" value="1" {{ old('father_mobile', $organization->student_registration_settings['father_mobile'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="father_mobile">@lang('users.father_mobile')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--mother_mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="mother_mobile" id="mother_mobile" value="1" {{ old('mother_mobile', $organization->student_registration_settings['mother_mobile'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="mother_mobile">@lang('users.mother_mobile')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--profile_photo (image)--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="image" id="image" value="1" {{ old('image', $organization->student_registration_settings['image'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="image">@lang('users.profile_photo')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--identity_document_file--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="identity_document_file" id="identity_document_file" value="1" {{ old('identity_document_file', $organization->student_registration_settings['identity_document_file'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="identity_document_file">@lang('users.identity_document_file')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--current_address--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="current_address" id="current_address" value="1" {{ old('current_address', $organization->student_registration_settings['current_address'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="current_address">@lang('users.current_address')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--has_previous_education--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="has_previous_education" id="has_previous_education" value="1" {{ old('has_previous_education', $organization->student_registration_settings['has_previous_education'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="has_previous_education">@lang('users.has_previous_education')</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--previous_education_details--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="previous_education_details" id="previous_education_details" value="1" {{ old('previous_education_details', $organization->student_registration_settings['previous_education_details'] ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="previous_education_details">@lang('users.previous_education_details')</label>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- end of row -->

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

