<form method="post" action="{{ route('organization.assessment_scheme_deductions.store') }}" class="ajax-form">
    @csrf
    @method('post')

    {{--name--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.name') <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="" autofocus required>
    </div>

    @if ($assessmentScheme)

        <input type="hidden" name="assessment_scheme_id" value="{{ $assessmentScheme->id }}">

    @else

        {{--assessment_scheme_id--}}

        <div class="form-group">
            <label>@lang('assessment_schemes.assessment_scheme') <span class="text-danger">*</span></label>
            <select name="assessment_scheme_id" class="form-control select2" required>
                <option value="">@lang('site.choose') @lang('assessment_schemes.assessment_scheme')</option>
                @foreach ($assessmentSchemes as $scheme)
                    <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                @endforeach
            </select>
        </div>

    @endif

    {{--value--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.value') <span class="text-danger">*</span></label>
        <input type="number" name="value" class="form-control" value="0" min="0" required>
    </div>

    {{--max_clicks--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.max_clicks')</label>
        <input type="number" name="max_clicks" class="form-control" value="" min="0" placeholder="@lang('assessment_scheme_deductions.max_clicks_placeholder')">
        <small class="form-text text-muted">@lang('assessment_scheme_deductions.max_clicks_help')</small>
    </div>

    {{--background_color--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.background_color') <span class="text-danger">*</span></label>
        <input type="color" name="background_color" class="form-control" value="#ffffff" required>
    </div>

    {{--text_color--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.text_color') <span class="text-danger">*</span></label>
        <input type="color" name="text_color" class="form-control" value="#000000" required>
    </div>

    </div><!-- end of row -->

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</button>
    </div>

</form><!-- end of form -->
