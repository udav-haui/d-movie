@extends('admin.layouts.app')
@section('app.title'){{ __('Assign user') }}
@endsection
@section('app.description')
    {{ __('Assign user') }}
@endsection
@section('titlebar.title')
    {{ __('Assign user') }}
@endsection
@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('roles.index') }}">{{ __('Roles Manage') }}</a></li>
    <li class="active">{{ __('Assign user') }}</li>
@endsection
@section('bottom.js')
{{--    <script src="{{ asset('adminhtml/assets/plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>--}}
    <script src="{{ asset('adminhtml/js/assign.js') }}"></script>
@endsection
@section('head.css')
{{--    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/bower_components/datatables/jquery.dataTables.min.css') }}">--}}
@endsection

@section('content')
    @can('create', \App\Role::class)
        <div class="row bg-title" id="dmovie-fix-top-block">
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="/admin/roles/create"
                   class="btn btn-block btn-default dmovie-btn dmovie-btn-success">
                    {{ __('Save') }}
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="{{ route('roles.index') }}"
                   class="btn btn-block btn-default waves-effect waves-light dmovie-btn"
                   type="button">
                    <span class="btn-label">
                        <i class="fa fa-chevron-left"></i>
                    </span>{{ __('Back') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="alert alert-danger alert-dismissable error-block display-none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

    </div>
    <div class="alert alert-success alert-dismissable success-block display-none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

    </div>
    <input class="lang-text display-none"
           swl-title-text="{{ __('Are you sure?') }}"
           swl-text-text="{{ __('This may affect to all user are being assigned. You not need to delete it, just edit.') }}"
           swl-icon-text="warning"
           swl-confirmButtonText="{{ __('Still delete it!') }}"
           swl-cancelButtonText="{{ __('Oke, I got it!') }}" />
    <div class="row">
        <form id="assign-form"
              class="col-md-12 form-horizontal"
              method="POST"
              action="{{ route('roles.doAssign') }}">
        @csrf
            <div class="form-group">
                <label for="user_id_select2" class="col-sm-5 control-label">{{ __('User') }} <strong class="text-danger">*</strong></label>
                <div class="col-sm-2">
                    <select id="user_id_select2" name="user_ids" class="form-control user_id_select2 @error('user_id') invalid @enderror">
                        <option>Select</option>
                        <optgroup label="Alaskan/Hawaiian Time Zone">
                            <option value="AK">Alaska</option>
                            <option value="HI">Hawaii</option>
                        </optgroup>
                        <optgroup label="Pacific Time Zone">
                            <option value="CA">California</option>
                            <option value="NV">Nevada</option>
                            <option value="OR">Oregon</option>
                            <option value="WA">Washington</option>
                        </optgroup>
                        <optgroup label="Mountain Time Zone">
                            <option value="AZ">Arizona</option>
                            <option value="CO">Colorado</option>
                            <option value="ID">Idaho</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NM">New Mexico</option>
                            <option value="ND">North Dakota</option>
                            <option value="UT">Utah</option>
                            <option value="WY">Wyoming</option>
                        </optgroup>
                        <optgroup label="Central Time Zone">
                            <option value="AL">Alabama</option>
                            <option value="AR">Arkansas</option>
                            <option value="IL">Illinois</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="OK">Oklahoma</option>
                            <option value="SD">South Dakota</option>
                            <option value="TX">Texas</option>
                            <option value="TN">Tennessee</option>
                            <option value="WI">Wisconsin</option>
                        </optgroup>
                        <optgroup label="Eastern Time Zone">
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="IN">Indiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="OH">Ohio</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WV">West Virginia</option>
                        </optgroup>
                    </select>
                    @error('user_id')
                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                    @enderror
                </div>
                <input id="permissions" type="hidden" name="permissions">
            </div>
        </form>
    </div>
@endsection
