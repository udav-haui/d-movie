@extends('admin.layouts.app')

@section('app.title'){{ __('Slider Manage') }}
@endsection

@section('app.description'){{ __('Slider Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Slider Manage') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Slider Manage') }}
@endsection

@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/datatables/plugins/dataTables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('adminhtml/css/slider/index.css') }}">
@endsection

@section('head.js')
    <script src="{{ asset('adminhtml/assets/plugins/jszip/jszip.min.js') }}"></script>
    @endsection



@section('bottom.js')
    <script>
        let columnDefs = [],
            colOrder = [],
            aoColumns = [],
            invisibleCols = [];
    @cannot('canEditDelete', \App\Slider::class)
        aoColumns = [
        {
            data: 'id',
            name: 'id',
            className: 'no-visible-filter'
        },
        {
            data: 'title',
            name: 'title'
        },
        {
            data: 'image',
            name: 'image',
            orderable: false,
            sortable: false
        },
        {
            data: 'href',
            name: 'href'
        },
        {
            data: 'status',
            name: 'status'
        },
        {
            data: 'order',
            name: 'order'
        }
    ];

        columnDefs= [
            {
                targets: ['no-sort'],
                orderable: false
            },
        ];
        colOrder = [[0, 'asc']];
    @else
        aoColumns = [
        {
            data: 'id',
            name: 'id',
            className: 'no-visible-filter'
        },
        {
            data: 'title',
            name: 'title',
            width: '10%'
        },
        {
            data: 'image',
            name: 'image',
            orderable: false,
            sortable: false
        },
        {
            data: 'href',
            name: 'href'
        },
        {
            data: 'status',
            name: 'status'
        },
        {
            data: 'order',
            name: 'order'
        },
        {
            data: 'task',
            sortable: false,
            orderable: false
        }
    ];
        columnDefs = [
            {
                targets: 'data-cell-id',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'id');
                }
            },
            {
                targets: 'data-cell-title',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'title');
                }
            },
            {
                targets: 'data-cell-order',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'order');
                }
            },
            {
                targets: 'data-cell-href',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'href');
                }
            },
            {
                targets: 'data-cell-status',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'status');
                    $(td).attr('not-selector', '');
                }
            },
            {
                targets: 'data-cell-image',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'image');
                }
            },
            {
                targets: 'data-cell-task',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('not-selector', '');
                }
            },
            {
                targets: ['data-cell-image', 'data-cell-checkbox', 'data-cell-status'],
                width: '1%',
            },
            {
                targets: ['no-sort'],
                orderable: false
            },
        ];
        colOrder = [[0, 'asc']];
    @endcannot
    </script>
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dt-buttons/buttons.flash.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dataTables.checkboxes.min.js') }}"></script>
    <script src="{{ asset('adminhtml/js/slider/index.js') }}"></script>
@endsection


@section('action_button')

    @can('create', \App\Slider::class)
        <div class="navbar dmovie-fix-top-container">
            <div class="row bg-title" id="dmovie-fix-top-block">
                <a href="{{ route('sliders.create') }}"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                    <i class="mdi mdi-shape-square-plus"></i>
                    {{ __('New item') }}
                </a>
            </div>
        </div>
    @endcan


    @endsection


@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')

    @can('canEditDelete', \App\Slider::class)
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
                        @if (!auth()->user()->can('delete', \App\Slider::class) && !auth()->user()->can('update',
                        \App\Slider::class))
                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                        @endif
                        @can('delete', \App\Slider::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_delete-sliders"
                                   swl-text="{{ __('Do you want to destroy this selected slide items?') }}">
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                        @can('update', \App\Slider::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_change-status-sliders"
                                   swl-text="{{ __('Do you want change all status of this slide items?') }}"
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
            <table id="sliders_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-title">{{ __('Title') }}</th>
                    <th class="data-cell-image">{{ __('Image') }}</th>
                    <th class="data-cell-href">{{ __('Href') }}</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-order">{{ __('Order') }}</th>
                    @can('canEditDelete', \App\Slider::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>


            <button id="btn-test" data-toggle="tooltip" >Test</button>

            <button class="dt-button buttons-copy buttons-html5 dmovie-border margin-0-auto" onclick="test();" tabindex="0" aria-controls="sliders_ajax_dt" type="button"><span><i class="mdi mdi-content-copy"></i><span class="m-l-5">Sao chép vào khay nhớ tạm</span></span></button>

            <a href="#" dmovie-tooltip title="hihihihi">ihiiiiiii</a>
        </div>
    </div>

@endsection
