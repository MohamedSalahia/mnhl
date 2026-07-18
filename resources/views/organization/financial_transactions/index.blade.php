@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('financial_transactions.financial_transactions')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item active">@lang('financial_transactions.financial_transactions')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of content header -->

        <div class="content-body">

            {{-- Summary Cards --}}
            <div class="row mb-2">

                <div class="col-md-4 col-12">
                    <div class="card bg-light-success">
                        <div class="card-body py-1 px-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-muted small">@lang('financial_transactions.total_income')</p>
                                    <h4 class="mb-0 text-success">{{ number_format($totalIncome, 3) }}</h4>
                                </div>
                                <i data-feather="trending-up" class="text-success" style="width:32px;height:32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="card bg-light-danger">
                        <div class="card-body py-1 px-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-muted small">@lang('financial_transactions.total_expense')</p>
                                    <h4 class="mb-0 text-danger">{{ number_format($totalExpense, 3) }}</h4>
                                </div>
                                <i data-feather="trending-down" class="text-danger" style="width:32px;height:32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="card {{ $balance >= 0 ? 'bg-light-primary' : 'bg-light-warning' }}">
                        <div class="card-body py-1 px-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-muted small">@lang('financial_transactions.balance')</p>
                                    <h4 class="mb-0 {{ $balance >= 0 ? 'text-primary' : 'text-warning' }}">{{ number_format($balance, 3) }}</h4>
                                </div>
                                <i data-feather="activity" class="{{ $balance >= 0 ? 'text-primary' : 'text-warning' }}" style="width:32px;height:32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- end summary cards -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- Action Buttons --}}
                            <div class="row mb-1">
                                <div class="col-md-12 d-flex flex-wrap gap-1">

                                    @if (auth()->user()->hasPermission('create_financial_transactions', session('selected_branch')['id']))
                                        <a href="{{ route('organization.financial_transactions.create') }}" wire:navigate class="btn btn-primary">
                                            <i data-feather="plus"></i> @lang('financial_transactions.add_transaction')
                                        </a>

                                        <a href="#" class="btn btn-outline-secondary ajax-modal"
                                           data-url="{{ route('organization.funds.create') }}"
                                           data-modal-title="@lang('funds.add_fund')"
                                        >
                                            <i data-feather="box"></i> @lang('funds.add_fund')
                                        </a>
                                    @endif

                                </div>
                            </div><!-- end of buttons row -->

                            {{-- Filters --}}
                            <div class="row mb-1">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="data-table-search" class="form-control" placeholder="@lang('site.search')">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="filter-type" class="form-control select2">
                                            <option value="">@lang('site.all') @lang('financial_transactions.types')</option>
                                            <option value="income">@lang('financial_transactions.income')</option>
                                            <option value="expense">@lang('financial_transactions.expense')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="filter-fund" class="form-control select2">
                                            <option value="">@lang('site.all') @lang('funds.funds')</option>
                                            @foreach ($funds as $fund)
                                                <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="filter-currency" class="form-control select2">
                                            <option value="">@lang('site.all') @lang('financial_transactions.currencies')</option>
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div><!-- end of filters row -->

                            {{-- DataTable --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table datatable" id="financial-transactions-table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>@lang('financial_transactions.type')</th>
                                                <th>@lang('financial_transactions.date')</th>
                                                <th>@lang('financial_transactions.description')</th>
                                                <th>@lang('financial_transactions.amount')</th>
                                                <th>@lang('funds.fund')</th>
                                                <th>@lang('site.action')</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- end of table row -->

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

            let filterType     = null;
            let filterFund     = null;
            let filterCurrency = null;

            let table = $('#financial-transactions-table').DataTable({
                dom: "tiplr",
                serverSide: true,
                processing: true,
                language: {
                    url: "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
                },
                ajax: {
                    url: '{{ route('organization.financial_transactions.data') }}',
                    data: function (d) {
                        d.type        = filterType;
                        d.fund_id     = filterFund;
                        d.currency_id = filterCurrency;
                    }
                },
                columns: [
                    {data: 'type',        name: 'type',        searchable: false},
                    {data: 'date',        name: 'date',        searchable: false},
                    {data: 'description', name: 'description'},
                    {data: 'amount',      name: 'amount',      searchable: false},
                    {data: 'fund_name',   name: 'fund.name',   searchable: false, sortable: false},
                    {data: 'actions',     name: 'actions',     searchable: false, sortable: false},
                ],
                order: [[1, 'desc']],
                drawCallback: function () {
                    if (feather) feather.replace({ width: 14, height: 14 });
                }
            });

            $('#data-table-search').keyup(function () {
                table.search(this.value).draw();
            });

            $('#filter-type').on('change', function () {
                filterType = this.value || null;
                table.ajax.reload();
            });

            $('#filter-fund').on('change', function () {
                filterFund = this.value || null;
                table.ajax.reload();
            });

            $('#filter-currency').on('change', function () {
                filterCurrency = this.value || null;
                table.ajax.reload();
            });

        });//end of document ready

    </script>

@endpush
