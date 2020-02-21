@extends('admin.layouts.app')

@section('app.title'){{ __('Static Pages Manage') }}
@endsection

@section('app.description'){{ __('Static Pages Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Static Pages Manage') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Static Pages Manage') }}
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
                    data: 'slug',
                    name: 'slug'
                },
                {
                    data: 'language',
                    name: 'language'
                },
                {
                    data: 'content',
                    name: 'content',
                    render: $.fn.dataTable.render.ellipsis( 60, true )
                }
            ],
            invisibleCols = ['.data-cell-content'];
        @cannot('canEditDelete', \App\StaticPage::class)
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
                    targets: 'data-cell-slug',
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('data-id', rowData.id);
                        $(td).attr('scope', 'slug');
                    }
                },
                {
                    targets: 'data-cell-language',
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('data-id', rowData.id);
                        $(td).attr('scope', 'language');
                    }
                },
                {
                    targets: 'data-cell-content',
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('data-id', rowData.id);
                        $(td).attr('scope', 'content');
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
    <script src="{{ asset('adminhtml/js/static_page/index.js') }}"></script>
@endsection

@section('action_button')
    @can('create', \App\StaticPage::class)
        <div class="navbar dmovie-fix-top-container">
            <div class="row bg-title" id="dmovie-fix-top-block">
                <a href="{{ route('static-pages.create') }}"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                    <i class="mdi mdi-google-pages"></i>
                    {{ __('New Page') }}
                </a>
            </div>
        </div>
    @endcan
@endsection

@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')


    @can('canEditDelete', App\StaticPage::class)
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
                        @if (!auth()->user()->can('delete', App\StaticPage::class) && !auth()->user()->can('update',
                        App\StaticPage::class))
                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                        @endif
                        @can('delete', App\StaticPage::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_delete-action"
                                   swl-text="{{ __('Do you want to destroy this selected :name?', ['name' => __('page')]) }}">
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                        @can('update', App\StaticPage::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_change-status-action"
                                   swl-text="{{ __('Do you want change status of selected :name?', ['name' => __('page')]) }}"
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
            <table id="static_pages_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-name">{{ __('Name') }}</th>
                    <th class="data-cell-slug">{{ __('Slug') }}</th>
                    <th class="data-cell-language">{{ __('Language') }}</th>
                    <th class="data-cell-content">{{ __('Content') }}</th>
                    @can('canEditDelete', \App\StaticPage::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
