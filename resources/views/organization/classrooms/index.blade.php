@php use App\Enums\ClassroomTypeEnum; @endphp
@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('classrooms.classrooms')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('classrooms.classrooms')</li>
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

                                    @if (auth()->user()->hasPermission('create_classrooms', session('selected_branch')['id']))
                                        <a href="{{ route('organization.classrooms.create') }}" wire:navigate class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</a>
                                    @endif

                                    @if (auth()->user()->hasPermission('delete_classrooms', session('selected_branch')['id']))
                                        <form method="post" action="{{ route('organization.classrooms.bulk_delete') }}" style="display: inline-block;" class="ajax-form">
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="type" class="form-control select2">
                                            <option value="0">@lang('site.all') @lang('classrooms.types')</option>
                                            @foreach (ClassroomTypeEnum::getConstants() as $type)
                                                <option value="{{ $type }}">{{ __('classrooms.' . strtolower($type)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="classrooms-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('classrooms.name')</th>
                                                <th>@lang('classrooms.teacher')</th>
                                                <th>@lang('classrooms.type')</th>
                                                <th>@lang('classrooms.start_date')</th>
                                                <th>@lang('classrooms.end_date')</th>
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

            let type;

            let classroomsTable = $('#classrooms-table').DataTable({
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
                "language": {
                    "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('organization.classrooms.data') }}',
                    data: function (d) {
                        d.type = type
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'teacher_id', name: 'teacher_id', searchable: false},
                    {data: 'type', name: 'type', searchable: false},
                    {data: 'start_date', name: 'start_date', searchable: false},
                    {data: 'end_date', name: 'end_date', searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[5, 'desc']],
                drawCallback: function (settings) {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#record-ids').val('');
                    $('#bulk-delete').attr('disabled', true);
                }
            });

            $('#type').change(function () {
                type = $(this).val();
                classroomsTable.ajax.reload();
            });

            $('#data-table-search').keyup(function () {
                classroomsTable.search(this.value).draw();
            });

        });//end of document ready
    </script>

@endpush
