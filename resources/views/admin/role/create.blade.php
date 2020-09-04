@extends('admin.layouts.app')
@section('app.title'){{ __('New Role') }}
@endsection
@section('app.description')
    {{ __('New Role') }}
@endsection

@section('titlebar.title')
    {{ __('New Role') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="/admin/roles">{{ __('Roles Manage') }}</a></li>
    <li class="active">{{ __('New Role') }}</li>
@endsection

@section('bottom.js')
    @include('admin.role.role_init')
    <script src="{{ asset('adminhtml/assets/plugins/tree-view/tree-view.js') }}"></script>
    <script src="{{ asset('adminhtml/js/role/role-create.js') }}"></script>
@endsection

@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/tree-view/tree-view.css') }}">
    <link rel="stylesheet" href="{{ asset('adminhtml/css/role/role-create.css') }}">
@endsection

@section('head.js')
@endsection

@section('action_button')
    <div class="navbar dmovie-fix-top-container">
        <div class="row bg-title" id="dmovie-fix-top-block">
        <a href="javascript:void(0);"
           onclick="event.preventDefault(); $('#create-form').submit();"
           class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
            {{ __('Save') }}
        </a>
        <a href="{{ route('roles.index') }}"
           class="btn waves-effect waves-light dmovie-btn dmovie-btn-large m-r-40 pull-right">
            <i class="fa fa-chevron-left m-r-5"></i>
            <span>{{ __('Back') }}</span>
        </a>
    </div>
    </div>

    @endsection

@section('content')


    <div class="row">
        <form id="create-form"
              method="POST"
              action="{{ route('roles.store') }}"
              class="col-md-12 form-horizontal">
            @csrf
            <div class="form-group">
                <label for="role_name" class="col-sm-5 control-label">{{ __('Role name') }} <strong class="text-danger">*</strong></label>
                <div class="col-sm-2">
                    <input name="role_name"
                           type="text"
                           class="form-control @error('role_name') invalid @enderror dmovie-textbox-border"
                           id="role_name"
                           value="{{ old('role_name') }}"
                           placeholder="{{ __('Role name') }}" />
                    @error('role_name')
                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                    @enderror
                </div>
                <input id="permissions" type="hidden" name="permissions">
            </div>
            <div class="form-group">
                <label for="roles" class="col-sm-5 control-label">{{ __('Permissions') }}</label>
                <div class="col-sm-5">
                    <div>
                        <div id="permission-tree"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
