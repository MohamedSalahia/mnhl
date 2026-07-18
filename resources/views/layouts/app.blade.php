<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    @if(app()->getLocale() == 'ar')
        <style>
            [dir="rtl"] {
                direction: rtl;
                text-align: right;
                font-family: 'Cairo', sans-serif;
            }

            [dir="rtl"] body {
                text-align: right;
                font-family: 'Cairo', sans-serif;
            }

            [dir="rtl"] * {
                font-family: 'Cairo', sans-serif;
            }

            [dir="rtl"] .text-left {
                text-align: right !important;
            }

            [dir="rtl"] .text-right {
                text-align: left !important;
            }

            [dir="rtl"] .ml-auto {
                margin-left: 0 !important;
                margin-right: auto !important;
            }

            [dir="rtl"] .mr-auto {
                margin-right: 0 !important;
                margin-left: auto !important;
            }

            [dir="rtl"] .pl-0 {
                padding-left: 0.25rem !important;
                padding-right: 0 !important;
            }

            [dir="rtl"] .pr-0 {
                padding-right: 0.25rem !important;
                padding-left: 0 !important;
            }

            /* Form elements RTL */
            [dir="rtl"] label {
                text-align: right !important;
            }

            [dir="rtl"] .form-group {
                text-align: right;
            }

            [dir="rtl"] .form-control {
                text-align: right;
                direction: rtl;
            }

            [dir="rtl"] .form-control:focus {
                text-align: right;
            }

            [dir="rtl"] input[type="text"],
            [dir="rtl"] input[type="email"],
            [dir="rtl"] input[type="password"],
            [dir="rtl"] input[type="tel"],
            [dir="rtl"] input[type="date"],
            [dir="rtl"] input[type="number"],
            [dir="rtl"] textarea,
            [dir="rtl"] select {
                text-align: right !important;
                direction: rtl;
            }

            [dir="rtl"] .form-check-label {
                padding-right: 1.5rem;
                padding-left: 0;
            }

            [dir="rtl"] .form-check-input {
                margin-right: -1.5rem;
                margin-left: 0;
            }

            /* Container and grid RTL */
            [dir="rtl"] .container,
            [dir="rtl"] .container-fluid {
                direction: rtl;
            }

            [dir="rtl"] .row {
                direction: rtl;
            }

            /* Card RTL */
            [dir="rtl"] .card {
                text-align: right;
            }

            [dir="rtl"] .card-header {
                text-align: right;
            }

            [dir="rtl"] .card-body {
                text-align: right;
            }

            /* Button RTL */
            [dir="rtl"] .btn {
                text-align: center;
            }
        </style>
    @endif


    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    @endif

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>

    @if(app()->getLocale() == 'ar')
        <script src="{{ asset('admin_assets/app-assets/vendors/js/pickers/flatpickr/flatpickr_ar.js')}}"></script>
    @endif

    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/vendors/js/noty/noty.css') }}">
    <script src="{{ asset('admin_assets/app-assets/vendors/js/noty/noty.min.js') }}"></script>

    {{--intl tel input (match organization dashboard: vendor + flag-icon + iti overrides)--}}
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/vendors/js/tel-input/css/intlTelInput.min.css') }}">
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tel-input/js/intlTelInput.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/fonts/flag-icon-css/css/flag-icon.min.css') }}">
    <style>
        /* Syria new independence flag override for phone input */
        .iti__flag.iti__sy {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 640 480'%3E%3Cdefs%3E%3Cpolygon id='s' points='0,-28 6.58,-9.06 26.63,-8.65 10.65,3.46 16.46,22.65 0,11.2 -16.46,22.65 -10.65,3.46 -26.63,-8.65 -6.58,-9.06'/%3E%3C/defs%3E%3Crect width='640' height='160' fill='%23007a3d'/%3E%3Crect y='160' width='640' height='160' fill='%23fff'/%3E%3Crect y='320' width='640' height='160' fill='%23000'/%3E%3Cuse href='%23s' transform='translate(160,240)' fill='%23ce1126'/%3E%3Cuse href='%23s' transform='translate(320,240)' fill='%23ce1126'/%3E%3Cuse href='%23s' transform='translate(480,240)' fill='%23ce1126'/%3E%3C/svg%3E") !important;
            background-position: center !important;
            background-size: cover !important;
        }
        .iti input[type=tel] { direction: ltr !important; text-align: left !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
<div id="app">
    <main class="py-4">
        @yield('content')
    </main>
</div>

<!-- Bootstrap 4 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>


<!-- Initialize Flatpickr with jQuery -->
<script>
    $(document).ready(function () {
        // Wait for flatpickr to be available
        if (typeof flatpickr === 'undefined') {
            console.error('Flatpickr is not loaded');
            return;
        }

        // Manually register jQuery plugin if not already registered
        if (typeof $.fn.flatpickr === 'undefined') {
            $.fn.flatpickr = function (options) {
                return this.each(function () {
                    if (!this._flatpickr) {
                        this._flatpickr = flatpickr(this, options);
                    }
                });
            };
        }

        $('.date-picker').each(function () {
            var $this = $(this);

            // Check if flatpickr is already initialized
            if ($this.data('flatpickr') || this._flatpickr) {
                return; // Skip if already initialized
            }

            var currentValue = $this.val();
            var options = {
                dateFormat: 'Y-m-d',
                disableMobile: true,
                locale: $('html').attr('dir') == 'rtl' ? 'ar' : 'en',
                position: 'auto',
            };

            // Preserve default value if it exists
            if (currentValue) {
                options['defaultDate'] = currentValue;
            }

            var maxDay = $this.attr('data-max-day');
            if (maxDay) {
                maxDay === 'now' || maxDay === 'today'
                    ? options['maxDate'] = 'today'
                    : options['maxDate'] = maxDay;
            }

            var minDay = $this.attr('data-min-day');
            if (minDay) {
                minDay === 'now' || minDay === 'today'
                    ? options['minDate'] = 'today'
                    : options['minDate'] = minDay;
            }

            $this.flatpickr(options);
        });
    });
</script>
</body>
</html>
