@extends('admin.layouts.app')

<?php
/**
 * @var \App\FilmSchedule $schedule
 */
?>

@section('app.title'){{ __('List showtime schedule of :filmTitle on :startDate.', ['filmTitle' => $schedule->getFilm()->getTitle(), 'startDate' => $schedule->getFormatStartDate('d-m-Y')]) }}
@endsection

@section('app.description'){{ __('List showtime schedule of :filmTitle on :startDate.', ['filmTitle' => $schedule->getFilm()->getTitle(), 'startDate' => $schedule->getFormatStartDate('d-m-Y')]) }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('fs.index') }}">{{ __('Schedule Manage') }}</a></li>
    <li class="active">{{ $schedule->getFilm()->getTitle() }}</li>
@endsection

@section('titlebar.title')
    {!! __('List showtime schedule of <code>:filmTitle</code> on <code>:startDate</code>.', ['filmTitle' => $schedule->getFilm()->getTitle(), 'startDate' => $schedule->getFormatStartDate('d-m-Y')]) !!}
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
            getTimesUrl = '{{ route('fs.getShowtime', ['schedule' => $schedule->getId()]) }}',
            colOrder = [[0, 'asc']],
            aoColumns = [
                {
                    data: 'id',
                    name: 'id',
                    className: 'no-visible-filter'
                },
                {
                    data: 'start_time',
                    name: 'start_time'
                },
                {
                    data: 'stop_time',
                    name: 'stop_time'
                },
                {
                    data: 'total_time',
                    name: 'total_time'
                }
            ],
            invisibleCols = [];
        @cannot('canEditDelete', \App\FilmSchedule::class)
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
                targets: 'data-cell-start_time',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'start_time');
                }
            },
            {
                targets: 'data-cell-stop_time',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'stop_time');
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

    <script src="{{ asset('adminhtml/js/time/index.js') }}"></script>
@endsection


@section('action_button')

    @can('create', \App\FilmSchedule::class)
        <div class="navbar dmovie-fix-top-container">
            <div class="row bg-title" id="dmovie-fix-top-block">
                <a href="{{ route('times.createTimeBySchedule', ['schedule' => $schedule]) }}"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                    <i class="mdi mdi-plus-circle"></i>
                    {{ __('New Showtime') }}
                </a>
                <a href="{{ route('fs.index') }}"
                   class="btn dmovie-btn m-r-40 dmovie-btn-large pull-right">
                    <i class="mdi mdi-arrow-left"></i>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    @endcan


@endsection


@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')

    @can('canEditDelete', \App\FilmSchedule::class)
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
                        @if (!auth()->user()->can('delete', \App\FilmSchedule::class) && !auth()->user()->can('update',
                        \App\FilmSchedule::class))
                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                        @endif
                        @can('delete', \App\FilmSchedule::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_delete-action"
                                   swl-text="{{ __('Do you want to destroy this selected showtime?') }}">
                                    {{ __('Delete') }}
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
            <table id="times_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-start_time">{{ __('Start Time') }}</th>
                    <th class="data-cell-stop_time">{{ __('Stop Time') }}</th>
                    <th class="data-cell-total_time">{{ __('Total Time') }}</th>
                    @can('canEditDelete', \App\FilmSchedule::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
