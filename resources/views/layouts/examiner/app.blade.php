<!DOCTYPE html>
<html class="light-layout loaded {{ auth()->user()->dark_mode ? 'dark-layout' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-textdirection="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-layout="bordered-layout">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <meta name="user-id" content="{{ auth()->user()->id }}">
    <meta name="loading" content="@lang('site.loading')">
    <meta name="no-data-found" content="@lang('site.no_data_found')">
    <meta name="no-contents-found" content="@lang('contents.no_contents_found')">
    <meta name="drop-images-text" content="@lang('site.drop_images')">
    <meta name="delete-text" content="@lang('site.delete')">
    <meta name="dropzone-text" content="@lang('site.drop_images')">
    <meta name="hadith-back-to-formating" content="@lang('hadiths.back_to_formating')">
    <meta name="lesson-page" content="@lang('lessons.page')">
    <meta name="attendance-status-required" content="@lang('lessons.attendance_status_required')">
    <meta name="student-lesson-saved" content="@lang('lessons.student_lesson_saved')">
    <meta name="please-fix-errors" content="@lang('site.please_fix_errors')">

    <!-- PWA  -->
    {{--<meta name="theme-color" content="#ffffff"/>--}}
    {{--<link rel="apple-touch-icon" href="{{ asset('logo.png') }}">--}}
    {{--<link rel="manifest" href="{{ asset('/manifest.json') }}">--}}

    <title>{{ setting('title') }}</title>

    <link rel="apple-touch-icon" href="{{ asset('admin_assets/app-assets/images/ico/favicon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin_assets/app-assets/images/ico/favicon.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/vendors/js/noty/noty.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <!-- END: Vendor CSS-->

    {{--Vendor js--}}
    <script src="{{ asset('admin_assets/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/noty/noty.min.js') }}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/pickers/flatpickr/flatpickr_ar.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/vendors/css/easy-autocomplete/easy-autocomplete.min.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    {{--dropzone--}}
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/vendors/js/dropzone/dropzone.min.css') }}">
    <script src="{{ asset('admin_assets/app-assets/vendors/js/dropzone/dropzone.min.js') }}"></script>

    <script>
        Dropzone.autoDiscover = false;
    </script>

    {{--intl tel input--}}
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/vendors/js/tel-input/css/intlTelInput.min.css') }}">
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tel-input/js/intlTelInput.min.js') }}"></script>
    <style>.iti__flag.iti__sy{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 640 480'%3E%3Cdefs%3E%3Cpolygon id='s' points='0,-28 6.58,-9.06 26.63,-8.65 10.65,3.46 16.46,22.65 0,11.2 -16.46,22.65 -10.65,3.46 -26.63,-8.65 -6.58,-9.06'/%3E%3C/defs%3E%3Crect width='640' height='160' fill='%23007a3d'/%3E%3Crect y='160' width='640' height='160' fill='%23fff'/%3E%3Crect y='320' width='640' height='160' fill='%23000'/%3E%3Cuse href='%23s' transform='translate(160,240)' fill='%23ce1126'/%3E%3Cuse href='%23s' transform='translate(320,240)' fill='%23ce1126'/%3E%3Cuse href='%23s' transform='translate(480,240)' fill='%23ce1126'/%3E%3C/svg%3E")!important;background-position:center!important;background-size:cover!important;}.iti input[type=tel]{direction:ltr!important;text-align:left!important;}</style>

    {{--summernote--}}
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/vendors/css/summernote/summernote-lite.css') }}">
    <script src="{{ asset('admin_assets/app-assets/vendors/js/summernote/summernote-lite.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/lang/summernote-ar-AR.min.js"></script>

    {{--jstree--}}
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/vendors/css/extensions/jstree.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css/plugins/extensions/ext-component-tree.css') }}">
    <script src="{{ asset('admin_assets/app-assets/vendors/js/extensions/jstree.min.js') }}"></script>

    {{--fontawesome--}}
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">

    {{--flag-icon--}}
    <link rel="stylesheet" href="{{ asset('admin_assets/app-assets/fonts/flag-icon-css/css/flag-icon.min.css') }}">

    <!-- BEGIN: Theme CSS-->
    @if (app()->getLocale() == 'ar')

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500&display=swap" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/vendors/css/vendors-rtl.min.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/bootstrap-extended.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/colors.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/components.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/themes/dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/themes/bordered-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/themes/semi-dark-layout.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css-rtl/custom-rtl.css') }}">


        <style>

            *, html, body {
                font-family: 'Cairo', sans-serif;
                /*line-height: 25px;*/
            }

        </style>

    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/vendors/css/vendors.min.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css/bootstrap-extended.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css/colors.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css/components.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css/themes/dark-layout.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/app-assets/css/plugins/forms/form-wizard.css') }}">

    @endif

    @stack('styles')

    @livewireStyles

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>

    <script src="{{ asset('admin_assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

    <script src="{{ asset('admin_assets/app-assets/js/scripts/components/components-collapse.js') }}"></script>

    {{--ckeditor--}}
    <script src="{{ asset('admin_assets/app-assets/vendors/js/ckeditor/build/ckeditor.js') }}"></script>

    @vite(['resources/css/admin/app.css', 'resources/js/admin/app.js'])

    <script>

        $(function () {

            //delete
            $(document).on('click', '.btn.delete, .btn#bulk-delete, .delete', function (e) {

                var that = $(this)

                e.preventDefault();

                var n = new Noty({
                    text: "@lang('site.confirm_delete')",
                    type: "alert",
                    killer: true,
                    buttons: [
                        Noty.button("@lang('site.yes')", 'btn btn-primary mr-1', function () {
                            that.closest('form').submit();
                        }),

                        Noty.button("@lang('site.no')", 'btn btn-danger', function () {
                            n.close();
                        })
                    ]
                });

                n.show();

            });//end of delete

        });//end of document ready

        $(window).on('load', function () {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

    {{--<script src="{{ asset('/sw.js') }}"></script>--}}

    {{--<script>--}}
    {{--    if ("serviceWorker" in navigator) {--}}
    {{--        // Register a service worker hosted at the root of the--}}
    {{--        // site using the default scope.--}}
    {{--        navigator.serviceWorker.register("/sw.js").then(--}}
    {{--            (registration) => {--}}
    {{--                console.log("Service worker registration succeeded:", registration);--}}
    {{--            },--}}
    {{--            (error) => {--}}
    {{--                console.error(`Service worker registration failed: ${error}`);--}}
    {{--            },--}}
    {{--        );--}}
    {{--    } else {--}}
    {{--        console.error("Service workers are not supported.");--}}
    {{--    }--}}
    {{--</script>--}}

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-sticky footer-static {{ auth()->user()->menu_collapsed ? 'menu-collapsed' : '' }}" data-open="click"
      data-menu="vertical-menu-modern" data-col=""
      style="position:relative;"
>

@include('layouts.examiner._header')

@include('layouts.examiner._aside')

<!-- BEGIN: Content-->
<div class="app-content content ">

    <div class="content-overlay"></div>

    <div class="header-navbar-shadow"></div>

    @include('examiner.partials._session')

    @yield('content')

</div>
<!-- END: Content-->

<div id="context-menu" class="context-menu">
    <ul id="menu-items"></ul>
</div>

{{--ajax modal--}}
<div class="modal fade text-left" id="ajax-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-dark">
    <p class="clearfix mb-0">
        <span class="float-md-left d-block d-md-inline-block mt-25">
            <span class="d-none d-sm-inline-block">@lang('site.rights_reserved') {{ now()->year }}</span>
        </span>
    </p>
</footer>

<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>

@livewireScripts

@stack('scripts')

<!-- Disable browser autocomplete hints on all dashboard forms -->
<script>
    (function () {
        function disableAutocomplete(root) {
            $(root || document).find('form').attr('autocomplete', 'off');
            $(root || document).find('input:not([type="password"]):not([type="hidden"]), textarea, select').attr('autocomplete', 'off');
        }
        $(document).ready(function () {
            disableAutocomplete();
            $(document).ajaxComplete(function () { disableAutocomplete(); });
        });
    })();
</script>


</body>
</html>
