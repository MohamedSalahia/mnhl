@php use App\Enums\OrganizationStudentStatusEnum; @endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body pt-2">

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

                            <table class="table datatable" id="classroom-students-table" style="width: 100%;">
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

            </div>
        </div>
    </div>
</div>

<script>

    $(function () {

        let studentsTable = $('#classroom-students-table').DataTable({
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
                url: '{{ route('organization.students.data') }}',
                data: function (d) {
                    d.classroom_id = {{ $classroom->id }};
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

        $('#data-table-search').keyup(function () {
            studentsTable.search(this.value).draw();
        });

    });//end of document ready
</script>


