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

    <script src="{{ asset('adminhtml/assets/plugins/tree-view/tree-view.js') }}"></script>
    @include('admin.role.role_init')

    <script src="{{ asset('adminhtml/js/role/role-create.js') }}"></script>
@endsection
@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('adminhtml/css/role/role-create.css') }}">
@endsection


@section('action_button')

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

@endsection



@section('content')


    <form id="create-form"
          method="POST"
          action="{{ route('roles.update', ['role' => $role->id]) }}"
          class="col-md-12 form-horizontal">
        <div class="row">
            {{ method_field('PUT') }}
            @csrf
            <div class="form-group">
                <label for="role_name" class="col-sm-5 control-label">{{ __('Role name') }} <strong class="text-danger">*</strong></label>
                <div class="col-sm-2">
                    <input name="role_name"
                           type="text"
                           class="form-control dmovie-textbox-border @error('role_name') invalid @enderror"
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
                </div>
            </div>

        </div>
        <div class="row">
            <?php $usersSelect = old('users', $relatedUsers) ?? [] ?>
            <select name="users[]" multiple id="users_select" class="col-md-12 display-none">
                @foreach($usersSelect as $user)
                    <option scope="users" value="{{ $user }}" selected>{{ $user }}</option>
                    @endforeach
            </select>
        </div>
    </form>

    <input class="lang-text display-none"
           main-lang="{{ str_replace('_', '-', app()->getLocale()) }}"/>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="users_data" class="display nowrap dmovie-table"
                   cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th>#</th>
                    <th>{{ __('Username') }}</th>
                    <th>{{ __('Full Name') }}</th>
                    <th>{{ __('Email') }}</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>{{ __('Username') }}</th>
                    <th>{{ __('Full Name') }}</th>
                    <th>{{ __('Email') }}</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td scope="checkbox">
                            <div class="dmovie-checkbox dmovie-checkbox-custom">
                                <input data-id="{{ $user->id }}" id="checkbox-{{ $user->id }}" type="checkbox"
                                       class="display-none user-checkbox" @if (in_array($user->id, $usersSelect)) checked="" @endif>
                                <label for="checkbox-{{ $user->id }}" class="cursor-pointer"></label>
                            </div>
                        </td>
                        <td scope="id">{{ $user->id }}</td>
                        <td scope="username">{{ $user->username ?? __('Not updated!') }}</td>
                        <td scope="username">{{ $user->name ?? __('Not updated!') }}</td>
                        <td scope="username">{{ $user->email ?? __('Not updated!') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
