@php use App\Enums\BranchStudentStatusEnum;use App\Enums\OrganizationStudentStatusEnum;use App\Enums\OrganizationTeacherStatusEnum;use App\Enums\AssessmentStatusEnum; @endphp
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route('organization.home') }}">
                    <span class="brand-logo">
                        <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                                            <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                    </span>
                    <h2 class="brand-text">منهل</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>

    <div class="shadow-bottom"></div>

    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="{{ request()->is('*home*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('organization.home') }}" wire:navigate>
                    <i data-feather="mail"></i><span class="menu-title text-truncate">@lang('site.home')</span>
                </a>
            </li>

            {{--roles--}}
            @if (auth()->user()->hasPermission('read_roles', session('selected_branch')['id']))
                <li class="{{ request()->is('*roles*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.roles.index') }}" wire:navigate>
                        <i data-feather="lock"></i><span class="menu-title text-truncate">@lang('roles.roles')</span>
                    </a>
                </li>
            @endif

            {{--admins--}}
            @if (auth()->user()->hasPermission('read_admins', session('selected_branch')['id']))
                <li class="{{ request()->is('*admins*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.admins.index') }}" wire:navigate>
                        <i data-feather="users"></i><span class="menu-title text-truncate">@lang('admins.admins')</span>
                    </a>
                </li>
            @endif

            {{--branches--}}
            @if (auth()->user()->hasPermission('read_branches', session('selected_branch')['id']))
                <li class="{{ request()->is('*branches*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.branches.index') }}" wire:navigate>
                        <i data-feather="git-branch"></i><span class="menu-title text-truncate">@lang('branches.branches')</span>
                    </a>
                </li>
            @endif

            {{--installments--}}
            @if (auth()->user()->hasPermission('read_installments', session('selected_branch')['id']))
                <li class="{{ request()->is('*installments*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.installments.index') }}" wire:navigate>
                        <i data-feather="credit-card"></i><span class="menu-title text-truncate">@lang('installments.installments')</span>
                    </a>
                </li>
            @endif

            {{--teacher salaries--}}
            @if (auth()->user()->hasPermission('read_teacher_salaries', session('selected_branch')['id']))
                <li class="{{ request()->is('*teacher_salaries*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.teacher_salaries.index') }}" wire:navigate>
                        <i data-feather="credit-card"></i><span class="menu-title text-truncate">@lang('teacher_salaries.teacher_salaries')</span>
                    </a>
                </li>
            @endif

            {{--financial transactions--}}
            @if (auth()->user()->hasPermission('read_financial_transactions', session('selected_branch')['id']))
                <li class="{{ request()->is('*financial_transactions*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.financial_transactions.index') }}" wire:navigate>
                        <i data-feather="credit-card"></i><span class="menu-title text-truncate">@lang('financial_transactions.financial_transactions')</span>
                    </a>
                </li>
            @endif

            {{--students--}}
            @if (auth()->user()->hasPermission('read_students', session('selected_branch')['id']))

                <li class="nav-item">
                    <a class="d-flex align-items-center" href="#"><i data-feather="user-check"></i><span class="menu-title text-truncate">@lang('students.students')</span></a>
                    <ul class="menu-content">
                        <li class="{{ request()->is('*students*') && request()->get('status') == OrganizationStudentStatusEnum::ACTIVE && request()->get('branch_status', BranchStudentStatusEnum::ACTIVE) == BranchStudentStatusEnum::ACTIVE ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('organization.students.index', ['status' => OrganizationStudentStatusEnum::ACTIVE, 'branch_status' => BranchStudentStatusEnum::ACTIVE]) }}" wire:navigate>
                                <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('students.students')</span>
                            </a>
                        </li>
                        <li class="{{ request()->is('*students*') && request()->status == OrganizationStudentStatusEnum::PENDING ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('organization.students.index', ['status' => OrganizationStudentStatusEnum::PENDING]) }}" wire:navigate>
                                <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('students.student_enrollment_requests')</span>
                            </a>
                        </li>
                        <li class="{{ request()->is('*students*') && request()->get('status') == OrganizationStudentStatusEnum::ACTIVE && request()->get('branch_status') == BranchStudentStatusEnum::INACTIVE ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('organization.students.index', ['status' => OrganizationStudentStatusEnum::ACTIVE, 'branch_status' => BranchStudentStatusEnum::INACTIVE]) }}" wire:navigate>
                                <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('students.inactive_in_this_branch')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            {{--teachers--}}
            @if (auth()->user()->hasPermission('read_teachers', session('selected_branch')['id']))
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="#"><i data-feather="user"></i><span class="menu-title text-truncate">@lang('teachers.teachers')</span></a>
                    <ul class="menu-content">
                        <li class="{{ (request()->is('*teachers*') && request()->get('status') == OrganizationTeacherStatusEnum::ACTIVE) || request()->is('*teachers*') && !request()->get('status') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('organization.teachers.index', ['status' => OrganizationTeacherStatusEnum::ACTIVE]) }}" wire:navigate>
                                <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('teachers.teachers')</span>
                            </a>
                        </li>
                        <li class="{{ request()->is('*teachers*') && request()->get('status') == OrganizationTeacherStatusEnum::PENDING ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('organization.teachers.index', ['status' => OrganizationTeacherStatusEnum::PENDING]) }}" wire:navigate>
                                <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('teachers.teacher_enrollment_requests')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            {{--examiners--}}
            @if (auth()->user()->hasPermission('read_examiners', session('selected_branch')['id']))
                <li class="{{ request()->is('*examiners*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.examiners.index') }}" wire:navigate>
                        <i data-feather="user-check"></i><span class="menu-title text-truncate">@lang('examiners.examiners')</span>
                    </a>
                </li>
            @endif

            {{--classrooms--}}
            @if (auth()->user()->hasPermission('read_classrooms', session('selected_branch')['id']))
                <li class="{{ request()->is('*classrooms*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.classrooms.index') }}" wire:navigate>
                        <i data-feather="book-open"></i><span class="menu-title text-truncate">@lang('classrooms.classrooms')</span>
                    </a>
                </li>
            @endif

            {{--lessons--}}
            @if (auth()->user()->hasPermission('read_lessons', session('selected_branch')['id']))
                <li class="{{ request()->is('*lessons*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organization.lessons.index') }}" wire:navigate>
                        <i data-feather="calendar"></i><span class="menu-title text-truncate">@lang('lessons.lessons')</span>
                    </a>
                </li>
            @endif

            {{--evaluation models--}}
            @if (
                auth()->user()->hasPermission('read_evaluation_models', session('selected_branch')['id']) ||
                auth()->user()->hasPermission('read_evaluation_items', session('selected_branch')['id'])
            )
                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="clipboard"></i><span class="menu-title text-truncate">@lang('evaluation_models.evaluation_models')</span></a>
                    <ul class="menu-content">
                        @if (auth()->user()->hasPermission('read_evaluation_models', session('selected_branch')['id']))
                            <li class="{{ request()->is('*evaluation_models*') && !request()->is('*evaluation_items*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.evaluation_models.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('evaluation_models.evaluation_models')</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_evaluation_items', session('selected_branch')['id']))
                            <li class="{{ request()->is('*evaluation_items*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.evaluation_items.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('evaluation_items.evaluation_items')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{--assessment schemes--}}
            @if (
                auth()->user()->hasPermission('read_assessment_schemes', session('selected_branch')['id']) ||
                auth()->user()->hasPermission('read_assessment_scheme_deductions', session('selected_branch')['id'])
            )
                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="file-text"></i><span class="menu-title text-truncate">@lang('assessment_schemes.assessment_schemes')</span></a>
                    <ul class="menu-content">
                        @if (auth()->user()->hasPermission('read_assessment_schemes', session('selected_branch')['id']))
                            <li class="{{ request()->is('*assessment_schemes*') && !request()->is('*assessment_scheme_deductions*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.assessment_schemes.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('assessment_schemes.assessment_schemes')</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_assessment_scheme_deductions', session('selected_branch')['id']))
                            <li class="{{ request()->is('*assessment_scheme_deductions*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.assessment_scheme_deductions.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('assessment_scheme_deductions.assessment_scheme_deductions')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{--assessments--}}
            <li class="nav-item">
                <a class="d-flex align-items-center" href="#"><i data-feather="clipboard"></i><span class="menu-title text-truncate">@lang('assessments.assessments')</span></a>
                <ul class="menu-content">
                    <li class="{{ request()->is('*assessments*') && !request()->is('*assessments/create*') && ((request()->get('status') == AssessmentStatusEnum::PENDING || (request()->get('status') == null && !request()->is('*assessments/*'))) || (isset($assessment) && $assessment->status == AssessmentStatusEnum::PENDING)) ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('organization.assessments.index', ['status' => AssessmentStatusEnum::PENDING]) }}" wire:navigate>
                            <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('assessments.assessment_requests')</span>
                        </a>
                    </li>

                    <li class="{{ request()->is('*assessments*') && !request()->is('*assessments/create*') && (request()->get('status') == AssessmentStatusEnum::IN_PROGRESS || (isset($assessment) && in_array($assessment->status, [AssessmentStatusEnum::IN_PROGRESS, AssessmentStatusEnum::PARTIALLY_IN_PROGRESS]))) ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('organization.assessments.index', ['status' => AssessmentStatusEnum::IN_PROGRESS]) }}" wire:navigate>
                            <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('assessments.in_progress_assessments')</span>
                        </a>
                    </li>

                    <li class="{{ request()->is('*assessments*') && !request()->is('*assessments/create*') && (request()->get('status') == AssessmentStatusEnum::COMPLETED || (isset($assessment) && $assessment->status == AssessmentStatusEnum::COMPLETED)) ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('organization.assessments.index', ['status' => AssessmentStatusEnum::COMPLETED]) }}" wire:navigate>
                            <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('assessments.completed_assessments')</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{--projects--}}
            @if (
                auth()->user()->hasPermission('read_projects', session('selected_branch')['id']) ||
                auth()->user()->hasPermission('read_levels', session('selected_branch')['id']) ||
                auth()->user()->hasPermission('read_curricula', session('selected_branch')['id'])
            )
                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="briefcase"></i><span class="menu-title text-truncate">@lang('projects.projects')</span></a>
                    <ul class="menu-content">

                        @if (auth()->user()->hasPermission('read_curricula', session('selected_branch')['id']))
                            <li class="{{ request()->is('*curricula*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.curricula.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('curricula.curricula')</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_projects', session('selected_branch')['id']))
                            <li class="{{ request()->is('*projects*') && !request()->is('*levels*') && !request()->is('*curricula*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.projects.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('projects.projects')</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_levels', session('selected_branch')['id']))
                            <li class="{{ request()->is('*levels*') && !request()->is('*curricula*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.levels.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('levels.levels')</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            {{--settings--}}
            @if (
                auth()->user()->hasPermission('read_settings', session('selected_branch')['id']) ||
                auth()->user()->hasPermission('read_currencies', session('selected_branch')['id']) ||
                auth()->user()->hasPermission('read_payment_methods', session('selected_branch')['id']) ||
                auth()->user()->hasPermission('read_subscription_types', session('selected_branch')['id'])
            )
                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="settings"></i><span class="menu-title text-truncate">@lang('settings.settings')</span></a>
                    <ul class="menu-content">
                        @if (auth()->user()->hasPermission('read_settings', session('selected_branch')['id']))
                            <li class="{{ request()->is('*settings/student_registration*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.settings.student_registration') }}">
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('settings.student_registration')</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('*settings/teacher_registration*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.settings.teacher_registration') }}">
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('settings.teacher_registration')</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_currencies', session('selected_branch')['id']))
                            <li class="{{ request()->is('*currencies*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.currencies.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('currencies.currencies')</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_payment_methods', session('selected_branch')['id']))
                            <li class="{{ request()->is('*payment_methods*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.payment_methods.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('payment_methods.payment_methods')</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasPermission('read_subscription_types', session('selected_branch')['id']))
                            <li class="{{ request()->is('*subscription_types*') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('organization.subscription_types.index') }}" wire:navigate>
                                    <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('subscription_types.subscription_types')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{--profile--}}
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="user"></i><span class="menu-title text-truncate">@lang('users.profile')</span></a>
                <ul class="menu-content">
                    <li class="{{ request()->is('*profile*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('organization.profile.edit') }}" wire:navigate>
                            <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('users.profile')</span>
                        </a>
                    </li>

                    <li class="{{ request()->is('*password*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('organization.profile.password.edit') }}" wire:navigate>
                            <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('users.change_password')</span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</div>
<!-- END: Main Menu-->
