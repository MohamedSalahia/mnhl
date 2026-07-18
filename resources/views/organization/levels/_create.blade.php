<form method="post" action="{{ route('organization.levels.store') }}" class="ajax-form">
    @csrf
    @method('post')


    {{--name--}}
    <div class="form-group">
        <label>@lang('levels.name') <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="" autofocus required>
    </div>

    {{--from_page and to_page--}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.from_page') <span class="text-danger">*</span></label>
                <input type="number" name="from_page" class="form-control" value="" min="1" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.to_page') <span class="text-danger">*</span></label>
                <input type="number" name="to_page" class="form-control" value="" min="1" required>
            </div>
        </div>
    </div>

    @if ($project)

        <input type="hidden" name="project_id" value="{{ $project->id }}">

    @else

        {{--project_id--}}
        <div class="form-group">
            <label>@lang('projects.project') <span class="text-danger">*</span></label>
            <select name="project_id" class="form-control" required>
                <option value="">@lang('site.choose') @lang('projects.project')</option>
                @foreach ($projects as $projectOption)
                    <option value="{{ $projectOption->id }}">{{ $projectOption->name }}</option>
                @endforeach
            </select>
        </div>

    @endif

    {{--assessment_scheme_id--}}
    <div class="form-group">
        <label>@lang('levels.assessment_scheme') <span class="text-danger">*</span></label>
        <select name="assessment_scheme_id" class="form-control select2" required>
            <option value="">@lang('site.choose') @lang('levels.assessment_scheme')</option>
            @foreach ($assessmentSchemes as $assessmentScheme)
                <option value="{{ $assessmentScheme->id }}">{{ $assessmentScheme->name }}</option>
            @endforeach
        </select>
    </div>

    {{--min_passing_score and max_score--}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.min_passing_score') <span class="text-danger">*</span></label>
                <input type="number" name="min_passing_score" class="form-control" value="0" min="0" max="1000" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.max_score') <span class="text-danger">*</span></label>
                <input type="number" name="max_score" class="form-control" value="100" min="0" max="1000" required>
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

        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</button>
    </div>

</form><!-- end of form -->

