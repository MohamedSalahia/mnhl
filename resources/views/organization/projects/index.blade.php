@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('projects.projects')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('projects.projects')</li>
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

                                    @if (auth()->user()->hasPermission('create_projects', session('selected_branch')['id']))
                                        <a href="{{ route('organization.projects.basic_information.create') }}" wire:navigate class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</a>
                                    @endif

                                    @if (auth()->user()->hasPermission('update_projects', session('selected_branch')['id']))
                                        <a href="#" class="btn btn-primary ajax-modal"
                                           data-url="{{ route('organization.projects.reorder') }}"
                                           data-modal-title="@lang('site.reorder')"
                                        >
                                            <i data-feather="sliders"></i> @lang('site.reorder')
                                        </a>
                                    @endif

                                    {{--                                    @if (auth()->user()->hasPermission('delete_projects', session('selected_branch')['id']))--}}
                                    {{--                                        <form method="post" action="{{ route('organization.projects.bulk_delete') }}" style="display: inline-block;" class="ajax-form">--}}
                                    {{--                                            @csrf--}}
                                    {{--                                            @method('delete')--}}
                                    {{--                                            <input type="hidden" name="record_ids" class="record-ids">--}}
                                    {{--                                            <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i data-feather="trash-2"></i> @lang('site.bulk_delete')</button>--}}
                                    {{--                                        </form><!-- end of form -->--}}
                                    {{--                                    @endif--}}

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
                                        <select id="curriculum-id" class="form-control select2">
                                            <option value="0">@lang('site.all') @lang('curricula.curricula')</option>
                                            @foreach($curricula as $curriculum)
                                                <option value="{{ $curriculum->id }}">{{ $curriculum->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="evaluation-model-id" class="form-control select2">
                                            <option value="0">@lang('site.all') @lang('evaluation_models.evaluation_models')</option>
                                            @foreach($evaluationModels as $evaluationModel)
                                                <option value="{{ $evaluationModel->id }}">{{ $evaluationModel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="projects-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('projects.name')</th>
                                                <th>@lang('projects.evaluation_model')</th>
                                                <th>@lang('projects.can_proceed_to_next_project')</th>
                                                <th>@lang('levels.levels')</th>
                                                <th>@lang('site.created_at')</th>
                                                <th>@lang('site.order')</th>
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

            let evaluationModelId;
            let curriculumId;

            let projectsTable = $('#projects-table').DataTable({
                dom: "tiplr",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                ],
                serverSide: true,
                processing: true,
                "language": {
                    "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('organization.projects.data') }}',
                    data: function (d) {
                        d.evaluation_model_id = evaluationModelId;
                        d.curriculum_id = curriculumId;
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'evaluation_model_id', name: 'evaluation_model_id', searchable: false},
                    {data: 'can_proceed_to_next_project', name: 'can_proceed_to_next_project', searchable: false},
                    {data: 'levels_count', name: 'levels_count', searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'order', name: 'order', searchable: false, visible: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[5, 'asc']],
                drawCallback: function (settings) {
                    $('.record__select').prop('checked', false);
                    $('#record__select-all').prop('checked', false);
                    $('#record-ids').val('');
                    $('#bulk-delete').attr('disabled', true);
                }
            });

            $('#data-table-search').keyup(function () {
                projectsTable.search(this.value).draw();
            });

            $('#evaluation-model-id').change(function () {
                evaluationModelId = $(this).val();
                projectsTable.ajax.reload();
            });

            $('#curriculum-id').change(function () {
                curriculumId = $(this).val();
                projectsTable.ajax.reload();
            });

        });//end of document ready
    </script>

@endpush

