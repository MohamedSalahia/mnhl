<form method="post" action="{{ route('organization.teachers.accept_enrollment', $teacher->hash_id) }}" class="ajax-form">
    @csrf
    @method('post')

    <div class="row">
        <div class="col-12 mb-0">
            <div class="alert alert-primary mb-1 p-2">
                <i data-feather="info" class="mr-1"></i>
                <strong>@lang('teachers.teacher'):</strong> {{ $teacher->name }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-2">
            <p class="mb-0 lead">@lang('teachers.confirm_accept_enrollment_message')</p>
        </div>
    </div>

    <div class="form-group mb-0 d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            @lang('site.cancel')
        </button>
        <button type="submit" class="btn btn-success">
            <i data-feather="check"></i> @lang('teachers.accept_enrollment')
        </button>
    </div>

</form>
