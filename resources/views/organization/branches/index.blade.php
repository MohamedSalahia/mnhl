@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('branches.branches')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
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

                            <div class="row mb-1">

                                <div class="col-md-12">

                                    @if (auth()->user()->hasPermission('create_branches', session('selected_branch')['id']))
                                        <a href="{{ route('organization.branches.create') }}" wire:navigate class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</a>
                                    @endif

                                    @if (auth()->user()->hasPermission('delete_branches', session('selected_branch')['id']))
                                        <form method="post" action="{{ route('organization.branches.bulk_delete') }}" style="display: inline-block;" class="ajax-form">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="record_ids" class="record-ids">
                                            <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i data-feather="trash-2"></i> @lang('site.bulk_delete')</button>
                                        </form><!-- end of form -->
                                    @endif

                                </div>

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" id="data-table-search" class="form-control" autofocus placeholder="@lang('site.search')">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="country-id" class="form-control select2"
                                                data-governorates-base-url="{{ route('organization.countries.governorates', 0) }}">
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
                                                data-areas-base-url="{{ route('organization.governorates.areas', 0) }}">
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
                                                <th>@lang('countries.country')</th>
                                                <th>@lang('governorates.governorate')</th>
                                                <th>@lang('areas.area')</th>
                                                <th>@lang('site.created_at')</th>
                                                <th style="width: 20%;">@lang('site.action')</th>
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

            let countryId;
            let governorateId;
            let areaId;

            let branchesTable = $('#branches-table').DataTable({
                dom: "tiplr",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                ],
                serverSide: true,
                processing: true,
                language: {
                    "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('organization.branches.data') }}',
                    data: function (d) {
                        d.country_id = countryId || null;
                        d.governorate_id = governorateId || null;
                        d.area_id = areaId || null;
                    }
                },
                columns: [
                    {data: 'name', name: 'translations.name', sortable: false},
                    {data: 'country', name: 'country', searchable: false, sortable: false},
                    {data: 'governorate', name: 'governorate', searchable: false, sortable: false},
                    {data: 'area', name: 'area', searchable: false, sortable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[4, 'desc']],
                drawCallback: function (settings) {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#record-ids').val('');
                    $('#bulk-delete').attr('disabled', true);
                }
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

