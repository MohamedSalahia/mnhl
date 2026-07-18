@extends('layouts.organization.app')

@section('content')

    <div class="content-wrapper">

        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">

                <div class="row breadcrumbs-top">

                    <div class="col-12">

                        <h2 class="content-header-title float-left mb-0">@lang('branches.branches')</h2>

                        <div class="breadcrumb-wrapper">

                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('organization.home') }}" wire:navigate>@lang('site.home')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('organization.branches.index') }}" wire:navigate>@lang('branches.branches')</a></li>
                                <li class="breadcrumb-item active">@lang('site.edit')</li>
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

                            <form method="post" action="{{ route('organization.branches.update', $branch->id) }}" class="ajax-form">
                                @csrf
                                @method('put')

                                {{--country_id--}}
                                <div class="form-group">
                                    <label>@lang('countries.country') <span class="text-danger">*</span></label>
                                    <select name="country_id" id="country-id" class="form-control select2" required
                                            data-governorates-base-url="{{ route('organization.countries.governorates', 0) }}">
                                        <option value="">@lang('site.choose') @lang('countries.country')</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" {{ $country->id == $branch->country_id ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--governorate_id--}}
                                <div class="form-group">
                                    <label>@lang('governorates.governorate') <span class="text-danger">*</span></label>
                                    <select name="governorate_id" id="governorate-id" class="form-control select2" required
                                            data-areas-base-url="{{ route('organization.governorates.areas', 0) }}">
                                        <option value="">@lang('site.choose') @lang('governorates.governorate')</option>
                                        @foreach ($governorates as $governorate)
                                            <option value="{{ $governorate->id }}" {{ $governorate->id == $branch->governorate_id ? 'selected' : '' }}>{{ $governorate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--area_id--}}
                                <div class="form-group">
                                    <label>@lang('areas.area') <span class="text-danger">*</span></label>
                                    <select name="area_id" id="area-id" class="form-control select2" required>
                                        <option value="">@lang('site.choose') @lang('areas.area')</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}" {{ $area->id == $branch->area_id ? 'selected' : ''  }}>{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--name--}}
                                <div class="row">

                                    @foreach ($activeLanguages as $activeLanguage)

                                        <div class="col-md-{{ $activeLanguages->count() == 2 ? '6' : '12' }}">
                                            <div class="form-group">
                                                <label>@lang('branches.name') (@lang('languages.' . $activeLanguage->code))@if($activeLanguage->code === 'ar')<span class="text-danger">*</span>@endif</label>
                                                <input type="text" name="{{ $activeLanguage->code }}[name]" data-error-name="{{ $activeLanguage->code }}.name"
                                                       {{ $loop->first ? 'autofocus' : '' }}
                                                       class="form-control"
                                                       value="{{ old($activeLanguage->code . '.name', $branch->translate($activeLanguage->code)?->name) }}"
                                                       {{ $activeLanguage->code === 'ar' ? 'required' : '' }}
                                                       autocomplete="off"
                                                >
                                            </div>
                                        </div>

                                    @endforeach

                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.update')</button>
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
    <script src="{{ asset('admin_assets/custom/js/countries.js') }}"></script>
@endpush
