@php
    use App\Enums\ClassroomTypeEnum;
    
    $isIndividual = $classroom->type === ClassroomTypeEnum::INDIVIDUAL;
    $bannerClass = $isIndividual ? 'individual-banner' : 'group-banner';
    $studentsCount = $classroom->students()->count();
    $duration = $classroom->start_date->diffInDays($classroom->end_date);
@endphp

<div class="row">
    <div class="col-12">
        <div class="classroom-hero-banner {{ $bannerClass }}">
            <!-- Decorative Shapes -->
            <div class="hero-shape-1"></div>
            <div class="hero-shape-2"></div>
            <div class="hero-shape-3"></div>
            <div class="hero-shape-5"></div>

            <div class="classroom-hero-content">

                <div class="row align-items-center">

                    <!-- Left: Icon & Basic Info -->
                    <div class="col-lg-6 col-md-12 mb-lg-0 mb-3">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="classroom-icon-wrapper mr-2 mb-2 mb-sm-0">
                                @if($isIndividual)
                                    <i data-feather="user"></i>
                                @else
                                    <i data-feather="users"></i>
                                @endif
                                <div class="classroom-type-badge">
                                    @if($isIndividual)
                                        <i data-feather="user" style="width: 14px; height: 14px;"></i>
                                    @else
                                        <i data-feather="users" style="width: 14px; height: 14px;"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="text-white flex-grow-1">
                                <h3 class="mb-50 font-weight-bolder text-white">{{ $classroom->name }}</h3>
                                <div class="d-flex flex-wrap gap-1 align-items-center mb-1">
                                    @if($isIndividual)
                                        <span class="badge" style="background: rgba(115, 103, 240, 0.3); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                            <i data-feather="user" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                            @lang('classrooms.individual')
                                        </span>
                                    @else
                                        <span class="badge" style="background: rgba(40, 199, 111, 0.3); color: #fff; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                            <i data-feather="users" class="mr-25" style="width: 14px; height: 14px; color: #fff;"></i>
                                            @lang('classrooms.group')
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-1" style="opacity: 0.9;">
                                    <div class="mb-25">
                                        <i data-feather="user" class="mr-50" style="width: 14px; height: 14px; color: #fff;"></i>
                                        <span>{{ $classroom->teacher->name ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <i data-feather="home" class="mr-50" style="width: 14px; height: 14px; color: #fff;"></i>
                                        <span>{{ $classroom->branch->name ?? '-' }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-2">
                                    @if (auth()->user()->hasPermission('update_classrooms', session('selected_branch')['id']))
                                        <a href="{{ route('organization.classrooms.edit', $classroom->id) }}" class="btn hero-action-btn-primary" wire:navigate>
                                            <i data-feather="edit-2" class="mr-50" style="color: #7367f0 !important;"></i> @lang('site.edit')
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Stats -->
                    <div class="col-lg-6 col-md-12">
                        <div>
                            <div class="hero-stat-card mb-1">
                                <div class="d-flex align-items-center">
                                    <div class="hero-stat-icon mr-1">
                                        <i data-feather="calendar" style="color: #fff !important; stroke: #fff !important;"></i>
                                    </div>
                                    <div class="text-white flex-grow-1">
                                        <small style="opacity: 0.8;">@lang('classrooms.dates')</small>
                                        <div class="font-weight-bold" style="font-size: 0.95rem;">
                                            {{ $classroom->start_date->format('Y-m-d') }} - {{ $classroom->end_date->format('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hero-stat-card mb-1">
                                <div class="d-flex align-items-center">
                                    <div class="hero-stat-icon mr-1">
                                        <i data-feather="clock" style="color: #fff !important; stroke: #fff !important;"></i>
                                    </div>
                                    <div class="text-white flex-grow-1">
                                        <small style="opacity: 0.8;">@lang('classrooms.duration')</small>
                                        <div class="font-weight-bold" style="font-size: 0.95rem;">
                                            {{ $duration }} @lang('classrooms.days')
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hero-stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="hero-stat-icon mr-1">
                                        <i data-feather="users" style="color: #fff !important; stroke: #fff !important;"></i>
                                    </div>
                                    <div class="text-white flex-grow-1">
                                        <small style="opacity: 0.8;">@lang('classrooms.number_of_students')</small>
                                        <div class="font-weight-bold" style="font-size: 0.95rem;">
                                            {{ $studentsCount }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <i data-feather="book-open" class="font-medium-3"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">@lang('classrooms.name')</small>
                    <span class="font-weight-bold">{{ $classroom->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-12">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar bg-light-info mr-1">
                    <div class="avatar-content">
                        <i data-feather="user" class="font-medium-3"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">@lang('classrooms.teacher')</small>
                    <span class="font-weight-bold">{{ $classroom->teacher->name ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-12">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar bg-light-warning mr-1">
                    <div class="avatar-content">
                        <i data-feather="home" class="font-medium-3"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">@lang('classrooms.branch')</small>
                    <span class="font-weight-bold">{{ $classroom->branch->name ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-12">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar bg-light-success mr-1">
                    <div class="avatar-content">
                        <i data-feather="layers" class="font-medium-3"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">@lang('classrooms.type')</small>
                    <span class="font-weight-bold">
                        @if($isIndividual)
                            @lang('classrooms.individual')
                        @else
                            @lang('classrooms.group')
                        @endif
                    </span>
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
                    <i data-feather="info" class="mr-50"></i>
                    @lang('classrooms.information')
                </h4>
            </div>
            <div class="card-body pt-2">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td class="text-muted" style="width: 40%;">
                            <i data-feather="book-open" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('classrooms.name')
                        </td>
                        <td class="font-weight-bold">{{ $classroom->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="user" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('classrooms.teacher')
                        </td>
                        <td class="font-weight-bold">{{ $classroom->teacher->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="home" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('classrooms.branch')
                        </td>
                        <td class="font-weight-bold">{{ $classroom->branch->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="layers" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('classrooms.type')
                        </td>
                        <td>
                            @if($isIndividual)
                                <span class="badge badge-light-primary">
                                    <i data-feather="user" class="mr-25" style="width: 12px; height: 12px;"></i>
                                    @lang('classrooms.individual')
                                </span>
                            @else
                                <span class="badge badge-light-success">
                                    <i data-feather="users" class="mr-25" style="width: 12px; height: 12px;"></i>
                                    @lang('classrooms.group')
                                </span>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Dates Card -->
    <div class="col-lg-6 col-12">
        <div class="card h-100">
            <div class="card-header border-bottom">
                <h4 class="card-title mb-0">
                    <i data-feather="calendar" class="mr-50"></i>
                    @lang('classrooms.dates')
                </h4>
            </div>
            <div class="card-body pt-2">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td class="text-muted" style="width: 40%;">
                            <i data-feather="calendar" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('classrooms.start_date')
                        </td>
                        <td class="font-weight-bold">{{ $classroom->start_date->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="calendar" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('classrooms.end_date')
                        </td>
                        <td class="font-weight-bold">{{ $classroom->end_date->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="clock" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('classrooms.duration')
                        </td>
                        <td class="font-weight-bold">{{ $duration }} @lang('classrooms.days')</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="users" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('classrooms.number_of_students')
                        </td>
                        <td class="font-weight-bold">{{ $studentsCount }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i data-feather="calendar" class="mr-50" style="width: 14px; height: 14px;"></i>
                            @lang('site.created_at')
                        </td>
                        <td class="font-weight-bold">{{ $classroom->created_at->format('Y-m-d') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
