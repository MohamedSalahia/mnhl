<form method="post" action="{{ route('organization.students.branch_enrollment.update', array_filter(['student' => $student->hash_id, 'project_id' => $branchStudent->project_id], fn ($v) => $v !== null)) }}" class="ajax-form">
    @csrf
    @method('put')

    <div class="row">
        <div class="col-12 mb-0">
            <div class="alert alert-primary mb-1 p-2">
                <i data-feather="info" class="mr-1"></i>
                <strong>@lang('students.student'):</strong> {{ $student->name }}
            </div>
        </div>
    </div>

    <div class="row">

        {{--curriculum_id--}}
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('curricula.curriculum') <span class="text-danger">*</span></label>
                <select name="curriculum_id" id="curriculum-id" class="form-control select2" required>
                    <option value="">@lang('site.choose') @lang('curricula.curriculum')</option>
                    @foreach ($curricula as $curriculum)
                        <option value="{{ $curriculum->id }}"
                                data-projects-url="{{ route('organization.curricula.projects', $curriculum) }}"
                            {{ (int) $branchStudent->curriculum_id === (int) $curriculum->id ? 'selected' : '' }}
                        >
                            {{ $curriculum->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{--project_id--}}
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('projects.project') <span class="text-danger">*</span></label>
                <select name="project_id" id="project-id" class="form-control select2" {{ $projects->isEmpty() ? 'disabled' : '' }} required>
                    <option value="">@lang('site.choose') @lang('projects.project')</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"
                                data-levels-url="{{ route('organization.projects.levels', $project) }}"
                            {{ (int) $branchStudent->project_id === (int) $project->id ? 'selected' : '' }}
                        >
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div><!-- end of row -->

    <div class="row">

        {{--level_id--}}
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.level') <span class="text-danger">*</span></label>
                <select name="level_id" id="level-id" class="form-control select2" {{ $levels->isEmpty() ? 'disabled' : '' }} required>
                    <option value="">@lang('site.choose') @lang('levels.level')</option>
                    @foreach ($levels as $level)
                        @php
                            $isLevelSelected = false;
                            if ($branchStudent->page_number !== null) {
                                $isLevelSelected = $branchStudent->page_number >= $level->from_page && $branchStudent->page_number <= $level->to_page;
                            } else {
                                $isLevelSelected = (int) ($branchStudent->level_id ?? 0) === (int) $level->id;
                            }
                        @endphp
                        <option value="{{ $level->id }}"
                                data-from-page="{{ $level->from_page }}"
                                data-to-page="{{ $level->to_page }}"
                            {{ $isLevelSelected ? 'selected' : '' }}
                        >
                            {{ $level->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{--page_number--}}
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('levels.page_number') <span class="text-danger">*</span></label>
                <select name="page_number" id="page-number" class="form-control select2" {{ $levels->isEmpty() ? 'disabled' : '' }} required>
                    <option value="">@lang('site.choose') @lang('levels.page_number')</option>
                    @php
                        $pageLevel = $branchStudent->level_id
                            ? $levels->firstWhere('id', $branchStudent->level_id)
                            : $levels->first(function ($level) use ($branchStudent) {
                                return $branchStudent->page_number !== null
                                    && $branchStudent->page_number >= $level->from_page
                                    && $branchStudent->page_number <= $level->to_page;
                            });
                    @endphp
                    @if($pageLevel)
                        @for($page = $pageLevel->from_page; $page <= $pageLevel->to_page; $page++)
                            <option value="{{ $page }}" {{ (int) ($branchStudent->page_number ?? 0) === (int) $page ? 'selected' : '' }}>
                                {{ $page }}
                            </option>
                        @endfor
                    @endif
                </select>
            </div>
        </div>

    </div><!-- end of row -->

    {{--classroom_id--}}
    <div class="form-group">
        <label>@lang('classrooms.classroom')</label>
        <select name="classroom_id" id="classroom-id" class="form-control select2">
            <option value="">@lang('site.choose') @lang('classrooms.classroom')</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" {{ (int) ($branchStudent->classroom_id ?? 0) === (int) $classroom->id ? 'selected' : '' }}>
                    {{ $classroom->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{--exempted_from_fees--}}
    <div class="form-group">
        <input type="hidden" name="exempted_from_fees" value="0">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="exempted_from_fees" value="1" id="exempted-from-fees"
                {{ $branchStudent->exempted_from_fees ? 'checked' : '' }}>
            <label class="custom-control-label" for="exempted-from-fees">@lang('students.exempted_from_fees')</label>
        </div>
    </div>

    <div id="student-subscription-fee-fields" style="display: {{ $branchStudent->exempted_from_fees ? 'none' : 'block' }}">

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
                    <input type="text" name="fees" id="student-fees" class="form-control" value="{{ $branchStudent->exempted_from_fees ? '' : (string) $branchStudent->fees }}">
                </div>
            </div>

            {{--currency_id--}}
            <div class="col-md-6">
                <div class="form-group">
                    <label>@lang('currencies.currency')</label>
                    <select name="currency_id" id="currency-id" class="form-control select2">
                        <option value="">@lang('site.choose') @lang('currencies.currency')</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ (int) ($branchStudent->currency_id ?? 0) === (int) $currency->id ? 'selected' : '' }}>
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
            <i data-feather="save"></i> @lang('site.update')
        </button>
    </div>

</form>
