<form method="post" action="{{ route('organization.funds.update', $fund->id) }}" class="ajax-form">
    @csrf
    @method('put')

    <div class="form-group">
        <label>@lang('funds.name') <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $fund->name) }}" autofocus required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.edit')</button>
    </div>

</form>
