<form method="post" action="{{ route('organization.installments.store') }}" class="ajax-form">

    @csrf

    <input type="hidden" name="student_id" value="{{ $student->id }}">

    <input type="hidden" name="project_id" value="{{ $project->id }}">


    {{--amount--}}
    <div class="form-group">
        <label>@lang('installments.amount') <span class="text-danger">*</span></label>
        <input type="number" name="amount" class="form-control" value="" step="0.001" min="0.001" required autofocus>
    </div>


    {{--payment_method_id--}}
    <div class="form-group">
        <label>@lang('payment_methods.payment_method') <span class="text-danger">*</span></label>
        <select name="payment_method_id" class="form-control select2" required>
            <option value="">@lang('site.choose')</option>
            @foreach ($paymentMethods as $paymentMethod)
                <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">@lang('site.save')</button>

</form>
