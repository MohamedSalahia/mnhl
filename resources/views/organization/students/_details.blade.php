@php
    use App\Enums\OrganizationStudentStatusEnum;
    use App\Helpers\PhoneHelper;

    $isActive = $organizationStudent && $organizationStudent->pivot->status === OrganizationStudentStatusEnum::ACTIVE;

    if (! isset($branchStudents)) {

        $branchStudents = collect();
        $branchStudentAcademicEditable = [];
        $selectedBranch = session('selected_branch');

        if ($selectedBranch && isset($selectedBranch['id'])) {

            $branchStudents = $student->branchStudents()
                ->with(['curriculum', 'project', 'level', 'classroom', 'currency'])
                ->whenBranchId($selectedBranch['id'])
                ->latest('id')
                ->get();

        }//end of if

    } elseif (! isset($branchStudentAcademicEditable)) {

        $branchStudentAcademicEditable = [];

    }//end of elseif
@endphp


<div class="row">
    <div class="col-lg-3 col-sm-6 col-12">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar bg-light-primary mr-1">
                    <div class="avatar-content">
                        <i data-feather="user" class="font-medium-3"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">@lang('users.gender')</small>
                    <span class="font-weight-bold">{{ $student->gender == 'male' ? __('users.male') : __('users.female') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-12">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar bg-light-info mr-1">
                    <div class="avatar-content">
                        <i data-feather="calendar" class="font-medium-3"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">@lang('users.date_of_birth')</small>
                    <span class="font-weight-bold">{{ $student->date_of_birth }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-12">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar bg-light-warning mr-1">
                    <div class="avatar-content">
                        <i data-feather="map-pin" class="font-medium-3"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">@lang('users.birth_location')</small>
                    <span class="font-weight-bold">{{ Str::limit($student->birth_place, 15) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-12">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar bg-light-success mr-1">
                    <div class="avatar-content">
                        <i data-feather="home" class="font-medium-3"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">@lang('users.current_address')</small>
                    <span class="font-weight-bold">{{ Str::limit($student->current_address, 15) }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row my-2">
    <div class="col-lg-6 col-12">
        <div class="card h-100">
            <div class="card-header border-bottom">
                <h4 class="card-title mb-0">
                    <i data-feather="user" class="mr-50"></i>
                    @lang('students.personal_information')
                </h4>
            </div>
            <div class="card-body pt-2">
                <table class="table table-borderless">
                    <tbody>
                    @if($student->student_number)
                    <tr>
                        <td class="text-muted" style="width: 40%;">
                            <i data-feather="hash" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('students.student_number')
                        </td>
                        <td class="font-weight-bold">{{ $student->student_number }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted" style="width: 40%;">
                            <i data-feather="user" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.name')
                        </td>
                        <td class="font-weight-bold">{{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="mail" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.email')
                        </td>
                        <td class="font-weight-bold">{{ $student->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="phone" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.mobile')
                        </td>
                        <td class="font-weight-bold">
                                        <span class="mobile">
                                            @if($student->mobile_country_code && $student->mobile)
                                                @php
                                                    $countryCode = PhoneHelper::getCountryCodeFromDialCode($student->mobile_country_code);
                                                @endphp
                                                @if($countryCode)
                                                    <i class="flag-icon flag-icon-{{ $countryCode }}"></i>
                                                @endif
                                                {{ $student->mobile_country_code }} {{ $student->mobile }}
                                            @else
                                                {{ $student->mobile }}
                                            @endif
                                        </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="{{ $student->gender == 'male' ? 'user' : 'user' }}" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.gender')
                        </td>
                        <td>
                                            <span class="badge badge-light-{{ $student->gender == 'male' ? 'primary' : 'danger' }}">
                                                {{ $student->gender == 'male' ? __('users.male') : __('users.female') }}
                                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="calendar" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.date_of_birth')
                        </td>
                        <td class="font-weight-bold">{{ $student->date_of_birth }}</td>
                    </tr>
                    @if($student->birth_place)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="map-pin" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.birth_location')
                            </td>
                            <td class="font-weight-bold">{{ $student->birth_place }}</td>
                        </tr>
                    @endif
                    @if($student->current_address)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="home" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.current_address')
                            </td>
                            <td class="font-weight-bold">{{ $student->current_address }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Family Information Card -->
    <div class="col-lg-6 col-12">
        <div class="card h-100">
            <div class="card-header border-bottom">
                <h4 class="card-title mb-0">
                    <i data-feather="users" class="mr-50"></i>
                    @lang('students.family_information')
                </h4>
            </div>
            <div class="card-body pt-2">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td class="text-muted" style="width: 40%;">
                            <i data-feather="user" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.father_name')
                        </td>
                        <td class="font-weight-bold">{{ $student->father_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="user" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.mother_name')
                        </td>
                        <td class="font-weight-bold">{{ $student->mother_name ?? '-' }}</td>
                    </tr>
                    @if($student->father_occupation)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="briefcase" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.father_occupation')
                            </td>
                            <td class="font-weight-bold">{{ $student->father_occupation }}</td>
                        </tr>
                    @endif
                    @if($student->mother_occupation)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="briefcase" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.mother_occupation')
                            </td>
                            <td class="font-weight-bold">{{ $student->mother_occupation }}</td>
                        </tr>
                    @endif
                    @if($student->father_mobile)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="phone" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.father_mobile')
                            </td>
                            <td class="font-weight-bold">
                                <a href="tel:{{ $student->father_mobile }}" class="text-body mobile">
                                    @if($student->father_mobile_country_code && $student->father_mobile)
                                        @php
                                            $countryCode = PhoneHelper::getCountryCodeFromDialCode($student->father_mobile_country_code);
                                        @endphp
                                        @if($countryCode)
                                            <i class="flag-icon flag-icon-{{ $countryCode }}"></i>
                                        @endif
                                        {{ $student->father_mobile_country_code }} {{ $student->father_mobile }}
                                    @else
                                        {{ $student->father_mobile }}
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @endif
                    @if($student->mother_mobile)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="phone" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.mother_mobile')
                            </td>
                            <td class="font-weight-bold">
                                <a href="tel:{{ $student->mother_mobile }}" class="text-body mobile">
                                    @if($student->mother_mobile_country_code && $student->mother_mobile)
                                        @php
                                            $countryCode = PhoneHelper::getCountryCodeFromDialCode($student->mother_mobile_country_code);
                                        @endphp
                                        @if($countryCode)
                                            <i class="flag-icon flag-icon-{{ $countryCode }}"></i>
                                        @endif
                                        {{ $student->mother_mobile_country_code }} {{ $student->mother_mobile }}
                                    @else
                                        {{ $student->mother_mobile }}
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@if($student->has_previous_education || $student->previous_education_details)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">
                        <i data-feather="book" class="mr-50"></i>
                        @lang('students.education_information')
                    </h4>
                </div>
                <div class="card-body pt-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-1">
                                            <span class="text-muted mr-1">
                                                <i data-feather="check-square" class="mr-50" style="width: 14px; height: 14px;"></i>
                                                @lang('users.has_previous_education'):
                                            </span>
                                <span class="badge badge-light-{{ $student->has_previous_education ? 'success' : 'secondary' }}">
                                                {{ $student->has_previous_education ? __('site.yes') : __('site.no') }}
                                            </span>
                            </div>
                        </div>
                        @if($student->previous_education_details)
                            <div class="col-md-12">
                                <div class="mt-1">
                                                <span class="text-muted d-block mb-50">
                                                    <i data-feather="file-text" class="mr-50" style="width: 14px; height: 14px;"></i>
                                                    @lang('users.previous_education_details'):
                                                </span>
                                    <div class="bg-light p-1 rounded">
                                        {{ $student->previous_education_details }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($student->identity_document_file)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">
                        <i data-feather="file" class="mr-50"></i>
                        @lang('users.identity_document_file')
                    </h4>
                </div>
                <div class="card-body pt-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="border rounded p-1 d-inline-block">
                                <a href="{{ $student->identity_document_file_path }}" target="_blank">
                                    <img src="{{ $student->identity_document_file_path }}" alt="@lang('users.identity_document_file')" class="img-fluid rounded" style="max-width: 400px; max-height: 300px; object-fit: contain;">
                                </a>
                            </div>
                            <div class="mt-1">
                                <a href="{{ $student->identity_document_file_path }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i data-feather="external-link" class="mr-25"></i>
                                    @lang('site.view_full_size')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($isActive && $branchStudents->isNotEmpty())
    <style>
        .student-branch-enrollment-card {
            border-radius: 0.5rem;
            border: 1px solid #ebe9f1;
            border-left: 4px solid #25B15D;
            transition: box-shadow 0.2s ease;
        }

        .student-branch-enrollment-card:hover {
            box-shadow: 0 0.25rem 1rem rgba(37, 177, 93, 0.12);
        }

        .student-branch-enrollment-card .enrollment-meta {
            font-size: 0.8125rem;
        }

        .student-branch-fee-pill {
            background: #f8f8f8;
            border-radius: 0.35rem;
            padding: 0.5rem 0.35rem;
            text-align: center;
        }

        .student-branch-fee-pill .fee-value {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .student-branch-fee-pill .fee-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            color: #6e6b7b;
            margin-bottom: 0.25rem;
        }

        .student-branch-fee-pill.fee-remaining .fee-value {
            color: #ea5455;
        }

        .student-branch-fee-pill.fee-paid .fee-value {
            color: #28c76f;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom d-flex flex-wrap align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-25">
                            <i data-feather="folder" class="mr-50"></i>
                            @lang('students.academic_information')
                        </h4>
                        <p class="card-text text-muted mb-0 small">@lang('students.branch_enrollments')</p>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <div class="row">
                        @foreach($branchStudents as $branchStudent)
                            <div class="col-12 {{ $branchStudents->count() === 1 ? 'col-md-12' : 'col-md-6' }} mb-2">
                                <div class="card student-branch-enrollment-card shadow-none mb-0 h-100">
                                    <div class="card-body p-1">

                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div class="pr-1">
                                                <h5 class="mb-25 font-weight-bolder">
                                                    {{ $branchStudent->project->name ?? '—' }}
                                                </h5>
                                                @if($branchStudent->curriculum)
                                                    <p class="text-muted enrollment-meta mb-0">
                                                        <i data-feather="layers" class="mr-25" style="width: 14px; height: 14px;"></i>
                                                        {{ $branchStudent->curriculum->name }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center flex-shrink-0">
                                                @if(session('selected_branch')['id'] ?? null)
                                                    @if(auth()->user()->hasPermission('update_students', session('selected_branch')['id'])
                                                        && ($branchStudentAcademicEditable[$branchStudent->id] ?? false))
                                                        <button type="button" class="btn btn-sm btn-flat-primary ajax-modal mr-50"
                                                                data-url="{{ route('organization.students.branch_enrollment.edit', array_filter(['student' => $student->hash_id, 'project_id' => $branchStudent->project_id], fn ($v) => $v !== null)) }}"
                                                                data-modal-title="@lang('students.edit_branch_enrollment')"
                                                                data-modal-size-class="modal-lg">
                                                            <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                                <span class="badge badge-light-secondary">#{{ $loop->iteration }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap enrollment-meta text-muted mb-1" style="gap: 0.75rem 1rem;">
                                            @if($branchStudent->level)
                                                <span>
                                                    <i data-feather="bar-chart-2" class="mr-25" style="width: 14px; height: 14px;"></i>
                                                    {{ $branchStudent->level->name }}
                                                </span>
                                            @endif
                                            @if($branchStudent->page_number !== null)
                                                <span>
                                                    <i data-feather="list" class="mr-25" style="width: 14px; height: 14px;"></i>
                                                    @lang('levels.page_number') {{ $branchStudent->page_number }}
                                                </span>
                                            @endif
                                            @if($branchStudent->classroom)
                                                <span>
                                                    <i data-feather="book-open" class="mr-25" style="width: 14px; height: 14px;"></i>
                                                    {{ $branchStudent->classroom->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="border-top pt-1 mt-1">
                                            <div class="d-flex align-items-center justify-content-between mb-50 flex-wrap">
                                                <small class="text-muted font-weight-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">
                                                    @lang('students.financial')
                                                </small>
                                                @if($branchStudent->currency)
                                                    <span class="badge badge-light-primary">
                                                        @lang('currencies.currency'): {{ $branchStudent->currency->code }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if($branchStudent->exempted_from_fees)
                                                <div class="alert alert-success mb-0 py-50 px-1">
                                                    <small class="mb-0"><i data-feather="shield" class="mr-25" style="width: 14px; height: 14px;"></i>@lang('students.exempted_from_fees')</small>
                                                </div>
                                            @else
                                                <div class="row mx-n25">
                                                    <div class="col-4 px-25">
                                                        <div class="student-branch-fee-pill">
                                                            <div class="fee-label">@lang('students.fees')</div>
                                                            <div class="fee-value">
                                                                {{ $branchStudent->fees !== null ? number_format((float) $branchStudent->fees, 2) : '—' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 px-25">
                                                        <div class="student-branch-fee-pill fee-paid">
                                                            <div class="fee-label">@lang('students.paid_fees')</div>
                                                            <div class="fee-value">
                                                                {{ $branchStudent->paid_fees !== null ? number_format((float) $branchStudent->paid_fees, 2) : '—' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 px-25">
                                                        <div class="student-branch-fee-pill fee-remaining">
                                                            <div class="fee-label">@lang('students.remaining_fees')</div>
                                                            <div class="fee-value">
                                                                {{ $branchStudent->remaining_fees !== null ? number_format((float) $branchStudent->remaining_fees, 2) : '—' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($isPending)
    <!-- Enrollment Status Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header border-bottom bg-warning">
                    <h4 class="card-title mb-0 text-white">
                        <i data-feather="clock" class="mr-50"></i>
                        @lang('students.enrollment_status')
                    </h4>
                </div>
                <div class="card-body pt-2">
                    <div class="alert alert-warning mb-0 p-2">
                        <i data-feather="alert-circle" class="mr-1"></i>
                        <strong>@lang('students.pending_enrollment')</strong>
                        <p class="mb-0 mt-1">@lang('students.pending_enrollment_message')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
