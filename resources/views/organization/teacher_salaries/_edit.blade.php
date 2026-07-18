<form method="post" action="{{ route('organization.teacher_salaries.update', $transaction) }}" class="ajax-form">
    @csrf
    @method('put')

    <div class="form-group">
        <label class="text-muted small">@lang('teacher_salaries.transaction_type')</label>
        <p class="font-weight-bold mb-0">
            @lang('teacher_salaries.type_' . $transaction->type->value)
            @if($transaction->carry_forward)
                <span class="badge badge-secondary ml-1">
                    <i data-feather="corner-down-right" style="width:10px;height:10px;"></i>
                    @lang('teacher_salaries.carry_forward_short')
                </span>
            @endif
        </p>
    </div>

    {{-- amount --}}
    <div class="form-group">
        <label>
            @lang('teacher_salaries.amount') <span class="text-danger">*</span>
            @if($currency)
                <span class="badge badge-secondary ml-1">{{ $currency->code ?? $currency->name }}</span>
            @endif
        </label>
        <input type="number" name="amount" class="form-control" value="{{ $transaction->amount }}" step="0.001" min="0.001" required autofocus>
    </div>

    {{-- payment_method (only for payment) --}}
    @if($transaction->isPayment())
        <div class="form-group">
            <label>@lang('payment_methods.payment_method') <span class="text-danger">*</span></label>
            <select name="payment_method_id" class="form-control select2" required>
                <option value="">@lang('site.choose')</option>
                @foreach($paymentMethods as $pm)
                    <option value="{{ $pm->id }}" {{ (int)$transaction->payment_method_id === (int)$pm->id ? 'selected' : '' }}>
                        {{ $pm->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- period --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.period')</label>
        <input type="month" name="period" class="form-control" value="{{ $currentPeriod }}">
    </div>

    {{-- date --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.date')</label>
        <input type="date" name="date" class="form-control"
               value="{{ $transaction->date ? $transaction->date->format('Y-m-d') : date('Y-m-d') }}">
    </div>

    {{-- notes --}}
    <div class="form-group">
        <label>@lang('teacher_salaries.notes')</label>
        <textarea name="notes" class="form-control" rows="2" maxlength="500">{{ $transaction->notes }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">@lang('site.save')</button>
</form>
