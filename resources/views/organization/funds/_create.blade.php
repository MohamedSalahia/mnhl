<form method="post" action="{{ route('organization.funds.store') }}" class="ajax-form">
    @csrf
    @method('post')

    <div class="form-group">
        <label>@lang('funds.name') <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="" autofocus required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</button>
    </div>

</form>
