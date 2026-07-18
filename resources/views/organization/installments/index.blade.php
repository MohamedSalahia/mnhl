@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('installments.installments')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('installments.installments')</li>
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

                                        <table class="table datatable" id="installments-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('students.student')</th>
                                                <th>@lang('projects.project')</th>
                                                <th>@lang('payment_methods.payment_method')</th>
                                                <th>@lang('installments.amount')</th>
                                                <th>@lang('site.created_at')</th>
                                                <th>@lang('site.action')</th>
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

            let installmentsTable = $('#installments-table').DataTable({
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
                    url: "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('organization.installments.data') }}',
                },
                columns: [
                    {data: 'student_name', name: 'student_name', sortable: false},
                    {data: 'project_name', name: 'project_name', sortable: false},
                    {data: 'payment_method_name', name: 'payment_method_name', sortable: false},
                    {data: 'amount', name: 'amount', searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[4, 'desc']],
                drawCallback: function (settings) {
                    if (feather) {
                        feather.replace({
                            width: 14,
                            height: 14
                        });
                    }
                }
            });

            $('#data-table-search').keyup(function () {
                installmentsTable.search(this.value).draw();
            })

        });//end of document ready
    </script>

@endpush
