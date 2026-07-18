@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('branches.branches')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('branches.branches')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of content header -->

        <div class="content-body">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" id="data-table-search" class="form-control" autofocus placeholder="@lang('site.search')">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="organization-id" class="form-control select2">
                                            <option value="">@lang('site.all') @lang('organizations.organizations')</option>
                                            @foreach ($organizations as $organization)
                                                <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="country-id" class="form-control select2"
                                                data-governorates-base-url="{{ route('admin.countries.governorates', 0) }}">
                                            <option value="">@lang('site.all') @lang('countries.countries')</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="governorate-id" class="form-control select2" disabled
                                                data-areas-base-url="{{ route('admin.governorates.areas', 0) }}">
                                            <option value="">@lang('site.all') @lang('governorates.governorates')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="area-id" class="form-control select2" disabled>
                                            <option value="">@lang('site.all') @lang('areas.areas')</option>
                                        </select>
                                    </div>
                                </div>

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="branches-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('branches.name')</th>
                                                <th>@lang('organizations.organization')</th>
                                                <th>@lang('countries.country')</th>
                                                <th>@lang('governorates.governorate')</th>
                                                <th>@lang('areas.area')</th>
                                                <th>@lang('site.created_at')</th>
                                            </tr>
                                            </thead>
                                        </table>

                                    </div><!-- end of table responsive -->

                                </div><!-- end of col -->

                            </div><!-- end of row -->

                        </div><!-- end of card body -->

                    </div><!-- end of card -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection

@push('scripts')

    <script>

        $(function () {

            let organizationId;
            let countryId;
            let governorateId;
            let areaId;

            let branchesTable = $('#branches-table').DataTable({
                dom: "tiplr",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                ],
                serverSide: true,
                processing: true,
                language: {
                    "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('admin.branches.data') }}',
                    data: function (d) {
                        d.organization_id = organizationId || null;
                        d.country_id = countryId || null;
                        d.governorate_id = governorateId || null;
                        d.area_id = areaId || null;
                    }
                },
                columns: [
                    {data: 'name', name: 'translations.name', sortable: false},
                    {data: 'organization', name: 'organization', sortable: false},
                    {data: 'country', name: 'country', searchable: false, sortable: false},
                    {data: 'governorate', name: 'governorate', searchable: false, sortable: false},
                    {data: 'area', name: 'area', searchable: false, sortable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                ],
                order: [[5, 'desc']],
            });

            $('#organization-id').on('change', function () {
                organizationId = this.value || null;
                branchesTable.ajax.reload();
            });

            $('#country-id').on('change', function () {
                countryId = this.value || null;
                // Reset governorate and area filters when country changes
                governorateId = null;
                areaId = null;
                branchesTable.ajax.reload();
            });

            $('#governorate-id').on('change', function () {
                governorateId = this.value || null;
                // Reset area filter when governorate changes
                areaId = null;
                branchesTable.ajax.reload();
            });

            $('#area-id').on('change', function () {
                areaId = this.value || null;
                branchesTable.ajax.reload();
            });

            $('#data-table-search').keyup(function () {
                branchesTable.search(this.value).draw();
            });

        });//end of document ready
    </script>

    <script src="{{ asset('admin_assets/custom/js/countries.js') }}"></script>

@endpush

