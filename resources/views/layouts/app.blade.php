<!doctype html>
<html class="no-js" lang="en">


<!-- Mirrored from theme-land.com/sapp/demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 11 Jun 2024 01:22:54 GMT -->

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title  -->
    <title>@yield('title')</title>

    <!-- Favicon  -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <!-- ***** All CSS Files ***** -->

    <!-- Style css -->
    <link rel="stylesheet" href="{{ asset('assets/landing-page/css/style.css') }}">

</head>

<body>
    <!--====== Scroll To Top Area Start ======-->
    <div id="scrollUp" title="Scroll To Top">
        <i class="fas fa-arrow-up"></i>
    </div>
    <!--====== Scroll To Top Area End ======-->

    @yield('content')


    <!-- ***** All jQuery Plugins ***** -->

    <!-- jQuery(necessary for all JavaScript plugins) -->
    <script src="{{ asset('assets/landing-page/js/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap js -->
    <script src="{{ asset('assets/landing-page/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/landing-page/js/bootstrap/bootstrap.min.js') }}"></script>

    <!-- Plugins js -->
    <script src="{{ asset('assets/landing-page/js/plugins/plugins.min.js') }}"></script>

    <!-- Active js -->
    <script src="{{ asset('assets/landing-page/js/active.js') }}"></script>

</body>


<!-- Mirrored from theme-land.com/sapp/demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 11 Jun 2024 01:23:08 GMT -->

</html>
