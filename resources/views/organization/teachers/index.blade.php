@php use App\Enums\OrganizationTeacherStatusEnum; @endphp
@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('teachers.teachers')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('teachers.teachers')</li>
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

                            @if (request()->status != OrganizationTeacherStatusEnum::PENDING)

                                <div class="row mb-1">

                                    <div class="col-md-12">

                                        <a href="{{ route('organization.teachers.create') }}" wire:navigate class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</a>

                                        @php
                                            $registrationLink = route('teachers.create', ['organization_id' => session('selected_organization')['hash_id'] ?? null, 'branch_id' => session('selected_branch')['hash_id'] ?? null]);
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

                                @if (request()->status != OrganizationTeacherStatusEnum::PENDING)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select id="status" class="form-control select2">
                                                <option value="0">@lang('site.all') @lang('teachers.statuses')</option>
                                                @foreach (OrganizationTeacherStatusEnum::getConstants() as $status)
                                                    <option value="{{ $status }}" @selected(request()->status == $status)>{{ __('teachers.' . strtolower($status)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="teachers-table" style="width: 100%;">
                                            <thead>
                                            <tr>
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

            let status = "{{ request()->input('status', OrganizationTeacherStatusEnum::ACTIVE) }}";

            let teachersTable = $('#teachers-table').DataTable({
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
                    url: '{{ route('organization.teachers.data') }}',
                    data: function (d) {
                        d.status = status;
                    }
                },
                columns: [
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
                order: [[4, 'desc']],
                drawCallback: function (settings) {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#record-ids').val();
                    $('#bulk-delete').attr('disabled', true);
                }
            });

            $('#status').on('change', function () {
                status = $(this).val();
                teachersTable.ajax.reload();
            });

            $('#data-table-search').keyup(function () {
                teachersTable.search(this.value).draw();
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

        });//end of document ready
    </script>

@endpush

