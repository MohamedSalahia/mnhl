@php use App\Helpers\PhoneHelper; @endphp
@if($mobile_country_code && $mobile)
    @php
        $countryCode = PhoneHelper::getCountryCodeFromDialCode($mobile_country_code);
    @endphp
    @if($countryCode)
        <i class="flag-icon flag-icon-{{ $countryCode }}"></i>
    @endif
    {{ $mobile_country_code }} {{ $mobile }}
@else
    {{ $mobile ?? '' }}
@endif
