@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">{{ __('organizations.organization_details') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.organizations.index') }}" wire:navigate>@lang('organizations.organizations')</a></li>
                                <li class="breadcrumb-item active">{{ $organization->name }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-header-right col-md-3 col-12 mb-2">
                <div class="btn-group float-md-right" role="group">


                    @if (auth()->user()->hasPermission('update_organizations'))
                        <a href="{{ route('admin.organizations.edit', $organization) }}" wire:navigate class="btn btn-primary">
                            <i data-feather="edit"></i> {{ __('organizations.edit_organization') }}
                        </a>
                    @endif

                </div>
            </div>
        </div><!-- end of content header -->

        <div class="content-body">

            <!-- Organization Overview Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">{{ __('organizations.overview') }}</h4>

                            <form action="{{ route('admin.organizations.impersonate', $organization->hash_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">
                                    <i data-feather="user-check"></i> {{ __('organizations.impersonate_super_admin') }}
                                </button>
                            </form>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    @if($organization->logo)
                                        <img src="{{ $organization->logo_path }}" alt="{{ $organization->name }}"
                                             class="img-fluid rounded" style="max-width: 120px; max-height: 120px; object-fit: cover; border-radius: 10px;"
                                        >
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 120px; height: 120px;">
                                            <i data-feather="image" style="width: 48px; height: 48px;"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-10">
                                    <h3 class="mb-1">{{ $organization->name }}</h3>
                                    <p class="text-muted mb-2">
                                        <i data-feather="calendar" style="width: 14px; height: 14px;"></i>
                                        {{ __('organizations.created_at') }}: {{ $organization->created_at->format('Y-m-d H:i') }}
                                    </p>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-light-primary rounded me-3">
                                                    <div class="avatar-content">
                                                        <i data-feather="home" style="width: 16px; height: 16px;"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-bold-500">{{ $organization->branches_count ?? 0 }}</span>
                                                    <span class="text-muted">{{ __('organizations.branches_count') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-light-success rounded me-3">
                                                    <div class="avatar-content">
                                                        <i data-feather="users" style="width: 16px; height: 16px;"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-bold-500">{{ $organization->students_count ?? 0 }}</span>
                                                    <span class="text-muted">{{ __('organizations.students_count') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-light-primary rounded me-3">
                                                    <div class="avatar-content">
                                                        <i data-feather="user" style="width: 16px; height: 16px;"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-bold-500">{{ $organization->teachers_count ?? 0 }}</span>
                                                    <span class="text-muted">{{ __('organizations.teachers_count') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-light-primary rounded me-3">
                                                    <div class="avatar-content">
                                                        <i data-feather="award" style="width: 16px; height: 16px;"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-bold-500">{{ $organization->examiners_count ?? 0 }}</span>
                                                    <span class="text-muted">{{ __('organizations.examiners_count') }}</span>
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

            <!-- Detailed Information Cards -->
            <div class="row">
                <!-- General Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i data-feather="info" style="width: 16px; height: 16px;"></i>
                                {{ __('organizations.general_information') }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-bold-500" style="width: 40%;">{{ __('organizations.name') }}</td>
                                    <td>{{ $organization->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-bold-500">{{ __('organizations.created_at') }}</td>
                                    <td>{{ $organization->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-bold-500">{{ __('organizations.updated_at') }}</td>
                                    <td>{{ $organization->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i data-feather="map-pin" style="width: 16px; height: 16px;"></i>
                                {{ __('organizations.location_information') }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-bold-500" style="width: 40%;">{{ __('countries.country') }}</td>
                                    <td>{{ $organization->country?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-bold-500">{{ __('governorates.governorate') }}</td>
                                    <td>{{ $organization->governorate?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-bold-500">{{ __('areas.area') }}</td>
                                    <td>{{ $organization->area?->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Branches Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i data-feather="git-branch" style="width: 16px; height: 16px;"></i>
                                {{ __('organizations.branches') }} ({{ $organization->branches->count() }})
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($organization->branches->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('organizations.branch_name') }}</th>
                                            <th>{{ __('countries.country') }}</th>
                                            <th>{{ __('governorates.governorate') }}</th>
                                            <th>{{ __('areas.area') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($organization->branches as $branch)
                                            <tr>
                                                <td>{{ $branch->name }}</td>
                                                <td>{{ $branch->country?->name ?? '-' }}</td>
                                                <td>{{ $branch->governorate?->name ?? '-' }}</td>
                                                <td>{{ $branch->area?->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i data-feather="git-branch" style="width: 48px; height: 48px;"></i>
                                    <p class="mt-2 text-muted">{{ __('organizations.no_branches_found') }}</p>
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
        $(function () {
            // Initialize feather icons
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
    </script>
@endpush
