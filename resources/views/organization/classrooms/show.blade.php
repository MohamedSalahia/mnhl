@php use App\Enums\ClassroomTypeEnum; @endphp
@extends('layouts.organization.app')

@section('content')

    <style>
        .classroom-hero-banner {
            background: linear-gradient(135deg, #7367f0 0%, #5c50c9 100%);
            border-radius: 0.75rem;
            position: relative;
            overflow: hidden;
            min-height: 260px;
        }

        .classroom-hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .classroom-hero-banner::after {
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

        .classroom-hero-content {
            position: relative;
            z-index: 10;
            padding: 2rem;
        }

        .classroom-icon-wrapper {
            position: relative;
            display: inline-block;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .classroom-icon-wrapper i,
        .classroom-icon-wrapper svg {
            color: #fff !important;
            stroke: #fff !important;
            width: 60px;
            height: 60px;
        }

        .classroom-type-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 32px;
            height: 32px;
            background: #28c76f;
            border: 3px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .classroom-type-badge i,
        .classroom-type-badge svg {
            color: #fff;
            width: 16px;
            height: 16px;
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
            color: #7367f0;
            border: none;
        }

        .hero-action-btn-primary:hover {
            background: rgba(255, 255, 255, 0.9);
            color: #5c50c9;
        }

        .individual-banner {
            background: linear-gradient(135deg, #ff9f43 0%, #e67e22 100%);
        }

        .individual-banner .hero-action-btn-primary {
            color: #ff9f43;
        }

        .individual-banner .hero-action-btn-primary:hover {
            color: #e67e22;
        }

        .individual-banner .hero-action-btn-primary i[data-feather],
        .individual-banner .hero-action-btn-primary [data-feather],
        .individual-banner .hero-action-btn-primary svg {
            color: #ff9f43 !important;
            stroke: #ff9f43 !important;
        }

        .group-banner {
            background: linear-gradient(135deg, #28c76f 0%, #1a8a47 100%);
        }

        .group-banner .hero-action-btn-primary {
            color: #28c76f;
        }

        .group-banner .hero-action-btn-primary:hover {
            color: #1a8a47;
        }

        /* Ensure all icons in hero section are white */
        .classroom-hero-banner i[data-feather],
        .classroom-hero-banner [data-feather],
        .classroom-hero-banner svg {
            color: #fff !important;
            stroke: #fff !important;
        }

        .classroom-hero-banner .hero-action-btn-primary i[data-feather],
        .classroom-hero-banner .hero-action-btn-primary [data-feather],
        .classroom-hero-banner .hero-action-btn-primary svg {
            color: #7367f0 !important;
            stroke: #7367f0 !important;
        }

        .group-banner .hero-action-btn-primary i[data-feather],
        .group-banner .hero-action-btn-primary [data-feather],
        .group-banner .hero-action-btn-primary svg {
            color: #28c76f !important;
            stroke: #28c76f !important;
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

                        <h2 class="content-header-title float-left mb-0">@lang('classrooms.classroom_details')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.classrooms.index') }}" wire:navigate>@lang('classrooms.classrooms')</a></li>
                                <li class="breadcrumb-item active">{{ $classroom->name }}</li>
                            </ol>

                        </div><!-- end of breadcrumb -->
                    </div>
                </div><!-- end of row -->

            </div><!-- end of content header -->

        </div><!-- end of content header -->

        <div class="content-body">

            <div class="btn-group d-flex" role="group" aria-label="Basic example">

                <button type="button" class="btn btn-primary waves-effect waves-float waves-light ajax-data active" data-url="{{ route('organization.classrooms.details', $classroom->id) }}" style="border-bottom-left-radius: 0">@lang('classrooms.details')</button>

                <button type="button" class="btn btn-primary waves-effect waves-float waves-light ajax-data" data-url="{{ route('organization.classrooms.students', $classroom->id) }}">@lang('classrooms.students_list')</button>

                <button type="button" class="btn btn-primary waves-effect waves-float waves-light ajax-data" data-url="{{ route('organization.classrooms.lessons', $classroom->id) }}">@lang('lessons.lessons')</button>

            </div>

            <div id="ajax-data-wrapper" style="padding-top: 20px">
                @include('organization.classrooms._details')
            </div>

            @if(request()->get('tab') === 'students')
                @push('scripts')
                    <script>
                        $(function () {
                            $('[data-url="{{ route('organization.classrooms.students', $classroom->id) }}"]').trigger('click');
                        });
                    </script>
                @endpush
            @endif

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
