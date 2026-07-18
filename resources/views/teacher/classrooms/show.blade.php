@php
    use App\Enums\ClassroomTypeEnum;
    use App\Helpers\PhoneHelper;
@endphp
@extends('layouts.teacher.app')

@section('content')

    <style>
        .classroom-hero {
            background: linear-gradient(135deg, #28C76F 0%, #20b85f 100%);
            border-radius: 12px;
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(40, 199, 111, 0.3);
        }

        .classroom-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }

        .classroom-hero::after {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            50% {
                transform: translate(20px, 20px) rotate(180deg);
            }
        }

        .classroom-hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-decoration {
            position: absolute;
            pointer-events: none;
            z-index: 0;
        }

        .hero-decoration-circle {
            width: 100px;
            height: 100px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            position: absolute;
        }

        .hero-decoration-circle-1 {
            top: 10%;
            right: 5%;
            animation: rotate 20s linear infinite;
        }

        .hero-decoration-circle-2 {
            bottom: 15%;
            left: 8%;
            width: 80px;
            height: 80px;
            animation: rotate 15s linear infinite reverse;
        }

        .hero-decoration-triangle {
            width: 0;
            height: 0;
            border-left: 30px solid transparent;
            border-right: 30px solid transparent;
            border-bottom: 50px solid rgba(255, 255, 255, 0.1);
            position: absolute;
            animation: floatTriangle 8s ease-in-out infinite;
        }

        .hero-decoration-triangle-1 {
            top: 20%;
            right: 15%;
            transform: rotate(45deg);
        }

        .hero-decoration-triangle-2 {
            bottom: 20%;
            right: 10%;
            transform: rotate(-45deg);
            animation-delay: -2s;
        }

        .hero-decoration-square {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            position: absolute;
            animation: floatSquare 10s ease-in-out infinite;
        }

        .hero-decoration-square-1 {
            top: 30%;
            left: 5%;
        }

        .hero-decoration-square-2 {
            bottom: 25%;
            left: 12%;
            width: 30px;
            height: 30px;
            animation-delay: -3s;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes floatTriangle {
            0%, 100% {
                transform: translate(0, 0) rotate(45deg);
            }
            50% {
                transform: translate(15px, -15px) rotate(45deg);
            }
        }

        @keyframes floatSquare {
            0%, 100% {
                transform: translate(0, 0) rotate(45deg);
            }
            50% {
                transform: translate(-10px, 10px) rotate(45deg);
            }
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, auto);
            gap: 1rem;
        }

        .hero-stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hero-stat-card .stat-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-stat-card .stat-icon i,
        .hero-stat-card .stat-icon svg {
            width: 20px;
            height: 20px;
            color: white !important;
            stroke: white !important;
        }

        .hero-stat-card .stat-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }

        .hero-stat-card .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
            line-height: 1.2;
            word-break: break-word;
        }

        .hero-stat-card .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .students-table-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
        }

        .students-table-card .card-header {
            /*background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);*/
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 1.25rem 1.5rem;
        }

        .students-table-card .card-title {
            color: #2d3748;
            font-weight: 600;
            margin: 0;
        }

        .students-table-card .table {
            margin-bottom: 0;
        }

        .students-table-card .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .students-table-card .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .students-table-card .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        @media (max-width: 768px) {
            .classroom-hero {
                padding: 2rem 1.5rem;
            }

            .hero-stats {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(3, auto);
                gap: 0.75rem;
            }

            .hero-stat-card {
                padding: 0.75rem 0.875rem;
                gap: 0.875rem;
            }

            .hero-stat-card .stat-icon {
                width: 36px;
                height: 36px;
            }

            .hero-stat-card .stat-icon i,
            .hero-stat-card .stat-icon svg {
                width: 18px;
                height: 18px;
            }

            .hero-stat-card .stat-value {
                font-size: 1.1rem;
            }

            .hero-stat-card .stat-label {
                font-size: 0.85rem;
            }

            /* Reduce decorative shapes on mobile */
            .hero-decoration-circle,
            .hero-decoration-triangle,
            .hero-decoration-square {
                opacity: 0.5;
            }

            .hero-decoration-circle-1 {
                width: 60px;
                height: 60px;
            }

            .hero-decoration-circle-2 {
                width: 50px;
                height: 50px;
            }
        }

        @media (max-width: 480px) {
            .hero-stats {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
            }

            /* Hide some decorative shapes on very small screens */
            .hero-decoration-triangle-2,
            .hero-decoration-square-2 {
                display: none;
            }
        }
    </style>
    
    <div class="content-wrapper">

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">{{ $classroom->name }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('teacher.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('teacher.classrooms.index') }}" wire:navigate>@lang('classrooms.classrooms')</a></li>
                                <li class="breadcrumb-item active">{{ $classroom->name }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of content header -->

        <div class="content-body">

            <!-- Hero Section -->
            <div class="classroom-hero">
                <!-- Decorative Shapes -->
                <div class="hero-decoration hero-decoration-circle hero-decoration-circle-1"></div>
                <div class="hero-decoration hero-decoration-circle hero-decoration-circle-2"></div>
                <div class="hero-decoration hero-decoration-triangle hero-decoration-triangle-1"></div>
                <div class="hero-decoration hero-decoration-triangle hero-decoration-triangle-2"></div>
                <div class="hero-decoration hero-decoration-square hero-decoration-square-1"></div>
                <div class="hero-decoration hero-decoration-square hero-decoration-square-2"></div>

                <div class="classroom-hero-content">
                    <div class="hero-stats">
                        <div class="hero-stat-card">
                            <div class="stat-icon">
                                <i data-feather="users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $classroom->students()->count() }}</div>
                                <div class="stat-label">@lang('classrooms.number_of_students')</div>
                            </div>
                        </div>

                        <div class="hero-stat-card">
                            <div class="stat-icon">
                                <i data-feather="user"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $classroom->teacher->name ?? 'N/A' }}</div>
                                <div class="stat-label">@lang('classrooms.teacher')</div>
                            </div>
                        </div>

                        <div class="hero-stat-card">
                            <div class="stat-icon">
                                <i data-feather="layers"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">
                                    @if($classroom->type == ClassroomTypeEnum::INDIVIDUAL)
                                        @lang('classrooms.individual')
                                    @else
                                        @lang('classrooms.group')
                                    @endif
                                </div>
                                <div class="stat-label">@lang('classrooms.type')</div>
                            </div>
                        </div>

                        <div class="hero-stat-card">
                            <div class="stat-icon">
                                <i data-feather="play-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $classroom->start_date->format('Y-m-d') }}</div>
                                <div class="stat-label">@lang('classrooms.start_date')</div>
                            </div>
                        </div>

                        <div class="hero-stat-card">
                            <div class="stat-icon">
                                <i data-feather="stop-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $classroom->end_date->format('Y-m-d') }}</div>
                                <div class="stat-label">@lang('classrooms.end_date')</div>
                            </div>
                        </div>

                        <div class="hero-stat-card">
                            <div class="stat-icon">
                                <i data-feather="clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $classroom->created_at->format('Y-m-d') }}</div>
                                <div class="stat-label">@lang('site.created_at')</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table Section -->
            <div class="row">

                <div class="col-12">

                    <div class="card students-table-card">

                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">

                            <h4 class="card-title mb-0">
                                <i data-feather="users" class="mr-50"></i>
                                @lang('students.students')
                            </h4>

                            <form method="post" action="{{ route('teacher.lessons.store') }}" class="ajax-form d-inline-flex align-items-center">
                                @csrf
                                @method('post')
                                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">

                                <div class="form-group mb-0 mr-2">
                                    <input type="text" name="date" class="form-control form-control-sm date-picker" value="{{ date('Y-m-d') }}" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="plus"></i>
                                    @lang('lessons.new_lesson')
                                </button>

                            </form>

                        </div>
                        <div class="card-body">
                            @if($classroom->students->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped mt-2">
                                        <thead>
                                        <tr>
                                            <th>@lang('users.name')</th>
                                            <th>@lang('users.email')</th>
                                            <th>@lang('users.mobile')</th>
                                            <th>@lang('site.created_at')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($classroom->students as $index => $student)
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->email ?? 'N/A' }}</td>
                                                <td class="mobile">
                                                    @if($student->mobile_country_code && $student->mobile)
                                                        @php
                                                            $countryCode = PhoneHelper::getCountryCodeFromDialCode($student->mobile_country_code);
                                                        @endphp
                                                        @if($countryCode)
                                                            <i class="flag-icon flag-icon-{{ $countryCode }}"></i>
                                                        @endif
                                                        {{ $student->mobile_country_code }} {{ $student->mobile }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $student->pivot->created_at ? $student->pivot->created_at->format('Y-m-d') : 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-primary text-center p-1 mt-1">
                                    <i data-feather="info" class="mr-50"></i>
                                    @lang('site.no_data_found')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection

@push('scripts')
    <script>
        $(window).on('load', function () {
            if (feather) {
                feather.replace({
                    width: 18,
                    height: 18
                });
            }
        });
    </script>
@endpush
