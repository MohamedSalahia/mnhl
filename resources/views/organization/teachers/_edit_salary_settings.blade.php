<form method="post" action="{{ route('organization.teachers.salary_settings.update', $teacher->hash_id) }}" class="ajax-form">
    @csrf
    @method('put')

    {{-- salary_type --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.salary_type') <span class="text-danger">*</span></label>
        <select name="salary_type" class="form-control" id="salary-type-select" required>
            <option value="hourly" {{ $salaryType === 'hourly' ? 'selected' : '' }}>@lang('teacher_salaries.salary_type_hourly')</option>
            <option value="fixed"  {{ $salaryType === 'fixed'  ? 'selected' : '' }}>@lang('teacher_salaries.salary_type_fixed')</option>
        </select>
    </div>

    {{-- hourly_rate --}}
    <div class="form-group" id="hourly-rate-group" style="{{ $salaryType === 'fixed' ? 'display:none' : '' }}">
        <label>@lang('teacher_salaries.hourly_rate') <span class="text-danger">*</span></label>
        <input type="number" name="hourly_rate" class="form-control" value="{{ $hourlyRate }}" step="0.001" min="0">
    </div>

    {{-- fixed_salary --}}
    <div class="form-group" id="fixed-salary-group" style="{{ $salaryType === 'hourly' ? 'display:none' : '' }}">
        <label>@lang('teacher_salaries.fixed_salary') <span class="text-danger">*</span></label>
        <input type="number" name="fixed_salary" class="form-control" value="{{ $fixedSalary }}" step="0.001" min="0">
    </div>

    {{-- currency --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.currency')</label>
        <select name="currency_id" class="form-control select2">
            <option value="">— @lang('teacher_salaries.no_currency') —</option>
            @foreach($currencies as $cur)
                <option value="{{ $cur->id }}" {{ (int)$currencyId === (int)$cur->id ? 'selected' : '' }}>
                    {{ $cur->name }} @if($cur->code)({{ $cur->code }})@endif
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">@lang('site.save')</button>
</form>

<script>
    $('#salary-type-select').on('change', function () {
        if ($(this).val() === 'hourly') {
            $('#hourly-rate-group').show();
            $('#fixed-salary-group').hide();
        } else {
            $('#hourly-rate-group').hide();
            $('#fixed-salary-group').show();
        }
    });
</script>
