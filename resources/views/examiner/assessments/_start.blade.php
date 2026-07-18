<form action="{{ route('examiner.assessments.deductions.store', $assessment->id) }}" method="POST" class="ajax-form" id="assessment-start-form">
    @csrf
    @method('post')
    
    {{-- Hidden input for status --}}
    <input type="hidden" name="status" id="assessment-status" value="">

    {{-- Assessment Quick Details Hero Section --}}
    <div class="modal-hero">

        <div class="modal-hero-content">

            <div class="modal-hero-title">
                <i data-feather="info"></i>
                @lang('assessments.assessment_details')
            </div>

            <div class="modal-hero-stats">
                @if($assessment->curriculum)
                    <div class="modal-hero-stat-card">
                        <div class="stat-icon">
                            <i data-feather="book"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">@lang('curricula.curriculum')</div>
                            <div class="stat-value">{{ $assessment->curriculum->name }}</div>
                        </div>
                    </div>
                @endif

                @if($assessment->project)
                    <div class="modal-hero-stat-card">
                        <div class="stat-icon">
                            <i data-feather="folder"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">@lang('projects.project')</div>
                            <div class="stat-value">{{ $assessment->project->name }}</div>
                        </div>
                    </div>
                @endif

                @if($assessment->level)
                    <div class="modal-hero-stat-card">
                        <div class="stat-icon">
                            <i data-feather="layers"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">@lang('levels.level')</div>
                            <div class="stat-value">{{ $assessment->level->name }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Score Display Section --}}
    @if($assessment->level)
        @php
            // Calculate initial remaining score based on existing deductions
            $initialTotalDeductions = 0;
            if ($assessment->assessmentScheme && $assessment->assessmentScheme->deductions) {
                $existingDeductionsForCalc = $assessment->assessmentDeductions->keyBy('assessment_scheme_deduction_id');
                foreach ($assessment->assessmentScheme->deductions as $deduction) {
                    $existingDeduction = $existingDeductionsForCalc->get($deduction->id);
                    $deductionQuantity = $existingDeduction ? $existingDeduction->quantity : 0;
                    $initialTotalDeductions += $deductionQuantity * $deduction->value;
                }
            }
            $initialRemainingScore = max(0, $assessment->level->max_score - $initialTotalDeductions);
            $isBelowPassing = $initialRemainingScore < $assessment->level->min_passing_score;
        @endphp
        <div class="score-display-section">
            <div class="score-display-title">
                <i data-feather="target"></i>
                @lang('assessments.score_details')
            </div>
            <div class="score-cards-grid">
                <div class="score-card max-score">
                    <div class="score-card-label">@lang('levels.max_score')</div>
                    <div class="score-card-value" id="max-score-display">{{ $assessment->level->max_score }}</div>
                </div>
                <div class="score-card min-passing">
                    <div class="score-card-label">@lang('levels.min_passing_score')</div>
                    <div class="score-card-value">{{ $assessment->level->min_passing_score }}</div>
                </div>
                <div class="score-card remaining {{ $isBelowPassing ? 'below-passing' : '' }}" id="remaining-score-card">
                    <div class="score-card-label">@lang('assessments.remaining_score')</div>
                    <div class="score-card-value" id="remaining-score-display">{{ $initialRemainingScore }}</div>
                </div>
            </div>
        </div>

        {{-- Hidden data for JS --}}
        <input type="hidden" id="original-max-score" value="{{ $assessment->level->max_score }}">
        <input type="hidden" id="min-passing-score" value="{{ $assessment->level->min_passing_score }}">
    @endif

    {{-- Deductions List --}}
    @if($assessment->assessmentScheme && $assessment->assessmentScheme->deductions && $assessment->assessmentScheme->deductions->count() > 0)

        <div class="deductions-section">
            <div class="deductions-section-title">
                <i data-feather="minus-circle"></i>
                @lang('assessment_scheme_deductions.assessment_scheme_deductions')
            </div>

            @php
                $existingDeductions = $assessment->assessmentDeductions->keyBy('assessment_scheme_deduction_id');
            @endphp

            @foreach($assessment->assessmentScheme->deductions as $deduction)
                @php
                    $existingDeduction = $existingDeductions->get($deduction->id);
                    $quantity = $existingDeduction ? $existingDeduction->quantity : 0;
                    $maxClicks = $deduction->max_clicks ?? null;
                    $isMaxReached = $maxClicks !== null && $quantity >= $maxClicks;
                @endphp
                <div class="deduction-item-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="deduction-info">
                                <span class="deduction-badge"
                                      style="background-color: {{ $deduction->background_color ?? '#6c757d' }}; color: {{ $deduction->text_color ?? '#fff' }};">
                                    {{ $deduction->name }}
                                </span>
                            <span class="deduction-value-text">
                                    (@lang('assessment_scheme_deductions.value'): {{ $deduction->value }})
                                </span>
                        </div>
                        <div class="deduction-quantity-controls">
                            <input type="hidden" name="deductions[{{ $loop->index }}][assessment_scheme_deduction_id]" value="{{ $deduction->id }}">

                            <button type="button" class="deduction-btn deduction-plus"
                                    {{ $isMaxReached ? 'disabled' : '' }}
                                    data-deduction-id="{{ $deduction->id }}"
                                    data-deduction-value="{{ $deduction->value }}"
                                    data-max-clicks="{{ $maxClicks ?? '' }}"
                            >
                                <i data-feather="plus"></i>
                            </button>

                            <input type="number"
                                   name="deductions[{{ $loop->index }}][quantity]"
                                   class="form-control deduction-quantity-input"
                                   value="{{ $quantity }}"
                                   min="0"
                                   {{ $maxClicks !== null ? 'max="' . $maxClicks . '"' : '' }}
                                   data-deduction-id="{{ $deduction->id }}"
                                   data-deduction-value="{{ $deduction->value }}"
                                   data-max-clicks="{{ $maxClicks ?? '' }}"
                            >

                            <button type="button" class="deduction-btn deduction-minus"
                                    {{ $quantity == 0 ? 'disabled' : '' }}
                                    data-deduction-id="{{ $deduction->id }}"
                                    data-deduction-value="{{ $deduction->value }}"
                            >
                                <i data-feather="minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else

        <div class="alert alert-info p-2">
            <i data-feather="info"></i>
            @lang('assessments.no_deductions_available')
        </div>

    @endif

    {{-- Notes Section --}}
    <div class="form-group" style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
        <label for="notes" class="form-label" style="font-weight: 600; color: #2d3748; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="file-text" style="width: 16px; height: 16px;"></i>
            @lang('assessments.notes')
        </label>
        <textarea 
            name="notes" 
            id="notes" 
            class="form-control" 
            rows="4" 
            placeholder="@lang('assessments.notes_placeholder')"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 0.75rem; resize: vertical;"
        >{{ old('notes', $assessment->notes ?? '') }}</textarea>
    </div>

    <div class="d-flex gap-2 justify-content-end">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i data-feather="x"></i>
            @lang('site.cancel')
        </button>
        
        <button type="button" class="btn btn-warning assessment-submit-btn" data-status="{{ \App\Enums\AssessmentStatusEnum::PARTIALLY_IN_PROGRESS }}">
            <i data-feather="save"></i>
            @lang('assessments.save_progress')
        </button>
        
        <button type="button" class="btn btn-primary assessment-submit-btn" data-status="{{ \App\Enums\AssessmentStatusEnum::COMPLETED }}">
            <i data-feather="check-circle"></i>
            @lang('assessments.finish_assessment')
        </button>
    </div>

</form>
