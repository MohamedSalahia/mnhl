@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('assessment_scheme_deductions.assessment_scheme_deductions')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.assessment_schemes.index') }}" wire:navigate>@lang('assessment_schemes.assessment_schemes')</a></li>
                                <li class="breadcrumb-item">{{ $assessmentScheme->name }}</li>
                                <li class="breadcrumb-item active">@lang('assessment_scheme_deductions.assessment_scheme_deductions')</li>
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

                                    @if (auth()->user()->hasPermission('create_assessment_scheme_deductions', session('selected_branch')['id']))
                                        <a href="#" class="btn btn-primary ajax-modal"
                                           data-url="{{ route('organization.assessment_scheme_deductions.create', ['assessment_scheme_id' => $assessmentScheme->id]) }}"
                                           data-modal-title="@lang('site.create') @lang('assessment_scheme_deductions.assessment_scheme_deduction')"
                                        >
                                            <i data-feather="plus"></i> @lang('site.create')
                                        </a>
                                    @endif

                                    @if (auth()->user()->hasPermission('update_assessment_scheme_deductions', session('selected_branch')['id']))
                                        <a href="#" class="btn btn-primary ajax-modal"
                                           data-url="{{ route('organization.assessment_scheme_deductions.reorder', ['assessment_scheme_id' => $assessmentScheme->id]) }}"
                                           data-modal-title="@lang('site.reorder')"
                                        >
                                            <i data-feather="sliders"></i> @lang('site.reorder')
                                        </a>
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

                                        <table class="table datatable" id="deductions-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('assessment_scheme_deductions.name')</th>
                                                <th>@lang('assessment_scheme_deductions.value')</th>
                                                <th>@lang('assessment_scheme_deductions.color_preview')</th>
                                                <th>@lang('site.order')</th>
                                                <th>@lang('site.created_at')</th>
                                                <th style="width: 20%;">@lang('site.action')</th>
                                            </tr>
                                            </thead>
                                        </table>

                                    </div><!-- end of table responsive -->

                                    <div class="form-group mt-1">
                                        <a href="{{ route('organization.assessment_schemes.index') }}" wire:navigate class="btn btn-primary"><i data-feather="arrow-right"></i> @lang('site.next')</a>
                                    </div>

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

            let assessmentSchemeId = "{{ $assessmentScheme->id }}";

            let deductionsTable = $('#deductions-table').DataTable({
                dom: "tiplr",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 3, 4]
                        }
                    },
                ],
                serverSide: true,
                processing: true,
                "language": {
                    "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('organization.assessment_scheme_deductions.data') }}',
                    data: function (d) {
                        d.assessment_scheme_id = assessmentSchemeId || null;
                    }
                },
                columnDefs: [
                    {
                        targets: 3,
                        visible: false
                    }
                ],
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'value', name: 'value'},
                    {data: 'color_preview', name: 'color_preview', searchable: false, sortable: false},
                    {data: 'order', name: 'order', searchable: false, visible: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[3, 'asc']],
                drawCallback: function (settings) {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#record-ids').val('');
                    $('#bulk-delete').attr('disabled', true);
                }
            });

            $('#assessment-scheme-id').on('change', function () {
                assessmentSchemeId = this.value || null;
                deductionsTable.ajax.reload();
            });

            $('#data-table-search').keyup(function () {
                deductionsTable.search(this.value).draw();
            })

        });//end of document ready
    </script>

@endpush
