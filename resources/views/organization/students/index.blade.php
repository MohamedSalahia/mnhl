@php use App\Enums\BranchStudentStatusEnum;use App\Enums\OrganizationStudentStatusEnum; @endphp
@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('students.students')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('students.students')</li>
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

                            @if (request()->status != OrganizationStudentStatusEnum::PENDING)

                                <div class="row mb-1">

                                    <div class="col-md-12">

                                        <a href="{{ route('organization.students.create') }}" wire:navigate class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</a>

                                        @php
                                            $registrationLink = route('students.create', ['organization_id' => session('selected_organization')['hash_id'] ?? null, 'branch_id' => session('selected_branch')['hash_id'] ?? null]);
                                        @endphp

                                        <button type="button" class="btn btn-primary" id="copy-registration-link" data-link="{{ $registrationLink }}">
                                            <i data-feather="copy"></i> @lang('site.copy_registration_link')
                                        </button>

                                    </div>

                                </div><!-- end of row -->

                            @endif

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" id="data-table-search" class="form-control" autofocus placeholder="@lang('site.search')">
                                    </div>
                                </div>

                                @if (request()->status != OrganizationStudentStatusEnum::PENDING)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select id="branch-status" class="form-control select2">
                                                <option value="0">@lang('site.all') @lang('students.branch_enrollment_statuses')</option>
                                                @foreach (BranchStudentStatusEnum::getConstants() as $branchStatus)
                                                    <option value="{{ $branchStatus }}" @selected(request()->get('branch_status', BranchStudentStatusEnum::ACTIVE) == $branchStatus)>{{ __('students.' . $branchStatus) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="students-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('students.student_number')</th>
                                                <th>@lang('users.name')</th>
                                                <th>@lang('users.email')</th>
                                                <th>@lang('users.mobile')</th>
                                                <th>@lang('users.gender')</th>
                                                <th>@lang('site.created_at')</th>
                                                <th style="width: 20%;">@lang('site.action')</th>
                                            </tr>
                                            </thead>
                                        </table>

                                    </div><!-- end of table responsive -->

                                </div><!-- end of col -->

                            </div><!-- end of row -->

                        </div><!-- end of card body -->

                    </div><!-- end of tile -->

                </div><!-- end of col -->

            </div><!-- end of row -->

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection

@push('scripts')

    <script>

        $(function () {

            let status = @json(request()->input('status', OrganizationStudentStatusEnum::ACTIVE));

            let branchStatus = @json(request()->status == OrganizationStudentStatusEnum::PENDING ? '0' : request()->input('branch_status', BranchStudentStatusEnum::ACTIVE));

            let studentsTable = $('#students-table').DataTable({
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
                    url: '{{ route('organization.students.data') }}',
                    data: function (d) {
                        d.status = status;
                        d.branch_status = branchStatus;
                    }
                },
                columns: [
                    {data: 'student_number', name: 'student_number'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {
                        data: 'mobile',
                        name: 'mobile',
                        render: function (data, type, row) {
                            return data ? '<span class="mobile">' + data + '</span>' : '';
                        }
                    },
                    {data: 'gender', name: 'gender'},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[5, 'desc']],
                drawCallback: function (settings) {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#record-ids').val();
                    $('#bulk-delete').attr('disabled', true);
                }
            });

            $('#branch-status').on('change', function () {
                branchStatus = $(this).val();
                studentsTable.ajax.reload();
            });

            // Copy registration link
            $('#copy-registration-link').on('click', function () {

                const link = $(this).data('link');

                const tempInput = $('<input>');

                $('body').append(tempInput);

                tempInput.val(link).select();
                document.execCommand('copy');
                tempInput.remove();

                // Show success notification using Noty
                new Noty({
                    layout: 'topRight',
                    text: '@lang("site.copied")',
                    timeout: 2000,
                    killer: true
                }).show();
            });

            $('#data-table-search').keyup(function () {
                studentsTable.search(this.value).draw();
            });

        });//end of document ready
    </script>

@endpush
