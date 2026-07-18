<form method="post" action="{{ route('organization.assessments.examiner.update', $assessment->id) }}" class="ajax-form">
    @csrf
    @method('put')

    {{--examiner_id--}}
    <div class="form-group">
        <label>@lang('assessments.select_examiner')<span class="text-danger">*</span></label>
        <select name="examiner_id" class="form-control select2" required autofocus>
            <option value="">@lang('site.choose') @lang('assessments.examiner')</option>
            @foreach($examiners as $examiner)
                <option value="{{ $examiner->id }}" {{ $assessment->examiner_id == $examiner->id ? 'selected' : '' }}>
                    {{ $examiner->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.update')</button>
    </div>

</form><!-- end of form -->
