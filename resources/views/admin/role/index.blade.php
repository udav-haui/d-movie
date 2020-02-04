@extends('admin.layouts.app')
@section('app.title'){{ __('Roles Manage') }}
@endsection
@section('app.description')
    {{ __('Roles Manage') }}
@endsection
@section('titlebar.title')
    {{ __('Roles Manage') }}
@endsection
@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Roles Manage') }}</li>
@endsection
@section('bottom.js')
   <script src="{{ asset('adminhtml/js/role.js') }}"></script>
@endsection
@section('head.css')
@endsection

@section('content')
    @can('create', \App\Role::class)
        <div class="row bg-title" id="dmovie-fix-top-block">
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="/admin/roles/create"
                   class="btn btn-block btn-default dmovie-btn dmovie-btn-success">
                    {{ __('New Role') }}
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="{{ route('roles.assignForm') }}"
                   class="btn btn-block btn-default waves-effect waves-light dmovie-btn"
                   type="button">
                    <span class="btn-label">
                        <i class="fa fa-user-plus"></i>
                    </span>{{ __('Assign user') }}
                </a>
            </div>
        </div>
    @endcan
    <input class="lang-text display-none"
           main-lang="{{ str_replace('_', '-', app()->getLocale()) }}"
           swl-title-text="{{ __('Are you sure?') }}"
           swl-text-text="{{ __('This may affect to all user are being assigned. You not need to delete it, just edit.') }}"
           swl-icon-warning-text="warning"
           swl-confirmButtonText="{{ __('Still delete it!') }}"
           swl-cancelButtonText="{{ __('Oke, I got it!') }}" />
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="roles_data" class="display nowrap dmovie-table"
                   cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Role name') }}</th>
                    <th class="no-sort">{{ __('Task') }}</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>{{ __('Role name') }}</th>
                    <th>{{ __('Task') }}</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td scope="id">{{ $role->id }}</td>
                        <td scope="name">{{ $role->role_name }}</td>
                        <td scope="task">
                            @can('update', \App\Role::class)
                                <a href="{{ route('roles.edit', ['role' => $role->id]) }}"
                                    type="button"
                                   class="col-md-6 col-xs-12 btn dmovie-btn dmovie-btn-success"
                                   title="{{ __('Edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @endcan
                            @can('delete', \App\Role::class)
                                <button id="deleteRoleBtn" type="button"
                                        class="col-md-6 col-xs-12 btn dmovie-btn btn-danger"
                                        title="{{ __('Delete') }}"
                                        data-id="{{ $role->id }}"
                                        url="{{ route('roles.destroy', ['role' => $role->id]) }}">
                                    <i class="fa fa-times"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
