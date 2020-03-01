@extends('admin.layouts.app')
@section('app.title')
    {{ __('Dashboard') }}
@endsection


<?php
    /** @var \Illuminate\Support\Collection $bookings */
?>

@section('app.description')
    {{ __('Dashboard') }}
@endsection

@section('titlebar.breadcrumb')
    <li class="active">{{ __('Dashboard') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Dashboard') }}
@endsection

@section('bottom.js')
    <script type="text/javascript" src="{{ asset('adminhtml/assets/plugins/chart/Chart.min.js') }}"></script>
    <script>
        let ctx = $('#myChart'),
            data = '@json($latest7DaysBooking)',
            langText = $('#lang-select'),
            successBookingsText = langText.attr('success-bookings-count'),
            failedBookingsText = langText.attr('failed-bookings-count');
        data = JSON.parse(data);
        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.success.days,
                datasets: [
                    {
                        label: successBookingsText,
                        data: data.success.booking_count,
                        fill: false,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            '#00c193',
                        ],
                        borderWidth: 1
                    },
                    {
                        label: failedBookingsText,
                        data: data.failed.booking_count,
                        fill: false,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    },
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                            max: data.max,
                        }
                    }]
                }
            }
        });
    </script>
@endsection

@section('content')

    <input type="hidden" id="lang-select"
           success-bookings-count="{{ __('# Success Bookings') }}"
           failed-bookings-count="{{ __('# Failed Bookings') }}"
    >

    <!-- /.row -->
    <!-- ============================================================== -->
    <!-- Different data widgets -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-lg-3 col-md-6    col-sm-6 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">{{ __('Daily Sales') }}</h3>
                <div class="text-right"> <span class="text-muted">{{ __('Today Income') }}</span>
                    <h1>
                        <sup>
                            <i class="ti-arrow-{{ $incomes['today'] > $incomes['yesterday'] ? 'up' : 'down' }} text-{{ $incomes['today'] > $incomes['yesterday'] ? 'success' : 'danger' }}"></i>
                        </sup>
                        {{ number_format($incomes['today']) }}&nbsp;&#8363;
                    </h1>
                </div>
                <span class="text-{{ $incomes['today'] > $incomes['yesterday'] ? 'success' : 'danger' }}">
                    @if ($incomes['today'] >= $incomes['yesterday'])
                        {{ __('Increase') }}
                    @else
                        {{ __('Decrease') }}
                    @endif
                        {{ $incomes['yesterday'] > 0 ? ($incomes['today'] < $incomes['yesterday'] ? 100 - number_format(($incomes['today']/$incomes['yesterday'])*100, 2) : number_format(($incomes['today']/$incomes['yesterday'])*100, 2)) : '100'}}&#37;
                        - {{ __('Yesterday') . ' ' . number_format($incomes['yesterday']) }}&nbsp;&#8363;
                </span>
                @if ($incomes['today'] > $incomes['yesterday'] && $incomes['yesterday'] > 0)
                    @if ($incomes['today']/$incomes['yesterday']*100 >= 100)
                        <div class="progress m-b-0">
                            <div class="progress-bar progress-bar-success"
                                 role="progressbar"
                                 aria-valuenow="100"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 style="width: 100%"
                            >
                            </div>
                        </div>
                    @else
                        <div class="progress m-b-0">
                            <div class="progress-bar progress-bar-success"
                                 role="progressbar"
                                 aria-valuenow="100"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 style="width: {{number_format($incomes['today']/$incomes['yesterday']*100, 2)}}%"
                            >
                            </div>
                        </div>
                    @endif
                @elseif ($incomes['today'] < $incomes['yesterday'] && $incomes['yesterday'] > 0)
                    @if ($incomes['today']/$incomes['yesterday']*100 >= 100)
                        <div class="progress m-b-0">
                            <div class="progress-bar progress-bar-danger"
                                 role="progressbar"
                                 aria-valuenow="100"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 style="width: 100%"
                            >
                            </div>
                        </div>
                    @else
                        <div class="progress m-b-0">
                            <div class="progress-bar progress-bar-danger"
                                 role="progressbar"
                                 aria-valuenow="100"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 style="width: {{number_format($incomes['today']/$incomes['yesterday']*100, 2)}}%"
                            >
                            </div>
                        </div>
                    @endif
                @else
                    <div class="progress m-b-0">
                        <div class="progress-bar progress-bar-success"
                             role="progressbar"
                             aria-valuenow="100"
                             aria-valuemin="0"
                             aria-valuemax="100"
                             style="width: 100%"
                        >
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">{{ __('# Success Bookings') }}</h3>
                <ul class="list-inline two-part">
                    <li>
                        <i class="ti-shopping-cart text-success"></i>
                    </li>
                    <li class="text-right">
                        <span class="counter">{{ $bookings->where(\App\Booking::STATUS, \App\Booking::SUCCESS)->count() }}</span>
                    </li>
                </ul>
                <span class="text-info">/ {{ $bookings->count() . ' ' . __('bookings')}}</span>
                <div class="progress m-b-0">
                    <div class="progress-bar progress-bar-info"
                         role="progressbar"
                         aria-valuenow="50"
                         aria-valuemin="0"
                         aria-valuemax="100" style="width: {{ ($bookings->where(\App\Booking::STATUS, \App\Booking::SUCCESS)->count()/$bookings->count())*100 }}%">
                        <span class="sr-only">20% Complete</span>
                    </div>
                </div>
            </div>
        </div>
{{--        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">--}}
{{--            <div class="white-box">--}}
{{--                <h3 class="box-title">Weekly Sales</h3>--}}
{{--                <div class="text-right"> <span class="text-muted">Weekly Income</span>--}}
{{--                    <h1><sup><i class="ti-arrow-down text-danger"></i></sup> $5,000</h1> </div> <span class="text-danger">30%</span>--}}
{{--                <div class="progress m-b-0">--}}
{{--                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:30%;"> <span class="sr-only">230% Complete</span> </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">{{ __('Total :name', ['name' => __('users')]) }}</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-people text-info"></i></li>
                    <li class="text-right"><span class="counter">{{ $usersCount }}</span></li>
                </ul>
            </div>
        </div>
{{--        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">--}}
{{--            <div class="white-box">--}}
{{--                <h3 class="box-title">Yearly Sales</h3>--}}
{{--                <div class="text-right"> <span class="text-muted">Yearly Income</span>--}}
{{--                    <h1><sup><i class="ti-arrow-up text-inverse"></i></sup> $9,000</h1> </div> <span class="text-inverse">80%</span>--}}
{{--                <div class="progress m-b-0">--}}
{{--                    <div class="progress-bar progress-bar-inverse" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:80%;"> <span class="sr-only">230% Complete</span> </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    <!--row -->
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="white-box">
                <h3 class="box-title">{{ __('Total bookings') }} - {{ __('Latest 7 days') }}</h3>
{{--                <ul class="list-inline">--}}
{{--                    <li>--}}
{{--                        <h5><i class="fa fa-circle m-r-5" style="color: #2cabe3;"></i>Returning Visitor</h5> </li>--}}
{{--                    <li>--}}
{{--                        <h5><i class="fa fa-circle m-r-5" style="color: #ff7676;"></i>New visits</h5> </li>--}}
{{--                </ul>--}}
                <canvas id="myChart" width="auto" height="100"></canvas>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="white-box">
                <h3 class="box-title">{{ __('Recent bookings') }} - {{ __('Latest 7 days') }}
{{--                    <div class="col-md-3 col-sm-4 col-xs-6 pull-right">--}}
{{--                        <select class="form-control pull-right row b-none">--}}
{{--                            <option>March 2017</option>--}}
{{--                            <option>April 2017</option>--}}
{{--                            <option>May 2017</option>--}}
{{--                            <option>June 2017</option>--}}
{{--                            <option>July 2017</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
                </h3>
{{--                <div class="row sales-report">--}}
{{--                    <div class="col-md-6 col-sm-6 col-xs-6">--}}
{{--                        <h2>March 2017</h2>--}}
{{--                        <p>SALES REPORT</p>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 col-sm-6 col-xs-6 ">--}}
{{--                        <h1 class="text-right text-success m-t-20">$3,690</h1> </div>--}}
{{--                </div>--}}
                <div class="table-responsive">
                    <?php $latest7DaysBooking = $bookings->filter(function (\App\Booking $booking) {
                        /** @var \Carbon\Carbon $createTime */
                        $createTime =$booking->created_at;
                        return $createTime->greaterThanOrEqualTo(\Carbon\Carbon::today()->subDays(7));
                    })->sortByDesc('updated_at')->sortByDesc('created_at'); ?>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php /** @var \App\Booking $booking */ ?>
                        @foreach($latest7DaysBooking as $booking)
                            <tr>
                                <td>{{ $booking->getId() }}</td>
                                <td class="txt-oflo">{{ $booking->getUser()->getName() }}</td>
                                <td>
                                    <span class="label label-{{ $booking->isSuccess() ? 'success' : ($booking->isWaitingForPayment() ? 'info' : 'danger')}} label-rouded">
                                        {{ __($booking->getMessage()) }}
                                    </span>
                                </td>
                                <td class="txt-oflo">
                                    {{ $booking->created_at->isoFormat('dddd, Do MMMM YYYY') }}
                                </td>
                                <td><span class="text-{{ $booking->isSuccess() ? 'success' : ($booking->isWaitingForPayment() ? 'info' : 'danger')}}">&#8363;{{ number_format($booking->getAmount()) }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a href="#">Check all the sales</a> </div>
            </div>
        </div>

    </div>

@endsection
@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/js/dashboard1.js') }}"></script>
@endsection
