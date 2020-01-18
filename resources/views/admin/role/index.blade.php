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
    <script src="{{ asset('adminhtml/assets/plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminhtml/js/role.js') }}"></script>
@endsection
@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/bower_components/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('content')
    <div class="row bg-title">
        <div class="col-md-2 col-xs-12 pull-right">
            <a href="/admin/roles/create"
               class="btn btn-block btn-default dmovie-btn dmovie-btn-success p-t-20 p-b-20">
                {{ __('New Role') }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="roles_data" class="display nowrap dmovie-table"
                   cellspacing="0" width="100%"
                   lengthMenu="{{ __('Display _MENU_ records per page') }}"
                   zeroRecords="{{ __('Nothing found - sorry') }}"
                   info="{{ __('Showing page _PAGE_ of _PAGES_') }}"
                   infoEmpty="{{ __('No records available') }}"
                   infoFiltered="{{ __('(filtered from _MAX_ total records)') }}"
                   search="{{ __('Search') }}">
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
                            <a type="button"
                               class="col-md-6 col-xs-12 btn dmovie-btn dmovie-btn-success"
                               title="{{ __('Edit') }}">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button type="button"
                                    class="col-md-6 col-xs-12 btn dmovie-btn btn-danger"
                                    title="{{ __('Delete') }}">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
