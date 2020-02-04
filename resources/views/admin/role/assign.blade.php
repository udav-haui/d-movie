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
<script src="{{ asset('adminhtml/assets/plugins/select2/i18n/' . Session::get('locale', config('app.locale')) . '.js') }}"></script>
@endsection
@section('head.css')
{{--    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/bower_components/datatables/jquery.dataTables.min.css') }}">--}}
@endsection

@section('content')
    @can('create', \App\Role::class)
        <div class="row bg-title" id="dmovie-fix-top-block">
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="javascript:void(0);"
                   class="btn btn-block btn-default dmovie-btn dmovie-btn-success"
                   onclick="event.preventDefault(); $('#assign-form').submit();">
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
{{--    Send data text to js file --}}
    <input type="hidden" class="lang-text"
           swl-title-text="{{ __('Are you sure?') }}"
           swl-text-text="{{ __('This may affect to all user are being assigned. You not need to delete it, just edit.') }}"
           swl-icon-warning-text="warning"
           swl-confirmButtonText="{{ __('Still delete it!') }}"
           swl-cancelButtonText="{{ __('Oke, I got it!') }}"
           placeholderUsersText="{{ __('Chose one or some users.') }}"
           placeholderRoleText="{{ __('Chose a role.') }}"
           unnamed="{{ __('Unnamed') }}"/>
{{--    ./ end send text --}}
    <div class="row">
        <form id="assign-form"
              class="col-md-12 form-horizontal"
              method="POST"
              action="{{ route('roles.doAssign') }}">
        @csrf
            <div class="form-group">
                <label for="user_id_select2" class="col-sm-5 control-label">{{ __('User') }} <strong class="text-danger">*</strong></label>
                <div class="col-sm-2">
                    <select id="user_id_select2" multiple name="user_ids[]" class="form-control user_id_select2 @error('user_ids') invalid @enderror">
                    </select>
                    @error('user_ids')
                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="role_select2" class="col-sm-5 control-label">{{ __('Role') }} <strong class="text-danger">*</strong></label>
                <div class="col-sm-2">
                    <select id="role_select2" oldRoleId="{{ old('role') }}" name="role" class="form-control @error('role') invalid @enderror"></select>
                    @error('role')
                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </form>
    </div>
@endsection
