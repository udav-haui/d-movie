@extends('admin.layouts.app')

@section('app.title'){{ __('Sales Bookings') }}
@endsection

@section('app.description'){{ __('Sales Bookings') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Sales Bookings') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Sales Bookings') }}
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
            colOrder = [[8, 'asc']],
            aoColumns = [
                {
                    data: 'id',
                    name: 'id',
                    className: 'no-visible-filter'
                },
                {
                    data: 'booking_code',
                    name: 'booking_code'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'message',
                    name: 'message'
                },
                {
                    data: 'qty',
                    name: 'qty'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'combo_name',
                    name: 'combo.name'
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ],
            invisibleCols = [];
        @cannot('canEditDelete', \App\Booking::class)
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
                targets: 'data-cell-combo',
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
    <script src="{{ asset('adminhtml/js/booking/index.js') }}"></script>
@endsection

@section('action_button')
    @can('create', \App\Booking::class)
        <div class="navbar dmovie-fix-top-container">
            <div class="row bg-title" id="dmovie-fix-top-block">
                <a href="{{ route('bookings.create') }}"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                    <i class="mdi mdi-home-variant"></i>
                    {{ __('New Booking') }}
                </a>
            </div>
        </div>
    @endcan
@endsection

@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')


{{--    @can('canEditDelete', App\Cinema::class)--}}
{{--        <div class="row m-b-15">--}}
{{--            <div class="col-md-3 col-lg-2">--}}
{{--                <div class="btn-group width-100">--}}
{{--                    <button--}}
{{--                        aria-expanded="false"--}}
{{--                        data-toggle="dropdown"--}}
{{--                        class="btn btn-default btn-outline width-100--}}
{{--                    dropdown-toggle waves-effect waves-light border-radius-0 dmovie-textbox-border"--}}
{{--                        type="button"> {{ __('Action') }}--}}
{{--                        <span class="caret"></span>--}}
{{--                    </button>--}}
{{--                    <ul role="menu" class="dropdown-menu border-radius-0 dmovie-border width-100">--}}
{{--                        @if (!auth()->user()->can('delete', App\Cinema::class) && !auth()->user()->can('update',--}}
{{--                        App\Cinema::class))--}}
{{--                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>--}}
{{--                        @endif--}}
{{--                        @can('delete', App\Cinema::class)--}}
{{--                            <li>--}}
{{--                                <a href="javascript:void(0);"--}}
{{--                                   class="_delete-action"--}}
{{--                                   swl-text="{{ __('Do you want to destroy this selected cinema?') }}">--}}
{{--                                    {{ __('Delete') }}--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}
{{--                        @can('update', App\Cinema::class)--}}
{{--                            <li>--}}
{{--                                <a href="javascript:void(0);"--}}
{{--                                   class="_change-status"--}}
{{--                                   swl-text="{{ __('Do you want change all status of selected cinemas?') }}"--}}
{{--                                   swl-state-alert-title="{{ __('Select status') }}"--}}
{{--                                   swl-select-disable-item="{{ __('Disable') }}"--}}
{{--                                   swl-select-enable-item="{{ __('Enable') }}"--}}
{{--                                   swl-cancel-btn-text="{{ __('Cancel') }}" >--}}
{{--                                    {{ __('Set state') }}--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-2 selected-rows-label-container">--}}
{{--                {{ __('Selected') }}&nbsp;<span class="selected-rows-label">0</span>&nbsp;{{ __('records') }}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endcan--}}



    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="bookings_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">{{ __('ID') }}</th>
                    <th class="data-cell-booking_code">{{ __('Code Bill') }}</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-message">{{ __('Status Message') }}</th>
                    <th class="data-cell-qty">{{ __('Quantity') }}</th>
                    <th class="data-cell-amount">{{ __('Amount') }}</th>
                    <th class="data-cell-combo">{{ __('Combo') }}</th>
                    <th class="data-cell-user">{{ __('Customer') }}</th>
                    <th class="data-cell-created_at">{{ __('Create Time') }}</th>
                    @can('canEditDelete', \App\Booking::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
