@extends('frontend.layouts.app')

<?php /** @var \App\User $member */ ?>

@section('app.title')
    {{ __('Account Information') }}
@endsection

@section('app.description')
    {{ __('Account Information') }}
@endsection

@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/air-datepicker/css/datepicker.min.css') }}">
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/air-datepicker/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/air-datepicker/js/i18n/datepicker.' . Session::get('locale', config('app.locale')) . '.js') }}"></script>
    <script src="{{ asset('frontend/js/member/profile.js') }}" type="text/javascript"></script>
@endsection

@section('content')

    <div class="container">
        @include('admin.layouts.components.normal_notifications')
    </div>

    <div class="container">
        <div class="row">
            <!-- TABS -->
            <div class="col-lg-16 tab-style-1 margin-bottom-35 margin-top-40">
                <ul class="nav nav-tabs text-uppercase tab-information">
                    <li class="text-center active">
                        <a class="font-16 font-sm-15 font-xs-12" href="#accountinfor" data-toggle="tab" aria-expanded="true">
                            {{ __('Account Information') }}</a></li>
                    <li class="text-center"><a class="font-16 font-sm-15 font-xs-12" href="#orders" data-toggle="tab" aria-expanded="false">
                            {{ __('My Orders') }}</a></li>
                </ul>
                <div class="tab-content font-family-san font-16" style="background-color: #fff;">
                    <div class="tab-pane fade active in" id="accountinfor">
                        <!-- BEGIN FORM-->
                        <form id="update-form" action="{{ route('member.changeInformation', ['member' => $member]) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="col-md-8 margin-bottom-10">
                                    <label for="name" class="control-label font-16"><span class="text-danger">*</span>&nbsp;{{ __('Name') }}</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $member->getName()) }}" class="form-control" placeholder="{{ __('Name') }}">
                                    @error('name')
                                    <span class="text-danger">{!! $message !!}</span>
                                    @enderror
                                </div>
                                <div class="col-md-8 margin-bottom-10">
                                    <label class="control-label font-16" for="email"><span class="text-danger">*</span>&nbsp;{{ __('E-Mail') }}</label>
                                    <div class="input-icon">
                                        <i class="fa fa-envelope"></i>
                                        <input type="text" name="email" id="email" disabled value="{{ old('email', $member->getEmail()) }}" class="form-control" placeholder="{{ __('E-Mail') }}">
                                        @error('email')
                                        <span class="text-danger">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <div class="col-md-8 margin-bottom-10">
                                    <label class="control-label font-16" for="phone"><span class="text-danger">*</span>&nbsp;{{ __('Phone') }}</label>
                                    <div class="input-icon">
                                        <i class="fa fa-phone-square"></i>
                                        <input type="text" id="phone" name="phone" value="{{ old('phone', $member->getPhone()) }}" class="form-control" placeholder="{{ __('Phone') }}">
                                        @error('phone')
                                            <span class="text-danger">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8 margin-bottom-10">
                                    <label class="control-label font-16" for="dob">
                                        <span class="text-danger">*</span>
                                        &nbsp;{{ __('Date of Birth') }}
                                    </label>
                                    <div class="input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input id="dob"
                                               name="dob"
                                               value="{{ old('dob', $member->getDobFormatted()) }}"
                                               class="form-control"
                                               dmovie-datepicker
                                               placeholder="{{ __('Date of Birth') }}"
                                               data-language="{{ Session::get('locale', config('app.locale')) }}" />
                                        @error('dob')
                                        <span class="text-danger">{!! $message !!}</span>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <div class="col-md-8 margin-bottom-10">
                                    <label for="gender" class="control-label font-16">{{ __('Gender') }}</label>
                                    <div class="input-icon">
                                        <i class="fa fa-male"></i>
                                        <select name="gender" id="gender" class="form-control" data-placeholder="{{ __('Gender') }}" tabindex="1">
                                            <option {{ (int)old('gender', $member->getGender()) === -1 ? 'selected' : "" }}
                                                class="option-item" value="-1">{{ __('Select your gender') }}</option>
                                            <option {{ (int)old('gender', $member->getGender()) === \App\User::MALE ?'selected' : ''  }}
                                                class="option-item" value="0">{{ __('Male') }}</option>
                                            <option {{ (int)old('gender', $member->getGender()) === \App\User::FEMALE ? 'selected' : '' }}
                                                class="option-item" value="1">{{ __('Female') }}</option>
                                            <option {{ (int)old('gender', $member->getGender()) === \App\User::OTHER ?'selected': '' }}
                                                class="option-item" value="2">{{ __('Other') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <div class="col-md-16 margin-bottom-30">
                                    <label for="address" class="control-label font-16">{{ __('Address') }}</label>
                                    <textarea name="address" class="form-control" rows="36" id="address" placeholder="{{ __('Address') }}">{{ old('address', $member->getAddress()) }}</textarea>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </form>

                        <div class="form-group">
                            <div class="col-md-16">
                                <div class="form-group">
                                    <a href="#changepass-pop-up" data-fancybox data-src="#changepass-pop-up" class="fancybox-fast-view">{{ __('Change password?') }}</a>
                                    <div style="display: none;width: 50%" id="changepass-pop-up">
                                        <div class="product-page product-pop-up">
                                            <div class="modal-header">
                                                <h3 class="no-padding no-margin">{{ __('Change password') }}</h3>
                                            </div>
                                            <div class="modal-body font-family-san font-16">
                                                <div class="row">
                                                    <div class="alert alert-danger alert-dismissable error-block"
                                                         style="display: none;">
                                                            <button type="button" class="close" dmovie-noti-dismiss>×</button>

                                                    </div>
                                                    <div class="alert alert-success alert-dismissable success-block"
                                                         style="display: none;">
                                                        <button type="button" class="close" dmovie-noti-dismiss>×</button>

                                                    </div>
                                                </div>
                                                @if (!$member->loginWithSocialAcc())
                                                    <div class="row">
                                                        <div class="form-group margin-bottom-10">
                                                            <label for="current_password" class="control-label font-16 col-lg-6 col-md-6 col-sm-16 col-xs-16">{{ __('Current Password') }}<span style="color: red;">*</span></label>
                                                            <div class="col-lg-10 col-md-10 col-sm-16 col-xs-16">
                                                                <input type="password" id="current_password" class="form-control" placeholder="{{ __('Enter current password') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                @endif
                                                <br>
                                                <div class="row">
                                                    <div class="form-group margin-bottom-10">
                                                        <label for="password" class="control-label font-16 col-lg-6 col-md-6 col-sm-16 col-xs-16">{{ __('New Password') }}<span style="color: red;">*</span></label>
                                                        <div class="col-lg-10 col-md-10 col-sm-16 col-xs-16">
                                                            <input type="password" id="password" class="form-control" autocomplete="new-password" placeholder="{{ __('Enter new password') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <br>
                                                <div class="row">
                                                    <div class="form-group margin-bottom-10">
                                                        <label for="password_confirmation" class="control-label font-16 col-lg-6 col-md-6 col-sm-16 col-xs-16">{{ __('Confirm New Password') }}<span style="color: red;">*</span></label>
                                                        <div class="col-lg-10 col-md-10 col-sm-16 col-xs-16">
                                                            <input type="password" id="password_confirmation" autocomplete="new-password" class="form-control" placeholder="{{ __('Enter confirm new password') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <br>
                                                <div class="row">
                                                    <div class="form-group margin-bottom-10">
                                                        <div class="col-md-16 text-right">
                                                            <div class="form-group">
                                                                <button data-id="{{ $member->getId() }}" id="_updatePassword" type="button" class="btn btn-2 btn-mua-ve" style="padding: 10px !important;">
                                                                    {{ __('Update') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group">
                            <div class="col-md-16 text-center">
                                <div class="form-group">
                                    <button onclick="event.preventDefault();$('#update-form').submit();" type="button" class="btn btn-2 btn-mua-ve">
                                        {{ __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END FORM-->
                    </div>
                    <div class="tab-pane fade" id="orders">
                        <div class="col-md-16">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase" style="width: 10%">Code bill
                                        </th>
                                        <th class="text-uppercase" style="width: 20%">Film
                                        </th>
                                        <th class="text-uppercase" style="width: 10%">Cinenma
                                        </th>
                                        <th class="text-uppercase" style="width: 10%">Show
                                        </th>
                                        <th class="text-uppercase" style="width: 15%">Seats
                                        </th>
                                        <th class="text-uppercase" style="width: 15%">Combo/Package
                                        </th>
                                        <th class="text-uppercase" style="width: 20%">Booked on date
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- END TABS -->
        </div>
    </div>
@endsection
