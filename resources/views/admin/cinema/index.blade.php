@extends('admin.layouts.app')

@section('app.title'){{ __('Cinemas Manage') }}
@endsection

@section('app.description'){{ __('Cinemas Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Cinemas Manage') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Cinemas Manage') }}
@endsection

@section('head.css')
@endsection

@section('head.js')
    <script src="{{ asset('adminhtml/assets/plugins/jszip/jszip.min.js') }}"></script>
@endsection


@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/ellipsis.js') }}"></script>
    <script>
        let columnDefs = [],
            colOrder = [[0, 'asc']],
            aoColumns = [
                {
                    data: 'id',
                    name: 'id'
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
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'province',
                    name: 'province'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'description',
                    name: 'description'
                }
            ],
            invisibleCols = ['.data-cell-description'];
        @cannot('canEditDelete', \App\Repositories\Interfaces\CinemaInterface::class)
            columnDefs = [
            {
                targets: '[class^="data-cell-"]',
                width: '1%',
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
                targets: 'data-cell-status',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'status');
                }
            },
            {
                targets: 'data-cell-name',
                render: $.fn.dataTable.render.ellipsis( 60, true )
            },
            {
                targets: 'data-cell-address',
                render: $.fn.dataTable.render.ellipsis( 60, true )
            },
            {
                targets: 'data-cell-description',
                render: $.fn.dataTable.render.ellipsis( 60, true )
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
        ];
        @endcannot
        columnDefs.push({
            targets: ['no-sort'],
            orderable: false
        });
    </script>
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dt-buttons/buttons.flash.js') }}"></script>
    <script src="{{ asset('adminhtml/js/cinema/index.js') }}"></script>
@endsection

@section('action_button')
    @can('create', \App\Repositories\Interfaces\CinemaInterface::class)
        <div class="navbar dmovie-fix-top-container">
            <div class="row bg-title" id="dmovie-fix-top-block">
                <a href="{{ route('cinemas.create') }}"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                    {{ __('New Cinema') }}
                </a>
            </div>
        </div>
    @endcan
@endsection

@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')


    @can('canEditDelete', App\Repositories\Interfaces\CinemaInterface::class)
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
                        @if (!auth()->user()->can('delete', App\Repositories\Interfaces\CinemaInterface::class) && !auth()->user()->can('update',
                        App\Repositories\Interfaces\CinemaInterface::class))
                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                        @endif
                        @can('delete', App\Repositories\Interfaces\CinemaInterface::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_delete-action"
                                   swl-text="{{ __('Do you want to destroy this selected cinema?') }}">
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                        @can('update', App\Repositories\Interfaces\CinemaInterface::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_change-status"
                                   swl-text="{{ __('Do you want change all status of selected cinemas?') }}"
                                   swl-state-alert-title="{{ __('Select status') }}"
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
            <table id="cinemas_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-name">{{ __('Name') }}</th>
                    <th class="data-cell-address">{{ __('Address') }}</th>
                    <th class="data-cell-province">{{ __('Province') }}</th>
                    <th class="data-cell-phone">{{ __('Phone') }}</th>
                    <th class="data-cell-description">{{ __('Description') }}</th>
                    @can('canEditDelete', \App\Repositories\Interfaces\CinemaInterface::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
