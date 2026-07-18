<form method="post" action="{{ route('organization.students.branch_enrollment.accept', $student->hash_id) }}" class="ajax-form">
    @csrf
    @method('post')

    <div class="row">
        <div class="col-12 mb-0">
            <div class="alert alert-primary mb-1 p-2">
                <i data-feather="info" class="mr-1"></i>
                <strong>@lang('students.student'):</strong> {{ $student->name }}
            </div>
        </div>
    </div>

    {{--curriculum_id--}}
    <div class="form-group">
        <label>@lang('curricula.curricula') <span class="text-danger">*</span></label>
        <select name="curriculum_id" id="curriculum-id" class="form-control select2" required>
            <option value="">@lang('site.choose') @lang('curricula.curriculum')</option>
            @foreach ($curricula as $curriculum)
                <option value="{{ $curriculum->id }}"
                        data-projects-url="{{ route('organization.curricula.projects', $curriculum) }}"
                >
                    {{ $curriculum->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{--project_id--}}
    <div class="form-group">
        <label>@lang('projects.project') <span class="text-danger">*</span></label>
        <select name="project_id" id="project-id" class="form-control select2" disabled required>
            <option value="">@lang('site.choose') @lang('projects.project')</option>
        </select>
    </div>

    {{--level_id--}}
    <div class="form-group">
        <label>@lang('levels.level') <span class="text-danger">*</span></label>
        <select name="level_id" id="level-id" class="form-control select2" disabled required>
            <option value="">@lang('site.choose') @lang('levels.level')</option>
        </select>
    </div>

    {{--page_number--}}
    <div class="form-group">
        <label>@lang('levels.page_number') <span class="text-danger">*</span></label>
        <select name="page_number" id="page-number" class="form-control select2" disabled required>
            <option value="">@lang('site.choose') @lang('levels.page_number')</option>
        </select>
    </div>

    {{--classroom_id--}}
    <div class="form-group">
        <label>@lang('classrooms.classroom')</label>
        <select name="classroom_id" id="classroom-id" class="form-control select2">
            <option value="">@lang('site.choose') @lang('classrooms.classroom')</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
            @endforeach
        </select>
    </div>

    {{--exempted_from_fees--}}
    <div class="form-group">
        <input type="hidden" name="exempted_from_fees" value="0">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="exempted_from_fees" value="1" id="exempted-from-fees">
            <label class="custom-control-label" for="exempted-from-fees">@lang('students.exempted_from_fees')</label>
        </div>
    </div>

    <div id="student-subscription-fee-fields">

        {{--subscription_type_id--}}
        <div class="form-group">
            <label>@lang('subscription_types.subscription_type')</label>
            <select name="subscription_type_id" id="subscription-type-id" class="form-control select2">
                <option value="">@lang('site.choose') @lang('subscription_types.subscription_type')</option>
                @foreach ($subscriptionTypes as $subscriptionType)
                    <option value="{{ $subscriptionType->id }}" data-fees="{{ $subscriptionType->fees }}" data-currency-id="{{ $subscriptionType->currency_id }}">
                        {{ $subscriptionType->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row">

            {{--fees--}}
            <div class="col-md-6">
                <div class="form-group">
                    <label>@lang('subscription_types.fees')</label>
                    <input type="text" name="fees" id="student-fees" class="form-control" value="">
                </div>
            </div>

            {{--currency_id--}}
            <div class="col-md-6">
                <div class="form-group">
                    <label>@lang('currencies.currency')</label>
                    <select name="currency_id" id="currency-id" class="form-control select2">
                        <option value="">@lang('site.choose') @lang('currencies.currency')</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">
                                {{ $currency->name }}{{ $currency->code ? ' (' . $currency->code . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div><!-- end of row -->

    </div><!-- end of student-subscription-fee-fields -->

    <div class="form-group mb-0">
        <button type="submit" class="btn btn-primary btn-block">
            <i data-feather="check"></i> @lang('students.accept_enrollment')
        </button>
    </div>

</form>
