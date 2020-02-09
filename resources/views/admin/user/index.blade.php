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
    <link rel="stylesheet" href="{{ asset('adminhtml/css/slider/index.css') }}">
@endsection
@section('bottom.js')
{{--    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dataTables.checkboxes.min.js') }}"></script>--}}
<script>
    let invisibleCols = ['.data-cell-address', '.data-cell-dob', '.data-cell-description', '.data-cell-avatar'];
    let columnDefs = [],
        aoColumns = [
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'username',
                name: 'username'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'gender',
                name: 'gender'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'address',
                name: 'address'
            },
            {
                data: 'avatar',
                name: 'avatar'
            },
            {
                data: 'dob',
                name: 'dob'
            },
            {
                data: 'state',
                name: 'state'
            },
            {
                data: 'description',
                name: 'description'
            }
        ],
        colOrder = [[0, 'desc']];
    @cannot('canEditDelete', \App\User::class)
        aoColumns.push({
            data: 'task',
            sortable: false,
            orderable: false
        });
        columnDefs = [
            {
                targets: 'no-sort',
                orderable: false
            },
        ];
    @else
        @if (auth()->user()->isAdmin())
            aoColumns.push({
                data: 'role_id',
                name: 'role_id'
            });
    @endif
        aoColumns.push({
            data: 'task',
            sortable: false,
            orderable: false
        });
        columnDefs = [
        {
            targets: 'data-cell-id',
            className: 'no-visible-filter',
            createdCell: function (td, cellData, rowData, row, col) {
                $(td).attr('data-id', rowData.id);
                $(td).attr('scope', 'id');
            }
        },
            {
                targets: 'data-cell-username',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'username');
                }
            },
            {
                targets: 'data-cell-name',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'name');
                }
            },
            {
                targets: 'data-cell-email',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'email');
                }
            },
            {
                targets: 'data-cell-gender',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'gender');
                }
            },
            {
                targets: 'data-cell-phone',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'phone');
                }
            },
            {
                targets: 'data-cell-address',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'address');
                }
            },
            {
                targets: 'data-cell-avatar',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'avatar');
                }
            },
            {
                targets: 'data-cell-dob',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'dob');
                }
            },
            {
                targets: 'data-cell-status',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'status');
                }
            },
            {
                targets: 'data-cell-description',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'description');
                }
            },
            @if (auth()->user()->isAdmin())
            {
                targets: 'data-cell-role',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'role');
                }
            },
            @endif
            {
                targets: 'data-cell-task',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('not-selector', '');
                }
            },
            {
                targets: 0,
                width: '1%'
            },
            {
                targets: 2,
                width: '15%'
            },
            {
                targets: 'no-sort',
                orderable: false
            },
        ];
    @endcannot
</script>
<script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dt-buttons/buttons.flash.js') }}"></script>
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

    @can('canEditDelete', \App\User::class)
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
    @endcan

    <div class="row">

        <div class="col-md-12 table-responsive">
            <table id="users_ajax_dt" class="display nowrap dmovie-table"
                   cellspacing="0"
                   width="100%">
                <thead>
                    <tr>
                        <th class="data-cell-id">ID</th>
                        <th class="data-cell-username">{{ __('Username') }}</th>
                        <th class="data-cell-name">{{ __('Name') }}</th>
                        <th class="data-cell-email">{{ __('Email') }}</th>
                        <th class="data-cell-gender">{{ __('Gender') }}</th>
                        <th class="data-cell-phone">{{ __('Phone') }}</th>
                        <th class="data-cell-address">{{ __('Address') }}</th>
                        <th class="data-cell-avatar">{{ __('Avatar') }}</th>
                        <th class="data-cell-dob">{{ __('Date of Birth') }}</th>
                        <th class="data-cell-status">{{ __('Status') }}</th>
                        <th class="data-cell-description">{{ __('Description') }}</th>
                        @if (auth()->user()->isAdmin())
                            <th class="data-cell-id">{{ __('Role name') }}</th>
                        @endif
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
