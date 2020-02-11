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
    <script>
        invisibleCols = [];
    </script>
   <script src="{{ asset('adminhtml/js/role/role.js') }}"></script>
@endsection
@section('head.css')

@endsection

@section('action_button')
    <div class="navbar dmovie-fix-top-container">
        @can('create', \App\Role::class)
        <div class="row bg-title" id="dmovie-fix-top-block">
            <a href="/admin/roles/create"
               class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                {{ __('New Role') }}
            </a>
            <a href="{{ route('roles.assignForm') }}"
               class="btn waves-effect waves-light dmovie-btn dmovie-btn-large m-r-40 pull-right"
               type="button">
                    <span class="btn-label">
                        <i class="fa fa-user-plus"></i>
                    </span>{{ __('Assign user') }}
            </a>
        </div>
    @endcan
    </div>
@endsection

@section('content')


    @include('admin.lang.global_text_lang')

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
