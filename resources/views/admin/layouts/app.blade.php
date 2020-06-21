<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="access-token" content="{{ auth()->user() ? auth()->user()->getApiToken() : null }}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo/logo-dm-512.png') }}">
    <title>@yield('app.title')</title>
    <meta name="description" content="@yield('app.description')">
    @routes()
    <!-- Styles -->
{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}

    @include('admin.layouts.components.head_assets')
    @yield('head.css')
    @yield('head.js')
    <!-- Scripts -->
{{--    <script src="{{ asset('js/app.js') }}" defer></script>--}}
</head>
<body class="fix-header">

    @yield('facebook_sdk')

    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="app">
        <!-- ============================================================== -->
        <!-- Wrapper -->
        <!-- ============================================================== -->
        <div id="wrapper">
            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->
            @include('admin.layouts.components.top_nav')
            <!-- End Top Navigation -->
            <!-- ============================================================== -->
            <!-- Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            @include('admin.layouts.components.sidebar')
            <!-- ============================================================== -->
            <!-- End Left Sidebar -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Page Content -->
            <!-- ============================================================== -->
            <div id="page-wrapper">
                <main class="container-fluid">
                    @include('admin.layouts.components.titlebar')

                    @yield('action_button')

                    @include('admin.layouts.components.normal_notifications')
                    @yield('content')
                    @include('admin.layouts.components.rightsidebar')
                </main>
                <!-- /.container-fluid -->
                <footer class="footer text-center"> 2017 &copy; Ample Admin brought to you by themedesigner.in </footer>
            </div>
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->
{{--        <button id="back-to-top-button"></button>--}}
        <a href="javascript:void(0);" id="rocketmeluncur" class="showrocket" ><i></i></a>
    </div>

    @include('admin.layouts.components.bottom_assets')
    @yield('bottom.js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>
</html>



