@extends('admin.layouts.app')

@section('app.title'){{ __('Combos Manage') }}
@endsection

@section('app.description'){{ __('Combos Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Combos Manage') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Combos Manage') }}
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
                    data: 'description',
                    name: 'description',
                    render: $.fn.dataTable.render.ellipsis( 50, true )
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'created_at',
                    name: 'created_at',

                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                }
            ],
            invisibleCols = ['.data-cell-description', '.data-cell-created_at', '.data-cell-updated_at'];
        @cannot('canEditDelete', \App\Combo::class)
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
                targets: 'data-cell-id',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'id');
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
                targets: 'data-cell-name',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'name');
                }
            },
            {
                targets: 'data-cell-description',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'description');
                }
            },
            {
                targets: 'data-cell-price',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'price');
                }
            },
            {
                targets: 'data-cell-created_at',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'created_at');
                }
            },
            {
                targets: 'data-cell-updated_at',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'updated_at');
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
        ];
        @endcannot
        columnDefs.push({
            targets: ['no-sort'],
            orderable: false
        });
    </script>
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dt-buttons/buttons.flash.js') }}"></script>
    <script src="{{ asset('adminhtml/js/combo/index.js') }}"></script>
@endsection

@section('action_button')
    @can('create', \App\Combo::class)
        <div class="navbar dmovie-fix-top-container">
            <div class="row bg-title" id="dmovie-fix-top-block">
                <a href="{{ route('combos.create') }}"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                    <i class="mdi mdi-popcorn"></i>
                    {{ __('New Combo') }}
                </a>
            </div>
        </div>
    @endcan
@endsection

@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')


    @can('canEditDelete', \App\Combo::class)
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
                        @if (!auth()->user()->can('delete', \App\Combo::class) && !auth()->user()->can('update',
                        \App\Combo::class))
                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                        @endif
                        @can('delete', \App\Combo::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_delete-action"
                                   swl-text="{{ __('Do you want to destroy this selected :name?', ['name' => __('combo')]) }}">
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                        @can('update', \App\Combo::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_change-status-action"
                                   swl-text="{{ __('Do you want change status of selected :name?', ['name' => __('combo')]) }}"
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
            <table id="combos_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-name">{{ __('Name') }}</th>
                    <th class="data-cell-description">{{ __('Description') }}</th>
                    <th class="data-cell-price">{{ __('Price') }}</th>
                    <th class="data-cell-created_at">{{ __('Create Time') }}</th>
                    <th class="data-cell-updated_at">{{ __('Modify Time') }}</th>
                    @can('canEditDelete', \App\Combo::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
