@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('curricula.curricula')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.curricula.index') }}" wire:navigate>@lang('curricula.curricula')</a></li>
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

                            <form method="post" action="{{ route('organization.curricula.update', $curriculum->id) }}" class="ajax-form" enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                {{--name--}}
                                <div class="form-group">
                                    <label>@lang('curricula.name')<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $curriculum->name) }}" autofocus required>
                                </div>

                                {{--branch_id--}}
                                <div class="form-group">
                                    <label>@lang('branches.branch') <span class="text-danger">*</span></label>
                                    <select name="branch_id" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('branches.branch')</option>
                                        @foreach ($branches as $branchOption)
                                            <option value="{{ $branchOption->id }}" {{ old('branch_id', $curriculum->branch_id) == $branchOption->id ? 'selected' : '' }}>{{ $branchOption->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--curriculum_type--}}
                                <div class="form-group">
                                    <label>@lang('curricula.curriculum_type') <span class="text-danger">*</span></label>
                                    <select name="curriculum_type" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('curricula.curriculum_type')</option>
                                        <option value="{{ \App\Enums\CurriculumTypeEnum::MAIN }}" {{ old('curriculum_type', $curriculum->curriculum_type) == \App\Enums\CurriculumTypeEnum::MAIN ? 'selected' : '' }}>@lang('curricula.main')</option>
                                        <option value="{{ \App\Enums\CurriculumTypeEnum::ADDITIONAL }}" {{ old('curriculum_type', $curriculum->curriculum_type) == \App\Enums\CurriculumTypeEnum::ADDITIONAL ? 'selected' : '' }}>@lang('curricula.additional')</option>
                                    </select>
                                </div>

                                {{--book_name--}}
                                <div class="form-group">
                                    <label>@lang('curricula.book_name') <span class="text-danger">*</span></label>
                                    <input type="text" name="book_name" class="form-control" value="{{ old('book_name', $curriculum->book_name) }}" required>
                                </div>

                                {{--book_file--}}
                                <div class="form-group">
                                    <label>@lang('curricula.book_file')</label>
                                    @if ($curriculum->book_file)
                                        <div class="mb-1">
                                            <a href="{{ $curriculum->book_file_path }}" target="_blank" class="">
                                                <i data-feather="file"></i> @lang('site.view_current_file')
                                            </a>
                                        </div>
                                    @endif
                                    <div class="input-group">
                                        <input type="file" name="book_file" class="form-control upload-image" id="book-file" accept=".pdf" style="display: none;">
                                        <label for="book-file" class="form-control custom-file-upload file-label">@lang('site.choose_file')</label>
                                        <button class="btn btn-outline-secondary browse-file-btn" type="button">@lang('site.browse')</button>
                                    </div>

                                    <small class="form-text text-muted">@lang('site.leave_empty_to_keep_current_file')</small>
                                </div>

                                {{--book_number_of_pages--}}
                                <div class="form-group">
                                    <label>@lang('curricula.book_number_of_pages')</label>
                                    <input type="number" name="book_number_of_pages" class="form-control" value="{{ old('book_number_of_pages', $curriculum->book_number_of_pages) }}" min="1">
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

    </div><!-- end of content wrapper -->

@endsection

