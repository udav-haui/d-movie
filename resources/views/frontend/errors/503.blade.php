<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo/logo-dm-512.png') }}">
    <title>DMovie</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('adminhtml/assets/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{{ asset('adminhtml/assets/css/animate.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('adminhtml/assets/css/style.css') }}" rel="stylesheet">
    <!-- color CSS -->
    <link href="{{ asset('adminhtml/assets/css/colors/default.css') }}" id="theme"  rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<!-- Preloader -->

<section id="wrapper" class="error-page">
    <div class="error-box">
        <div class="error-body text-center">
            <h1 class="text-warning">503</h1>
            <h3 class="text-uppercase">{{ __('This site is getting a up in few minute.') }}</h3>
            <p class="text-muted m-t-30 m-b-30">{{ __('Please try after some time') }}</p>
        </div>
    </div>
</section>
<!-- jQuery -->
<script src="{{ asset('adminhtml/assets/plugins/bower_components/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('adminhtml/assets/bootstrap/dist/js/bootstrap.min.js') }}"></script>


</body>
</html>
