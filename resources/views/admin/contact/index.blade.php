@extends('admin.layouts.app')

@section('app.title'){{ __('Contacts Manage') }}
@endsection

@section('app.description'){{ __('Contacts Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Contacts Manage') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Contacts Manage') }}
@endsection

@section('head.css')
    {{--    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/datatables/plugins/dataTables.checkboxes.css') }}">--}}
    {{--    <link rel="stylesheet" href="{{ asset('adminhtml/css/show/index.css') }}">--}}
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
                    data: 'cinema.name',
                    name: 'cinema.name'
                },
                {
                    data: 'contact_name',
                    name: 'contact_name'
                },
                {
                    data: 'contact_email',
                    name: 'contact_email'
                },
                {
                    data: 'contact_phone',
                    name: 'contact_phone'
                },
                {
                    data: 'contact_content',
                    name: 'contact_content'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
            ],
            invisibleCols = ['.data-cell-created_at'];
        @cannot('canEditDelete', \App\Contact::class)
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
                targets: 'data-cell-cinema_name',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'cinema_name');
                }
            },
            {
                targets: 'data-cell-contact_name',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'contact_name');
                }
            },
            {
                targets: 'data-cell-contact_email',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'contact_email');
                }
            },
            {
                targets: 'data-cell-contact_phone',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'contact_phone');
                }
            },
            {
                targets: 'data-cell-contact_content',
                render: $.fn.dataTable.render.ellipsis( 60, true ),
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'contact_content');
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

    <script src="{{ asset('adminhtml/js/contact/index.js') }}"></script>
@endsection


@section('action_button')




@endsection


@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')

    @can('canEditDelete', \App\Contact::class)
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
                        @if (!auth()->user()->can('delete', \App\Contact::class) && !auth()->user()->can('update',
                        \App\Contact::class))
                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                        @endif
                        @can('delete', \App\Contact::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_delete-action"
                                   swl-text="{{ __('Do you want to destroy this selected contacts?') }}">
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                            @can('update', App\Contact::class)
                                <li>
                                    <a href="javascript:void(0);"
                                       class="_change-status-action"
                                       swl-text="{{ __('Do you want change status of selected :name?', ['name' => __('contact')]) }}"
                                       swl-state-alert-title="{{ __('Select status') }}"
                                       swl-select-contacted-item="{{ __('Contacted') }}"
                                       swl-select-pending-item="{{ __('Pending') }}"
                                       swl-cancel-btn-text="{{ __('Cancel') }}" >
                                        {{ __('Set state') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       class="_change-send-mail-action">
                                        {{ __('Send contact mail') }}
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
            <table id="contacts_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-cinema_name">{{ __('Cinema Name') }}</th>
                    <th class="data-cell-contact_name">{{ __('Customer Name') }}</th>
                    <th class="data-cell-contact_email">{{ __('Customer Email') }}</th>
                    <th class="data-cell-contact_phone">{{ __('Customer Phone') }}</th>
                    <th class="data-cell-contact_content">{{ __('Content') }}</th>
                    <th class="data-cell-created_at">{{ __('Create Time') }}</th>
                    @can('canEditDelete', \App\FilmSchedule::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
