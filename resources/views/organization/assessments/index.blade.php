@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">
                            @if($status == 'pending')
                                @lang('assessments.assessment_requests')
                            @elseif($status == 'in_progress')
                                @lang('assessments.in_progress_assessments')
                            @elseif($status == 'completed')
                                @lang('assessments.completed_assessments')
                            @else
                                @lang('assessments.assessments')
                            @endif
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">
                                    @if($status == 'pending')
                                        @lang('assessments.assessment_requests')
                                    @elseif($status == 'in_progress')
                                        @lang('assessments.in_progress_assessments')
                                    @elseif($status == 'completed')
                                        @lang('assessments.completed_assessments')
                                    @else
                                        @lang('assessments.assessments')
                                    @endif
                                </li>
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" id="data-table-search" class="form-control" autofocus placeholder="@lang('site.search')">
                                    </div>
                                </div>

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="assessments-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('assessments.student')</th>
                                                <th>@lang('assessments.examiner')</th>
                                                <th>@lang('assessments.assessment_scheme')</th>
                                                <th>@lang('assessments.status')</th>
                                                <th>@lang('assessments.notes')</th>
                                                <th>@lang('assessments.created_at')</th>
                                                <th style="width: 10%;">@lang('site.action')</th>
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

            let assessmentsTable = $('#assessments-table').DataTable({
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
                    url: "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('organization.assessments.data') }}',
                    data: function (d) {
                        d.status = '{{ $status }}';
                    }
                },
                columns: [
                    {data: 'student_id', name: 'student_id', searchable: false},
                    {data: 'examiner_id', name: 'examiner_id', searchable: false},
                    {data: 'assessment_scheme_id', name: 'assessment_scheme_id', searchable: false},
                    {data: 'status', name: 'status', searchable: false},
                    {data: 'notes', name: 'notes', searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[4, 'desc']],
                drawCallback: function () {
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }
            });

            $('#data-table-search').keyup(function () {
                assessmentsTable.search(this.value).draw();
            });

        });//end of document ready
    </script>

@endpush
