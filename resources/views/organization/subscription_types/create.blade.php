@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('subscription_types.subscription_types')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.subscription_types.index') }}" wire:navigate>@lang('subscription_types.subscription_types')</a></li>
                                <li class="breadcrumb-item active">@lang('site.create')</li>
                            </ol>

                        </div><!-- end of breadcrumb -->
                    </div>
                </div><!-- end of row -->
            </div><!-- end of content header -->
        </div><!-- end of content header -->

        <div class="content-body">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">

                            <form method="post" action="{{ route('organization.subscription_types.store') }}" class="ajax-form">
                                @csrf
                                @method('post')

                                {{--year--}}
                                <div class="form-group">
                                    <label>@lang('subscription_types.year') <span class="text-danger">*</span></label>
                                    <input type="number" name="year" data-error-name="year" class="form-control"
                                           value="{{ old('year', now()->year) }}" min="1900" max="2100" required autofocus>
                                </div>

                                {{--name--}}
                                <div class="form-group">
                                    <label>@lang('subscription_types.name') <span class="text-danger">*</span></label>
                                    <input type="text" name="name" data-error-name="name"
                                           class="form-control"
                                           value="{{ old('name') }}"
                                           required
                                    >
                                </div>

                                {{--fees--}}
                                <div class="form-group">
                                    <label>@lang('subscription_types.fees') <span class="text-danger">*</span></label>
                                    <input type="number" name="fees" data-error-name="fees" class="form-control" value="{{ old('fees', '0') }}" step="0.01" min="0" required>
                                </div>

                                {{--currency_id--}}
                                <div class="form-group">
                                    <label>@lang('currencies.currency')</label>
                                    <select name="currency_id" class="form-control select2">
                                        <option value="">@lang('site.choose') @lang('currencies.currency')</option>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                                {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--has_specific_date--}}
                                <div class="form-group">
                                    <input type="hidden" name="has_specific_date" value="0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="has_specific_date" value="1" id="has_specific_date"
                                            {{ old('has_specific_date') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="has_specific_date">@lang('subscription_types.has_specific_date')</label>
                                    </div>
                                </div>

                                <div class="row" id="subscription-type-dates">

                                    {{--start_date--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subscription_types.start_date') <span class="text-danger">*</span></label>
                                            <input type="text" name="start_date" data-error-name="start_date" class="form-control date-picker" value="{{ old('start_date') }}">
                                        </div>
                                    </div>

                                    {{--end_date--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('subscription_types.end_date') <span class="text-danger">*</span></label>
                                            <input type="text" name="end_date" data-error-name="end_date" class="form-control date-picker" value="{{ old('end_date') }}">
                                        </div>
                                    </div>

                                </div><!-- end of row -->

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> @lang('site.create')</button>
                                </div>

                            </form><!-- end of form -->

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

            handleInit();

        });// end of document ready

        let handleInit = () => {

            let handleSubscriptionDatesToggle = () => {

                let checked = $('#has_specific_date').is(':checked');
                $('#subscription-type-dates').toggle(checked);

            };

            $('#has_specific_date').on('change', handleSubscriptionDatesToggle);
            handleSubscriptionDatesToggle();

        };

    </script>

@endpush
