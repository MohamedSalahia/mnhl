@php
    use App\Enums\AttendanceStatusEnum;
    use App\Enums\ClassroomTypeEnum;
    use App\Helpers\PhoneHelper;

    $student = $studentLesson->student;
    $lesson = $studentLesson->lesson;
@endphp

<meta name="lesson-page" content="@lang('lessons.page')">

<style>
    .student-lesson-modal-wrapper {
        padding: 0;
    }

    /* Student Profile Header */
    .student-profile-header {
        background: linear-gradient(135deg, #25B15D 0%, #1e8f4a 100%);
        padding: 1.25rem 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }

    .student-profile-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .student-profile-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .student-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .student-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.25);
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background: white;
    }

    .student-info {
        flex: 1;
        min-width: 0;
    }

    .student-name {
        font-size: 1.375rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .student-contact-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.95);
    }

    .contact-item i {
        width: 16px;
        height: 16px;
        opacity: 0.9;
    }

    /* Lesson Details Section */
    .lesson-details-section {
        padding: 2rem 0;
        background: #ffffff;
    }

    .details-title {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6b7280;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .details-title i {
        width: 16px;
        height: 16px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
    }

    .detail-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: #d1d5db;
    }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9ca3af;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-value i {
        width: 20px;
        height: 20px;
        color: #25B15D;
    }

    .page-number-display {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #25B15D 0%, #1e8f4a 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(37, 177, 93, 0.3);
    }

    .page-number-display i {
        width: 18px;
        height: 18px;
    }

    /* Evaluation Items Section */
    .evaluation-items-section {
        padding: 1rem 0;
        background: #ffffff;
        position: relative;
    }

    .evaluation-items-title {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6b7280;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .evaluation-items-title i {
        width: 16px;
        height: 16px;
    }

    .evaluation-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(0, 1fr));
        gap: 0.75rem;
    }

    .evaluation-item-btn {
        position: relative;
        width: 100%;
        transition: opacity 0.2s ease, box-shadow 0.2s ease;
    }

    .evaluation-item-btn:hover,
    .evaluation-item-btn:focus {
        opacity: 0.9 !important;
        box-shadow: none !important;
    }

    .evaluation-item-btn:active {
        opacity: 0.85 !important;
    }

    .evaluation-item-btn.active {
        opacity: 0.8 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
        border-width: 2px !important;
    }

    /*.evaluation-item-btn:hover {*/
    /*    transform: translateY(-2px);*/
    /*    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);*/
    /*}*/

    .evaluation-item-btn.active {
        border-color: #25B15D;
        box-shadow: 0 2px 8px rgba(37, 177, 93, 0.3);
    }

    .evaluation-item-btn .page-number-badge {
        position: absolute;
        top: -8px;
        left: -8px;
        z-index: 10;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.75rem;
        box-shadow: 0 2px 6px rgba(220, 53, 69, 0.4);
        padding: 0 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .student-profile-content {
            flex-direction: column;
            text-align: center;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .student-profile-header {
            padding: 1rem 1.25rem;
        }

        .lesson-details-section,
        .evaluation-items-section {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .evaluation-item-btn .page-number-badge {
            top: -6px;
            left: -6px;
            min-width: 20px;
            height: 20px;
            font-size: 0.65rem;
        }

        .evaluation-items-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<form method="post" action="{{ route('teacher.student_lessons.update', $studentLesson->id) }}"
      class="ajax-form"
      data-student-lesson-id="{{ $studentLesson->id }}"
      data-select-evaluation-item-url="{{ route('teacher.student_lessons.select_evaluation_item', $studentLesson->id) }}"
      data-deselect-evaluation-item-url="{{ route('teacher.student_lessons.deselect_evaluation_item', $studentLesson->id) }}"
      data-page-number="{{ $pageNumber ?? '' }}"
      data-level-to-page="{{ $levelToPage ?? '' }}">
    @csrf
    @method('put')

    <div class="student-lesson-modal-wrapper">

        <!-- Student Profile Header -->
        <div class="student-profile-header">
            <div class="student-profile-content">
                <div class="student-avatar-wrapper">
                    <img src="{{ $student->image_path }}" alt="{{ $student->name }}" class="student-avatar">
                </div>
                <div class="student-info">
                    <h2 class="student-name">{{ $student->name }}</h2>
                    <div class="student-contact-info">
                        @if($student->email)
                            <div class="contact-item">
                                <i data-feather="mail"></i>
                                <span>{{ $student->email }}</span>
                            </div>
                        @endif
                        @if($student->mobile)
                            <div class="contact-item">
                                <i data-feather="phone"></i>
                                <span class="mobile">
                                @if($student->mobile_country_code)
                                        {{ $student->mobile_country_code }}
                                    @endif
                                    {{ $student->mobile }}
                            </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Lesson Details Section -->
        <div class="lesson-details-section">

            <div class="details-grid">
                @if($lesson->classroom)
                    <div class="detail-card">
                        <div class="detail-label">@lang('classrooms.classroom')</div>
                        <div class="detail-value">
                            <i data-feather="users"></i>
                            <span>{{ $lesson->classroom->name }}</span>
                        </div>
                    </div>
                @endif

                <div class="detail-card">
                    <div class="detail-label">@lang('lessons.date')</div>
                    <div class="detail-value">
                        <i data-feather="calendar"></i>
                        <span>{{ $lesson->date->format('Y-m-d') }}</span>
                    </div>
                </div>

                @if($pageNumber !== null)
                    <div class="detail-card">
                        <div class="detail-label">@lang('levels.page_number')</div>
                        <div class="detail-value">
                        <span class="page-number-display">
                            <i data-feather="book-open"></i>
                            {{ $pageNumber }}
                        </span>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        @if(!$existingAssessment)
            {{--attendance_status--}}
            <div class="form-group">
                <label>@lang('lessons.attendance_status') <span class="text-danger">*</span></label>
                <select name="attendance_status" class="form-control select2" required>
                    <option value="">@lang('site.choose') @lang('lessons.attendance_status')</option>
                    @foreach (AttendanceStatusEnum::getConstants() as $status)
                        <option value="{{ $status }}" {{ old('attendance_status', $studentLesson->attendance_status) == $status ? 'selected' : '' }}>
                            {{ __('lessons.' . strtolower($status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Evaluation Items Section -->
            @if($evaluationItems->count() > 0)
                <div class="evaluation-items-section" @if($pageNumber !== null && $levelToPage !== null && $pageNumber >= $levelToPage) style="display: none;" @endif>
                    <div class="evaluation-items-title">
                        <i data-feather="check-circle"></i>
                        @lang('evaluation_items.evaluation_items')
                    </div>

                    <div class="row">
                        @foreach($evaluationItems as $item)
                            <div class="col-md-3 mb-1">
                                <button type="button"
                                        class="btn btn-primary evaluation-item-btn w-100"
                                        data-item-id="{{ $item->id }}"
                                        style="background-color: {{ $item->background_color ?? '#007bff' }} !important; color: {{ $item->text_color ?? '#fff' }} !important; border-color: {{ $item->background_color ?? '#007bff' }} !important;"
                                >
                                    @if($pageNumber !== null)
                                        <span class="page-number-badge">{{ $pageNumber }}</span>
                                    @endif
                                    {{ $item->name }}
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{--Pages with evaluation items--}}
            <div id="pages-with-evaluation-items" class="form-group" @if($lessonEvaluationItems->isEmpty() || old('attendance_status', $studentLesson->attendance_status) === AttendanceStatusEnum::ABSENT) style="display: none;" @endif>
                <label>@lang('levels.page_number')</label>
                <div id="page-buttons-container" class="row">
                    @foreach($lessonEvaluationItems as $lessonEvalItem)
                        <div class="col-md-3 mb-1">
                            <button type="button"
                                    class="btn btn-dark w-100 position-relative"
                                    data-page="{{ $lessonEvalItem->page_number }}"
                            >
                                {{ $lessonEvalItem->page_number }}: {{ $lessonEvalItem->evaluationItem->name }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{--time_elapsed--}}
            @if($lesson->classroom && $lesson->classroom->type === ClassroomTypeEnum::INDIVIDUAL)
                <div id="time-elapsed-wrapper" class="form-group"
                     @if(old('attendance_status', $studentLesson->attendance_status) === AttendanceStatusEnum::ABSENT) style="display: none;" @endif>
                    <label>@lang('lessons.time_elapsed')</label>
                    <div class="input-group">
                        <input type="number" name="time_elapsed" class="form-control" min="0" step="1"
                               value="{{ old('time_elapsed', $studentLesson->time_elapsed) }}"
                               placeholder="@lang('lessons.time_elapsed_placeholder')">
                        <div class="input-group-append">
                            <span class="input-group-text">@lang('site.minutes')</span>
                        </div>
                    </div>
                </div>
            @endif

            {{--notes--}}
            <div class="form-group">
                <label>@lang('lessons.notes')</label>
                <textarea name="notes" class="form-control">{{ old('notes', $studentLesson->notes) }}</textarea>
            </div>

        @endif

        <!-- Reached End of Level Message -->
        @if($evaluationItems->count() > 0)
            <div id="reached-end-of-level-message" class="form-group" @if(!($pageNumber !== null && $levelToPage !== null && $pageNumber >= $levelToPage)) style="display: none;" @endif>
                <div class="alert alert-primary p-2" role="alert">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i data-feather="info"></i>
                            @lang('levels.reached_end_of_level')
                        </div>
                        @if($existingAssessment)
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-{{ $existingAssessment->status === 'pending' ? 'warning' : ($existingAssessment->status === 'in_progress' ? 'info' : 'success') }}">
                                    @lang('assessments.status_' . $existingAssessment->status)
                                </span>
                                @if($existingAssessment->examiner)
                                    <span class="text-muted">
                                        <i data-feather="user"></i>
                                        {{ $existingAssessment->examiner->name }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <button type="button" class="btn btn-success btn-sm ml-3"
                                    data-create-assessment="true"
                                    data-url="{{ route('teacher.assessments.create') }}?student_lesson_id={{ $studentLesson->id }}"
                                    data-modal-title="@lang('assessments.create_assessment')">
                                <i data-feather="plus-circle"></i>
                                @lang('assessments.create_assessment')
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>

    @if(!$existingAssessment)
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i data-feather="check-circle"></i> @lang('lessons.evaluate')</button>
        </div>
    @endif

</form>

@push('scripts')
    <script src="{{ asset('admin_assets/custom/js/lessons.js') }}"></script>
    <script>
        window.fileLockedWarningText = "@lang('lessons.file_locked_warning')";
    </script>
@endpush

