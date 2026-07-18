<form method="post" action="{{ route('organization.teacher_salaries.store') }}" class="ajax-form">
    @csrf

    <input type="hidden" name="teacher_id"       value="{{ $teacher->id }}">
    <input type="hidden" name="organization_id"   value="{{ session('selected_organization')['id'] ?? '' }}">
    <input type="hidden" name="branch_id"         value="{{ session('selected_branch')['id'] ?? '' }}">

    {{-- type --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.transaction_type') <span class="text-danger">*</span></label>
        <select name="type" class="form-control" id="transaction-type-select" required>
            @foreach($types as $type)
                <option value="{{ $type->value }}">@lang('teacher_salaries.type_' . $type->value)</option>
            @endforeach
        </select>
    </div>

    {{-- amount --}}
    <div class="form-group">
        <label>
            @lang('teacher_salaries.amount') <span class="text-danger">*</span>
            @if($currency)
                <span class="badge badge-secondary ml-1">{{ $currency->code ?? $currency->name }}</span>
            @endif
        </label>
        <input type="number" name="amount" class="form-control" step="0.001" min="0.001" required autofocus>
    </div>

    {{-- payment_method (only for payment) --}}
    <div class="form-group" id="payment-method-group">
        <label>@lang('payment_methods.payment_method') <span class="text-danger">*</span></label>
        <select name="payment_method_id" class="form-control select2">
            <option value="">@lang('site.choose')</option>
            @foreach($paymentMethods as $pm)
                <option value="{{ $pm->id }}">{{ $pm->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- period --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.period') <span class="text-danger">*</span></label>
        <input type="month" name="period" class="form-control" value="{{ $currentPeriod }}" required>
    </div>

    {{-- carry_forward (advance only) --}}
    <div class="form-group" id="carry-forward-group" style="display:none;">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="carry_forward" name="carry_forward" value="1">
            <label class="custom-control-label" for="carry_forward">
                @lang('teacher_salaries.carry_forward_label')
                <small class="text-muted d-block">@lang('teacher_salaries.carry_forward_hint')</small>
            </label>
        </div>
    </div>

    {{-- date --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.date')</label>
        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
    </div>

    {{-- notes --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.notes')</label>
        <textarea name="notes" class="form-control" rows="2" maxlength="500"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">@lang('site.save')</button>
</form>

<script>
    (function () {
        var typeSelect   = document.getElementById('transaction-type-select');
        var pmGroup      = document.getElementById('payment-method-group');
        var cfGroup      = document.getElementById('carry-forward-group');

        function toggleFields() {
            var val = typeSelect.value;
            pmGroup.style.display = (val === 'payment') ? '' : 'none';
            cfGroup.style.display = (val === 'advance') ? '' : 'none';
            // Uncheck carry_forward when switching away from advance
            if (val !== 'advance') {
                document.getElementById('carry_forward').checked = false;
            }
        }

        toggleFields();
        typeSelect.addEventListener('change', toggleFields);
    })();
</script>
