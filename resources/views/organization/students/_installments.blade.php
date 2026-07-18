@php
    use App\Models\Project;

    $selectedBranch = session('selected_branch');
    $canCreateInstallments = $selectedBranch
        && isset($selectedBranch['id'])
        && auth()->user()->hasPermission('create_installments', $selectedBranch['id']);
    $canUpdateInstallments = $selectedBranch
        && isset($selectedBranch['id'])
        && auth()->user()->hasPermission('update_installments', $selectedBranch['id']);
    $canDeleteInstallments = $selectedBranch
        && isset($selectedBranch['id'])
        && auth()->user()->hasPermission('delete_installments', $selectedBranch['id']);
@endphp

<div class="card">
    <div class="card-body">

        @if(!$selectedBranch || !isset($selectedBranch['id']))
            <p class="text-muted mb-0">@lang('installments.installments_select_branch')</p>
        @elseif($isPending)
            <p class="text-muted mb-0">@lang('installments.installments_pending_enrollment')</p>
        @elseif($branchStudentsByProject->isEmpty())
            <p class="text-muted mb-0">@lang('installments.installments_no_enrollments')</p>
        @else
            @foreach($branchStudentsByProject as $projectId => $rows)
                @php
                    $project = ($projectId !== null && $projectId !== '') ? Project::find($projectId) : null;
                    $totalFees = $rows->sum(fn ($r) => (float) ($r->fees ?? 0));
                    $totalPaidFees = $rows->sum(fn ($r) => (float) ($r->paid_fees ?? 0));
                    $totalRemainingFees = $rows->sum(fn ($r) => (float) ($r->remaining_fees ?? 0));
                    $currencyRow = $rows->first(fn ($r) => $r->currency !== null);
                    $currency = $currencyRow?->currency;
                    $projectInstallments = $installmentsByProjectId[$projectId] ?? collect();
                    $hasProjectKey = $projectId !== null && $projectId !== '';
                    $exemptedFromFeesForProject = $rows->every(fn ($r) => (bool) $r->exempted_from_fees);
                @endphp

                <div class="card mb-3" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">

                    <div class="card-header" style="background: linear-gradient(135deg, #25B15D 0%, #1a8a47 100%); color: #fff; padding: 1rem 1.5rem; border-bottom: none;">

                        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap">
                            <div class="d-flex align-items-center flex-grow-1 mb-2 mb-md-0">
                                <div class="mr-2" style="flex-shrink: 0;">
                                    <i data-feather="folder" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0" style="color: #fff; font-weight: 600;">
                                        {{ $project ? $project->name : __('lessons.no_project') }}
                                    </h5>
                                </div>
                            </div>

                            @if($canCreateInstallments && $hasProjectKey && ! $exemptedFromFeesForProject)
                                <div class="ml-md-3">
                                    <button type="button" class="btn btn-sm btn-light ajax-modal"
                                            data-url="{{ route('organization.installments.create', ['student' => $student->hash_id, 'project_id' => $projectId]) }}"
                                            data-modal-title="@lang('installments.create_installment')"
                                            data-modal-size-class="modal-md">
                                        <i data-feather="plus" style="width: 14px; height: 14px;"></i>
                                        @lang('site.create')
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-body" style="padding: 1rem 1.5rem;">
                        <div class="row mb-2">
                            <div class="col-md-3 col-6 mb-2">
                                <div class="text-muted small">@lang('students.fees')</div>
                                <div class="font-weight-bold">{{ number_format($totalFees, 2) }}</div>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="text-muted small">@lang('students.paid_fees')</div>
                                <div class="font-weight-bold">{{ number_format($totalPaidFees, 2) }}</div>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="text-muted small">@lang('students.remaining_fees')</div>
                                <div class="font-weight-bold">{{ number_format($totalRemainingFees, 2) }}</div>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="text-muted small">@lang('currencies.currency')</div>
                                <div class="font-weight-bold">
                                    @if($currency)
                                        {{ $currency->name }}{{ $currency->code ? ' (' . $currency->code . ')' : '' }}
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>@lang('installments.amount')</th>
                                    <th>@lang('payment_methods.payment_method')</th>
                                    <th>@lang('currencies.currency')</th>
                                    <th>@lang('site.created_at')</th>
                                    @if($canUpdateInstallments || $canDeleteInstallments)
                                        <th class="text-right">@lang('site.action')</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($projectInstallments as $installment)
                                    <tr>
                                        <td>{{ number_format((float) $installment->amount, 2) }}</td>
                                        <td>{{ $installment->paymentMethod?->name ?? '—' }}</td>
                                        <td>
                                            @if($currency)
                                                {{ $currency->name }}{{ $currency->code ? ' (' . $currency->code . ')' : '' }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $installment->created_at->format('Y-m-d') }}</td>
                                        @if($canUpdateInstallments || $canDeleteInstallments)
                                            @php
                                                $showInstallmentEdit = $canUpdateInstallments && ! $exemptedFromFeesForProject;
                                                $showInstallmentDelete = $canDeleteInstallments;
                                            @endphp
                                            <td class="text-right text-nowrap">
                                                @if($showInstallmentEdit || $showInstallmentDelete)
                                                    @if($showInstallmentEdit)
                                                        <button type="button" class="btn btn-warning btn-sm ajax-modal mr-1"
                                                                data-url="{{ route('organization.installments.edit', $installment) }}"
                                                                data-modal-title="@lang('installments.edit_installment')"
                                                                data-modal-size-class="modal-md">
                                                            <i data-feather="edit"></i>
                                                        </button>
                                                    @endif
                                                    @if($showInstallmentDelete)
                                                        <form action="{{ route('organization.installments.destroy', $installment) }}" class="ajax-form d-inline-block" method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-danger btn-sm delete"><i data-feather="trash-2"></i></button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ ($canUpdateInstallments || $canDeleteInstallments) ? 5 : 4 }}" class="text-muted">@lang('installments.no_installment_records')</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            @endforeach
        @endif

    </div>
</div>
