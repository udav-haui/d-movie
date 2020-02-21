<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>
        @yield('app.title')
    </title>
    <meta name="description" content="@yield('app.description')">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="36x36" href="{{ asset('images/logo/logo-dm-512.png') }}">
    @routes()

    @include('frontend.layouts.components.head_assets')

    @yield('head.css')
    @yield('head.js')
</head>
<body class="corporate no-js">
@yield('facebook_sdk')
<div id="app">
    @include('frontend.layouts.components.pre_topbar')

    @include('frontend.layouts.components.header')

    <div class="margin-none">
        @yield('content_top')
        @yield('content')
        @yield('content_bottom')
    </div>

    <a href="javascript:void(0);" id="rocketmeluncur" class="showrocket" ><i></i></a>

    @include('frontend.layouts.components.footer')
</div>

@include('frontend.layouts.components.bottom_assets')

@yield('bottom.js')
</body>
</html>
