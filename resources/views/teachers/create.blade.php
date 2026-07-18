@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-10">
                
                <div class="card">

                    <div class="card-header bg-primary text-white">
                        <h4>@lang('teachers.teacher_registration')</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('teachers.store') }}" class="ajax-form empty-form">
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
                                            <input type="tel" name="mobile" class="form-control" value="{{ old('mobile') }}" required>
                                        </div>
                                    </div>
                                @endif

                                {{--date_of_birth--}}
                                @if($settings['date_of_birth'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.date_of_birth') <span class="text-danger">*</span></label>
                                            <input type="text" name="date_of_birth" class="form-control date-picker" value="{{ old('date_of_birth') }}" required>
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

                                {{--marital_status--}}
                                @if($settings['marital_status'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.marital_status') <span class="text-danger">*</span></label>
                                            <select name="marital_status" class="form-control" required>
                                                <option value="">@lang('site.choose') @lang('users.marital_status')</option>
                                                @foreach (\App\Enums\MaritalStatusEnum::getConstants() as $maritalStatus)
                                                    <option value="{{ $maritalStatus }}" {{ old('marital_status') == $maritalStatus ? 'selected' : '' }}>
                                                        @lang('users.' . $maritalStatus)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                {{--nationality_id--}}
                                @if($settings['nationality_id'] ?? false)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('nationalities.nationality') <span class="text-danger">*</span></label>
                                            <select name="nationality_id" class="form-control" required>
                                                <option value="">@lang('site.choose') @lang('nationalities.nationality')</option>
                                                @foreach(\App\Models\Nationality::query()->with(['translations'])->get() as $nationality)
                                                    <option value="{{ $nationality->id }}" {{ old('nationality_id') == $nationality->id ? 'selected' : '' }}>
                                                        {{ $nationality->name }}
                                                    </option>
                                                @endforeach
                                            </select>
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

                                {{--identity_document_file--}}
                                @if($settings['identity_document_file'] ?? false)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.identity_document_file') <span class="text-danger">*</span></label>
                                            <input type="file" name="identity_document_file" class="form-control" accept="image/*,application/pdf" required>
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

                                {{--last_educational_certificate--}}
                                @if($settings['last_educational_certificate'] ?? false)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.last_educational_certificate') <span class="text-danger">*</span></label>
                                            <input type="text" name="last_educational_certificate" class="form-control" value="{{ old('last_educational_certificate') }}" required>
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
@endsection
