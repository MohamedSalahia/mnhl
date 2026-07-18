@use('App\Helpers\PhoneHelper')
@use('App\Enums\BranchStudentStatusEnum')

@php
    $branchEnrollmentInactive = $isActive
        && $latestBranchStudent
        && ($latestBranchStudent->status ?? BranchStudentStatusEnum::ACTIVE) === BranchStudentStatusEnum::INACTIVE;

    $selectedBranchSession = session('selected_branch');
@endphp

<div class="row mb-1">
    <div class="col-12">
        <div class="student-hero-banner {{ $isPending ? 'pending-hero-banner' : '' }} {{ !$isPending && $branchEnrollmentInactive ? 'branch-inactive-hero-banner' : '' }}">
            <!-- Decorative Shapes -->
            <div class="hero-shape-1"></div>
            <div class="hero-shape-2"></div>
            <div class="hero-shape-3"></div>
            <div class="hero-shape-5"></div>

            <div class="student-hero-content">

                <div class="row align-items-center">

                    <!-- Left: Avatar & Basic Info -->
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="student-avatar-wrapper mr-2 mb-1 mb-sm-0">
                                <img src="{{ $student->image_path }}" alt="{{ $student->name }}" class="rounded-circle">
                                @if($isHeroBranchActive ?? false)
                                    <div class="student-avatar-badge">
                                        <i data-feather="check"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="text-white flex-grow-1 min-w-0">
                                <div class="d-flex align-items-start justify-content-between mb-25">
                                    <h4 class="mb-0 font-weight-bolder text-white student-hero-title flex-grow-1 min-w-0 pr-1">{{ $student->name }}</h4>
                                    @if($isActive)
                                        <div class="dropdown student-hero-actions-dropdown flex-shrink-0">
                                            <button type="button"
                                                    class="btn hero-action-btn-primary student-hero-edit-btn d-inline-flex align-items-center dropdown-toggle"
                                                    id="student-hero-actions-dropdown"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                    title="@lang('site.actions')">
                                                <i data-feather="more-horizontal" class="student-hero-edit-icon mr-25"></i>
                                                <span class="student-hero-edit-label">@lang('site.actions')</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right student-hero-actions-menu py-1 shadow"
                                                 aria-labelledby="student-hero-actions-dropdown">
                                                <a href="{{ route('organization.students.edit', $student->hash_id) }}"
                                                   class="dropdown-item d-flex align-items-center"
                                                   wire:navigate>
                                                    <i data-feather="edit-2" class="mr-50" style="width: 14px; height: 14px;"></i>
                                                    @lang('site.edit')
                                                </a>
                                                @if(isset($selectedBranchSession['id']))
                                                    <a href="#"
                                                       class="dropdown-item ajax-modal d-flex align-items-center"
                                                       data-url="{{ route('organization.students.create_extra_project_enrollment', $student->hash_id) }}"
                                                       data-modal-title="@lang('students.extra_project_enrollment_modal_title')"
                                                       data-modal-size-class="modal-lg">
                                                        <i data-feather="plus" class="mr-50" style="width: 14px; height: 14px;"></i>
                                                        @lang('students.add_extra_project_enrollment')
                                                    </a>
                                                @endif
                                                @if($latestBranchStudent)
                                                    <div class="dropdown-item" onclick="event.stopPropagation();" style="cursor: default;">
                                                        <form method="post"
                                                              action="{{ route('organization.students.toggle_branch_student_status', [$student->hash_id, $latestBranchStudent->id]) }}"
                                                              class="ajax-form mb-0"
                                                              data-submit-on-change>
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="toggle" value="1">
                                                            <div class="custom-control custom-control-primary custom-switch mb-0">
                                                                <input type="checkbox"
                                                                       name="is_active"
                                                                       value="1"
                                                                       class="custom-control-input"
                                                                       id="branch-student-status-{{ $latestBranchStudent->id }}"
                                                                    {{ ($latestBranchStudent->status ?? BranchStudentStatusEnum::ACTIVE) === BranchStudentStatusEnum::ACTIVE ? 'checked' : '' }}>
                                                                <label class="custom-control-label text-body" for="branch-student-status-{{ $latestBranchStudent->id }}" style="font-size: 1rem;">
                                                                    @lang('students.branch_enrollment_status_toggle')
                                                                </label>
                                                            </div>
                                                            <button type="submit" class="d-none" tabindex="-1" aria-hidden="true"></button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if($isPending)
                                    <span class="badge" style="background: rgba(255,255,255,0.25); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                                    <i data-feather="clock" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                                    @lang('students.pending')
                                                </span>
                                @elseif(!$isActive)
                                    <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                                    <i data-feather="slash" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                                    @lang('students.inactive')
                                                </span>
                                @elseif($isHeroBranchActive ?? false)
                                    <span class="badge" style="background: rgba(40, 199, 111, 0.3); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                                    <i data-feather="check-circle" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                                    @lang('students.active')
                                                </span>
                                @else
                                    <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                                    <i data-feather="pause-circle" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                                    @lang('students.inactive_in_this_branch')
                                                </span>
                                @endif
                                <div class="mt-50" style="opacity: 0.9; font-size: 0.875rem;">
                                    @if($student->student_number)
                                    <div class="mb-25">
                                        <i data-feather="hash" class="mr-50" style="width: 14px; height: 14px; color: #fff;"></i>
                                        <span>{{ $student->student_number }}</span>
                                    </div>
                                    @endif
                                    <div class="mb-25">
                                        <i data-feather="mail" class="mr-50" style="width: 14px; height: 14px; color: #fff;"></i>
                                        <span>{{ $student->email }}</span>
                                    </div>
                                    <div>
                                        <i data-feather="phone" class="mr-50" style="width: 14px; height: 14px; color: #fff;"></i>
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
                                    </div>
                                </div>

                                <!-- Action Buttons & Registration Date -->
                                <div class="mt-1">
                                    @if($isPending)
                                        <div class="d-flex flex-wrap gap-2 mb-1">
                                            <button type="button" class="btn hero-action-btn-primary ajax-modal mr-1"
                                                    data-url="{{ route('organization.students.branch_enrollment.confirm_accept', $student->hash_id) }}"
                                                    data-modal-title="@lang('students.accept_enrollment')"
                                                    data-modal-size-class="modal-lg">
                                                <i data-feather="check" class="mr-50" style="color: #fff;"></i> @lang('students.accept_enrollment')
                                            </button>
                                            <button type="button" class="btn hero-action-btn ajax-modal"
                                                    data-url="{{ route('organization.students.branch_enrollment.confirm_reject', $student->hash_id) }}"
                                                    data-modal-title="@lang('students.reject_enrollment')"
                                                    data-modal-size-class="modal-md">
                                                <i data-feather="x" class="mr-50" style="color: #fff;"></i> @lang('students.reject_enrollment')
                                            </button>
                                        </div>
                                    @elseif($isActive)
                                        <div class="mb-0" style="color: rgba(255,255,255,0.8);">
                                            <small>
                                                <i data-feather="calendar" class="mr-25" style="width: 12px; height: 12px; color: #fff;"></i>
                                                @lang('site.registered_at'): <strong style="color: #fff;">{{ $student->created_at->format('Y-m-d') }}</strong>
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Academic Stats (latest project) -->
                    <div class="col-12 col-md-7">

                        @if($isActive && $latestBranchStudent)
                            <div>
                                @if($latestBranchStudent->curriculum || $latestBranchStudent->project)
                                    <div class="row {{ $latestBranchStudent->level || $latestBranchStudent->classroom ? 'mb-1' : '' }}">
                                        @if($latestBranchStudent->curriculum)
                                            <div class="col-md-{{ $latestBranchStudent->project ? '6' : '12' }} mb-1 mb-md-0">
                                                <div class="hero-stat-card h-100">
                                                    <div class="d-flex align-items-center">
                                                        <div class="hero-stat-icon mr-1">
                                                            <i data-feather="layers" style="color: #fff !important; stroke: #fff !important;"></i>
                                                        </div>
                                                        <div class="text-white flex-grow-1 min-w-0">
                                                            <small style="opacity: 0.8;">@lang('curricula.curriculum')</small>
                                                            <div class="font-weight-bold text-truncate" style="font-size: 0.875rem;" title="{{ $latestBranchStudent->curriculum->name }}">{{ $latestBranchStudent->curriculum->name }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($latestBranchStudent->project)
                                            <div class="col-md-{{ $latestBranchStudent->curriculum ? '6' : '12' }} mb-1 mb-md-0">
                                                <div class="hero-stat-card h-100">
                                                    <div class="d-flex align-items-center">
                                                        <div class="hero-stat-icon mr-1">
                                                            <i data-feather="folder" style="color: #fff !important; stroke: #fff !important;"></i>
                                                        </div>
                                                        <div class="text-white flex-grow-1 min-w-0">
                                                            <small style="opacity: 0.8;">@lang('projects.project')</small>
                                                            <div class="font-weight-bold text-truncate" style="font-size: 0.875rem;" title="{{ $latestBranchStudent->project->name }}">{{ $latestBranchStudent->project->name }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if($latestBranchStudent->level || $latestBranchStudent->classroom)
                                    <div class="row">
                                        @if($latestBranchStudent->level)
                                            <div class="col-md-{{ $latestBranchStudent->classroom ? '6' : '12' }} mb-1 mb-md-0">
                                                <div class="hero-stat-card h-100">
                                                    <div class="d-flex align-items-center">
                                                        <div class="hero-stat-icon mr-1">
                                                            <i data-feather="bar-chart-2" style="color: #fff !important; stroke: #fff !important;"></i>
                                                        </div>
                                                        <div class="text-white flex-grow-1 min-w-0">
                                                            <small style="opacity: 0.8;">@lang('levels.level')</small>
                                                            <div class="d-flex align-items-center flex-wrap" style="gap: 0.5rem;">
                                                                <span class="font-weight-bold" style="font-size: 0.875rem;">{{ $latestBranchStudent->level->name }}</span>
                                                                @if($latestBranchStudent->page_number !== null)
                                                                    <span class="d-inline-flex align-items-center" style="background: rgba(255, 255, 255, 0.2); color: #fff; font-size: 0.8rem; padding: 0.3rem 0.65rem; border-radius: 8px; font-weight: 500; border: 1px solid rgba(255, 255, 255, 0.15);">
                                                                        @lang('levels.page_number'): {{ $latestBranchStudent->page_number }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($latestBranchStudent->classroom)
                                            <div class="col-md-{{ $latestBranchStudent->level ? '6' : '12' }} mb-1 mb-md-0">
                                                <div class="hero-stat-card h-100">
                                                    <div class="d-flex align-items-center">
                                                        <div class="hero-stat-icon mr-1">
                                                            <i data-feather="book-open" style="color: #fff !important; stroke: #fff !important;"></i>
                                                        </div>
                                                        <div class="text-white flex-grow-1 min-w-0">
                                                            <small style="opacity: 0.8;">@lang('classrooms.classroom')</small>
                                                            <div class="font-weight-bold text-truncate" style="font-size: 0.875rem;" title="{{ $latestBranchStudent->classroom->name }}">{{ $latestBranchStudent->classroom->name }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
