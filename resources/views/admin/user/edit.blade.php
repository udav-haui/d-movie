@extends('admin.layouts.app')

@section('app.title'){{ __('User Manage') }}
@endsection

@section('app.description'){{ __('User Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('users.index') }}">{{ __('User Manage') }}</a></li>
    <li class="active">{{ __('New user') }}</li>
@endsection

@section('titlebar.title')
    {{ __('New user') }}
@endsection

@section('head.css')
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/js/user/create.js') }}"></script>
@endsection

@section('content')
    <div class="row bg-title" id="dmovie-fix-top-block">
        @can('update', $user)
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="javascript:void(0);"
                   onclick="event.preventDefault(); $('#create-form').submit();"
                   class="btn btn-block btn-default dmovie-btn dmovie-btn-success">
                    {{ __('Save') }}
                </a>
            </div>
        @endcan
        <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
            <a href="{{ route('users.index') }}"
               class="btn btn-block btn-default dmovie-btn dmovie-btn-default">
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="row">
        <form id="create-form"
              method="POST"
              action="{{ route('users.manageUpdate', ['user' => $user]) }}"
              class="col-md-12 form-horizontal">
            {{ method_field('PUT') }}
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ __('Edit') }} - {!! $user->getName() !!}</div>
                        <div class="panel-body">
                            <h3 class="box-title">{{ __('Account info') }}</h3>
                            <hr class="m-t-0 m-b-30">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-xs-12 cursor-pointer" for="username">
                                        {{ __('Username') }}
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <div class="col-md-9 col-xs-12">
                                        <input id="username" type="text" placeholder="{{ __('Input your username') }}"
                                               class="form-control dmovie-textbox-border @error('username') invalid @enderror"
                                               @cannot('update', $user) disabled @endcannot
                                               value="{{ old('username', $user->username) }}" name="username"
                                               autofocus />
                                        <span class="help-block">{{ __('Username can edit for one times') }}</span>
                                        @error('username')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="control-label col-md-3 col-xs-12">{{ __('Email') }}
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="email" placeholder="{{ __('Input your E-Mail') }}"
                                               class="form-control dmovie-textbox-border
                                               @error('email') invalid @enderror"
                                               @cannot('update', $user) disabled @endcannot
                                               name="email"
                                               id="example-email" value="{{ old('email', $user->email) }}" />
                                        @error('email')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-xs-12 cursor-pointer" for="password">
                                        {{ __('Password') }}
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <div class="col-md-6 col-xs-12">
                                        <input id="password" type="password" placeholder="{{ __('Input user password') }}"
                                               class="form-control dmovie-textbox-border
                                               @error('password') invalid @enderror"
                                               value="{{ old('password') }}" name="password"
                                               autocomplete="password"
                                               autofocus @if (!$errors->has('password')) disabled @endif />
                                        <span class="help-block">{{ __('Input a strong password') }}</span>
                                        @error('password')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <div class="checkbox checkbox-custom">
                                            <input name="changePass" id="changePass" value="1" type="checkbox"
                                                   @cannot('update', $user) disabled @endcannot
                                                    @if ($errors->has('password')) checked @endif/>
                                            <label for="changePass"
                                                   class="select-none"> {{ __('Change password') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @can('viewAny', \App\Role::class)
                                    <div class="form-group">
                                        <label for="role" class="control-label col-md-3 col-xs-12">
                                            {{ __('Assign role') }}
                                        </label>
                                        <div class="col-md-9 col-xs-12">
                                            <select name="role" id="role"
                                                    class="role-selector bs-select-hidden"
                                                    data-style="form-control" @cannot('update', $user) disabled @endcannot>
                                                <option value="0" {{ old('role') ? '' : 'selected' }}>
                                                    {{ __('Not now') }}
                                                </option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                            @if ($user->role_id === $role->id) selected @endif>
                                                        {{ $role->role_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endcan
                            </div>

                            <h3 class="col-md-12 box-title">{{ __('User info') }}</h3>
                            <hr class="col-md-12 m-t-0 m-b-30">

                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="col-xs-12 col-md-3 control-label"
                                           for="address">{{ __('Address') }}
                                    </label>
                                    <div class="col-xs-12 col-md-9">
                                        <input name="address" id="address" type="text"
                                               class="form-control dmovie-textbox-border
                                               @error('address') invalid @enderror"
                                               placeholder="{{ __('Provide your address...') }}"
                                               value="{{ old('address', $user->address) }}"
                                               @cannot('update', $user) disabled @endcannot />
                                        @error('address')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-xs-12" for="name">{{ __('Full Name') }}
                                    </label>
                                    <div class="col-md-9 col-xs-12">
                                        <input type="text" placeholder="{{ __('Input your name') }}"
                                               class="form-control dmovie-textbox-border
                                               @error('name') invalid @enderror"
                                               value="{{ old('name', $user->name) }}" name="name"
                                               @cannot('update', $user) disabled @endcannot />
                                        @error('name')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label"
                                           for="dob-datepicker">{{ __('Date of birth') }}
                                    </label>
                                    <div class="col-xs-12 col-md-9">
                                        <div class="input-group">
                                            <input name="dob" type="text"
                                                   class="form-control dmovie-textbox-border
                                                   @error('dob') invalid @enderror"
                                                   lang="{{ \Session::get('locale', config('app.locale')) }}"
                                                   id="dob-datepicker"
                                                   placeholder="dd/mm/yyyy"
                                                   value="{{ old('dob', $user->getDob()) }}"
                                                   @cannot('update', $user) disabled @endcannot />
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                        @error('dob')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-md-3"
                                           for="gender">{{ __('Gender') }}
                                    </label>
                                    <div class="col-md-9 col-xs-12">
                                        <select name="gender" id="gender"
                                                class="gender-selector bs-select-hidden"
                                                data-style="form-control"
                                                @cannot('update', $user) disabled @endcannot>
                                            <option value="-1" {{ old('gender', $user->gender) ? '' : 'selected' }}>
                                                {{ __('Select your gender') }}
                                            </option>
                                            <option value="0" {{ old('gender', $user->gender) != 0 ? '' : 'selected' }}>{{ __('Male') }}</option>
                                            <option value="1" {{ old('gender', $user->gender) != 1 ? '' : 'selected' }}>{{ __('Female') }}</option>
                                            <option value="2" {{ old('gender', $user->gender) != 2 ? '' : 'selected' }}>{{ __('Other') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label" for="phone">{{ __('Phone') }}
                                    </label>
                                    <div class="col-md-9 col-xs-12">
                                        <input name="phone" type="text" placeholder="{{ __('Input your phone number') }}"
                                               class="form-control dmovie-textbox-border
                                               @error('phone') invalid @enderror"
                                               value="{{ old('phone', $user->phone) }}"
                                               @cannot('update', $user) disabled @endcannot />
                                        @error('phone')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label"
                                           for="description">{{ __('Description') }}
                                    </label>
                                    <div class="col-md-9 col-xs-12">
                                            <textarea name="description" id="description" rows="3"
                                                      class="form-control dmovie-textbox-border
                                                      @error('description') invalid @enderror"
                                                      @cannot('update', $user) disabled @endcannot
                                                      placeholder="{{ __('Say something about your-self...') }}">{{ old('description', $user->description) }}</textarea>
                                        @error('description')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection