@extends('admin.layouts.app')

@section('app.title'){{ __('System Logs') }}
@endsection

@section('app.description'){{ __('System Logs') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('System Logs') }}</li>
@endsection

@section('titlebar.title')
    {{ __('System Logs') }}
@endsection

@section('head.css')
@endsection

@section('head.js')
    <script src="{{ asset('adminhtml/assets/plugins/jszip/jszip.min.js') }}"></script>
@endsection


@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/ellipsis.js') }}"></script>
    <script>
        let colOrder = [[0, 'desc']],
            aoColumns = [
                {
                    data: 'id',
                    name: 'id',
                    className: 'no-visible-filter'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'action',
                    name: 'action',
                    //render: $.fn.dataTable.render.ellipsis( 50, true )
                },
                {
                    data: 'task',
                    sortable: false,
                    orderable: false
                }
            ],
            columnDefs = [
                {
                    targets: 'data-cell-id',
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('data-id', rowData.id);
                        $(td).attr('scope', 'id');
                    }
                },
                {
                    targets: 'data-cell-user_id',
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('data-id', rowData.id);
                        $(td).attr('scope', 'user_id');
                    }
                },
                {
                    targets: 'data-cell-action',
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('data-id', rowData.id);
                        $(td).attr('scope', 'action');
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
                }
            ];
            invisibleCols = [];
    </script>
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dt-buttons/buttons.flash.js') }}"></script>
    <script src="{{ asset('adminhtml/js/log/index.js') }}"></script>
@endsection

@section('action_button')

@endsection

@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')


    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="logs_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-created_at">{{ __('Create Time') }}</th>
                    <th class="data-cell-user_id">{{ __('By User') }}</th>
                    <th class="data-cell-action">{{ __('Action') }}</th>
                    <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
