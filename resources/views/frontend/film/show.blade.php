@extends('frontend.layouts.app')

<?php /** @var \App\Film $film */ ?>

@section('app.title'){{ $film->getTitle() }}
@endsection
@section('app.description'){{ $film->getTitle() }}
@endsection


@section('head.css')

@endsection


@section('bottom.js')
    <script src="{{ asset('frontend/js/film/index.js') }}"></script>
@endsection

@section('content')

    @include('frontend.layouts.components.fb_sdk')

    <div class="container">
        @include('admin.layouts.components.normal_notifications')
    </div>

    <div class="container">
        <h3 class="margin-bottom-20">{{ __('Home page') }} > <span class="color1">{!! $film->getTitle() !!}</span></h3>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-16">
                <div class="pi-img-wrapper">
                    <img class="img-responsive border-radius-20"
                         style="width: 100%" alt="{{ $film->getTitle() }}"
                         src="{{ $film->getPosterPath() }}">
                    <span style="position: absolute; top: 10px; left: 10px;">
                        <img src="{{ asset('Assets/Common/icons/films/'.$film->getMark().'.png') }}" class="img-responsive" />
                    </span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-16 padding-xs-top-15">
                <h1 class="bold no-margin margin-bottom-15">{!! $film->getTitle() !!}</h1>

                <p class="margin-bottom-15 font-lg font-family-san text-justify">
                    {!! $film->getDescription() !!}
                </p>

                <div class="row font-lg font-family-san font-xs-14">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <span class="bold font-transform-uppercase">{{__('Director')}}: </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-10">{{ $film->getDirector() }}</div>
                </div>
                <div class="row font-lg font-family-san font-xs-14">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <span class="bold font-transform-uppercase">{{__('Cast')}}: </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-10">
                        {{ $film->getCast() }}
                    </div>
                </div>
                <div class="row font-lg font-family-san font-xs-14">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <span class="bold font-transform-uppercase">{{ __('Genre') }}: </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-10">{{ $film->getGenre() }}</div>
                </div>
                <div class="row font-lg font-family-san font-xs-14">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <span class="bold font-transform-uppercase">{{ __('Running time') }}: </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-10">{{ $film->getRunningTime() }} {{ __('minutes') }}</div>
                </div>
                <div class="row font-lg font-family-san font-xs-14">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <span class="bold font-transform-uppercase">{{ __('Language') }}: </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-10">{!! $film->getLanguage() !!}</div>
                </div>
                <div class="row font-lg font-family-san font-xs-14">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <span class="bold font-transform-uppercase">{{ __('Release date') }}: </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-10">{{ \Carbon\Carbon::make($film->getReleaseDate())->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        @if ($film->isOpenSaleTicket())
        <div class="row">
            <!-- TABS -->
            <div class="col-md-16 tab-style-1 margin-bottom-15">

                <ul class="nav nav-tabs dayofweek" style="margin-bottom: 10px; margin-left: 1%; margin-right: 1%;">


                    <?php
                        /** @var \App\FilmSchedule $date */
                    $dates = [];
                    $active = true;
                    ?>
                    @foreach ($releaseDate as $key => $date)
                        <?php /** @var \Carbon\Carbon $startDate */
                            $startDate = \Carbon\Carbon::make($date->getStartDate());
                        ?>
                        @if ($startDate->greaterThanOrEqualTo(\Carbon\Carbon::today()))
                            <?php
                                array_push($dates, $date->start_date);
                            ?>
                                <li class="@if ($active) in active @endif">
                                    <a href="#movie{{ $date->start_date }}"
                                       data-toggle="tab" class="dayofweek" id="{{ $date->start_date }}">
                                        <span class="font-38 font-s-35">{{ \Carbon\Carbon::make($date->start_date)->format('d') }}</span>
                                        {{ \Carbon\Carbon::make($date->start_date)->format('m') }} -
                                        <?php $dayOfWeek = \Carbon\Carbon::make($date->start_date)->dayOfWeek ?>
                                        {{ convert_locale_day_of_week($dayOfWeek) }}
                                    </a>
                                </li>
                                <?php $active = false; ?>
                            @endif
                    @endforeach

                </ul>
                <div class="tab-content" id="tab-content">

                    @foreach($dates as $key => $date)

                        <?php
                        $times = $film->times()->where(['times.start_date' => $date])->get();
                        $existTimes = [];
                        ?>

                            <div class="tab-pane fade @if ($key === 0) in active @endif" id="movie{{ $date }}">

                                <div class="row">

                                    <div class="col-md-16 col-sm-16 col-xs-16" style="margin-bottom: 10px;margin-top: 10px;"><span class="font-lg bold font-transform-uppercase">2D Phụ đề</span></div>

                                    <?php /** @var \App\Time $time */  ?>
                                    @foreach($times as $key => $time)
                                        @if (!in_array($time->getStartTime(), $existTimes))
                                            <?php array_push($existTimes, $time->getStartTime()); ?>
                                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-5 text-center">

                                                <a href="#booking-pop-up" style="width: 100%"
                                                   <?php /** available to click when show time is greater than now time */ ?>
                                                   @if (\Carbon\Carbon::now()->lessThanOrEqualTo(\Carbon\Carbon::make($time->getStartDate() . $time->getStartTime())))
                                                       data-fancybox data-src="#booking-pop-up"
                                                       onclick="bookingSeat(this, '{{ $date }}', '{{ json_encode($time) }}', '{{ json_encode($film) }}', '{{ $film->getTitle() }}');"
                                                   @else
                                                    disabled
                                                   @endif
                                                   class="btn default">{{ $time->getFormatStartTime() }}</a>

                                                <div class="font-smaller padding-top-5">{{ $film->getEmptySeats($date, $time->getStartTime()) }}  {{ __('empty seats') }}</div>
                                            </div>
                                        @endif
                                    @endforeach



                                </div>


                            </div>


                    @endforeach




                </div>
            </div>
            <!-- END TABS -->
        </div>
        @endif
        <br />
        <div class="row margin-bottom-15">
            <div class="fb-like hidden-xs"
                 data-href="{{ asset('') }}/film-detail/film-id-{{ $film->getId() }}"
                 data-layout="button_count"
                 data-action="like"
                 data-size="small"
                 data-show-faces="true"
                 data-share="true"></div>
        </div>
    </div>
    <div class="fullwidthbanner-container">
        <div class="container">
            <div class="text-center margin-top-20">
                <ul class="nav tab-films">
                    <li class="active">
                        <a data-toggle="tab" class="no-padding">
                            <h1 style="color: #fff;" class="bold">{{ strtoupper(__('Trailer')) }}</h1>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="row margin-bottom-20">
                <div class="col-md-12 col-md-offset-2 margin-bottom-35">
                    <iframe style="width: 100%; height: 60vh"
                            src="{{ $film->getTrailer() }}?rel=0&amp;showinfo=0&amp;autoplay=1" allowfullscreen></iframe>

                </div>
                <div class="col-md-16 margin-bottom-35">
                    <div class="fb-comments"
                         data-href="{{ asset('') }}/film-detail/film-id-{{ $film->getId() }}"
                         data-width="auto"
                         data-numposts="5"
                         data-colorscheme="dark"></div>
                </div>
            </div>
        </div>
    </div>



    <div class="container display-none">
        <div id="booking-pop-up" class="popup-w-700 popup-xs-w fancybox-content">
            <div class="product-page product-pop-up">
                <div class="modal-header">
                    <h3 class="no-padding no-margin"> {{ __('Booking movie tickets') }}</h3>
                </div>
                <div class="modal-body">
                    <h1 class="color1 text-center" id="film-name">Gai Gia Lam Chieu 3</h1>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 30%">
                                <h4>{{ __('Cinema') }}</h4>
                            </th>
                            <th class="text-center" style="width: 30%">
                                <h4>{{ __('Date') }}</h4>
                            </th>
                            <th class="text-center" style="width: 30%">
                                <h4>{{ __('Time') }}</h4>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center font-lg bold">
                                <h3 class="font-xs-small"><span id="cinema"><span class="bold">{{ __('D-Mỹ Đình') }}</span></span></h3>
                            </td>
                            <td class="text-center font-lg bold">
                                <h3 class="font-xs-small"><span id="start_date"><span class="bold">24/02/2020</span></span></h3>
                            </td>
                            <td class="text-center font-lg bold">
                                <h3 class="font-xs-small"><span id="start_time"><span class="bold">22:00</span></span></h3>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer text-center">
                    <a id="_booking-action" class="btn btn-2 btn-mua-ve" style="font-weight: normal;"
                       href="#">
                    <span class="dmovie-ticket-icon-container">
                        <i class="fa fa-ticket dmovie-ticket-icon mr-3"></i>
                    </span>
                        {{ __('OK') }}
                    </a>
                </div>
            </div>
        </div>
    </div>


@endsection
