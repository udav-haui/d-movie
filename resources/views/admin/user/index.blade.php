@extends('admin.layouts.app')

@section('app.title'){{ __('User Manage') }}
@endsection
@section('app.description'){{ __('User Manage') }}
@endsection
@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('User Manage') }}</li>
@endsection
@section('titlebar.title')
    {{ __('User Manage') }}
    @endsection
@section('head.css')
{{--    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/datatables/plugins/dataTables.checkboxes.css') }}">--}}
@endsection
@section('bottom.js')
{{--    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dataTables.checkboxes.min.js') }}"></script>--}}
    <script src="{{ asset('adminhtml/js/user/index.js') }}"></script>
@endsection
@section('content')
    @can('create', \App\User::class)
        <div class="row bg-title" id="dmovie-fix-top-block">
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="{{ route('users.create') }}"
                   class="btn btn-block btn-default dmovie-btn dmovie-btn-success">
                    {{ __('New user') }}
                </a>
            </div>
        </div>
    @endcan
    <input class="lang-text display-none"
           main-lang="{{ str_replace('_', '-', app()->getLocale()) }}"
           swl-title-text="{{ __('Are you sure?') }}"
           swl-text-text="{{ __('Do you want to destroy this user?') }}"
           swl-icon-text="warning"
           swl-confirmButtonText="{{ __('Ok') }}"
           swl-cancelButtonText="{{ __('Cancel') }}" users-deleted="{{ __('Deleted: ') }}"/>
    <div class="row m-b-15">
        <div class="col-md-12">
            <div class="btn-group">
                <button
                    aria-expanded="false"
                    data-toggle="dropdown"
                    class="btn btn-default btn-outline dropdown-toggle waves-effect waves-light border-radius-0"
                    type="button"> {{ __('Action') }}
                    <span class="caret"></span>
                </button>
                <ul role="menu" class="dropdown-menu border-radius-0">
                    <li><a href="javascript:void(0);"
                           class="_delete-users"
                           swl-title="{{ __('Do you want to destroy this user?') }}">{{ __('Delete') }}</a></li>
                    <li><a href="javascript:void(0);">{{ __('Set state') }}</a></li>
                    <li><a href="javascript:void(0);">{{ __('Set role') }}</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="users_data" class="display nowrap dmovie-table"
                   cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th class="no-sort">
                        <div class="dmovie-checkbox dmovie-checkbox-custom">
                            <input value="0" id="checkbox-all" type="checkbox"
                                   class="display-none user-checkbox">
                            <label for="checkbox-all" class="cursor-pointer background-fff"></label>
                        </div>
                    </th>
                    <th>#</th>
                    <th>{{ __('Username') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role name') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="no-sort">{{ __('Task') }}</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>{{ __('Username') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role name') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="no-sort">{{ __('Task') }}</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td scope="checkbox">
                            <div class="dmovie-checkbox dmovie-checkbox-custom">
                                <input value="{{ $user->id }}" id="checkbox-{{ $user->id }}" type="checkbox"
                                       class="display-none user-checkbox">
                                <label for="checkbox-{{ $user->id }}" class="cursor-pointer"></label>
                            </div>
                        </td>
                        <td scope="id">{{ $user->id }}</td>
                        <td scope="username">{{ $user->username }}</td>
                        <td scope="name">{!! $user->getName() !!}</td>
                        <td scope="email">{{ $user->email }}</td>
                        <td scope="name">{{ $user->getRoleName() }}</td>
                        <td scope="status">
                            <span class="status-text">
                                {!! __($user->getStatus()) !!}
                            </span>
                           @can('cannotSelfUpdate', $user)
                                <i class="ti-reload"
                                   data-id="{{ $user->state }}"
                                   cancel-text="{{ __('Cancel') }}"
                                   onclick="changeStatus(this, '{{ $user->id }}', '{{__('Select state')}}', '{{ __('Not active') }}',
                                       '{{ __('Not verify') }}', '{{ __('Active') }}');"
                                   title="{{ __('Change status') }}"></i>
                            @endcan
                        </td>
                        <td scope="task">
                            @can('view', $user)
                                <a href="{{ route('users.edit', ['user' => $user->id]) }}"
                                   type="button"
                                   class="col-md-6 col-xs-12 btn dmovie-btn dmovie-btn-success"
                                   title="{{ __('Detail') }}">
                                    <i class="mdi mdi-account-edit"></i>
                                </a>
                            @endcan
                            @can('delete', $user)
                                <button id="deleteUserBtn" type="button"
                                        class="col-md-6 col-xs-12 btn dmovie-btn btn-danger"
                                        title="{{ __('Delete') }}"
                                        data-id="{{ $user->id }}"
                                        url="{{ route('users.destroy', ['user' => $user->id]) }}">
                                    <i class="mdi mdi-account-minus"></i>
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
