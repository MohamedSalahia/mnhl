@extends('layouts.teacher.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('lessons.lessons')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('teacher.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('lessons.lessons')</li>
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
                                        <input type="text" class="form-control date-range-picker" placeholder="@lang('lessons.date_range')">
                                        <input type="hidden" id="from-date">
                                        <input type="hidden" id="to-date">
                                    </div>
                                </div>

                            </div><!-- end of row -->

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div id="time-total-display" class="alert alert-success mb-0" style="display: none;">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="clock" class="mr-1"></i>
                                            <span id="time-total-text"></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end of row -->

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive">

                                        <table class="table datatable" id="lessons-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('lessons.date')</th>
                                                <th>@lang('classrooms.classroom')</th>
                                                <th>@lang('branches.branch')</th>
                                                <th>@lang('students.students')</th>
                                                <th>@lang('lessons.time_elapsed')</th>
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

@push('styles')
    <style>
        #time-total-display {
            border-left: 4px solid #25B15D !important;
            background-color: #e8f8f0 !important;
            border-color: #25B15D !important;
            border-radius: 6px;
            padding: 12px 16px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(37, 177, 93, 0.1);
            transition: all 0.3s ease;
        }

        #time-total-display i {
            width: 18px;
            height: 18px;
            stroke-width: 2.5;
            color: #25B15D !important;
        }

        #time-total-text {
            font-size: 15px;
            color: #1e8f4a !important;
        }

        @media (max-width: 768px) {
            #time-total-display {
                padding: 10px 12px;
            }

            #time-total-text {
                font-size: 14px;
            }
        }
    </style>
@endpush

@push('scripts')

    <script>

        $(function () {

            // Translations for time display
            let translations = {
                total: '{{ __("site.total") }}',
                hours: '{{ __("site.hours") }}',
                minutes: '{{ __("site.minutes") }}',
                and: '{{ __("site.and") }}'
            };

            let dateRange = {};

            let lessonsTable = $('#lessons-table').DataTable({
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
                    url: '{{ route('teacher.lessons.data') }}',
                    data: function (d) {
                        d.date_range = dateRange;
                    }
                },
                columns: [
                    {data: 'date', name: 'date', searchable: false},
                    {data: 'classroom_id', name: 'classroom_id', searchable: false},
                    {data: 'branch_id', name: 'branch_id', searchable: false},
                    {data: 'students_count', name: 'students_count', searchable: false, sortable: false},
                    {data: 'time_elapsed', name: 'time_elapsed', searchable: false, sortable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false},
                ],
                order: [[0, 'desc']],
                drawCallback: function (settings) {
                    let api = this.api();
                    let totalMinutes = 0;
                    let timeElapsedColumnIndex = 4; // Column index for time_elapsed

                    // Get all visible rows (for server-side, this gets current page rows)
                    // For client-side filtering, use { filter: 'applied' }
                    // For server-side, iterate through all rows in current view
                    api.rows({page: 'current'}).every(function () {
                        let timeElapsedCell = api.cell(this.index(), timeElapsedColumnIndex).data();

                        // Parse minutes from cell content (e.g., "45 minutes" or "-")
                        // Handle both string data and potential HTML content
                        if (timeElapsedCell && timeElapsedCell !== '-') {
                            // Convert to string and extract numeric value
                            let cellText = typeof timeElapsedCell === 'string'
                                ? timeElapsedCell
                                : $(timeElapsedCell).text() || timeElapsedCell.toString();

                            // Extract numeric value from string like "45 minutes"
                            let match = cellText.match(/(\d+)/);
                            if (match) {
                                let minutes = parseInt(match[1], 10);
                                if (!isNaN(minutes)) {
                                    totalMinutes += minutes;
                                }
                            }
                        }
                    });

                    // Calculate hours and minutes
                    let hours = Math.floor(totalMinutes / 60);
                    let minutes = totalMinutes % 60;

                    // Format display text
                    let displayText = '';
                    if (totalMinutes === 0) {
                        displayText = translations.total + ': 0 ' + translations.minutes;
                    } else if (hours > 0 && minutes > 0) {
                        displayText = translations.total + ': <strong>' + hours + '</strong> ' + translations.hours + ' ' + translations.and + ' <strong>' + minutes + '</strong> ' + translations.minutes;
                    } else if (hours > 0) {
                        displayText = translations.total + ': <strong>' + hours + '</strong> ' + translations.hours;
                    } else {
                        displayText = translations.total + ': <strong>' + minutes + '</strong> ' + translations.minutes;
                    }

                    // Update display element
                    let $totalDisplay = $('#time-total-display');
                    let $totalText = $('#time-total-text');

                    if (totalMinutes > 0) {
                        $totalText.html(displayText);
                        $totalDisplay.fadeIn(200);
                    } else {
                        $totalDisplay.fadeOut(200);
                    }

                    // Re-initialize feather icons if needed
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }
            });

            $('#data-table-search').keyup(function () {
                lessonsTable.search(this.value).draw();
            });

            $('#from-date, #to-date').on('change', function () {

                dateRange = {
                    from: $('#from-date').val() || null,
                    to: $('#to-date').val() || null
                };

                lessonsTable.ajax.reload();
            });

        });//end of document ready
    </script>

@endpush
