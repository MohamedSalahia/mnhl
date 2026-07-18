<form method="post" action="{{ route('organization.assessment_scheme_deductions.update', $assessmentSchemeDeduction->id) }}" class="ajax-form">
    @csrf
    @method('put')
    
    {{--name--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.name')<span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ $assessmentSchemeDeduction->name }}" autofocus required>
    </div>

    {{--assessment_scheme_id--}}
    <div class="form-group">
        <label>@lang('assessment_schemes.assessment_scheme')<span class="text-danger">*</span></label>
        <select name="assessment_scheme_id" class="form-control select2" required>
            <option value="">@lang('site.choose') @lang('assessment_schemes.assessment_scheme')</option>
            @foreach ($assessmentSchemes as $scheme)
                <option value="{{ $scheme->id }}" {{ $scheme->id == $assessmentSchemeDeduction->assessment_scheme_id ? 'selected' : '' }}>{{ $scheme->name }}</option>
            @endforeach
        </select>
    </div>

    {{--value--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.value')<span class="text-danger">*</span></label>
        <input type="number" name="value" class="form-control" value="{{ $assessmentSchemeDeduction->value }}" min="0" required>
    </div>

    {{--max_clicks--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.max_clicks')</label>
        <input type="number" name="max_clicks" class="form-control" value="{{ $assessmentSchemeDeduction->max_clicks ?? '' }}" min="0" placeholder="@lang('assessment_scheme_deductions.max_clicks_placeholder')">
        <small class="form-text text-muted">@lang('assessment_scheme_deductions.max_clicks_help')</small>
    </div>

    {{--background_color--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.background_color')<span class="text-danger">*</span></label>
        <input type="color" name="background_color" class="form-control" value="{{ $assessmentSchemeDeduction->background_color }}" required>
    </div>

    {{--text_color--}}
    <div class="form-group">
        <label>@lang('assessment_scheme_deductions.text_color')<span class="text-danger">*</span></label>
        <input type="color" name="text_color" class="form-control" value="{{ $assessmentSchemeDeduction->text_color }}" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.update')</button>
    </div>

</form><!-- end of form -->
