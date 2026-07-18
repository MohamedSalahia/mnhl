@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('whatsapp_templates.whatsapp_templates')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('whatsapp_templates.whatsapp_templates')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <a href="{{ route('admin.whatsapp_templates.create') }}" wire:navigate class="btn btn-primary">
                                        <i data-feather="plus"></i> @lang('site.create')
                                    </a>
                                    <form method="post" action="{{ route('admin.whatsapp_templates.bulk_delete') }}" style="display: inline-block;">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="record_ids" class="record-ids">
                                        <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true">
                                            <i data-feather="trash-2"></i> @lang('site.bulk_delete')
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" id="data-table-search" class="form-control" placeholder="@lang('site.search')">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table datatable" id="whatsapp-templates-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>@lang('whatsapp_templates.title')</th>
                                                <th>@lang('whatsapp_templates.type')</th>
                                                <th>@lang('whatsapp_templates.status')</th>
                                                <th>@lang('site.created_at')</th>
                                                <th style="width: 15%;">@lang('site.action')</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            let table = $('#whatsapp-templates-table').DataTable({
                dom: "tiplr",
                serverSide: true,
                processing: true,
                "language": {
                    "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('admin.whatsapp_templates.data') }}',
                },
                columns: [
                    {data: 'record_select', name: 'record_select', searchable: false, sortable: false},
                    {data: 'title', name: 'title'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'is_active', searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[4, 'desc']],
                drawCallback: function () {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#bulk-delete').attr('disabled', true);
                }
            });

            $('#data-table-search').keyup(function () {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush
