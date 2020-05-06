@extends('admin.layouts.app')
<?php /** @var $booking \App\Booking */ ?>
@section('app.title'){{ __('#:booking Bookings\'s Tickets', ['booking' => $booking->getBookingCode()]) }}
@endsection

@section('app.description'){{ __('#:booking Bookings\'s Tickets', ['booking' => $booking->getBookingCode()]) }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('bookings.index') }}">{{ __('Sales Bookings') }}</a></li>
    <li class="active">{{ $booking->getBookingCode() }}</li>
@endsection

@section('titlebar.title')
    {{ __('#:booking Bookings\'s Tickets', ['booking' => $booking->getBookingCode()]) }}
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
            bookingId = '{{ $booking->getId() }}',
            colOrder = [[7, 'asc']],
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
                    data: 'ticket_code',
                    name: 'ticket_code'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'seat',
                    name: 'seat'
                },
                {
                    data: 'start_time',
                    name: 'time.start_time'
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
    <script src="{{ asset('adminhtml/js/booking/ticket/index.js') }}"></script>
@endsection

@section('action_button')
    <div class="navbar dmovie-fix-top-container">
        <div class="row bg-title" id="dmovie-fix-top-block">
            <a href="{{ route('bookings.index') }}"
               class="btn dmovie-btn m-r-40 dmovie-btn-large pull-right">
                <i class="mdi mdi-arrow-left"></i>
                {{ __('Back') }}
            </a>
        </div>
    </div>
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
            <table id="tickets_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">{{ __('ID') }}</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-ticket_code">{{ __('Ticket Code') }}</th>
                    <th class="data-cell-price">{{ __('Price') }}</th>
                    <th class="data-cell-seat">{{ __('Seat') }}</th>
                    <th class="data-cell-start_time">{{ __('Start Time') }}</th>
                    <th class="data-cell-created_at">{{ __('Create Time') }}</th>
                    @can('printTicket', \App\Booking::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
