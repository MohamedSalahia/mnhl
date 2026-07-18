@extends('layouts.organization.app')

@section('content')

    <style>
        .teacher-hero-banner {
            background: linear-gradient(135deg, #25B15D 0%, #1a8a47 100%);
            border-radius: 0.75rem;
            position: relative;
            overflow: hidden;
            min-height: 260px;
        }

        .teacher-hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .teacher-hero-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .hero-shape-1 {
            position: absolute;
            top: 20px;
            right: 15%;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            transform: rotate(45deg);
        }

        .hero-shape-2 {
            position: absolute;
            bottom: 40px;
            right: 25%;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .hero-shape-3 {
            position: absolute;
            top: 40%;
            right: 8%;
            width: 80px;
            height: 80px;
            border: 3px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
        }

        .hero-shape-5 {
            position: absolute;
            top: 15%;
            left: 30%;
            width: 15px;
            height: 15px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
        }

        .teacher-hero-content {
            position: relative;
            z-index: 10;
            padding: 2rem;
        }

        .teacher-avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .teacher-avatar-wrapper img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .teacher-avatar-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 28px;
            height: 28px;
            background: #28c76f;
            border: 3px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .teacher-avatar-badge i {
            color: #fff;
            width: 14px;
            height: 14px;
        }

        .hero-stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 0.5rem 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .hero-stat-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .hero-stat-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
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
            width: 20px;
            height: 20px;
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
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
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

        .pending-hero-banner {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        }

        .pending-hero-banner .hero-action-btn-primary {
            color: #25B15D;
        }

        .pending-hero-banner .hero-action-btn-primary:hover {
            color: #1a8a47;
        }

        /* Ensure all icons in hero section are white */
        .teacher-hero-banner i[data-feather],
        .teacher-hero-banner [data-feather],
        .teacher-hero-banner svg {
            color: #fff !important;
            stroke: #fff !important;
        }

        .teacher-hero-banner .hero-action-btn-primary i[data-feather],
        .teacher-hero-banner .hero-action-btn-primary [data-feather],
        .teacher-hero-banner .hero-action-btn-primary svg {
            color: #25B15D !important;
            stroke: #25B15D !important;
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

                        <h2 class="content-header-title float-left mb-0">@lang('teachers.teacher_details')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.teachers.index') }}" wire:navigate>@lang('teachers.teachers')</a></li>
                                <li class="breadcrumb-item active">{{ $teacher->name }}</li>
                            </ol>

                        </div><!-- end of breadcrumb -->
                    </div>
                </div><!-- end of row -->

            </div><!-- end of content header -->

        </div><!-- end of content header -->

        <div class="btn-group" role="group" aria-label="Basic example">

            <button type="button" class="btn btn-primary waves-effect waves-float waves-light ajax-data active" data-url="{{ route('organization.teachers.details', $teacher->hash_id) }}" style="border-bottom-left-radius: 0">@lang('teachers.details')</button>

            <button type="button" class="btn btn-primary waves-effect waves-float waves-light ajax-data" data-url="{{ route('organization.teachers.lessons', $teacher->hash_id) }}">@lang('teachers.lessons')</button>

            <button type="button" class="btn btn-primary waves-effect waves-float waves-light ajax-data" data-url="{{ route('organization.teachers.salaries', $teacher->hash_id) }}">@lang('teachers.salaries')</button>

        </div>

        <div class="content-body">
            <div id="ajax-data-wrapper" style="padding-top: 20px">
                @include('organization.teachers._details')
            </div>

            @if($isPending)
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="d-flex justify-content-end align-items-center gap-4">
                            <button type="button" class="btn btn-success ajax-modal"
                                    data-url="{{ route('organization.teachers.confirm_accept_enrollment', $teacher->hash_id) }}"
                                    data-modal-title="@lang('teachers.accept_enrollment')"
                                    data-modal-size-class="modal-md">
                                <i data-feather="check"></i> @lang('teachers.accept_enrollment')
                            </button>
                            <button type="button" class="btn btn-danger ajax-modal"
                                    data-url="{{ route('organization.teachers.confirm_reject_enrollment', $teacher->hash_id) }}"
                                    data-modal-title="@lang('teachers.reject_enrollment')"
                                    data-modal-size-class="modal-md">
                                <i data-feather="x"></i> @lang('teachers.reject_enrollment')
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
