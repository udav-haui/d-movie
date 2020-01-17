@extends('admin.layouts.app')
@section('app.title')
    {{ __('Profile - :name', ['name' => $user->name]) }}
@endsection
@section('app.description')
    {{ __(':name\'s Profile', ['name' => $user->name]) }}
@endsection
@section('content')
    <!-- /.row -->
    <!-- .row -->
    <div class="row">
        <div class="@can('update', $user) col-md-4 col-xs-12 @else col-md-12 col-xs-12 @endcan">
            <div class="white-box">
                <div class="user-bg"> <img width="100%" alt="user" src="{{ asset('images/icons/wall.jpg') }}">
                    <div class="overlay-box">
                        <div class="user-content">
                            <a href="javascript:void(0)">
                                <img src="{{ $user->getAvatar() ?? asset('images/icons/account.png') }}" class="thumb-lg img-circle" alt="img">
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
                    @if (session('error'))
                        <div class="alert alert-danger text-center">{{ session('error') }}</div>
                    @endif
                </div>
            </div>
        </div>
        @can('update', $user)
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
                    <li class="tab">
                        <a href="#change_avatar" data-toggle="tab" aria-expanded="false">
                            <span class="visible-xs"><i class="fa fa-user"></i></span>
                            <span class="hidden-xs">{{ __('Avatar') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="settings">
                        <form class="form-horizontal form-material" id="user_data_form"
                              method="POST"
                              action="{{ route('user.update', ['user' => $user]) }}">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12" for="username">
                                            {{ __('Username') }}
                                            <strong class="text-danger small">({{ __('Username can edit for one times') }})</strong>
                                        </label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="{{ __('Input your username') }}"
                                                   class="form-control form-control-line @error('username') invalid @enderror @if($user->can_change_username == 0) disabled @endif"
                                                   value="{{ old('username', $user->username) }}" name="username"
                                                   {{ $user->can_change_username == '1' ? '' : 'disabled' }} />
                                            @error('username')
                                                <span class="error text-danger">{{ $message }}</span>
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
                                            <span class="error text-danger">{{ $message }}</span>
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
                                                <span class="error text-danger">{{ $message }}</span>
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
                                            <span class="error text-danger">{{ $message }}</span>
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
                                            <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-12" for="gender">{{ __('Gender') }}</label>
                                        <select name="gender" id="gender" class="col-md-12 gender-selector bs-select-hidden" data-style="form-control">
                                            <option value="-1" {{ old('gender', $user->gender) ? '' : 'selected' }}>{{ __('Select your gender') }}</option>
                                            <option value="0" {{ old('gender', $user->gender) != 0 ? '' : 'selected' }}>{{ __('Male') }}</option>
                                            <option value="1" {{ old('gender', $user->gender) != 1 ? '' : 'selected' }}>{{ __('Female') }}</option>
                                            <option value="2" {{ old('gender', $user->gender) != 2 ? '' : 'selected' }}>{{ __('Other') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group cold-md-12">
                                        <label class="col-md-12" for="dob-datepicker-autoclose">{{ __('Date of birth') }} <strong class="text-danger">*</strong></label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <input name="dob" type="text" class="form-control @error('dob') invalid @enderror"
                                                    lang="{{ \Session::get('locale', config('app.locale')) }}"
                                                    id="dob-datepicker-autoclose"
                                                    placeholder="dd/mm/yyyy" value="{{ old('dob', $user->getDob()) }}"/>
                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                            </div>
                                            @error('dob')
                                            <span class="error text-danger">{{ $message }}</span>
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
                                            <span class="error text-danger">{{ $message }}</span>
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
                    <div class="tab-pane" id="change_pass">
                        <form class="form-horizontal form-material">
                            @csrf
                            @if (!auth()->user()->isAdmin() || (auth()->user()->isAdmin() && auth()->user()->id == $user->id))
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer"
                                       for="current_password">
                                    {{ __('Current Password') }}
                                </label>
                                <div class="col-md-12">
                                    <input name="current_password" id="current_password" type="password" class="form-control form-control-line"
                                           placeholder="{{ __('Input your current password') }}"
                                           autocomplete="current_password" />
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer" for="new_password">{{ __('New Password') }}</label>
                                <div class="col-md-12">
                                    <input name="new_password" id="new_password" type="password" class="form-control form-control-line"
                                           placeholder="{{ __('Input your new password') }}"
                                           autocomplete="new_password" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 cursor-pointer" for="repeat_password">{{ __('Repeat Password') }}</label>
                                <div class="col-md-12">
                                    <input name="repeat_password" id="repeat_password" type="password" class="form-control form-control-line"
                                           placeholder="{{ __('Re-input your new password') }}" autocomplete="repeat_password"/>
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="col-sm-12">
                                <button class="btn btn-success background-main-color border-none">{{ __('Change password') }}</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="change_avatar">
                        <form class="form-horizontal form-material"
                              method="POST"
                              action="{{ route('user.setAvatar', ['user' => $user->id]) }}"
                              enctype="multipart/form-data">
                            {{ method_field('PUT') }}
                            @csrf
                            <div class="form-group">
                                <div class="white-box">
                                    <h3 class="box-title">{{ __('Upload avatar') }}</h3>
                                    <input name="avatar" type="file"
                                           class="avatar-dropify"
                                           data-height="350"
                                           required
                                           msg-default="{{ __('Drag and drop a file here or click') }}"
                                           msg-replace="{{ __('Drag and drop or click to replace') }}"
                                           msg-remove="{{ __('Remove') }}"
                                           msg-error="{{ __('Ooops, something wrong appended.') }}" />
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
@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/css/profile.css') }}">
@endsection
