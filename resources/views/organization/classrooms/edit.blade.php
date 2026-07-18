@php use App\Enums\ClassroomTypeEnum; @endphp
@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('classrooms.classrooms')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.classrooms.index') }}" wire:navigate>@lang('classrooms.classrooms')</a></li>
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

                            <form method="post" action="{{ route('organization.classrooms.update', $classroom->id) }}" class="ajax-form">
                                @csrf
                                @method('put')

                                {{--teacher_id--}}
                                <div class="form-group">
                                    <label>@lang('classrooms.teacher') <span class="text-danger">*</span></label>
                                    <select name="teacher_id" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('classrooms.teacher')</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $classroom->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                {{--name--}}
                                <div class="form-group">
                                    <label>@lang('classrooms.name') <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" autofocus value="{{ old('name', $classroom->name) }}" required>
                                </div>

                                <div class="row">

                                    {{--start_date--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('classrooms.start_date') <span class="text-danger">*</span></label>
                                            <input type="text" name="start_date" class="form-control date-picker" value="{{ old('start_date', $classroom->start_date->format('Y-m-d')) }}" required>
                                        </div>
                                    </div>

                                    {{--end_date--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('classrooms.end_date') <span class="text-danger">*</span></label>
                                            <input type="text" name="end_date" class="form-control date-picker" value="{{ old('end_date', $classroom->end_date->format('Y-m-d')) }}" required>
                                        </div>
                                    </div>

                                </div><!-- end of row -->

                                {{--type--}}
                                <div class="form-group">
                                    <label>@lang('classrooms.type') <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('classrooms.type')</option>
                                        <option value="{{ ClassroomTypeEnum::INDIVIDUAL }}" {{ old('type', $classroom->type) == ClassroomTypeEnum::INDIVIDUAL ? 'selected' : '' }}>
                                            @lang('classrooms.individual')
                                        </option>
                                        <option value="{{ ClassroomTypeEnum::GROUP }}" {{ old('type', $classroom->type) == ClassroomTypeEnum::GROUP ? 'selected' : '' }}>
                                            @lang('classrooms.group')
                                        </option>
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

    </div><!-- end of content wrapper -->

@endsection
