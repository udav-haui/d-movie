@extends('admin.layouts.app')

@section('app.title'){{ __('Shows Manage') }}
@endsection

@section('app.description'){{ __('Shows Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Shows Manage') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Shows Manage') }}
@endsection

@section('head.css')
{{--    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/datatables/plugins/dataTables.checkboxes.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('adminhtml/css/show/index.css') }}">--}}
@endsection

@section('head.js')
    <script src="{{ asset('adminhtml/assets/plugins/jszip/jszip.min.js') }}"></script>
@endsection



@section('bottom.js')
    <script>
        let columnDefs = [],
            colOrder = [[0, 'asc']],
            aoColumns = [
                {
                    data: 'id',
                    name: 'id',
                    className: 'no-visible-filter'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'cinema.name',
                    name: 'cinema.name'
                },{
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
            ],
            invisibleCols = ['.data-cell-description', '.data-cell-created_at', '.data-cell-updated_at'];
        @can('view', \App\Seat::class)
            aoColumns.push({
                data: 'seats_list',
                sortable: false,
                orderable: false
            });
        @endcan
        @cannot('canEditDelete', \App\Show::class)
        columnDefs= [
            {
                targets: ['no-sort'],
                orderable: false
            },
        ];
        @else
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
                targets: 'data-cell-name',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'name');
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
                targets: 'data-cell-cinema',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.cinema.id);
                    $(td).attr('scope', 'cinema');
                }
            },
            {
                targets: 'data-cell-task',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('not-selector', '');
                }
            },
            {
                targets: '[class^="data-cell-"]',
                width: '1%',
            },
            {
                targets: ['no-sort'],
                orderable: false
            },
        ];
        @endcannot
    </script>
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dt-buttons/buttons.flash.js') }}"></script>

    <script src="{{ asset('adminhtml/js/show/index.js') }}"></script>
@endsection


@section('action_button')

    @can('create', \App\Show::class)
        <div class="navbar dmovie-fix-top-container">
            <div class="row bg-title" id="dmovie-fix-top-block">
                <a href="{{ route('shows.create') }}"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                    <i class="mdi mdi-film"></i>
                    {{ __('New show') }}
                </a>
            </div>
        </div>
    @endcan


@endsection


@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')

    @can('canEditDelete', \App\Show::class)
        <div class="row m-b-15">
            <div class="col-md-3 col-lg-2">
                <div class="btn-group width-100">
                    <button
                        aria-expanded="false"
                        data-toggle="dropdown"
                        class="btn btn-default btn-outline width-100
                    dropdown-toggle waves-effect waves-light border-radius-0 dmovie-textbox-border"
                        type="button"> {{ __('Action') }}
                        <span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu border-radius-0 dmovie-border width-100">
                        @if (!auth()->user()->can('delete', \App\Show::class) && !auth()->user()->can('update',
                        \App\Show::class))
                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                        @endif
                        @can('delete', \App\Show::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_delete-action"
                                   swl-text="{{ __('Do you want to destroy this selected shows?') }}">
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                        @can('update', \App\Show::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_change-status-action"
                                   swl-text="{{ __('Do you want change all status of this selected shows?') }}"
                                   swl-state-alert-title="{{ __('Select state') }}"
                                   swl-select-disable-item="{{ __('Disable') }}"
                                   swl-select-enable-item="{{ __('Enable') }}"
                                   swl-cancel-btn-text="{{ __('Cancel') }}" >
                                    {{ __('Set state') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </div>
            <div class="col-md-2 selected-rows-label-container">
                {{ __('Selected') }}&nbsp;<span class="selected-rows-label">0</span>&nbsp;{{ __('records') }}
            </div>
        </div>
    @endcan

    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="shows_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-name">{{ __('Name') }}</th>
                    <th class="data-cell-cinema">{{ __('Cinema Name') }}</th>
                    <th class="data-cell-created_at">{{ __('Create Time') }}</th>
                    <th class="data-cell-updated_at">{{ __('Update Time') }}</th>
                    @can('view', \App\Seat::class)
                        <th class="no-sort min-width-65 data-cell-seats_list">{{ __('Seats list') }}</th>
                    @endcan
                    @can('canEditDelete', \App\Show::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
