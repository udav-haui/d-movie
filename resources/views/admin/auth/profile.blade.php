<?php /** @var \App\User $user */ ?>
@extends('admin.layouts.app')
@section('app.title')
    {{ __('Profile - :name', ['name' => $user->name]) }}
@endsection
@section('app.description')
    {{ __(':name\'s Profile', ['name' => $user->name]) }}
@endsection
@section('content')
    <!-- /.row -->

    @include('admin.lang.global_text_lang')

    <!-- .row -->
    <div class="row">
        <div class="@can('selfUpdate', $user) col-md-4 col-xs-12 @else col-md-12 col-xs-12 @endcan">
            <div class="white-box">
                <div class="user-bg"> <img width="100%" alt="user" src="{{ asset('images/icons/wall.jpg') }}">
                    <div class="overlay-box">
                        <div class="user-content">
                            <a href="{{ $user->getAvatarPath() }}" data-fancybox>
                                <img src="{{ $user->getAvatarPath() }}"
                                     class="thumb-lg img-circle dmovie-img-cover"
                                     alt="img" />
                            </a>
                            <h4 class="text-white">{{ $user->name ?? __('Not update') }}</h4>
                            <h5 class="text-white">{{ $user->email ?? __('Not update') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="user-btm-box">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>{{ __('Full Name') }}</strong>
                            <br>
                            <p class="text-muted">{{ $user->name ?? __('Not update') }}</p>
                        </div>
                        <div class="col-md-12"> <strong>{{ __('Mobile') }}</strong>
                            <br>
                            <p class="text-muted">{{ $user->phone ?? __('Not update') }}</p>
                        </div>
                        <div class="col-md-12"> <strong>{{ __('Email') }}</strong>
                            <br>
                            <p class="text-muted">{{ $user->email ?? __('Not update') }}</p>
                        </div>
                        <div class="col-md-12"> <strong>{{ __('Location') }}</strong>
                            <br>
                            <p class="text-muted" title="{{ $user->address }}">{{ $user->address ?? __('Not update') }}</p>
                        </div>
                    </div>
                    <hr>
                    <h4 class="font-bold m-t-30">{{ __('Description') }}</h4>
                    <blockquote class="m-t-30">
                        {{ $user->description ?? __('This user have not say anything!') }}
                    </blockquote>
                </div>
            </div>
        </div>
        @can('selfUpdate', $user)
        <div class="col-md-8 col-xs-12">
            <div class="white-box">
                <ul class="nav nav-tabs tabs customtab">
                    <li class="tab @if (session('change_info')) {{ session('change_info') }} @endif @error('change_info'){{ $message }}@enderror">
                        <a href="#settings" data-toggle="tab" aria-expanded="true">
                            <span class="visible-xs"><i class="fa fa-cog"></i></span>
                            <span class="hidden-xs">{{ __('Settings') }}</span>
                        </a>
                    </li>
                    <li class="tab @if (session('change_pass')) {{ session('change_pass') }} @endif @error('change_pass'){{ $message }}@enderror">
                        <a href="#change_pass" data-toggle="tab" aria-expanded="false">
                            <span class="visible-xs"><i class="fa fa-lock"></i></span>
                            <span class="hidden-xs">{{ __('Change password') }}</span>
                        </a>
                    </li>
                    <li class="tab @if (session('change_avatar')) {{ session('change_avatar') }} @endif @error('change_avatar'){{ $message }}@enderror">
                        <a href="#change_avatar" data-toggle="tab" aria-expanded="false">
                            <span class="visible-xs"><i class="fa fa-user"></i></span>
                            <span class="hidden-xs">{{ __('Avatar') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane @if (session('change_info')) {{ session('change_info') }} @endif @error('change_info'){{ $message }}@enderror" id="settings">
                        <form class="form-horizontal form-material" id="user_data_form"
                              method="POST"
                              action="{{ route('users.update', ['user' => $user]) }}">
                            @csrf
                            {{ method_field('PUT') }}


                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12" for="username">
                                            {{ __('Username') }}
                                            @if (auth()->user()->cant('updateUserName', $user) || (auth()->user()->getAuthIdentifier() === $user->getAuthIdentifier() && $user->canChangeUsername()))
                                                <strong class="text-danger small">({{ __('Username can edit for one times') }})</strong>
                                            @endif
                                        </label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="{{ __('Input your username') }}"
                                                   class="form-control form-control-line @error('username') invalid @enderror
                                                   @cannot('updateUserName', $user) disabled @endcannot"
                                                   value="{{ old('username', $user->username) }}" name="username"
                                                   @cannot('updateUserName', $user) disabled @endcannot />
                                            @error('username')
                                                <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-12" for="name">{{ __('Full Name') }} <strong class="text-danger">*</strong></label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="{{ __('Input your name') }}"
                                                   class="form-control form-control-line @error('name') invalid @enderror"
                                                   value="{{ old('name', $user->name) }}" name="name"
                                            />
                                            @error('name')
                                                <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="email" class="col-md-12">{{ __('Email') }} <strong class="text-danger">*</strong></label>
                                        <div class="col-md-12">
                                            <input type="email" placeholder="{{ __('Input your E-Mail') }}"
                                                   class="form-control form-control-line @error('email') invalid @enderror"
                                                   name="email"
                                                   id="example-email" value="{{ old('email', $user->email) }}"/>
                                            @error('email')
                                                <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-12" for="phone">{{ __('Phone') }} <strong class="text-danger">*</strong></label>
                                        <div class="col-md-12">
                                            <input name="phone" type="text" placeholder="{{ __('Input your phone number') }}"
                                                   class="form-control form-control-line @error('phone') invalid @enderror"
                                                   value="{{ old('phone', $user->phone) }}" />
                                            @error('phone')
                                                <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-12" for="address">{{ __('Address') }}</label>
                                        <div class="col-md-12">
                                            <input name="address" id="address" type="text"
                                                   class="form-control form-control-line @error('address') invalid @enderror"
                                                   placeholder="{{ __('Provide your address...') }}"
                                                   value="{{ old('address', $user->address) }}" />
                                            @error('address')
                                                <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12" for="gender">{{ __('Gender') }}</label>
                                        <div class="col-md-12">
                                            <select name="gender" id="gender"
                                                    class="gender-selector bs-select-hidden"
                                                    data-style="form-control">
                                                <option value="-1" {{ old('gender', $user->gender) ? '' : 'selected' }}>{{ __('Select your gender') }}</option>
                                                <option value="0" {{ old('gender', $user->gender) != 0 ? '' : 'selected' }}>{{ __('Male') }}</option>
                                                <option value="1" {{ old('gender', $user->gender) != 1 ? '' : 'selected' }}>{{ __('Female') }}</option>
                                                <option value="2" {{ old('gender', $user->gender) != 2 ? '' : 'selected' }}>{{ __('Other') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 col-xs-12">


                                    <div class="form-group cold-md-12">
                                        <label class="col-md-12" for="dob-datepicker-autoclose">{{ __('Date of birth') }} <strong class="text-danger">*</strong></label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <input name="dob" type="text" class="form-control @error('dob') invalid @enderror"
                                                    lang="{{ \Session::get('locale', config('app.locale')) }}"
                                                    id="dob-datepicker-autoclose"
                                                    placeholder="dd/mm/yyyy" value="{{ old('dob', $user->getDobFormated()) }}"/>
                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                            </div>
                                            @error('dob')
                                                <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-12" for="description">{{ __('Description') }}</label>
                                        <div class="col-md-12">
                                            <textarea name="description" id="description" rows="3"
                                                      class="form-control form-control-line @error('description') invalid @enderror"
                                                      placeholder="{{ __('Say something about your-self...') }}">{{ old('description', $user->description) }}</textarea>
                                            @error('description')
                                                <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-success background-main-color border-none">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="tab-pane @if (session('change_pass')) {{ session('change_pass') }} @endif @error('change_pass'){{ $message }}@enderror" id="change_pass">
                        <form class="form-horizontal form-material"
                              method="POST"
                              action="{{ route('users.changePassword', ['user' => $user->id]) }}">
                            @csrf
                            @if ((!$user->loginWithSocialAcc() && !auth()->user()->isAdmin())|| (auth()->user()->isAdmin() && auth()->user()->id == $user->id))
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer"
                                       for="current_password">
                                    {{ __('Current Password') }}
                                </label>
                                <div class="col-md-12">
                                    <input name="current_password" id="current_password" type="password"
                                           class="form-control form-control-line @error('current_password') invalid @enderror"
                                           placeholder="{{ __('Input your current password') }}"
                                           autocomplete="current_password" />
                                    @error('current_password')
                                        <span class="error text-danger  dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer" for="password">{{ __('New Password') }}</label>
                                <div class="col-md-12">
                                    <input name="password" id="password" type="password"
                                           class="form-control form-control-line @error('password') invalid @enderror"
                                           placeholder="{{ __('Input your new password') }}"
                                           autocomplete="new-password" />
                                    @error('password')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer" for="password-confirm">{{ __('Repeat Password') }}</label>
                                <div class="col-md-12">
                                    <input name="password_confirmation" id="password-confirm" type="password" class="form-control form-control-line"
                                           placeholder="{{ __('Re-input your new password') }}" autocomplete="new-password"/>
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="col-sm-12">
                                <button class="btn btn-success background-main-color border-none">{{ __('Change password') }}</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane @if (session('change_avatar')) {{ session('change_avatar') }} @endif @error('change_avatar'){{ $message }}@enderror" id="change_avatar">
                        <form class="form-horizontal form-material"
                              method="POST"
                              action="{{ route('users.setAvatar', ['user' => $user->id]) }}"
                              enctype="multipart/form-data">
                            {{ method_field('PUT') }}
                            @csrf
                            <div class="form-group">
                                <div class="white-box">
                                    <h3 class="box-title">{{ __('Upload avatar') }}</h3>
                                    @error('avatar')
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <input name="avatar" type="file"
                                           class="avatar-dropify"
                                           data-height="350"
                                           required
                                           data-default-file="{{ $user->getAvatarPath() ? $user->getAvatarPath() : '' }}"
                                           dropify-msg-default="{{ __('Drag and drop a file here or click') }}"
                                           dropify-msg-replace="{{ __('Drag and drop or click to replace') }}"
                                           dropify-msg-remove="{{ __('Remove') }}"
                                           dropify-msg-error="{{ __('Ooops, something wrong appended.') }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success background-main-color border-none">{{ __('Change avatar') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            @endcan
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('momo.testing') }}"
                  method="post"
                  target="_blank">
                @csrf
                <div class="form-group">
                    <input name="amount" type="text" class="form-control" placeholder="amount" />
                </div>
                <button type="submit"
                        id="momo-payment-test-d" class="btn btn-block dmovie-btn-success">
                    Continue
                </button>
            </form>
        </div>
    </div>
    <!-- /.row -->

@endsection
@section('titlebar.title')
    {{ __('Profile') }}
@endsection
@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('users.index') }}">{{ __('User') }}</a></li>
    <li class="active">{{ $user->name }}</li>
@endsection
@section('bottom.js')
    <script src="{{ asset('adminhtml/js/profile.js') }}"></script>
@endsection
@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/css/profile.css') }}">
@endsection

