<form method="post" action="{{ route('organization.levels.update', $level->id) }}" class="ajax-form">
    @csrf
    @method('put')

    {{--name--}}
    <div class="form-group">
        <label>@lang('levels.name')<span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ $level->name }}" autofocus required>
    </div>

    <input type="hidden" name="redirect_to" value="{{ request()->redirect_to }}">

    {{--from_page and to_page--}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.from_page') <span class="text-danger">*</span></label>
                <input type="number" name="from_page" class="form-control" value="{{ $level->from_page ?? '' }}" min="1" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.to_page') <span class="text-danger">*</span></label>
                <input type="number" name="to_page" class="form-control" value="{{ $level->to_page ?? '' }}" min="1" required>
            </div>
        </div>
    </div>

    {{--assessment_scheme_id--}}
    <div class="form-group">
        <label>@lang('levels.assessment_scheme') <span class="text-danger">*</span></label>
        <select name="assessment_scheme_id" class="form-control select2" required>
            <option value="">@lang('site.choose') @lang('levels.assessment_scheme')</option>
            @foreach ($assessmentSchemes as $assessmentScheme)
                <option value="{{ $assessmentScheme->id }}" {{ $level->assessment_scheme_id == $assessmentScheme->id ? 'selected' : '' }}>{{ $assessmentScheme->name }}</option>
            @endforeach
        </select>
    </div>

    {{--min_passing_score and max_score--}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.min_passing_score') <span class="text-danger">*</span></label>
                <input type="number" name="min_passing_score" class="form-control" value="{{ $level->min_passing_score ?? 0 }}" min="0" max="1000" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.max_score') <span class="text-danger">*</span></label>
                <input type="number" name="max_score" class="form-control" value="{{ $level->max_score ?? 100 }}" min="0" max="1000" required>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-2">
        <h4>@lang('curricula.additional_curricula')</h4>
        <a class="add-additional-curriculum text-primary">
            <i data-feather="plus"></i> @lang('site.add') @lang('curricula.additional_curriculum')
        </a>
    </div>

    {{--additional curricula--}}
    <div class="form-group">
        <div class="repeatable"
             data-single-row=".additional-curriculum-row"
             data-add-btn=".add-additional-curriculum"
             data-delete-btn=".delete-additional-curriculum"
             data-field-name="additional_curricula">

            @if($level->attachedCurricula && $level->attachedCurricula->count() > 0)
                @foreach($level->attachedCurricula as $index => $attachedCurriculum)
                    <div class="additional-curriculum-row">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('curricula.curriculum')</label>
                                    <select name="additional_curricula[{{ $index }}][curriculum_id]" class="form-control select2">
                                        <option value="">@lang('site.choose') @lang('curricula.curriculum')</option>
                                        @foreach ($additionalCurricula as $curriculum)
                                            <option value="{{ $curriculum->id }}" {{ $attachedCurriculum->id == $curriculum->id ? 'selected' : '' }}>{{ $curriculum->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('levels.from_page')</label>
                                    <input type="number" name="additional_curricula[{{ $index }}][from_page]" data-error-name="additional_curricula.{{ $index }}.from_page" class="form-control" value="{{ $attachedCurriculum->pivot->from_page }}" min="1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('levels.to_page')</label>
                                    <input type="number" name="additional_curricula[{{ $index }}][to_page]" data-error-name="additional_curricula.{{ $index }}.to_page" class="form-control" value="{{ $attachedCurriculum->pivot->to_page }}" min="1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-outline-danger btn-block delete-additional-curriculum" disabled>
                                        <i data-feather="trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="additional-curriculum-row">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('curricula.curriculum')</label>
                                <select name="additional_curricula[0][curriculum_id]" class="form-control select2">
                                    <option value="">@lang('site.choose') @lang('curricula.curriculum')</option>
                                    @foreach ($additionalCurricula as $curriculum)
                                        <option value="{{ $curriculum->id }}">{{ $curriculum->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('levels.from_page')</label>
                                <input type="number" name="additional_curricula[0][from_page]" data-error-name="additional_curricula.0.from_page" class="form-control" value="" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('levels.to_page')</label>
                                <input type="number" name="additional_curricula[0][to_page]" data-error-name="additional_curricula.0.to_page" class="form-control" value="" min="1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger btn-block delete-additional-curriculum" disabled>
                                    <i data-feather="trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <button type="button" class="btn btn-primary add-additional-curriculum" style="{{ $level->attachedCurricula && $level->attachedCurricula->count() > 0 ? 'display: none;' : '' }}">
                <i data-feather="plus"></i> @lang('site.add') @lang('curricula.additional_curriculum')
            </button>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.update')</button>
    </div>

</form><!-- end of form -->
