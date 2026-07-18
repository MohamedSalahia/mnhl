@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('evaluation_items.evaluation_items')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.evaluation_models.index') }}" wire:navigate>@lang('evaluation_models.evaluation_models')</a></li>
                                <li class="breadcrumb-item">{{ $evaluationModel->name }}</li>
                                <li class="breadcrumb-item active">@lang('evaluation_items.evaluation_items')</li>
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

                                    @if (auth()->user()->hasPermission('create_evaluation_items', session('selected_branch')['id']))
                                        <a href="#" class="btn btn-primary ajax-modal"
                                           data-url="{{ route('organization.evaluation_items.create', ['evaluation_model_id' => $evaluationModel->id]) }}"
                                           data-modal-title="@lang('site.create') @lang('evaluation_items.evaluation_item')"
                                        >
                                            <i data-feather="plus"></i> @lang('site.create')
                                        </a>
                                    @endif

                                    @if (auth()->user()->hasPermission('update_evaluation_items', session('selected_branch')['id']))
                                        <a href="#" class="btn btn-primary ajax-modal"
                                           data-url="{{ route('organization.evaluation_items.reorder', ['evaluation_model_id' => $evaluationModel->id]) }}"
                                           data-modal-title="@lang('site.reorder')"
                                        >
                                            <i data-feather="sliders"></i> @lang('site.reorder')
                                        </a>
                                    @endif


                                    {{--                                    @if (auth()->user()->hasPermission('delete_evaluation_items', session('selected_branch')['id']))--}}
                                    {{--                                        <form method="post" action="{{ route('organization.evaluation_items.bulk_delete') }}" style="display: inline-block;" class="ajax-form">--}}
                                    {{--                                            @csrf--}}
                                    {{--                                            @method('delete')--}}
                                    {{--                                            <input type="hidden" name="record_ids" class="record-ids">--}}
                                    {{--                                            <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i data-feather="trash-2"></i> @lang('site.bulk_delete')</button>--}}
                                    {{--                                        </form><!-- end of form -->--}}
                                    {{--                                    @endif--}}

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

                                        <table class="table datatable" id="evaluation-items-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('evaluation_items.name')</th>
                                                <th>@lang('evaluation_models.evaluation_model')</th>
                                                <th>@lang('evaluation_items.color_preview')</th>
                                                <th>@lang('evaluation_items.pass')</th>
                                                <th>@lang('site.order')</th>
                                                <th>@lang('site.created_at')</th>
                                                <th style="width: 20%;">@lang('site.action')</th>
                                            </tr>
                                            </thead>
                                        </table>

                                    </div><!-- end of table responsive -->

                                    <div class="form-group mt-1">
                                        <a href="{{ route('organization.evaluation_models.index') }}" wire:navigate class="btn btn-primary"><i data-feather="arrow-right"></i> @lang('site.next')</a>
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

            let evaluationModelId = "{{ $evaluationModel->id }}";

            let evaluationItemsTable = $('#evaluation-items-table').DataTable({
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
                    url: '{{ route('organization.evaluation_items.data') }}',
                    data: function (d) {
                        d.evaluation_model_id = evaluationModelId || null;
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'evaluation_model', name: 'evaluation_model', searchable: false, sortable: false},
                    {data: 'color_preview', name: 'color_preview', searchable: false, sortable: false},
                    {data: 'pass', name: 'pass', searchable: false, sortable: false},
                    {data: 'order', name: 'order', searchable: false, visible: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[4, 'asc']],
                drawCallback: function (settings) {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#record-ids').val('');
                    $('#bulk-delete').attr('disabled', true);
                }
            });

            $('#evaluation-model-id').on('change', function () {
                evaluationModelId = this.value || null;
                evaluationItemsTable.ajax.reload();
            });

            $('#data-table-search').keyup(function () {
                evaluationItemsTable.search(this.value).draw();
            })

        });//end of document ready
    </script>

@endpush

