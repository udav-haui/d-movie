<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('Login to your world!') }}">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo/logo-dm-trim.png') }}">
    <title>{{ __('Login') }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('adminhtml/assets/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{{ asset('adminhtml/assets/css/animate.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('adminhtml/assets/css/style.css') }}" rel="stylesheet">
    <!-- color CSS -->
    <link href="{{ asset('adminhtml/assets/css/colors/blue.css') }}" id="theme"  rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('adminhtml/assets/plugins/bower_components/jquery/jquery-3.4.1.min.js') }}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('adminhtml/assets/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Override Login page stylesheet -->
    <link rel="stylesheet" href="{{ asset('adminhtml/css/login.page.css') }}">
    <script src="{{ asset('adminhtml/js/login.page.js') }}"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ asset('adminhtml/assets/js/html5shiv.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/js/respond.min.js') }}"></script>
    <![endif]-->

    <script>
        function isRecoverPass(mode) {
            var link = document.getElementById('to-recover');
            if (mode === 'recover') {
                link.click();
            }
        }
    </script>
</head>
<body onload="isRecoverPass('{{ $mode ?? 'login' }}');">
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register">
    <div class="login-box login-sidebar">
        <div class="white-box">
            <form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="POST">
                @csrf
                <a id="dmovieLogo" href="javascript:void(0)"
                   class="text-center db"><img src="{{ asset('images/logo/logo-dm-trim.png') }}"
                                               alt="D-Movie" width="64px"/></a>

                @if (session('error'))
                    <div class="form-group alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('error') }}
                    </div>
                @endif
                <div class="form-group m-t-40" style="overflow: inherit;">
                    <div class="col-xs-12">
                        <input name="login"
                               class="form-control @if ($errors->has('username') || $errors->has('email')) invalid @endif"
                               type="text" required="" placeholder="{{ __('Email or Username') }}"
                               value="{{ old('username') ?: old('email') }}" autofocus autocomplete="email username">
                        @if ($errors->has('username') || $errors->has('email'))
                            <span class="error text-danger dmovie-error-box">
                                {{ $errors->first('username') ?: $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="password" class="form-control" type="password" required="" placeholder="{{ __('Password') }}"
                               autocomplete="current-password"
                        >
                    </div>
                    @error('password')
                    <span class="col-xs-12 error text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="checkbox checkbox-primary pull-left p-t-0">
                            <input id="checkbox-signup" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="checkbox-signup select-none"> {{ __('Remember me') }} </label>
                        </div>
                        <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> {{ __('Forgot Your Password?') }}</a>
                    </div>
                </div>
                <div class="form-group text-center m-t-20 ml--15 mr--15">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">{{ __('Login') }}</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                        <div class="social">
                            <a href="javascript:void(0)" class="btn btn-facebook"
                               data-toggle="tooltip" title="{{ __('Login with Facebook') }}"
                               onclick="loginWithSocial('facebook')"
                            >
                                <i aria-hidden="true" class="fa fa-facebook"></i>
                            </a> <a href="javascript:void(0)" class="btn btn-googleplus"
                                    data-toggle="tooltip" title="{{ __('Login with Google') }}"
                                    onclick="loginWithSocial('google')"
                            >
                                <i aria-hidden="true" class="fa fa-google-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            <form class="form-horizontal" id="recoverform" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group ">
                    <div class="col-xs-12">
                        <h3>{{ __('Recover Password') }}</h3>
                        <p class="text-muted color-313131">{{ __('Enter your Email and instructions will be sent to you!') }} </p>
                    </div>
                </div>
                @if (session('recover'))
                    <div class="form-group alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('recover') }}
                    </div>
                @endif
                <div class="form-group ">
                    <div class="col-xs-12">
                        <input name="email"
                               class="form-control"
                               style="border-radius: 0;"
                               type="text"
                               required
                               placeholder="{{ __('Email') }}"
                               value="{{ old('email') }}">
                    </div>
                </div>
                <div class="form-group text-center mr--15 ml--15 m-b-0">
                    <div class="col-xs-12">
                        <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">{{ __('Reset Password') }}</button>
                    </div>
                </div>
                <div class="form-group text-center m-t-20 mr--15 ml--15 m-t-5">
                    <div class="col-xs-12">
                        <a href="javascript:void(0)" id="to-login" class="btn btn-warning btn-lg btn-block text-uppercase waves-effect waves-light">
                            <i class="fa fa-chevron-left m-r-5" ></i>
                            {{ __('Back to login') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Menu Plugin JavaScript -->
<script src="{{ asset('adminhtml/assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>

<!--slimscroll JavaScript -->
<script src="{{ asset('adminhtml/assets/js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('adminhtml/assets/js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('adminhtml/assets/js/custom.js') }}"></script>
<!--Style Switcher -->
<script src="{{ asset('adminhtml/assets/plugins/bower_components/styleswitcher/jQuery.style.switcher.js') }}"></script>
</body>
</html>

