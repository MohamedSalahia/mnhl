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
                            <table class="table datatable" id="classroom-lessons-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>@lang('lessons.date')</th>
                                    <th>@lang('lessons.description')</th>
                                    <th>@lang('teachers.teacher')</th>
                                    <th>@lang('students.students')</th>
                                    <th>@lang('lessons.time_elapsed')</th>
                                    <th style="width: 15%;">@lang('site.action')</th>
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

        let lessonsTable = $('#classroom-lessons-table').DataTable({
            dom: "tiplr",
            serverSide: true,
            processing: true,
            language: {
                url: "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
            },
            ajax: {
                url: '{{ route('organization.lessons.data') }}',
                data: function (d) {
                    d.classroom_id = {{ $classroom->id }};
                }
            },
            columns: [
                {data: 'date', name: 'date'},
                {data: 'description', name: 'description', defaultContent: '-'},
                {data: 'teacher_id', name: 'teacher_id'},
                {data: 'students_count', name: 'students_count', searchable: false},
                {data: 'time_elapsed', name: 'time_elapsed', searchable: false},
                {data: 'actions', name: 'actions', searchable: false, sortable: false},
            ],
            order: [[0, 'desc']],
        });

        $('#data-table-search').keyup(function () {
            lessonsTable.search(this.value).draw();
        });

    });//end of document ready

</script>
