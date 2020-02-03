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

    @include('admin.lang.global_text_lang')

    <div class="row m-b-15">
        <div class="col-md-1">
            <div class="btn-group">
                <button
                    aria-expanded="false"
                    data-toggle="dropdown"
                    class="btn btn-default btn-outline
                    dropdown-toggle waves-effect waves-light border-radius-0 dmovie-textbox-border"
                    type="button"> {{ __('Action') }}
                    <span class="caret"></span>
                </button>
                <ul role="menu" class="dropdown-menu border-radius-0 dmovie-border">
                    @if (!auth()->user()->can('delete', \App\User::class) && !auth()->user()->can('update',
                    \App\User::class))
                        <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                    @endif
                    @can('delete', \App\User::class)
                        <li>
                            <a href="javascript:void(0);"
                               class="_delete-users"
                               swl-text="{{ __('Do you want to destroy this user?') }}">
                                {{ __('Delete') }}
                            </a>
                        </li>
                    @endcan
                    @can('update', \App\User::class)
                        <li>
                            <a href="javascript:void(0);"
                               class="_change-state-users"
                               swl-text="{{ __('Do you want change all state of this users?') }}"
                               swl-state-alert-title="{{ __('Select state') }}"
                               swl-select-not-active-item="{{ __('Not active') }}"
                               swl-select-not-verify-item="{{ __('Not verify') }}"
                               swl-select-active-item="{{ __('Active') }}"
                               swl-cancel-btn-text="{{ __('Cancel') }}" >
                                {{ __('Set state') }}
                            </a>
                        </li>
                    @endcan
                    @if (auth()->user()->isAdmin())
                        <li>
                            <a href="javascript:void(0);"
                               class="_assign-role-users"
                               swl-text="{{ __('Do you want assign a role to selected users?') }}"
                               sl2-placeholder="{{ __('Select role') }}" unnamed="{{ __('Unnamed') }}">
                                {{ __('Set role') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-md-2 selected-rows-label-container m-l-10">
            {{ __('Selected') }}&nbsp;<span class="selected-rows-label">0</span>&nbsp;{{ __('rows') }}
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
                    @if (auth()->user()->isAdmin())
                        <th>{{ __('Role name') }}</th>
                    @endif
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
                    @if (auth()->user()->isAdmin())
                        <th>{{ __('Role name') }}</th>
                    @endif
                    <th>{{ __('Status') }}</th>
                    <th class="no-sort">{{ __('Task') }}</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td scope="checkbox">
                            @if (auth()->user()->getAuthIdentifier() !== $user->getAuthIdentifier())
                                <div class="dmovie-checkbox dmovie-checkbox-custom">
                                    <input value="{{ $user->id }}" id="checkbox-{{ $user->id }}" type="checkbox"
                                           class="display-none user-checkbox"
                                           grid-item-checkbox>
                                    <label for="checkbox-{{ $user->id }}" class="cursor-pointer"></label>
                                </div>
                            @endif
                        </td>
                        <td scope="id">{{ $user->id }}</td>
                        <td scope="username">{!! $user->getUsername()  !!}</td>
                        <td scope="name">{!! $user->getName() !!}</td>
                        <td scope="email">{{ $user->email }}</td>
                        @if (auth()->user()->isAdmin())
                            <td scope="role">{{ $user->getRoleName() }}</td>
                        @endif
                        <td scope="status">
                            <span class="status-text">
                                {!! __($user->getStatus()) !!}
                            </span>
                           @can('cannotSelfUpdate', $user)
                                <i class="ti-reload"
                                   data-id="{{ $user->state }}"
                                   user-id="{{ $user->id }}"
                                   cancel-text="{{ __('Cancel') }}"
                                   onclick="changeStatus(this, '{{ $user->id }}', '{{__('Select state')}}', '{{ __('Not active') }}',
                                       '{{ __('Not verify') }}', '{{ __('Active') }}');"
                                   title="{{ __('Change status') }}"
                                   scope="change-state"></i>
                            @endcan
                        </td>
                        <td scope="task">
                            @can('view', $user)
                                <a href="{{ route('users.edit', ['user' => $user->id]) }}"
                                   type="button"
                                   class=" @if (auth()->user()->id === $user->id || !auth()->user()->can('delete', $user)) col-md-12 @else col-md-6 @endif
                                   col-xs-12 btn dmovie-btn dmovie-btn-success"
                                   title="{{ __('Detail') }}">
                                    <i class="mdi mdi-account-edit"></i>
                                </a>
                            @endcan
                            @can('delete', $user)
                                @if (auth()->user()->id !== $user->id)
                                    <button id="deleteUserBtn" type="button"
                                            class="col-md-6 col-xs-12 btn dmovie-btn btn-danger"
                                            title="{{ __('Delete') }}"
                                            data-id="{{ $user->id }}"
                                            url="{{ route('users.destroy', ['user' => $user->id]) }}">
                                        <i class="mdi mdi-account-minus"></i>
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
