@extends('admin.layouts.app')
@section('app.title')
    {{ __('Profile - ') }}{{ $user->name }}
@endsection
@section('app.description')
    {{ __(':name\'s Profile', ['name' => $user->name]) }}
@endsection
@section('content')
    <!-- /.row -->
    <!-- .row -->
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="white-box">
                <div class="user-bg"> <img width="100%" alt="user" src="{{ asset('images/icons/wall.jpg') }}">
                    <div class="overlay-box">
                        <div class="user-content">
                            <a href="javascript:void(0)">
                                <img src="{{ asset('images/icons/account.png') }}" class="thumb-lg img-circle" alt="img">
                            </a>
                            <h4 class="text-white">{{ $user->name }}</h4>
                            <h5 class="text-white">{{ $user->email }}</h5>
                        </div>
                    </div>
                </div>
                <div class="user-btm-box">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>{{ __('Full Name') }}</strong>
                            <br>
                            <p class="text-muted">{{ $user->name }}</p>
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
                    <p class="m-t-30">
                        {!! $user->description ?? '<blockquote>' . __('This user have not say anything!') . '</blockquote>' !!}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="white-box">
                <ul class="nav nav-tabs tabs customtab">
                    <li class="tab active">
                        <a href="#settings" data-toggle="tab" aria-expanded="true">
                            <span class="visible-xs"><i class="fa fa-cog"></i></span>
                            <span class="hidden-xs">{{ __('Settings') }}</span>
                        </a>
                    </li>
                    <li class="tab">
                        <a href="#change_pass" data-toggle="tab" aria-expanded="false">
                            <span class="visible-xs"><i class="fa fa-lock"></i></span>
                            <span class="hidden-xs">{{ __('Change password') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="settings">
                        <form class="form-horizontal form-material">
                            <div class="form-group">
                                <label class="col-md-12" for="name">{{ __('Full Name') }}</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="{{ __('Input your name') }}"
                                           class="form-control form-control-line"
                                           value="{{ old('name', $user->name) }}" name="name"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-12">Email</label>
                                <div class="col-md-12">
                                    <input type="email" placeholder="johnathan@admin.com"
                                           class="form-control form-control-line" name="email"
                                           id="example-email" value="{{ old('email', $user->email) }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">{{ __('Phone') }}</label>
                                <div class="col-md-12">
                                    <input type="text" placeholder="{{ __('Input your phone number') }}" class="form-control form-control-line"> </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12" for="description">{{ __('Description') }}</label>
                                <div class="col-md-12">
                                    <textarea name="description" id="description" rows="3" class="form-control form-control-line" placeholder="{{ __('Say something about your-self...') }}">
                                        {{ old('description', $user->description) }}
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12">Select Country</label>
                                <div class="col-sm-12">
                                    <select class="form-control form-control-line">
                                        <option>London</option>
                                        <option>India</option>
                                        <option>Usa</option>
                                        <option>Canada</option>
                                        <option>Thailand</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success background-main-color border-none">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="change_pass">
                        <form class="form-horizontal form-material">
                            @csrf
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer" for="old_password">{{ _('Old Password') }}</label>
                                <div class="col-md-12">
                                    <input name="old_password" id="old_password" type="password" class="form-control form-control-line"
                                           placeholder="{{ __('Input your current password') }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer" for="new_password">{{ __('New Password') }}</label>
                                <div class="col-md-12">
                                    <input name="new_password" id="new_password" type="password" class="form-control form-control-line"
                                           placeholder="{{ __('Input your new password') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer" for="repeat_password">{{ __('Repeat Password') }}</label>
                                <div class="col-md-12">
                                    <input name="repeat_password" id="repeat_password" type="password" class="form-control form-control-line"
                                           placeholder="{{ __('Re-input your new password') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="col-sm-12">
                                <button class="btn btn-success background-main-color border-none">{{ __('Change password') }}</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection
@section('titlebar.title')
    {{ __('Profile') }}
@endsection
@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="/admin/users">{{ __('User') }}</a></li>
    <li class="active">{{ $user->name }}</li>
@endsection
@section('bottom.js')
    <script src="{{ asset('adminhtml/js/profile.js') }}"></script>
@endsection
