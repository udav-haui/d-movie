@extends('admin.layouts.app')
@section('app.title')
    {{ __('Dashboard') }}
@endsection


<?php
    /** @var \Illuminate\Support\Collection $bookings */
    /** @var array $incomes */
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
    <script type="module" src="{{ asset('adminhtml/assets/plugins/countup.js/countUp.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('adminhtml/assets/plugins/chart/Chart.min.js') }}"></script>
    <script type="text/javascript">
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
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <?php
            $increaseClassBar = 'progress-bar-success';
            $decreaseClassBar = 'progress-bar-danger';
            $increaseText = __('Increase');
            $decreaseText = __('Decrease');
            $noIncomeText = __('No income');
            $percentProcessVal = 0;
            $percentValue = 0;
            $statusClass = '';
            $statusText = '';
            $isNoChanged = false;

            if ($incomes['today'] > $incomes['yesterday'] && $incomes['yesterday'] > 0) {
                $statusClass = $increaseClassBar;
                $statusText = $increaseText;
                $percentValue = $incomes['today']/$incomes['yesterday'] * 100;
                $percentProcessVal = $percentValue >= 100 ?
                    100 :
                    number_format($incomes['today']/$incomes['yesterday']*100, 2);
            }
            if ($incomes['today'] < $incomes['yesterday'] && $incomes['yesterday'] > 0) {
                $statusClass = $decreaseClassBar;
                if ($incomes['today'] <= 0) {
                    $statusText = $noIncomeText;
                    $isNoChanged = true;
                    $percentProcessVal = 0;
                } else {
                    $statusText = $decreaseText;
                    $percentProcessVal = number_format($incomes['today']/$incomes['yesterday']*100, 2);
                    $percentValue = 100 - $percentProcessVal;
                }
            }
            if ($incomes['today'] > 0 && $incomes['yesterday'] == 0) {
                $statusClass = $increaseClassBar;
                $statusText = $increaseText;
                $percentProcessVal = 100;
                $percentValue = 100;
            }
            if ($incomes['today'] == 0 && $incomes['yesterday'] == 0) {
                $statusClass = 'progress-bar-info';
                $statusText = $noIncomeText;
                $percentProcessVal = 0;
                $isNoChanged = true;
            }
            ?>
                <script type="module">
                    import { CountUp } from '{{ asset('adminhtml/assets/plugins/countup.js/countUp.min.js') }}';
                    const options = {
                        startVal: 0,
                        duration: 5,
                        decimalPlaces: 2
                    };
                    const dailyIncome = {{ $incomes['today'] }};
                    let dailyIncomeCounter = new CountUp('daily_income_counter', dailyIncome, options);
                    if (!dailyIncomeCounter.error) {
                        dailyIncomeCounter.start();
                    } else {
                        console.error(dailyIncomeCounter.error);
                    }
                </script>
            <div class="white-box" title="{{ number_format($incomes['today'], 2) }}&nbsp;&#8363;">
                <h3 class="box-title">{{ __('Daily Sales') }}</h3>
                <ul class="list-inline two-part dot-dot-dot">
                    <li style="width: 9%">
                        <i class="ti-money text-success"></i>
                    </li>
                    <li class="text-right" style="width: calc(100% - 11.4%)">
                        <span class="text-muted" style="font-size: 14px">{{ __('Today Income') }}</span>
                        <h1>
                            <sup>
                                <i class="{{ $isNoChanged ? "" : ($incomes['today'] > $incomes['yesterday'] ? 'ti-arrow-up' : 'ti-arrow-down') }} text-{{ $incomes['today'] > $incomes['yesterday'] ? 'success' : 'danger' }}"></i>
                            </sup>
                            <span><counter id="daily_income_counter" class="counter">0</counter>&nbsp;&#8363;</span>
                        </h1>
                    </li>
                </ul>
                <span class="text-{{ $isNoChanged ? "info" : ($incomes['today'] > $incomes['yesterday'] ? 'success' : 'danger') }}">
                    {{ $statusText }}
                    {{ !$isNoChanged ? $percentValue . "&#37;" : "" }}
{{--                    {{ $incomes['yesterday'] > 0 ? ($incomes['today'] < $incomes['yesterday'] ? 100 - number_format(($incomes['today']/$incomes['yesterday'])*100, 2) : number_format(($incomes['today']/$incomes['yesterday'])*100, 2)) : ''}}&#37;--}}
                        - {{ __('Yesterday') . ' ' . number_format($incomes['yesterday']) }}&nbsp;&#8363;
                </span>
                <div class="progress m-b-0">
                    <div class="progress-bar {{ $statusClass }}"
                         role="progressbar"
                         aria-valuenow="100"
                         aria-valuemin="0"
                         aria-valuemax="100"
                         style="width: {{ $percentProcessVal }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <?php
                $todayBookings = $bookings->where(\App\Booking::CREATED_AT, \Carbon\Carbon::today()->toDateString());
                $totalBookingsToday = $todayBookings->count();
                $totalSuccessBookingsToday = $todayBookings->where(\App\Booking::STATUS, \App\Booking::SUCCESS)->count();
                if ($totalBookingsToday == 0 && $totalSuccessBookingsToday > 0) {
                    $percentProcessVal = 100;
                }
                if ($totalBookingsToday > 0 && $totalSuccessBookingsToday <= 0) {
                    $percentProcessVal = 0;
                }
                if ($totalBookingsToday > 0 && $totalSuccessBookingsToday > 0) {
                    $percentProcessVal = $totalSuccessBookingsToday / $totalBookingsToday;
                }
            ?>
                <script type="module">
                    import { CountUp } from '{{ asset('adminhtml/assets/plugins/countup.js/countUp.min.js') }}';
                    const options = {
                        startVal: 0,
                        duration: 3
                    };
                    const dailyBookings = {{ $totalSuccessBookingsToday }};
                    let dailyBookingsCounter = new CountUp('daily_success_bookings', dailyBookings, options);
                    if (!dailyBookingsCounter.error) {
                        dailyBookingsCounter.start();
                    } else {
                        console.error(dailyBookingsCounter.error);
                    }
                </script>
            <div class="white-box">
                <h3 class="box-title">{{ __('# Daily Bookings') }}</h3>
                <ul class="list-inline two-part">
                    <li style="width: 9%">
                        <i class="ti-ticket text-success"></i>
                    </li>
                    <li class="text-right" style="width: calc(100% - 11.4%)">
                        <span class="text-muted" style="font-size: 14px">{{ __('Today Success Bookings') }}</span>
                        <h1>
                            <span id="daily_success_bookings" class="counter">0</span>
                        </h1>
                    </li>
                </ul>
                <span class="text-info">
                    &#47; {{ $totalBookingsToday . ' ' . __('bookings')}}
                </span>
                <div class="progress m-b-0">
                    <div class="progress-bar"
                         role="progressbar"
                         aria-valuenow="100"
                         aria-valuemin="0"
                         aria-valuemax="100"
                         style="width: {{ $percentProcessVal }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <?php
                $totalBookings = $bookings->count();
                $totalSuccessBookings = $bookings->where(\App\Booking::STATUS, \App\Booking::SUCCESS)->count();

                if ($totalBookings > 0) {
                    if ($totalSuccessBookings > 0) {
                        $percentProcessVal = $totalSuccessBookings / $totalBookings;
                    }
                    $percentProcessVal = 0;
                } else {
                    $percentProcessVal = 0;
                }
            ?>
            <script type="module">
                import { CountUp } from '{{ asset('adminhtml/assets/plugins/countup.js/countUp.min.js') }}';
                const options = {
                    startVal: 0,
                    duration: 3
                };
                const totalBookings = {{ $totalSuccessBookings }};
                let totalBookingsCounter = new CountUp('total_success_bookings', totalBookings, options);
                if (!totalBookingsCounter.error) {
                    totalBookingsCounter.start();
                } else {
                    console.error(totalBookingsCounter.error);
                }
            </script>
            <div class="white-box">
                <h3 class="box-title">{{ __('# Total Bookings') }}</h3>
                <ul class="list-inline two-part">
                    <li style="width: 9%">
                        <i class="ti-ticket text-success"></i>
                    </li>
                    <li class="text-right" style="width: calc(100% - 11.4%)">
                        <span class="text-muted" style="font-size: 14px">{{ __('Success Bookings') }}</span>
                        <h1>
                            <span id="total_success_bookings" class="counter">0</span>
                        </h1>
                    </li>
                </ul>
                <span class="text-info">&#47; {{ $totalBookings . ' ' . __('bookings')}}</span>
                <div class="progress m-b-0">
                    <div class="progress-bar progress-bar-info"
                         role="progressbar"
                         aria-valuenow="50"
                         aria-valuemin="0"
                         aria-valuemax="100" style="width: {{ $percentProcessVal }}%">
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
            <script type="module">
                import { CountUp } from '{{ asset('adminhtml/assets/plugins/countup.js/countUp.min.js') }}';
                const options = {
                    startVal: 0,
                    duration: 3
                };
                const userCounter = {{ $usersCount }};
                let userCounterCounter = new CountUp('user_counter', userCounter, options);
                if (!userCounterCounter.error) {
                    userCounterCounter.start();
                } else {
                    console.error(userCounterCounter.error);
                }
            </script>
            <div class="white-box">
                <h3 class="box-title">{{ __('Total :name', ['name' => __('customers')]) }}</h3>
                <ul class="list-inline two-part">
                    <li><i class="icon-people text-info"></i></li>
                    <li class="text-right"><span id="user_counter" class="counter">0</span></li>
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
                <div class="row sales-report">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <h2>March 2017</h2>
                        <p>SALES REPORT</p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6 ">
                        <h1 class="text-right text-success m-t-20">$3,690</h1> </div>
                </div>
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
                    <a href="{{ route('bookings.index') }}">{{ __('Check all the sales booking.') }}</a> </div>
            </div>
        </div>

    </div>

@endsection
@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/js/dashboard1.js') }}"></script>
@endsection
