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
                                <li class="breadcrumb-item"><a href="{{ route('organization.financial_transactions.index') }}" wire:navigate>@lang('financial_transactions.financial_transactions')</a></li>
                                <li class="breadcrumb-item active">@lang('site.create')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of content header -->

        <div class="content-body">

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-body">

                            <form method="post" action="{{ route('organization.financial_transactions.store') }}" class="ajax-form">
                                @csrf
                                @method('post')

                                {{-- Type --}}
                                <div class="form-group">
                                    <label>@lang('financial_transactions.type') <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="type-income" value="income" checked>
                                            <label class="form-check-label text-success fw-bold" for="type-income">
                                                <i data-feather="trending-up"></i> @lang('financial_transactions.income')
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="type-expense" value="expense">
                                            <label class="form-check-label text-danger fw-bold" for="type-expense">
                                                <i data-feather="trending-down"></i> @lang('financial_transactions.expense')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Date --}}
                                <div class="form-group">
                                    <label>@lang('financial_transactions.date') <span class="text-danger">*</span></label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', $today) }}" required>
                                </div>

                                {{-- Description --}}
                                <div class="form-group">
                                    <label>@lang('financial_transactions.description')</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="@lang('financial_transactions.description_placeholder')">{{ old('description') }}</textarea>
                                </div>

                                {{-- Amount --}}
                                <div class="form-group">
                                    <label>@lang('financial_transactions.amount') <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" step="0.001" min="0.001" required>
                                </div>

                                {{-- Currency --}}
                                <div class="form-group">
                                    <label>@lang('financial_transactions.currency')</label>
                                    <select name="currency_id" class="form-control select2">
                                        <option value="">@lang('financial_transactions.no_currency')</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                                {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Fund --}}
                                <div class="form-group">
                                    <label>@lang('funds.fund')</label>
                                    <select name="fund_id" class="form-control select2">
                                        <option value="">@lang('funds.no_fund')</option>
                                        @foreach ($funds as $fund)
                                            <option value="{{ $fund->id }}" {{ old('fund_id') == $fund->id ? 'selected' : '' }}>
                                                {{ $fund->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="save"></i> @lang('site.create')
                                    </button>
                                    <a href="{{ route('organization.financial_transactions.index') }}" wire:navigate class="btn btn-outline-secondary">
                                        @lang('site.cancel')
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div><!-- end of content body -->

    </div><!-- end of content wrapper -->

@endsection
