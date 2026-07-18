<form method="post" action="{{ route('organization.teachers.hourly_rate.update', $teacher->hash_id) }}" class="ajax-form">

    @csrf
    @method('put')

    <div class="form-group">
        <label>@lang('teacher_salaries.hourly_rate') <span class="text-danger">*</span></label>
        <input type="number" name="hourly_rate" class="form-control" value="{{ $hourlyRate }}" step="0.001" min="0" required autofocus>
    </div>

    <button type="submit" class="btn btn-primary">@lang('site.save')</button>

</form>
