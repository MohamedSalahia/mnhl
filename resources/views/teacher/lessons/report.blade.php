@php use App\Enums\ClassroomTypeEnum; @endphp
    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('lessons.lesson') @lang('site.report')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'cairo', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            padding: 20px;

        }

        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #25B15D;
            padding-bottom: 15px;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            color: #25B15D;
            margin-bottom: 10px;
        }

        .org-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .header-info {
            margin-top: 10px;
        }

        .header-info p {
            margin: 5px 0;
            font-size: 11px;
        }

        .header-info strong {
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }

        thead {
            background-color: #25B15D;
            color: white;
        }

        thead th {
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #ddd;
        }

        tbody tr {
            page-break-inside: avoid;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody td {
            padding: 10px 8px;
            font-size: 11px;
            vertical-align: top;
            border: 1px solid #ddd;
            text-align: center;
        }

        .attendance-present {
            color: #155724;
            font-weight: bold;
        }

        .attendance-absent {
            color: #721c24;
            font-weight: bold;
        }

        .attendance-late {
            color: #856404;
            font-weight: bold;
        }

        .evaluation-items {
            font-size: 10px;
            line-height: 1.4;
        }

        .notes-cell {
            font-size: 10px;
            line-height: 1.4;
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .total-row td {
            padding: 10px 8px;
            font-size: 11px;
            border: 1px solid #ddd;
            text-align: center;
        }

        body[dir="ltr"] .total-row td:first-child {
            text-align: left !important;
        }

        body[dir="rtl"] .total-row td:first-child {
            text-align: right !important;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="header-top">
        <div>
            <h1>@lang('lessons.report')</h1>
            @if($lesson->organization && $lesson->organization->name)
                <div style="font-size: 14px; font-weight: bold; color: #333;">{{ $lesson->organization->name }}</div>
            @endif
        </div>
        @if($lesson->organization && $lesson->organization->logo_base64)
            <img class="org-logo" src="{{ $lesson->organization->logo_base64 }}" alt="logo">
        @endif
    </div>
    <div class="header-info">
        <p><strong>@lang('lessons.date'):</strong> {{ $lesson->date->format('Y-m-d') }}</p>

        @if($lesson->classroom)
            <p><strong>@lang('classrooms.classroom'):</strong> {{ $lesson->classroom->name }}</p>
        @endif

        @if($lesson->description)
            <p><strong>@lang('lessons.description'):</strong> {{ $lesson->description }}</p>
        @endif

        @if($lesson->time_elapsed !== null)
            <p><strong>@lang('lessons.time_elapsed'):</strong> {{ $lesson->time_elapsed }} @lang('site.minutes')</p>
        @endif

    </div>
</div>

@if($lesson->studentLessons && $lesson->studentLessons->count() > 0)
    <table>
        <thead>
        <tr>
            <th>@lang('users.name')</th>
            <th>@lang('students.student_number')</th>
            <th>@lang('projects.project')</th>
            <th>@lang('lessons.attendance_status')</th>
            <th>@lang('evaluation_items.evaluation_items')</th>
            <th>@lang('lessons.time_elapsed')</th>
            <th>@lang('site.notes')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($lesson->studentLessons as $studentLesson)
            <tr>
                <td>{{ $studentLesson->student->name }}</td>
                <td>{{ $studentSequentialNumbers[$studentLesson->student_id] ?? $studentLesson->student->student_number ?? str_pad($studentLesson->student->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $studentLesson->project->name ?? '-' }}</td>
                <td>
                    @if($studentLesson->attendance_status)
                        <span class="attendance-{{ $studentLesson->attendance_status }}">
                                    @lang('lessons.' . $studentLesson->attendance_status)
                                </span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $studentLessonEvaluationItems = $lesson->lessonEvaluationItems->where('student_id', $studentLesson->student_id)->sortBy('page_number');
                    @endphp
                    @if($studentLessonEvaluationItems->count() > 0)
                        <div class="evaluation-items">
                            @foreach($studentLessonEvaluationItems as $lessonEvalItem)
                                @if($lessonEvalItem->evaluationItem)
                                    {{ $lessonEvalItem->page_number }}: {{ $lessonEvalItem->evaluationItem->name }}@if(!$loop->last)
                                        ,
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($lesson->classroom && $lesson->classroom->type === ClassroomTypeEnum::GROUP)
                        -
                    @elseif($studentLesson->time_elapsed !== null)

                        {{ $studentLesson->time_elapsed }} @lang('site.minutes')

                    @elseif($lesson->time_elapsed !== null && $lesson->classroom && $lesson->classroom->type === ClassroomTypeEnum::INDIVIDUAL)

                        {{ $lesson->time_elapsed }} @lang('site.minutes')

                    @else
                        -
                    @endif
                </td>
                <td class="notes-cell">{{ $studentLesson->notes ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        @php
            $totalMinutes = 0;
            if ($lesson->classroom && $lesson->classroom->type === 'group' && $lesson->time_elapsed !== null) {
                // For group classrooms, total is the lesson time_elapsed (session duration)
                $totalMinutes = (int) $lesson->time_elapsed;
            } else {
                // For individual classrooms, sum all student times
                foreach ($lesson->studentLessons as $sl) {
                    $minutes = $sl->time_elapsed ?? (
                        ($lesson->time_elapsed !== null && $lesson->classroom && $lesson->classroom->type === 'individual')
                            ? $lesson->time_elapsed
                            : 0
                    );
                    $totalMinutes += (int) $minutes;
                }
            }
        @endphp
        <tr class="total-row">
            <td colspan="5" style="text-align: right;"><strong>@lang('site.total')</strong></td>
            <td><strong>{{ $totalMinutes }} @lang('site.minutes')</strong></td>
            <td></td>
        </tr>
        </tfoot>
    </table>
@else
    <div class="no-data">
        @lang('site.no_data_found')
    </div>
@endif

<div class="footer">
    <p>{{ now()->format('Y-m-d H:i:s') }}</p>
</div>
</body>
</html>
