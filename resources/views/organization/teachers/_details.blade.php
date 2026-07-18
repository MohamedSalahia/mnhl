@php
    use App\Enums\OrganizationTeacherStatusEnum;
    use App\Enums\UserTypeEnum;
    use App\Helpers\PhoneHelper;

    $isActive = $organizationTeacher && $organizationTeacher->pivot->status === OrganizationTeacherStatusEnum::ACTIVE;
    $certificatesCount = $teacher->teacherCertificates ? $teacher->teacherCertificates->count() : 0;
    $isExaminer = $isExaminer ?? false;
@endphp

<div class="row">
    <div class="col-12">
        <div class="teacher-hero-banner {{ $isPending ? 'pending-hero-banner' : '' }}">
            <!-- Decorative Shapes -->
            <div class="hero-shape-1"></div>
            <div class="hero-shape-2"></div>
            <div class="hero-shape-3"></div>
            <div class="hero-shape-5"></div>

            <div class="teacher-hero-content">

                <div class="row align-items-center">

                    <!-- Left: Avatar & Basic Info -->
                    <div class="col-lg-6 col-md-12 mb-lg-0 mb-3">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="teacher-avatar-wrapper mr-2 mb-2 mb-sm-0">
                                <img src="{{ $teacher->image_path }}" alt="{{ $teacher->name }}" class="rounded-circle">
                                @if($isActive)
                                    <div class="teacher-avatar-badge">
                                        <i data-feather="check"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="text-white flex-grow-1">
                                <h3 class="mb-50 font-weight-bolder text-white">{{ $teacher->name }}</h3>
                                <div class="d-flex flex-wrap gap-1 align-items-center mb-1">
                                    @if($isPending)
                                        <span class="badge" style="background: rgba(255,255,255,0.25); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                            <i data-feather="clock" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                            @lang('teachers.pending')
                                        </span>
                                    @else
                                        <span class="badge" style="background: rgba(40, 199, 111, 0.3); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                            <i data-feather="check-circle" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                            @lang('teachers.active')
                                        </span>
                                    @endif
                                    @if($isActive && $isExaminer)
                                        <span class="badge" style="background: rgba(255, 193, 7, 0.3); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                            <i data-feather="award" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                            @lang('teachers.examiner')
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-1" style="opacity: 0.9;">
                                    <div class="mb-25">
                                        <i data-feather="mail" class="mr-50" style="width: 14px; height: 14px; color: #fff;"></i>
                                        <span>{{ $teacher->email }}</span>
                                    </div>
                                    <div>
                                        <i data-feather="phone" class="mr-50" style="width: 14px; height: 14px; color: #fff;"></i>
                                        <span class="mobile">
                                            @if($teacher->mobile_country_code && $teacher->mobile)
                                                @php
                                                    $countryCode = PhoneHelper::getCountryCodeFromDialCode($teacher->mobile_country_code);
                                                @endphp
                                                @if($countryCode)
                                                    <i class="flag-icon flag-icon-{{ $countryCode }}"></i>
                                                @endif
                                                {{ $teacher->mobile_country_code }} {{ $teacher->mobile }}
                                            @else
                                                {{ $teacher->mobile }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Action Buttons & Registration Date -->
                                <div class="mt-2">
                                    @if($isPending)
                                        <div class="d-flex flex-wrap gap-2 mb-1">
                                            <button type="button" class="btn hero-action-btn-primary ajax-modal mr-1"
                                                    data-url="{{ route('organization.teachers.confirm_accept_enrollment', $teacher->hash_id) }}"
                                                    data-modal-title="@lang('teachers.accept_enrollment')"
                                                    data-modal-size-class="modal-md">
                                                <i data-feather="check" class="mr-50" style="color: #fff;"></i> @lang('teachers.accept_enrollment')
                                            </button>
                                            <button type="button" class="btn hero-action-btn ajax-modal"
                                                    data-url="{{ route('organization.teachers.confirm_reject_enrollment', $teacher->hash_id) }}"
                                                    data-modal-title="@lang('teachers.reject_enrollment')"
                                                    data-modal-size-class="modal-md">
                                                <i data-feather="x" class="mr-50" style="color: #fff;"></i> @lang('teachers.reject_enrollment')
                                            </button>
                                        </div>
                                    @elseif($isActive)
                                        <div>
                                            <div class="mb-1" style="color: rgba(255,255,255,0.8);">
                                                <small>
                                                    <i data-feather="calendar" class="mr-25" style="width: 12px; height: 12px; color: #fff;"></i>
                                                    @lang('site.registered_at'): <strong style="color: #fff;">{{ $teacher->created_at->format('Y-m-d') }}</strong>
                                                </small>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <a href="{{ route('organization.teachers.edit', $teacher->hash_id) }}" class="btn hero-action-btn-primary" wire:navigate>
                                                    <i data-feather="edit-2" class="mr-50" style="color: #25B15D !important;"></i> @lang('site.edit')
                                                </a>
                                                <div class="custom-control custom-control-primary custom-switch" style="margin-top: 0;">
                                                    <input type="checkbox" class="custom-control-input toggle-examiner" 
                                                           id="examiner-toggle-{{ $teacher->id }}" 
                                                           {{ $isExaminer ? 'checked' : '' }}
                                                           data-url="{{ route('organization.teachers.toggle_examiner', $teacher->hash_id) }}"
                                                           data-details-url="{{ route('organization.teachers.details', $teacher->hash_id) }}">
                                                    <label class="custom-control-label text-white" for="examiner-toggle-{{ $teacher->id }}" style="font-size: 0.85rem;">
                                                        @lang('teachers.mark_as_examiner')
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Stats -->
                    <div class="col-lg-6 col-md-12">
                        @if($isActive)
                            <div>
                                <div class="hero-stat-card mb-1">
                                    <div class="d-flex align-items-center">
                                        <div class="hero-stat-icon mr-1">
                                            <i data-feather="award" style="color: #fff !important; stroke: #fff !important;"></i>
                                        </div>
                                        <div class="text-white flex-grow-1">
                                            <small style="opacity: 0.8;">@lang('teachers.teacher_certificates')</small>
                                            <div class="font-weight-bold" style="font-size: 0.95rem;">
                                                {{ $certificatesCount }} {{ $certificatesCount == 1 ? __('site.certificate') : __('site.certificates') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="hero-stat-card mb-1">
                                    <div class="d-flex align-items-center">
                                        <div class="hero-stat-icon mr-1">
                                            <i data-feather="file-text" style="color: #fff !important; stroke: #fff !important;"></i>
                                        </div>
                                        <div class="text-white flex-grow-1">
                                            <small style="opacity: 0.8;">@lang('users.identity_document_file')</small>
                                            <div class="font-weight-bold" style="font-size: 0.95rem;">
                                                @if($teacher->identity_document_file)
                                                    <span style="color: #28c76f;"><i data-feather="check" style="width: 14px; height: 14px;"></i> @lang('site.available')</span>
                                                @else
                                                    <span style="color: #ea5455;"><i data-feather="x" style="width: 14px; height: 14px;"></i> @lang('site.not_available')</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($teacher->nationality)
                                    <div class="hero-stat-card">
                                        <div class="d-flex align-items-center">
                                            <div class="hero-stat-icon mr-1">
                                                <i data-feather="globe" style="color: #fff !important; stroke: #fff !important;"></i>
                                            </div>
                                            <div class="text-white flex-grow-1">
                                                <small style="opacity: 0.8;">@lang('nationalities.nationality')</small>
                                                <div class="font-weight-bold" style="font-size: 0.95rem;">{{ $teacher->nationality->name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
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
                    <span class="font-weight-bold">{{ $teacher->gender == 'male' ? __('users.male') : __('users.female') }}</span>
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
                    <span class="font-weight-bold">{{ $teacher->date_of_birth ?? '-' }}</span>
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
                    <span class="font-weight-bold">{{ Str::limit($teacher->birth_place, 15) ?? '-' }}</span>
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
                    <span class="font-weight-bold">{{ Str::limit($teacher->current_address, 15) ?? '-' }}</span>
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
                    @lang('teachers.personal_information')
                </h4>
            </div>
            <div class="card-body pt-2">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td class="text-muted" style="width: 40%;">
                            <i data-feather="user" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.name')
                        </td>
                        <td class="font-weight-bold">{{ $teacher->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="mail" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.email')
                        </td>
                        <td class="font-weight-bold">{{ $teacher->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="phone" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.mobile')
                        </td>
                        <td class="font-weight-bold">
                            <span class="mobile">
                                @if($teacher->mobile_country_code && $teacher->mobile)
                                    @php
                                        $countryCode = PhoneHelper::getCountryCodeFromDialCode($teacher->mobile_country_code);
                                    @endphp
                                    @if($countryCode)
                                        <i class="flag-icon flag-icon-{{ $countryCode }}"></i>
                                    @endif
                                    {{ $teacher->mobile_country_code }} {{ $teacher->mobile }}
                                @else
                                    {{ $teacher->mobile }}
                                @endif
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="user" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.gender')
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $teacher->gender == 'male' ? 'primary' : 'danger' }}">
                                {{ $teacher->gender == 'male' ? __('users.male') : __('users.female') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="calendar" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('users.date_of_birth')
                        </td>
                        <td class="font-weight-bold">{{ $teacher->date_of_birth ?? '-' }}</td>
                    </tr>
                    @if($teacher->birth_place)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="map-pin" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.birth_location')
                            </td>
                            <td class="font-weight-bold">{{ $teacher->birth_place }}</td>
                        </tr>
                    @endif
                    @if($teacher->marital_status)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="heart" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.marital_status')
                            </td>
                            <td class="font-weight-bold">@lang('users.' . $teacher->marital_status)</td>
                        </tr>
                    @endif
                    @if($teacher->nationality)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="globe" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('nationalities.nationality')
                            </td>
                            <td class="font-weight-bold">{{ $teacher->nationality->name }}</td>
                        </tr>
                    @endif
                    @if($teacher->current_address)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="home" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.current_address')
                            </td>
                            <td class="font-weight-bold">{{ $teacher->current_address }}</td>
                        </tr>
                    @endif
                    @if($teacher->last_educational_certificate)
                        <tr>
                            <td class="text-muted">
                                <i data-feather="book" class="mr-50" style="width: 14px; height: 14px;"></i>
                                @lang('users.last_educational_certificate')
                            </td>
                            <td class="font-weight-bold">{{ $teacher->last_educational_certificate }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Certificates Card -->
    <div class="col-lg-6 col-12">
        <div class="card h-100">
            <div class="card-header border-bottom">
                <h4 class="card-title mb-0">
                    <i data-feather="award" class="mr-50"></i>
                    @lang('teachers.teacher_certificates')
                </h4>
            </div>
            <div class="card-body pt-2">
                @if($teacher->teacherCertificates && $teacher->teacherCertificates->count() > 0)
                    <div class="row">
                        @foreach($teacher->teacherCertificates as $certificate)
                            <div class="col-md-6 mb-2">
                                <div class="card border">
                                    <div class="card-body p-2 text-center">
                                        <img src="{{ $certificate->preview_path }}" alt="Certificate" class="img-fluid mb-2" style="max-height: 120px;">
                                        <br>
                                        <a href="{{ $certificate->file_path }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i data-feather="download" style="width: 14px; height: 14px;"></i> @lang('site.view')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i data-feather="award" style="width: 48px; height: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-2">@lang('site.no_certificates_available')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($teacher->identity_document_file)
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
                                <a href="{{ $teacher->identity_document_file_path }}" target="_blank">
                                    <img src="{{ $teacher->identity_document_file_path }}" alt="@lang('users.identity_document_file')" class="img-fluid rounded" style="max-width: 400px; max-height: 300px; object-fit: contain;">
                                </a>
                            </div>
                            <div class="mt-1">
                                <a href="{{ $teacher->identity_document_file_path }}" target="_blank" class="btn btn-sm btn-outline-primary">
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

@if($isPending)
    <!-- Enrollment Status Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header border-bottom bg-warning">
                    <h4 class="card-title mb-0 text-white">
                        <i data-feather="clock" class="mr-50"></i>
                        @lang('teachers.enrollment_status')
                    </h4>
                </div>
                <div class="card-body pt-2">
                    <div class="alert alert-warning mb-0 p-2">
                        <i data-feather="alert-circle" class="mr-1"></i>
                        <strong>@lang('teachers.pending_enrollment')</strong>
                        <p class="mb-0 mt-1">@lang('teachers.pending_enrollment_message')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
