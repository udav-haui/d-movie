@extends('admin.layouts.app')
@section('app.title'){{ $role->role_name . ' - ' . __('Edit') }}
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
    <script>
        window.view = '{{ __('View') }}';
        window.create = '{{ __('Create') }}';
        window.edit = '{{ __('Edit') }}';
        window.deleteText = '{{ __('Delete') }}';
        window.roleText = '{{ __('Roles manage') }}';
        window.userText = '{{ __('User manage') }}';
        window.checkedNodes = '{{ old('permissions', $permissionsString) }}'.length === 0 ? [] :
            '{{ old('permissions', $permissionsString) }}'.split(',');
        console.log(checkedNodes);
        window.permissionData = [{
            id: 0, text: '{{ __('Dmovie System') }}', expanded: true, spriteCssClass: "dmovie", items: [
                {
                    id: 'user', text: userText, expanded: true, spriteCssClass: "user-item", items: [
                        { id: 'user-view', text: view, spriteCssClass: 'view' },
                        { id: 'user-create', text: create, spriteCssClass: "create" },
                        { id: 'user-edit', text: edit, spriteCssClass: "edit" },
                        { id: 'user-delete', text: deleteText, spriteCssClass: "delete" }
                    ]
                },
                {
                    id: 'role', text: roleText, expanded: false, spriteCssClass: "role-item", enabled: false, items: [
                        { id: 'role-view', text: view, enabled: false,  spriteCssClass: "view"  },
                        { id: 'role-create', text: create, enabled: false, spriteCssClass: "create" },
                        { id: 'role-edit', text: edit, enabled: false, spriteCssClass: "edit" },
                        { id: 'role-delete', text: deleteText, enabled: false, spriteCssClass: "delete" }
                    ]
                },
            ]
        }]
    </script>
    <script src="{{ asset('adminhtml/js/role-create.js') }}"></script>
@endsection
@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/css/role-create.css') }}">
@endsection
@section('content')
    <div class="row bg-title">
        <div class="col-md-2 col-xs-12 pull-right">
            <a href="javascript:void(0);"
               onclick="event.preventDefault(); $('#create-form').submit();"
               class="btn btn-block btn-default dmovie-btn dmovie-btn-success">
                {{ __('Save') }}
            </a>
        </div>
        <div class="col-md-2 col-xs-12 pull-right">
            <a href="/admin/roles" class="btn btn-block btn-default waves-effect waves-light dmovie-btn">
                <i class="fa fa-chevron-left m-r-5"></i>
                <span>{{ __('Back') }}</span>
            </a>
        </div>
    </div>
    <div class="row">
        <form id="create-form"
              method="POST"
              action="{{ route('roles.update', ['role' => $role->id]) }}"
              class="col-md-12 form-horizontal">
            {{ method_field('PUT') }}
            @csrf
            <div class="form-group">
                <label for="role_name" class="col-sm-5 control-label">{{ __('Role name') }} <strong class="text-danger">*</strong></label>
                <div class="col-sm-2">
                    <input name="role_name"
                           type="text"
                           class="form-control @error('role_name') invalid @enderror"
                           id="role_name"
                           placeholder="{{ __('Role name') }}"
                           value="{{ old('role_name', $role->role_name) }}"/>
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
                    <div style="padding-top: 2em;">
                        <h4>Status</h4>
                        <p>{{ old('permissions') }}</p>
                        <p id="result">No nodes checked.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
