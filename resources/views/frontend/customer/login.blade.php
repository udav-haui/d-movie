@extends('frontend.layouts.app')
@section('app.title')
    {{ __('Login') . '/' . __('Register') }}
@endsection

@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/air-datepicker/css/datepicker.min.css') }}">
@endsection
@section('content')

    <div class="container">
        <div class="col-lg-8 col-md-8 col-sm-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2 tab-style-1 margin-bottom-20 margin-top-20">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs text-uppercase tab-information" role="tablist">
                <li role="presentation" class="text-center w-50 @if ($action === 'login') active @endif">
                    <a href="#login"class="font-16" aria-controls="login" role="tab" data-toggle="tab" aria-expanded="true">
                        <span class="visible-xs"><i class="ti-home"></i></span>
                        <span class="hidden-xs"><i class="fa fa-sign-in"></i> {{__('Login')}}</span>
                    </a>
                </li>
                <li role="presentation" class="text-center w-50 @if ($action === 'register') active @endif">
                    <a href="#register"class="font-16" aria-controls="register" role="tab" data-toggle="tab" aria-expanded="false">
                        <span class="visible-xs"><i class="ti-user"></i></span>
                        <span class="hidden-xs"><i class="fa fa-key"></i> {{ __('Register') }}</span>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content font-family-san font-16">
                <div role="tabpanel" class="tab-pane fade @if ($action === 'login') in active @endif" id="login">

                    @if (session('error'))
                        <div class="form-group alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('frontend.doLogin') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-16 margin-bottom-10">
                                <label class="control-label font-16" for="email">{{ __('E-Mail') }}</label>
                                <div class="input-icon">
                                    <i class="fa fa-user"></i>
                                    <input type="text"
                                           id="email"
                                           name="email"
                                           class="form-control"
                                           required
                                           value="{{ old('email') }}"
                                           placeholder="Email" />
                                    @error('email')
                                        <span class="text-danger">{!! $message !!}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>


                        <div class="form-group">
                            <div class="col-md-16 margin-bottom-20">
                                <label class="control-label font-16" for="password">{{ __('Password') }}</label>
                                <div class="input-icon">
                                    <i class="fa fa-lock"></i>
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           class="form-control"
                                           required
                                           placeholder="{{ __('Password') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>


                        <div class="form-group">
                            <div class="col-md-16 margin-bottom-20">
                                <a href="#forgot-password" class="fancybox-fast-view">
                                    {{ __('Forgot password?') }}
                                </a>
                            </div>
                        </div>
                        <div class="clearfix"></div>



                        <div class="form-group">
                            <div class="col-md-16 margin-bottom-20">
                                <label for="" class="control-label font-16">
                                    ReCaptcha
                                </label>
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}

                                @error('g-recaptcha-response')
                                    <span class="text-danger">{!! $message !!}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group">
                            <div class="col-md-16 text-center">
                                <div class="form-group">
                                    <button type="submit" style="min-width: 220px;" id="login" class="btn btn-3 btn-mua-ve">
                                        {{ __('Login') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>


                        <div class="form-group">
                            <div class="col-md-16 text-center display-flex">
                                <div class="form-group">
                                    <a href="{{ route('fe.getLoginWith', ['provider' => 'facebook']) }}" class="btn btn-2 btn-mua-ve"><i class="fa fa-facebook-f"></i> {{ __('Login with Facebook') }}</a>
                                </div>
                                <div class="form-group w-50">
                                    <a href="{{ route('fe.getLoginWith', ['provider' => 'google']) }}" class="btn btn-3 btn-mua-ve"><i class="fa fa-google-plus"></i> {{ __('Login with Google') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>

                </div>
                <div role="tabpanel" class="tab-pane fade @if ($action === 'register') in active @endif" id="register">

                    @if (session('error'))
                        <div class="form-group alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('error') }}
                        </div>
                    @endif

                        <form action="{{ route('member.register') }}" method="post">
                            @csrf
                            <!-- BEGIN FORM-->
                                <div class="form-group">

                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-16 margin-bottom-10">
                                        <label class="control-label font-16" for="name"><span style="color: red;">*</span>&nbsp;{{ __('Full Name') }}</label>
                                        <input type="text" value="{{ old('name') }}"
                                               style="height: 30px;" name="name" id="name" class="form-control" placeholder="{{ __('Full Name') }}">
                                        @error('name')
                                        <span class="text-danger">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-16 margin-bottom-10">
                                        <label class="control-label font-16" for="register_email"><span style="color: red;">*</span>&nbsp;{{ __('Email') }}</label>
                                        <div class="input-icon">
                                            <i class="fa fa-envelope"></i>
                                            <input type="text" style="height: 30px;" value="{{ old('email') }}"
                                                   name="email" id="register_email" class="form-control"
                                                   placeholder="{{ __('E-Mail') }}">
                                            @error('email')
                                            <span class="text-danger">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-16 margin-bottom-10">
                                        <label class="control-label font-16" for="register_password"><span style="color: red;">*</span>&nbsp;{{ __('Password') }}</label>
                                        <div class="input-icon">
                                            <i class="fa fa-lock"></i>
                                            <input type="password" style="height: 30px;" name="password" id="register_password" class="form-control" placeholder="{{ __('Password') }}">
                                            @error('password')
                                            <span class="text-danger">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-16 margin-bottom-10">
                                        <label class="control-label font-16" for="password-confirm"><span style="color: red;">*</span>&nbsp;{{ __('Repeat Password') }}</label>
                                        <div class="input-icon">
                                            <i class="fa fa-lock"></i>
                                            <input type="password" style="height: 30px;" name="password_confirmation" id="password-confirm" class="form-control" placeholder="{{ __('Repeat Password') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-16 margin-bottom-10">
                                        <label class="control-label font-16" for="dob"><span style="color: red;">*</span>&nbsp;{{ __('Date of Birth') }}</label>
                                        <div class="input-icon">
                                            <i class="fa fa-calendar"></i>
                                            <input id="dob"
                                                   dmovie-datepicker
                                                   style="height: 30px;"
                                                   name="dob"
                                                   class="form-control"
                                                   value="{{ old('dob') }}"
                                                   placeholder="{{ __('Date of Birth') }}"
                                                   data-language="{{ Session::get('locale', config('app.locale')) }}">
                                            @error('dob')
                                            <span class="text-danger">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-16 margin-bottom-10">
                                        <label class="control-label font-16" for="gender"> {{ __('Gender') }}</label>
                                        <div class="input-icon">
                                            <i class="fa fa-male"></i>
                                            <select id="gender" style="height: 30px;" name="gender" class="form-control" data-placeholder="{{ __('Gender') }}" tabindex="1">
                                                <option {{ old('gender') && (int)old('gender') === -1 ? 'selected' : ''  }}
                                                    class="option-item" value="-1">{{ __('Select your gender') }}</option>
                                                <option {{ old('gender') && (int)old('gender') === 0 ? 'selected' : ''  }}
                                                        class="option-item" value="0">{{ __('Male') }}</option>
                                                <option {{ old('gender') && (int)old('gender') === 1 ? 'selected' : ''  }}
                                                        class="option-item" value="1">{{ __('Female') }}</option>
                                                <option {{ old('gender') && (int)old('gender') === 2 ? 'selected' : ''  }}
                                                        class="option-item" value="2">{{ __('Other') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-16 margin-bottom-10">
                                        <label class="control-label font-16" for="phone"><span style="color: red;">*</span>&nbsp;{{ __('Phone') }}</label>
                                        <div class="input-icon">
                                            <i class="fa fa-phone-square"></i>
                                            <input type="text" style="height: 30px;"
                                                   value="{{ old('phone') }}"
                                                   id="phone" name="phone" class="form-control" placeholder="{{ __('Phone') }}">
                                            @error('phone')
                                            <span class="text-danger">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <div class="col-md-16 margin-bottom-20">
                                        <label for="" class="control-label font-16">
                                            ReCaptcha
                                        </label>
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}
                                        @error('g-recaptcha-response')
                                        <span class="text-danger">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <div class="col-md-16 text-center">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-3 btn-mua-ve">
                                                {{ __('Register') }}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                        </form>

                    <div class="form-group">
                        <div class="col-md-16 text-center">
                            <div class="form-group">
                                <a href="{{ route('fe.getLoginWith', ['provider' => 'facebook']) }}" class="btn btn-2 btn-mua-ve">
                                    <i class="fa fa-facebook-f"></i> {{ __('Continue with :name', ['name' => 'Facebook']) }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-md-16 text-center">
                            <div class="form-group">
                                <a href="{{ route('fe.getLoginWith', ['provider' => 'facebook']) }}" class="btn btn-3 btn-mua-ve">
                                    <i class="fa fa-google-plus"></i> {{ __('Continue with :name', ['name' => 'Google']) }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <!-- END FORM-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/air-datepicker/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/air-datepicker/js/i18n/datepicker.' . Session::get('locale', config('app.locale')) . '.js') }}"></script>
    <script src="{{ asset('frontend/js/login.register.js') }}"></script>
@endsection
