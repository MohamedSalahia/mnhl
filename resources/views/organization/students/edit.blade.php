@extends('layouts.organization.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')

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
                                <li class="breadcrumb-item active">@lang('site.edit')</li>
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

                            <form method="post" action="{{ route('organization.students.update', $student->hash_id) }}" class="ajax-form" enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                <div class="row">

                                    {{--name--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.name') <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" autofocus value="{{ old('name', $student->name) }}" required>
                                        </div>
                                    </div>

                                    {{--email--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.email')</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" autocomplete="off">
                                        </div>
                                    </div>

                                    {{--gender--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.gender') <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-control select2" required>
                                                <option value="">@lang('site.choose') @lang('users.gender')</option>
                                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>@lang('users.male')</option>
                                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>@lang('users.female')</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{--mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mobile') <span class="text-danger">*</span></label>
                                            <input type="tel" name="mobile" class="form-control intl-tel" value="{{ old('mobile', $student->mobile_country_code && $student->mobile ? (str_starts_with($student->mobile_country_code, '+') ? '' : '+') . $student->mobile_country_code . $student->mobile : $student->mobile) }}" required>

                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>

                                            <input type="hidden" name="mobile_country_code" class="mobile-country-code" value="{{ old('mobile_country_code', $student->mobile_country_code) }}">
                                        </div>
                                    </div>

                                    {{--date_of_birth--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.date_of_birth') <span class="text-danger">*</span></label>
                                            <input type="text" name="date_of_birth" class="form-control date-picker" value="{{ old('date_of_birth', $student->date_of_birth) }}" required>
                                        </div>
                                    </div>

                                    {{--birth_place--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.birth_location')</label>
                                            <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $student->birth_place) }}">
                                        </div>
                                    </div>

                                    {{--father_name--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_name') <span class="text-danger">*</span></label>
                                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $student->father_name) }}" required>
                                        </div>
                                    </div>

                                    {{--mother_name--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_name') <span class="text-danger">*</span></label>
                                            <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $student->mother_name) }}" required>
                                        </div>
                                    </div>

                                    {{--father_occupation--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_occupation')</label>
                                            <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation', $student->father_occupation) }}">
                                        </div>
                                    </div>

                                    {{--mother_occupation--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_occupation')</label>
                                            <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation', $student->mother_occupation) }}">
                                        </div>
                                    </div>

                                    {{--father_mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.father_mobile')</label>
                                            <input type="tel" name="father_mobile" class="form-control intl-tel" value="{{ old('father_mobile', $student->father_mobile_country_code && $student->father_mobile ? (str_starts_with($student->father_mobile_country_code, '+') ? '' : '+') . $student->father_mobile_country_code . $student->father_mobile : $student->father_mobile) }}">

                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>

                                            <input type="hidden" name="father_mobile_country_code" class="mobile-country-code" value="{{ old('father_mobile_country_code', $student->father_mobile_country_code) }}">
                                        </div>
                                    </div>

                                    {{--mother_mobile--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('users.mother_mobile')</label>
                                            <input type="tel" name="mother_mobile" class="form-control intl-tel" value="{{ old('mother_mobile', $student->mother_mobile_country_code && $student->mother_mobile ? (str_starts_with($student->mother_mobile_country_code, '+') ? '' : '+') . $student->mother_mobile_country_code . $student->mother_mobile : $student->mother_mobile) }}">

                                            <span class="invalid-mobile-feedback text-danger" style="font-size : 0.857rem; display: none;"></span>

                                            <input type="hidden" name="mother_mobile_country_code" class="mobile-country-code" value="{{ old('mother_mobile_country_code', $student->mother_mobile_country_code) }}">
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
                                                @if($student->image)
                                                    <img class="uploaded-image" src="{{ $student->image_path }}" style="display:block; width: 100px; border-radius: 50%;">
                                                @else
                                                    <img class="uploaded-image" src="" style="display:none; width: 100px; border-radius: 50%;">
                                                @endif
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
                                                @if($student->identity_document_file)
                                                    <img class="uploaded-image" src="{{ $student->identity_document_file_path }}" style="display:block; width: 100px;">
                                                @else
                                                    <img class="uploaded-image" src="" style="display:none; width: 100px;">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{--current_address--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.current_address')</label>
                                            <input type="text" name="current_address" class="form-control" value="{{ old('current_address', $student->current_address) }}"/>
                                        </div>
                                    </div>

                                    {{--has_previous_education--}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('users.has_previous_education')</label>
                                            <select name="has_previous_education" id="has-previous-education" class="form-control select2">
                                                <option value="0" {{ old('has_previous_education', $student->has_previous_education ? '1' : '0') == '0' ? 'selected' : '' }}>@lang('site.no')</option>
                                                <option value="1" {{ old('has_previous_education', $student->has_previous_education ? '1' : '0') == '1' ? 'selected' : '' }}>@lang('site.yes')</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{--previous_education_details--}}
                                    <div class="col-md-12" id="previous-education-details-wrapper" style="display: {{ $student->has_previous_education ? 'block' : 'none' }};">
                                        <div class="form-group">
                                            <label>@lang('users.previous_education_details')</label>
                                            <textarea name="previous_education_details" id="previous-education-details" class="form-control" rows="3">{{ old('previous_education_details', $student->previous_education_details) }}</textarea>
                                        </div>
                                    </div>

                                </div><!-- end of row -->

                                {{--curriculum_id--}}
                                <div class="form-group">
                                    <label>@lang('curricula.curricula') <span class="text-danger">*</span></label>
                                    <select name="curriculum_id" id="curriculum-id" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('curricula.curriculum')</option>
                                        @foreach ($curricula as $curriculum)
                                            <option value="{{ $curriculum->id }}"
                                                    data-projects-url="{{ route('organization.curricula.projects', $curriculum) }}"
                                                {{ old('curriculum_id', $currentPivot->curriculum_id ?? null) == $curriculum->id ? 'selected' : '' }}
                                            >
                                                {{ $curriculum->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--project_id--}}
                                <div class="form-group">
                                    <label>@lang('projects.project') <span class="text-danger">*</span></label>
                                    <select name="project_id" id="project-id" class="form-control select2" {{ ($currentPivot && $currentPivot->curriculum_id) ? '' : 'disabled' }} required>
                                        <option value="">@lang('site.choose') @lang('projects.project')</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                    data-levels-url="{{ route('organization.projects.levels', $project) }}"
                                                {{ old('project_id', $currentPivot->project_id ?? null) == $project->id ? 'selected' : '' }}
                                            >
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--level_id--}}
                                <div class="form-group">
                                    <label>@lang('levels.level') <span class="text-danger">*</span></label>
                                    <select name="level_id" id="level-id" class="form-control select2" {{ ($currentPivot && $currentPivot->project_id) ? '' : 'disabled' }} required>
                                        <option value="">@lang('site.choose') @lang('levels.level')</option>
                                        @foreach ($levels as $level)
                                            @php
                                                $isSelected = false;
                                                if (old('level_id')) {
                                                    $isSelected = old('level_id') == $level->id;
                                                } elseif ($currentPivot && $currentPivot->page_number !== null) {
                                                    $isSelected = $currentPivot->page_number >= $level->from_page && $currentPivot->page_number <= $level->to_page;
                                                } else {
                                                    $isSelected = ($currentPivot->level_id ?? null) == $level->id;
                                                }
                                            @endphp
                                            <option value="{{ $level->id }}"
                                                    data-from-page="{{ $level->from_page }}"
                                                    data-to-page="{{ $level->to_page }}"
                                                {{ $isSelected ? 'selected' : '' }}
                                            >
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--page_number--}}
                                <div class="form-group">
                                    <label>@lang('levels.page_number') <span class="text-danger">*</span></label>
                                    <select name="page_number" id="page-number" class="form-control select2" {{ ($currentPivot && $currentPivot->level_id) ? '' : 'disabled' }} required>
                                        <option value="">@lang('site.choose') @lang('levels.page_number')</option>

                                        @if($currentPivot && $currentPivot->level_id)

                                            @php
                                                $selectedLevel = $levels->firstWhere('id', $currentPivot->level_id);
                                            @endphp

                                            @if($selectedLevel)
                                                @for($page = $selectedLevel->from_page; $page <= $selectedLevel->to_page; $page++)
                                                    <option value="{{ $page }}" {{ ($currentPivot->page_number ?? null) == $page ? 'selected' : '' }}>
                                                        {{ $page }}
                                                    </option>
                                                @endfor
                                            @endif
                                        @endif
                                    </select>
                                </div>

                                {{--classroom_id--}}
                                <div class="form-group">
                                    <label>@lang('classrooms.classroom')</label>
                                    <select name="classroom_id" class="form-control select2">
                                        <option value="">@lang('site.choose') @lang('classrooms.classroom')</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}" {{ old('classroom_id', $currentPivot->classroom_id ?? null) == $classroom->id ? 'selected' : '' }}>
                                                {{ $classroom->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.update')</button>
                                </div>

                            </form><!-- end of form -->

                        </div><!-- end of card body -->

                    </div><!-- end of card -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper  -->

@endsection

@push('scripts')
    <script src="{{ asset('admin_assets/custom/js/curricula.js') }}"></script>
@endpush
