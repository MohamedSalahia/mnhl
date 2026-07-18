@php
    use App\Enums\TeacherSalaryCalculationTypeEnum;
    use App\Enums\TeacherSalaryTypeEnum;

    $selectedBranch  = session('selected_branch');
    $canCreate   = $selectedBranch && isset($selectedBranch['id']) && auth()->user()->hasPermission('create_teacher_salaries', $selectedBranch['id']);
    $canUpdate   = $selectedBranch && isset($selectedBranch['id']) && auth()->user()->hasPermission('update_teacher_salaries', $selectedBranch['id']);
    $canDelete   = $selectedBranch && isset($selectedBranch['id']) && auth()->user()->hasPermission('delete_teacher_salaries', $selectedBranch['id']);
    $canSettings = $selectedBranch && isset($selectedBranch['id']) && auth()->user()->hasPermission('update_teachers', $selectedBranch['id']);

    $isHourly      = $salaryType === TeacherSalaryCalculationTypeEnum::HOURLY;
    $currencyLabel = $currency ? ($currency->code ?? $currency->name) : '';

    $typeLabels = [
        TeacherSalaryTypeEnum::PAYMENT->value   => ['label' => __('teacher_salaries.type_payment'),   'badge' => 'badge-success',  'icon' => 'dollar-sign'],
        TeacherSalaryTypeEnum::BONUS->value     => ['label' => __('teacher_salaries.type_bonus'),     'badge' => 'badge-primary',  'icon' => 'star'],
        TeacherSalaryTypeEnum::DEDUCTION->value => ['label' => __('teacher_salaries.type_deduction'), 'badge' => 'badge-danger',   'icon' => 'minus-circle'],
        TeacherSalaryTypeEnum::ADVANCE->value   => ['label' => __('teacher_salaries.type_advance'),   'badge' => 'badge-warning',  'icon' => 'credit-card'],
    ];

    $monthNames = [
        1  => 'يناير',  2  => 'فبراير', 3  => 'مارس',
        4  => 'أبريل',  5  => 'مايو',   6  => 'يونيو',
        7  => 'يوليو',  8  => 'أغسطس',  9  => 'سبتمبر',
        10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
    ];
@endphp

<div class="card mb-0">

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-1"
         style="background:linear-gradient(135deg,#25B15D 0%,#1a8a47 100%);border-radius:.5rem .5rem 0 0;">
        <h5 class="mb-0 text-white">
            <i data-feather="dollar-sign" style="width:18px;height:18px;stroke:#fff;margin-left:4px;"></i>
            @lang('teacher_salaries.salaries')
            @if($currencyLabel)
                <span class="badge badge-light text-success ml-1" style="font-size:.75rem;">{{ $currencyLabel }}</span>
            @endif
        </h5>
        <div class="d-flex gap-1 flex-wrap">
            @if($canSettings)
                <button type="button" class="btn btn-sm btn-light ajax-modal"
                        data-url="{{ route('organization.teachers.salary_settings.edit', $teacher->hash_id) }}"
                        data-modal-title="@lang('teacher_salaries.salary_settings')"
                        data-modal-size-class="modal-sm">
                    <i data-feather="settings" style="width:14px;height:14px;"></i>
                    @lang('teacher_salaries.salary_settings')
                </button>
            @endif
            @if($canCreate)
                <button type="button" class="btn btn-sm btn-light ajax-modal"
                        data-url="{{ route('organization.teacher_salaries.create', ['teacher' => $teacher->hash_id]) }}"
                        data-modal-title="@lang('teacher_salaries.add_transaction')"
                        data-modal-size-class="modal-md">
                    <i data-feather="plus" style="width:14px;height:14px;"></i>
                    @lang('teacher_salaries.add_transaction')
                </button>
            @endif
        </div>
    </div>

    <div class="card-body pb-1">

        {{-- ── Grand Totals ─────────────────────────────────────────────────── --}}
        <div class="row mb-3">
            <div class="col-6 col-md-3 mb-2">
                <div class="p-2 rounded text-center" style="background:#e3f2fd;border:1px solid #90caf9;">
                    <div class="text-muted small">@lang('teacher_salaries.net_salary')</div>
                    <div class="font-weight-bold" style="color:#1565c0;">
                        {{ number_format($grandNet, 2) }}
                        @if($currencyLabel) <small class="text-muted">{{ $currencyLabel }}</small> @endif
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="p-2 rounded text-center" style="background:#fff8e1;border:1px solid #ffe082;">
                    <div class="text-muted small">@lang('teacher_salaries.type_advance')</div>
                    <div class="font-weight-bold" style="color:#e65100;">
                        {{ number_format($grandAdvances, 2) }}
                        @if($currencyLabel) <small class="text-muted">{{ $currencyLabel }}</small> @endif
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="p-2 rounded text-center" style="background:#e8f5e9;border:1px solid #a5d6a7;">
                    <div class="text-muted small">@lang('teacher_salaries.total_paid')</div>
                    <div class="font-weight-bold text-success">
                        {{ number_format($grandPaid, 2) }}
                        @if($currencyLabel) <small class="text-muted">{{ $currencyLabel }}</small> @endif
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="p-2 rounded text-center"
                     style="background:{{ $grandRemaining > 0 ? '#fdecea' : '#e8f5e9' }};border:2px solid {{ $grandRemaining > 0 ? '#f5c6cb' : '#a5d6a7' }};">
                    <div class="text-muted small">@lang('teacher_salaries.remaining')</div>
                    <div class="font-weight-bold" style="color:{{ $grandRemaining > 0 ? '#c62828' : '#2e7d32' }};font-size:1.05rem;">
                        {{ number_format($grandRemaining, 2) }}
                        @if($currencyLabel) <small class="text-muted">{{ $currencyLabel }}</small> @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Monthly Breakdown ────────────────────────────────────────────── --}}
        <h6 class="mb-2" style="color:#555;">
            <i data-feather="calendar" style="width:16px;height:16px;"></i>
            @lang('teacher_salaries.monthly_breakdown')
        </h6>

        @foreach($monthlyRows as $row)
            @php
                $periodLabel = ($monthNames[$row['month']] ?? $row['month']) . ' ' . $row['year'];
                $rowId       = 'period-' . $row['year'] . '-' . $row['month'];
                $periodTx    = $row['periodTx'];
            @endphp
            <div class="border rounded mb-2" style="overflow:hidden;">

                {{-- Month header row --}}
                <div class="px-3 pt-2 pb-1" style="background:#f8f9fa;cursor:pointer;"
                     data-toggle="collapse" data-target="#{{ $rowId }}">

                    {{-- Line 1: month name | مستحق | متبقي | chevron --}}
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="font-weight-bold">{{ $periodLabel }}</span>
                        <div class="d-flex align-items-center" style="gap:8px;">
                            <span style="font-size:.8rem;background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;padding:2px 7px;border-radius:4px;white-space:nowrap;">
                                @lang('teacher_salaries.month_due'): <strong>{{ number_format($row['net'], 2) }}</strong>
                                @if($currencyLabel) {{ $currencyLabel }} @endif
                            </span>
                            <span class="font-weight-bold"
                                  style="color:{{ $row['remaining'] > 0 ? '#c62828' : ($row['remaining'] < 0 ? '#e65100' : '#2e7d32') }};white-space:nowrap;">
                                @lang('teacher_salaries.remaining'): {{ number_format($row['remaining'], 2) }}
                                @if($currencyLabel) <small class="text-muted">{{ $currencyLabel }}</small> @endif
                            </span>
                            <i data-feather="chevron-down" style="width:14px;height:14px;flex-shrink:0;"></i>
                        </div>
                    </div>

                    {{-- Line 2: breakdown badges (smaller) --}}
                    <div class="d-flex flex-wrap mt-1" style="gap:4px;">
                        <span style="font-size:.75rem;background:#1565c0;color:#fff;padding:1px 6px;border-radius:3px;">
                            @lang('teacher_salaries.base_salary'): {{ number_format($row['base'], 2) }}
                            @if($isHourly && $row['hours'] !== null)
                                ({{ $row['hours'] }}h)
                            @endif
                        </span>
                        @if($row['bonuses'] > 0)
                            <span style="font-size:.75rem;background:#28a745;color:#fff;padding:1px 6px;border-radius:3px;">
                                @lang('teacher_salaries.type_bonus'): {{ number_format($row['bonuses'], 2) }}
                            </span>
                        @endif
                        @if($row['deductions'] > 0)
                            <span style="font-size:.75rem;background:#dc3545;color:#fff;padding:1px 6px;border-radius:3px;">
                                @lang('teacher_salaries.type_deduction'): {{ number_format($row['deductions'], 2) }}
                            </span>
                        @endif
                        @if($row['advances'] > 0)
                            <span style="font-size:.75rem;background:#ffc107;color:#333;padding:1px 6px;border-radius:3px;">
                                @lang('teacher_salaries.type_advance'): {{ number_format($row['advances'], 2) }}
                            </span>
                        @endif
                        @if($row['paid'] > 0)
                            <span style="font-size:.75rem;background:#17a2b8;color:#fff;padding:1px 6px;border-radius:3px;">
                                @lang('teacher_salaries.type_payment'): {{ number_format($row['paid'], 2) }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Transactions collapse --}}
                <div id="{{ $rowId }}" class="collapse">
                    @if($periodTx->isEmpty())
                        <p class="text-muted text-center py-2 mb-0 small">@lang('teacher_salaries.no_transactions')</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0" style="font-size:.85rem;">
                                <thead style="background:#f0f0f0;">
                                    <tr>
                                        <th>@lang('teacher_salaries.transaction_type')</th>
                                        <th>@lang('teacher_salaries.amount')</th>
                                        <th>@lang('payment_methods.payment_method')</th>
                                        <th>@lang('teacher_salaries.notes')</th>
                                        <th>@lang('teacher_salaries.date')</th>
                                        @if($canUpdate || $canDelete)
                                            <th class="text-right">@lang('site.action')</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($periodTx->sortByDesc('date') as $transaction)
                                        @php $meta = $typeLabels[$transaction->type->value]; @endphp
                                        <tr>
                                            <td>
                                                <span class="badge {{ $meta['badge'] }}">
                                                    <i data-feather="{{ $meta['icon'] }}" style="width:11px;height:11px;"></i>
                                                    {{ $meta['label'] }}
                                                </span>
                                                @if($transaction->carry_forward)
                                                    <span class="badge badge-secondary ml-1" title="@lang('teacher_salaries.carried_forward')">
                                                        <i data-feather="corner-down-right" style="width:10px;height:10px;"></i>
                                                        @lang('teacher_salaries.carry_forward_short')
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="font-weight-bold">{{ number_format((float)$transaction->amount, 2) }}</td>
                                            <td>{{ $transaction->paymentMethod?->name ?? '—' }}</td>
                                            <td class="text-muted">{{ $transaction->notes ?? '—' }}</td>
                                            <td>{{ $transaction->date ? $transaction->date->format('Y-m-d') : $transaction->created_at->format('Y-m-d') }}</td>
                                            @if($canUpdate || $canDelete)
                                                <td class="text-right text-nowrap">
                                                    @if($canUpdate)
                                                        <button type="button" class="btn btn-warning btn-sm ajax-modal mr-1"
                                                                data-url="{{ route('organization.teacher_salaries.edit', $transaction) }}"
                                                                data-modal-title="@lang('teacher_salaries.edit_transaction')"
                                                                data-modal-size-class="modal-md">
                                                            <i data-feather="edit"></i>
                                                        </button>
                                                    @endif
                                                    @if($canDelete)
                                                        <form action="{{ route('organization.teacher_salaries.destroy', $transaction) }}"
                                                              class="ajax-form d-inline-block" method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-danger btn-sm delete">
                                                                <i data-feather="trash-2"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
</div>
