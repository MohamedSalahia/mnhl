@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('countries.countries')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('countries.countries')</li>
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

                                    @if (auth()->user()->hasPermission('create_countries'))
                                        <a href="{{ route('admin.countries.create') }}" wire:navigate class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</a>
                                    @endif

                                    @if (auth()->user()->hasPermission('delete_countries'))
                                        <form method="post" action="{{ route('admin.countries.bulk_delete') }}" style="display: inline-block;">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="record_ids" class="record-ids">
                                            <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i data-feather="trash-2"></i> @lang('site.bulk_delete')</button>
                                        </form><!-- end of form -->
                                    @endif

                                </div>

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" id="data-table-search" class="form-control" autofocus placeholder="@lang('site.search')">
                                    </div>
                                </div>

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="countries-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('countries.country')</th>
                                                <th>@lang('countries.governorates_count')</th>
                                                <th>@lang('countries.areas_count')</th>
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

            let countriesTable = $('#countries-table').DataTable({
                dom: "tiplr",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                ],
                serverSide: true,
                processing: true,
                language: {
                    "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('admin.countries.data') }}',
                },
                columns: [
                    {data: 'name', name: 'translations.name', sortable: false},
                    {data: 'governorates_count', name: 'governorates_count', searchable: false},
                    {data: 'areas_count', name: 'areas_count', searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[3, 'desc']],
                drawCallback: function (settings) {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#record-ids').val();
                    $('#bulk-delete').attr('disabled', true);
                }
            });

            $('#data-table-search').keyup(function () {
                countriesTable.search(this.value).draw();
            })

        });//end of document ready
    </script>

@endpush