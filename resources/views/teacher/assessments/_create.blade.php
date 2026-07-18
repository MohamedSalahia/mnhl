<form action="{{ route('teacher.assessments.store') }}" method="POST" class="ajax-form">
    @csrf

    <input type="hidden" name="student_lesson_id" value="{{ $studentLesson->id }}">

    <div class="form-group">
        <label>@lang('assessments.select_examiner') <span class="text-danger">*</span></label>
        <select name="examiner_id" class="form-control select2" required>
            <option value="">@lang('site.choose') @lang('assessments.examiner')</option>
            @foreach($examiners as $examiner)
                <option value="{{ $examiner->id }}" {{ old('examiner_id') == $examiner->id ? 'selected' : '' }}>
                    {{ $examiner->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i data-feather="check-circle"></i> @lang('assessments.create_assessment')
        </button>
    </div>
</form>
