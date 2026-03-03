<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title> @yield('title') </title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    @include('sweetalert::alert')
    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/boxicons/fonts/basic/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/boxicons/fonts/animations.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/boxicons/fonts/transformations.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables/datatables.min.css') }}" rel="stylesheet">
    @yield('head')

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    @include('dashboard.layouts.header')
    <!-- ======= Sidebar ======= -->
    @include('dashboard.layouts.sidebar')
    <main id="main" class="main">

        @yield('container')

    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('js/main.js') }}"></script>
    {{-- <script src="{{ asset('js/jquery-4.0.0.min.js') }}"></script> --}}
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
        {{-- <script src="{{ asset('assets/notiflix/src/notiflix.js') }}"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            Notiflix.Confirm.init({
                width: '350px',
                titleColor: '#3656dc',
                okButtonBackground: '#3656dc',
            });
            Notiflix.Loading.init({
                backgroundColor: '#4b49a14d',
                svgColor: '#0f12b6',
            })
            Notiflix.Notify.init({
                width: '280px',
                position: 'right-top',
                distance: '10px',
                opacity: 1,
                borderRadius: '5px',
                rtl: false,
                timeout: 3000,
                messageMaxLength: 110,
                backOverlay: false,
                backOverlayColor: 'rgba(0,0,0,0.5)',
                plainText: true,
                showOnlyTheLastOne: false,
                clickToClose: false,
                pauseOnHover: true,
                ID: 'NotiflixNotify',
                className: 'notiflix-notify',
                zindex: 4001,
                fontFamily: 'Quicksand',
                fontSize: '13px',
                cssAnimation: true,
                cssAnimationDuration: 400,
                cssAnimationStyle: 'fade',
                closeButton: false,
                useIcon: true,
                useFontAwesome: false,
                fontAwesomeIconStyle: 'basic',
                fontAwesomeIconSize: '34px',
                success: {
                    background: '#32c682',
                    textColor: '#fff',
                    childClassName: 'notiflix-notify-success',
                    notiflixIconColor: 'rgba(0,0,0,0.2)',
                    fontAwesomeClassName: 'fas fa-check-circle',
                    fontAwesomeIconColor: 'rgba(0,0,0,0.2)',
                    backOverlayColor: 'rgba(50,198,130,0.2)',
                },
                failure: {
                    background: '#ff5549',
                    textColor: '#fff',
                    childClassName: 'notiflix-notify-failure',
                    notiflixIconColor: 'rgba(0,0,0,0.2)',
                    fontAwesomeClassName: 'fas fa-times-circle',
                    fontAwesomeIconColor: 'rgba(0,0,0,0.2)',
                    backOverlayColor: 'rgba(255,85,73,0.2)',
                },
                warning: {
                    background: '#eebf31',
                    textColor: '#fff',
                    childClassName: 'notiflix-notify-warning',
                    notiflixIconColor: 'rgba(0,0,0,0.2)',
                    fontAwesomeClassName: 'fas fa-exclamation-circle',
                    fontAwesomeIconColor: 'rgba(0,0,0,0.2)',
                    backOverlayColor: 'rgba(238,191,49,0.2)',
                },
                info: {
                    background: '#26c0d3',
                    textColor: '#fff',
                    childClassName: 'notiflix-notify-info',
                    notiflixIconColor: 'rgba(0,0,0,0.2)',
                    fontAwesomeClassName: 'fas fa-info-circle',
                    fontAwesomeIconColor: 'rgba(0,0,0,0.2)',
                    backOverlayColor: 'rgba(38,192,211,0.2)',
                },
            });
        </script> --}}
    @yield('script')
</body>

</html>
