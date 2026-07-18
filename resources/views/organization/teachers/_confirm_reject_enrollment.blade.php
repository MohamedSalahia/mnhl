<form method="post" action="{{ route('organization.teachers.reject_enrollment', $teacher->hash_id) }}" class="ajax-form">
    @csrf
    @method('post')

    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger mb-1 p-2">
                <i data-feather="alert-triangle" class="mr-1"></i>
                <strong>@lang('teachers.teacher'):</strong> {{ $teacher->name }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-2">
            <p class="mb-0 lead">@lang('teachers.confirm_reject_enrollment_message')</p>
        </div>
    </div>

    <div class="form-group mb-0 d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            @lang('site.cancel')
        </button>
        <button type="submit" class="btn btn-danger">
            <i data-feather="x"></i> @lang('teachers.reject_enrollment')
        </button>
    </div>

</form>
