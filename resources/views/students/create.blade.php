@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-10">

                <div class="card">

                    <div class="card-header bg-primary text-white">
                        <h4>@lang('students.student_registration')</h4>
                    </div>

                    <div class="card-body">

                        <form method="POST" action="{{ route('students.store') }}" class="ajax-form empty-form">
                            @csrf
                            @method('post')

                            <input type="hidden" name="organization_id" value="{{ $organization->hash_id }}">
                            <input type="hidden" name="branch_id" value="{{ $branch->hash_id }}">

                            <div class="row">

                                {{--name--}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('users.name') <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                                    </div>
                                </div>

                                {{--email--}}
                                @if($settings['email'] ?? false)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('users.email') <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                @endif

                                {{--gender--}}
                                @if($settings['gender'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.gender') <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-control" required>
                                                <option value="">@lang('site.choose') @lang('users.gender')</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>@lang('users.male')</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>@lang('users.female')</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                {{--mobile--}}
                                @if($settings['mobile'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mobile') <span class="text-danger">*</span></label>
                                            <input type="tel" name="mobile" class="form-control intl-tel" value="{{ old('mobile') }}" required>
                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>
                                            <input type="hidden" name="mobile_country_code" class="mobile-country-code" value="">
                                        </div>
                                    </div>
                                @endif

                                {{--date_of_birth--}}
                                @if($settings['date_of_birth'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.date_of_birth') <span class="text-danger">*</span></label>
                                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                                        </div>
                                    </div>
                                @endif

                                {{--birth_place--}}
                                @if($settings['birth_place'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.birth_place') <span class="text-danger">*</span></label>
                                            <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place') }}" required>
                                        </div>
                                    </div>
                                @endif

                                {{--father_name--}}
                                @if($settings['father_name'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_name') <span class="text-danger">*</span></label>
                                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" required>
                                        </div>
                                    </div>
                                @endif

                                {{--mother_name--}}
                                @if($settings['mother_name'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_name') <span class="text-danger">*</span></label>
                                            <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}" required>
                                        </div>
                                    </div>
                                @endif

                                {{--father_occupation--}}
                                @if($settings['father_occupation'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_occupation') <span class="text-danger">*</span></label>
                                            <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation') }}" required>
                                        </div>
                                    </div>
                                @endif

                                {{--mother_occupation--}}
                                @if($settings['mother_occupation'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_occupation') <span class="text-danger">*</span></label>
                                            <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation') }}" required>
                                        </div>
                                    </div>
                                @endif

                                {{--father_mobile--}}
                                @if($settings['father_mobile'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_mobile') <span class="text-danger">*</span></label>
                                            <input type="tel" name="father_mobile" class="form-control intl-tel" value="{{ old('father_mobile') }}" required>
                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>
                                            <input type="hidden" name="father_mobile_country_code" class="mobile-country-code" value="">
                                        </div>
                                    </div>
                                @endif

                                {{--mother_mobile--}}
                                @if($settings['mother_mobile'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_mobile') <span class="text-danger">*</span></label>
                                            <input type="tel" name="mother_mobile" class="form-control intl-tel" value="{{ old('mother_mobile') }}" required>
                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>
                                            <input type="hidden" name="mother_mobile_country_code" class="mobile-country-code" value="">
                                        </div>
                                    </div>
                                @endif

                                {{--profile_photo--}}
                                @if($settings['image'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.profile_photo') <span class="text-danger">*</span></label>
                                            <input type="file" name="image" class="form-control" accept="image/*" required>
                                        </div>
                                    </div>
                                @endif

                                {{--identity_document--}}
                                @if($settings['identity_document_file'] ?? false)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.identity_document_file') <span class="text-danger">*</span></label>
                                            <input type="file" name="identity_document_file" class="form-control" accept="image/*" required>
                                        </div>
                                    </div>
                                @endif

                                {{--current_address--}}
                                @if($settings['current_address'] ?? false)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.current_address') <span class="text-danger">*</span></label>
                                            <input type="text" name="current_address" class="form-control" value="{{ old('current_address') }}" required>
                                        </div>
                                    </div>
                                @endif

                                {{--has_previous_education--}}
                                @if($settings['has_previous_education'] ?? false)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.has_previous_education') <span class="text-danger">*</span></label>
                                            <select name="has_previous_education" id="has-previous-education" class="form-control" required>
                                                <option value="0" {{ old('has_previous_education', '0') == '0' ? 'selected' : '' }}>@lang('site.no')</option>
                                                <option value="1" {{ old('has_previous_education') == '1' ? 'selected' : '' }}>@lang('site.yes')</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                {{--previous_education_details--}}
                                @if($settings['previous_education_details'] ?? false)
                                    <div class="col-md-12" id="previous-education-details-wrapper">
                                        <div class="form-group">
                                            <label>@lang('users.previous_education_details') <span class="text-danger">*</span></label>
                                            <textarea name="previous_education_details" id="previous-education-details" class="form-control" rows="3" required>{{ old('previous_education_details') }}</textarea>
                                        </div>
                                    </div>
                                @endif

                            </div><!-- end of row -->

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="plus"></i> @lang('site.submit')
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($settings['has_previous_education'] ?? false)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const hasPreviousEducation = document.getElementById('has-previous-education');
                const previousEducationDetailsWrapper = document.getElementById('previous-education-details-wrapper');
                const previousEducationDetails = document.getElementById('previous-education-details');

                if (hasPreviousEducation && previousEducationDetailsWrapper) {
                    hasPreviousEducation.addEventListener('change', function () {
                        if (this.value == '1') {
                            previousEducationDetailsWrapper.style.display = 'block';
                        } else {
                            previousEducationDetailsWrapper.style.display = 'none';
                            if (previousEducationDetails) {
                                previousEducationDetails.value = '';
                            }
                        }
                    });

                    // Show on page load if already selected
                    if (hasPreviousEducation.value == '1') {
                        previousEducationDetailsWrapper.style.display = 'block';
                    }
                }
            });
        </script>
    @endif
@endsection

