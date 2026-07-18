@php use App\Enums\AssetRelatedToEnum; use App\Enums\MaritalStatusEnum; @endphp
@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('teachers.teachers')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.teachers.index') }}" wire:navigate>@lang('teachers.teachers')</a></li>
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

                            <form method="post" action="{{ route('organization.teachers.store') }}" class="ajax-form" enctype="multipart/form-data">
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

                                    {{--marital_status--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.marital_status') <span class="text-danger">*</span></label>
                                            <select name="marital_status" class="form-control select2" required>
                                                <option value="">@lang('site.choose') @lang('users.marital_status')</option>
                                                @foreach (MaritalStatusEnum::getConstants() as $maritalStatus)
                                                    <option value="{{ $maritalStatus }}" {{ old('marital_status') == $maritalStatus ? 'selected' : '' }}>
                                                        @lang('users.' . $maritalStatus)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--nationality_id--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('nationalities.nationality') <span class="text-danger">*</span></label>
                                            <select name="nationality_id" class="form-control select2" required>
                                                <option value="">@lang('site.choose') @lang('nationalities.nationality')</option>
                                                @foreach($nationalities as $nationality)
                                                    <option value="{{ $nationality->id }}" {{ old('nationality_id') == $nationality->id ? 'selected' : '' }}>
                                                        {{ $nationality->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--image--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.image')</label>
                                            <div class="input-group">
                                                <input type="file" name="image" class="form-control upload-image" id="teacher-image" accept="image/*" style="display: none;">
                                                <label for="teacher-image" class="form-control custom-file-upload file-label">@lang('site.choose_file')</label>
                                                <button class="btn btn-outline-secondary browse-file-btn" type="button">@lang('site.browse')</button>
                                            </div>
                                            <div class="mt-1">
                                                <img class="uploaded-image" src="" style="display:none; width: 100px; border-radius: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    {{--identity_document_file--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.identity_document_file')</label>
                                            <div class="input-group">
                                                <input type="file" name="identity_document_file" class="form-control upload-image" id="identity-document-file" accept="image/*,application/pdf" style="display: none;">
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
                                            <input type="text" name="current_address" class="form-control" value="{{ old('current_address') }}">
                                        </div>
                                    </div>

                                    {{--last_educational_certificate--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.last_educational_certificate')</label>
                                            <input type="text" name="last_educational_certificate" class="form-control" value="{{ old('last_educational_certificate') }}">
                                        </div>
                                    </div>

                                </div><!-- end of row -->

                                {{--assets--}}
                                <div class="form-group">
                                    <div class="dropzone" data-url="{{ route('organization.assets.store') }}"
                                         data-default-message="@lang('site.drop_pdf_word_image_files')"
                                         data-input-field="#teacher-certificate-ids"
                                         data-asset-col-class="col-md-3"
                                         data-extra-params='{{ json_encode(["related_to" => AssetRelatedToEnum::TEACHER_CERTIFICATE]) }}'
                                    ></div>

                                    <input type="hidden" name="teacher_certificate_ids" id="teacher-certificate-ids">

                                    <div class="row assets-wrapper">

                                    </div>
                                </div>

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

