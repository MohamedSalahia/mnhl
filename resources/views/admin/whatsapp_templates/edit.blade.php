@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('whatsapp_templates.whatsapp_templates')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp_templates.index') }}" wire:navigate>@lang('whatsapp_templates.whatsapp_templates')</a></li>
                                <li class="breadcrumb-item active">@lang('site.edit')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <form method="post" action="{{ route('admin.whatsapp_templates.update', $whatsapp_template->id) }}" class="ajax-form">
                                @csrf
                                @method('put')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('whatsapp_templates.title') <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" value="{{ old('title', $whatsapp_template->title) }}" autofocus required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('whatsapp_templates.type') <span class="text-danger">*</span></label>
                                            <input type="text" name="type" class="form-control" value="{{ old('type', $whatsapp_template->type) }}" required>
                                            <small class="text-muted">@lang('whatsapp_templates.type_hint')</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('whatsapp_templates.description') <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" rows="5" required>{{ old('description', $whatsapp_template->description) }}</textarea>
                                    <small class="text-muted">@lang('whatsapp_templates.variables_hint')</small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $whatsapp_template->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">@lang('whatsapp_templates.is_active')</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="save"></i> @lang('site.save')</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
