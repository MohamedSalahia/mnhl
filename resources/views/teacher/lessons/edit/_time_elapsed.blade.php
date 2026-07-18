<form action="{{ route('teacher.lessons.update_time_elapsed', $lesson) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>@lang('lessons.time_elapsed') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" name="time_elapsed" class="form-control" min="0" step="1"
                   value="{{ old('time_elapsed', $lesson->time_elapsed) }}"
                   placeholder="@lang('lessons.time_elapsed_placeholder')" required>
            <div class="input-group-append">
                <span class="input-group-text">@lang('site.minutes')</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i data-feather="check-circle"></i> @lang('site.save')
        </button>
    </div>
</form>
