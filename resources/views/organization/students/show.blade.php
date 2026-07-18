@extends('layouts.organization.app')

@section('content')

    @use('App\Enums\BranchStudentStatusEnum')
    @use('App\Enums\OrganizationStudentStatusEnum')

    @php
        $isActive = $organizationStudent && $organizationStudent->pivot->status === OrganizationStudentStatusEnum::ACTIVE;

        $selectedBranch = session('selected_branch');

        // Hero badge/avatar: reflect branch_student.status for the selected branch (not organization_student alone)
        $isHeroBranchActive = false;
        if (!$isPending && $isActive) {
            if ($selectedBranch && isset($selectedBranch['id'])) {
                if ($latestBranchStudent) {
                    $isHeroBranchActive = ($latestBranchStudent->status ?? BranchStudentStatusEnum::ACTIVE) === BranchStudentStatusEnum::ACTIVE;
                }
            } else {
                $isHeroBranchActive = true;
            }
        }
    @endphp

    <style>
        .student-hero-banner {
            background: linear-gradient(135deg, #25B15D 0%, #1a8a47 100%);
            border-radius: 0.75rem;
            position: relative;
            /* visible so the Actions dropdown menu is not clipped at the banner edge */
            overflow: visible;
            min-height: 0;
        }

        /* Keep decorative circles inside the banner horizontally so overflow:visible (for the Actions
           dropdown) does not widen the page and cause a horizontal scrollbar. */
        .student-hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: 0;
            width: 260px;
            height: 260px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .student-hero-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .hero-shape-1 {
            position: absolute;
            top: 12px;
            right: 15%;
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            transform: rotate(45deg);
        }

        .hero-shape-2 {
            position: absolute;
            bottom: 24px;
            right: 25%;
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .hero-shape-3 {
            position: absolute;
            top: 40%;
            right: 8%;
            width: 56px;
            height: 56px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
        }

        .hero-shape-5 {
            position: absolute;
            top: 15%;
            left: 30%;
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
        }

        .student-hero-content {
            position: relative;
            z-index: 10;
            padding: 1rem 1.25rem;
        }

        .student-hero-title {
            font-size: 1.25rem;
            line-height: 1.35;
        }

        .student-avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .student-avatar-wrapper img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .student-avatar-badge {
            position: absolute;
            bottom: 3px;
            right: 3px;
            width: 22px;
            height: 22px;
            background: #28c76f;
            border: 2px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .student-avatar-badge i {
            color: #fff;
            width: 11px;
            height: 11px;
        }

        .hero-stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 0.35rem 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .hero-stat-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .hero-stat-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-stat-icon i,
        .hero-stat-icon [data-feather],
        .hero-stat-icon svg {
            color: #fff !important;
            stroke: #fff !important;
            fill: none !important;
            width: 16px;
            height: 16px;
        }

        .hero-stat-icon svg {
            stroke: #fff !important;
            color: #fff !important;
        }

        .hero-stat-card .hero-stat-icon i,
        .hero-stat-card .hero-stat-icon svg,
        .hero-stat-card .hero-stat-icon [data-feather] {
            color: #fff !important;
            stroke: #fff !important;
            fill: none !important;
        }

        .hero-action-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .hero-action-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            transform: translateY(-2px);
        }

        .hero-action-btn-primary {
            background: #fff;
            color: #25B15D;
            border: none;
        }

        .hero-action-btn-primary:hover {
            background: rgba(255, 255, 255, 0.9);
            color: #1a8a47;
        }

        .student-hero-edit-btn {
            padding: 0.25rem 0.5rem;
            line-height: 1.2;
            border-radius: 6px;
            font-size: 0.8125rem;
        }

        .student-hero-edit-btn:hover {
            transform: none;
        }

        .student-hero-edit-label {
            white-space: nowrap;
        }

        .student-hero-actions-dropdown {
            position: relative;
            z-index: 1080;
        }

        .student-hero-actions-dropdown .dropdown-menu.student-hero-actions-menu {
            z-index: 1090;
            min-width: 14rem;
        }

        /* Dropdown sits on white background; reset global hero “all icons white” for menu items */
        .student-hero-actions-menu i[data-feather],
        .student-hero-actions-menu [data-feather],
        .student-hero-actions-menu svg {
            color: #6e6b7b !important;
            stroke: #6e6b7b !important;
        }

        .student-hero-edit-btn .student-hero-edit-icon,
        .student-hero-edit-btn > svg {
            width: 14px !important;
            height: 14px !important;
        }

        .pending-hero-banner {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        }

        .pending-hero-banner .hero-action-btn-primary {
            color: #f6851b;
        }

        .pending-hero-banner .hero-action-btn-primary:hover {
            color: #f6851b;
        }

        .branch-inactive-hero-banner {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        }

        .branch-inactive-hero-banner .hero-action-btn-primary {
            color: #475569;
        }

        .branch-inactive-hero-banner .hero-action-btn-primary:hover {
            color: #334155;
        }

        /* Ensure all icons in hero section are white */
        .student-hero-banner i[data-feather],
        .student-hero-banner [data-feather],
        .student-hero-banner svg {
            color: #fff !important;
            stroke: #fff !important;
        }

        .student-hero-banner .hero-action-btn-primary i[data-feather],
        .student-hero-banner .hero-action-btn-primary [data-feather],
        .student-hero-banner .hero-action-btn-primary svg {
            color: #25B15D !important;
            stroke: #25B15D !important;
        }

        .branch-inactive-hero-banner .hero-action-btn-primary i[data-feather],
        .branch-inactive-hero-banner .hero-action-btn-primary [data-feather],
        .branch-inactive-hero-banner .hero-action-btn-primary svg {
            color: #475569 !important;
            stroke: #475569 !important;
        }

        /* Force white color for Feather icons in hero-stat-card */
        .hero-stat-card svg {
            stroke: #fff !important;
            color: #fff !important;
        }

        .hero-stat-card .hero-stat-icon svg {
            stroke: #fff !important;
            color: #fff !important;
            fill: none !important;
        }
    </style>

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-12 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('students.student_details')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.students.index') }}" wire:navigate>@lang('students.students')</a></li>
                                <li class="breadcrumb-item active">{{ $student->name }}</li>
                            </ol>

                        </div><!-- end of breadcrumb -->
                    </div>
                </div><!-- end of row -->

            </div><!-- end of content header -->

        </div><!-- end of content header -->

        <div class="content-body">

            @include('organization.students._student_hero')

            <div class="btn-group" role="group" aria-label="Basic example">

                <button type="button" class="btn btn-outline-primary waves-effect waves-float waves-light ajax-data active" data-url="{{ route('organization.students.details', $student->hash_id) }}" style="border-bottom-left-radius: 0">@lang('students.details')</button>

                <button type="button" class="btn btn-outline-primary waves-effect waves-float waves-light ajax-data" data-url="{{ route('organization.students.lessons', $student->hash_id) }}">@lang('students.lessons')</button>

                <button type="button" class="btn btn-outline-primary waves-effect waves-float waves-light ajax-data" data-url="{{ route('organization.students.installments', $student->hash_id) }}" style="border-bottom-right-radius: 0">@lang('students.installments_tab')</button>

            </div>

            <div id="ajax-data-wrapper" style="padding-top: 20px">
                @include('organization.students._details')
            </div>

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
