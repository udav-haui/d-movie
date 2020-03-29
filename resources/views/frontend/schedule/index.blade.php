@extends('frontend.layouts.app')

@section('app.title'){{ __('Movie Schedule') }}@endsection

@section('app.description'){{ __('Movie Schedule') }}@endsection

@section('head.css')
    <link rel="stylesheet" id="slick-style-css"
          href="{{ asset('Assets/Common/Plugins/amy-movie/css/vendor/slick81db81db.css') }}" type="text/css"
          media="all">
    <link rel="stylesheet" id="slick-theme-css"
          href="{{ asset('Assets/Common/Plugins/amy-movie/css/vendor/slick-theme81db81db.css') }}" type="text/css"
          media="all">
    <link rel="stylesheet" id="amy-movie-style-css"
          href="{{ asset('Assets/Common/Plugins/amy-movie/css/style8a548a54.css') }}" type="text/css" media="all">
@endsection
@section('bottom.js')
    <script src="{{ asset('Assets/Common/Plugins/jquery.easing.min.js') }}"></script>
{{--    <script type="text/javascript" src="{{ asset('Assets/Common/Plugins/amy-movie/js/vendor/slick.minaff7aff7.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('Assets/Common/Plugins/amy-movie/js/vendor/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Assets/Common/Plugins/amy-movie/js/script8a548a54.js') }}"></script>
    <script>
        $('.slicker').slick({
            slidesToShow: 5,
            slidesToScroll: 5,
            autoplay: true,
            autoplaySpeed: 3000,
            infinite: true,
            centerMode: true,
            responsive: [
                {"breakpoint": 480,"settings": {"slidesToShow": 1,"slidesToScroll": 1}},
                {"breakpoint": 979,"settings": {"slidesToShow": 3,"slidesToScroll": 3}},
                {"breakpoint": 1199,"settings": {"slidesToShow": 5,"slidesToScroll": 5}},
                {"breakpoint": 1999,"settings": {"slidesToShow": 7,"slidesToScroll": 7}}
                ],
            dots: false
        });
    </script>
@endsection

@section('content')
    <div class="margin-none">
        <div class="ecm-panel">
            <div class="container">
                <div class="tab-style-1 margin-bottom-35">
                    <div class="tab-style-1 margin-bottom-35">
                        <ul class="nav nav-tabs dayofweek"
                            style="margin-bottom: 10px; margin-left: 1%; margin-right: 1%;">
                            <?php
                            $active = true;
                            $activeDate = null;
                            ?>
                            @foreach ($visibleDates as $key => $date)
                                <li class="@if ($active) <?php $active = false; $activeDate = $key ?> active @endif">
                                    <a href="#time_{{ $key }}" data-toggle="tab"
                                       class="dayofweek" id="{{ $key }}" aria-expanded="false"><span
                                            class="font-38 font-s-35">{{ \Carbon\Carbon::make($key)->format('d') }}</span>
                                        /{{ \Carbon\Carbon::make($key)->format('m') }} -
                                        <?php $dayOfWeek = \Carbon\Carbon::make($key)->dayOfWeek ?>
                                        {{ convert_locale_day_of_week($dayOfWeek) }}
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                        <div class="tab-content" id="tab-content">

                            @foreach($visibleDates as $key => $value)
                                <?php $films = $value['films']; $breakPoint = 2 ?>
                                <div class="tab-pane fade @if ($key === $activeDate) in active @endif"
                                     id="time_{{ $key }}">
                                    <div class="content-page">
                                        <?php
                                        $filmGroupData = $films[$films['max_times_film']];
                                        /** @var \App\Film $film */
                                        $film = $filmGroupData['film'];
                                        $times = $filmGroupData['times']
                                        ?>
                                        <div class="row margin-left-0 margin-right-0 margin-bottom-40">
                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6">
                                                <div class="product-item padding-xs margin-xs padding-sm margin-sm">
                                                    <div class="pi-img-wrapper">
                                                    <span style="position:absolute;top:10px;left:10px;">
                                                        <img
                                                            src="{{ asset('Assets/Common/icons/films/'.$film->getAgeMark().'.png') }}"
                                                            class="img-responsive">
                                                    </span>
                                                        <img class="img-responsive border-radius-20"
                                                             alt="{{ $film->getTitle() }}"
                                                             src="{{ $film->getPosterPath() }}">
                                                        <div class="border-radius-20">
                                                            <a href="#trailer-pop-up"
                                                               onclick="viewTrailer('Bloodshot', 'https://www.youtube.com/watch?v=7olskkntp9c');"
                                                               class="fancybox-fast-view">
                                                                <i class="fa fa-play-circle"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-10">
                                                <div class="row"><h1 class="no-margin no-padding">
                                                        <a href="{{ route('fe.filmDetail', ['slug' => convert_vi_to_en($film->getTitle()), 'film' => $film]) }}">
                                                            {{ $film->getTitle() }}
                                                        </a>
                                                    </h1>
                                                    <ul class="blog-info">
                                                        <li><i class="fa fa-tags"></i>{{ $film->getGenre() }}</li>
                                                        <li>
                                                            <i class="fa fa-clock-o"></i>{{ $film->getRunningTime() . ' ' . __('minutes') }}
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-16 col-sm-16 col-xs-16"
                                                         style="margin-bottom:10px; margin-top: 10px; padding-left: unset;">
                                                        <span
                                                            class="font-lg bold font-transform-uppercase">{{ __('2D Subtitle') }}</span>
                                                    </div>
                                                    @foreach ($times as $time)
                                                        <?php /** @var \App\Time $time */ $time = \App\Time::find($time['id']); ?>
                                                        <div style="padding-left: unset;"
                                                             class="col-lg-2 col-md-5 col-sm-5 col-xs-7 margin-xs-bottom-10 text-center">
                                                            <a style="width: 100%" href="#product-pop-up"
                                                               class="btn default show-in fancybox-fast-view">
                                                                {{ $time->getFormatStartTime() }}
                                                            </a>
                                                            <div class="font-smaller padding-top-5">
                                                                {{ $film->getEmptySeats($key, $time->getStartTime()) }}  {{ __('empty seats') }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <?php unset($films[$films['max_times_film']]);unset($films['max_times_film']) ?>
                                        <?php $loopCount = 0; ?>
                                        @if (!empty($films))
                                            @foreach($films as $film)
                                                <?php $film = $film['film'];
                                                $times = $film['times']
                                                ?>
                                                @if ($loopCount % $breakPoint === 0)
                                                    <div class="row margin-left-0 margin-right-0 padding-xs">
                                                        <div
                                                            class="col-md-8 col-sm-16 col-b col-b-xs col-b-sm padding-xs-left padding-xs-right padding-sm-left padding-sm-right">
                                                            <div class="col-lg-8 col-md-8 col-sm-5 col-xs-6">
                                                                <div class="product-item no-padding">
                                                                    <div class="pi-img-wrapper">
                                                                                <span
                                                                                    style="position:absolute;top:10px;left:10px;">
                                                                                    <img
                                                                                        src="{{ asset('Assets/Common/icons/films/'.$film->getAgeMark().'.png') }}"
                                                                                        class="img-responsive">
                                                                                </span>
                                                                        <img class="img-responsive border-radius-20"
                                                                             alt="{{ $film->getTitle() }}"
                                                                             src="{{ $film->getPosterPath() }}">
                                                                        <div class="border-radius-20">
                                                                            <a href="#trailer-pop-up"
                                                                               class="fancybox-fast-view">
                                                                                <i class="fa fa-play-circle"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-11 col-xs-10">
                                                                <div class="row">
                                                                    <h2>
                                                                        <a style="color: #03599d !important;"
                                                                           href="{{ route('fe.filmDetail', ['film' => $film->getId(), 'slug' => convert_vi_to_en($film->getTitle())]) }}">
                                                                            {{ $film->getTitle() }}
                                                                        </a>
                                                                    </h2>
                                                                    <ul class="blog-info">
                                                                        <li>
                                                                            <i class="fa fa-tags"></i>{{ $film->getGenre() }}
                                                                        </li>
                                                                        <li>
                                                                            <i class="fa fa-clock-o"></i>{{ __(':time minutes', ['time' => $film->getRunningTime()]) }}
                                                                        </li>
                                                                    </ul>
                                                                    <div class="col-md-16 col-sm-16 col-xs-16"
                                                                         style="margin-bottom:10px; margin-top: 10px; padding-left: unset;">
                                                                        <span
                                                                            class="font-lg bold font-transform-uppercase">{{ __('2D Subtitle') }}</span>
                                                                    </div>
                                                                    @foreach($times as $time)
                                                                        <?php /** @var \App\Time $time */ $time = \App\Time::find($time['id']); ?>
                                                                        <div style="padding-left: unset;"
                                                                             class="col-lg-5 col-md-7 col-sm-5 col-xs-7 margin-xs-bottom-10 text-center">
                                                                            <a style="width: 100%"
                                                                               href="#product-pop-up"
                                                                               class="btn default show-in fancybox-fast-view">
                                                                                {{ $time->getFormatStartTime() }}
                                                                            </a>
                                                                            <div class="font-smaller padding-top-5">
                                                                                {{ $film->getEmptySeats($key, $time->getStartTime()) }}  {{ __('empty seats') }}
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @else
                                                            <div
                                                                class="col-md-8 col-sm-16 col-b col-b-xs col-b-sm padding-xs-left padding-xs-right padding-sm-left padding-sm-right">
                                                                <div class="col-lg-8 col-md-8 col-sm-5 col-xs-6">
                                                                    <div class="product-item no-padding">
                                                                        <div class="pi-img-wrapper">
                                                                                <span
                                                                                    style="position:absolute;top:10px;left:10px;">
                                                                                    <img
                                                                                        src="{{ asset('Assets/Common/icons/films/'.$film->getAgeMark().'.png') }}"
                                                                                        class="img-responsive"></span><img
                                                                                class="img-responsive border-radius-20"
                                                                                alt="{{ $film->getTitle() }}"
                                                                                src="{{ $film->getPosterPath() }}">
                                                                            <div class="border-radius-20">
                                                                                <a href="#trailer-pop-up"
                                                                                   class="fancybox-fast-view">
                                                                                    <i class="fa fa-play-circle"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-8 col-md-8 col-sm-11 col-xs-10">
                                                                    <div class="row">
                                                                        <h2>
                                                                            <a style="color: #03599d !important;"
                                                                               href="{{ route('fe.filmDetail', ['film' => $film->getId(), 'slug' => convert_vi_to_en($film->getTitle())]) }}">
                                                                                {{ $film->getTitle() }}
                                                                            </a>
                                                                        </h2>
                                                                        <ul class="blog-info">
                                                                            <li><i class="fa fa-tags"></i>
                                                                                {{ $film->getGenre() }}
                                                                            </li>
                                                                            <li><i class="fa fa-clock-o"></i>
                                                                                {{ __(':time minutes', ['time' => $film->getRunningTime()]) }}
                                                                            </li>
                                                                        </ul>
                                                                        <div class="col-md-16 col-sm-16 col-xs-16"
                                                                             style="margin-bottom:10px; margin-top: 10px; padding-left: unset;">
                                                                            <span
                                                                                class="font-lg bold font-transform-uppercase">{{ __('2D Subtitle') }}</span>
                                                                        </div>
                                                                        @foreach($times as $time)
                                                                            <?php /** @var \App\Time $time */ $time = \App\Time::find($time['id']); ?>
                                                                            <div style="padding-left: unset;"
                                                                                 class="col-lg-5 col-md-7 col-sm-5 col-xs-7 margin-xs-bottom-10 text-center">
                                                                                <a style="width: 100%"
                                                                                   href="#product-pop-up"
                                                                                   class="btn default show-in fancybox-fast-view">
                                                                                    {{ $time->getFormatStartTime() }}
                                                                                </a>
                                                                                <div class="font-smaller padding-top-5">
                                                                                    {{ $film->getEmptySeats($key, $time->getStartTime()) }}  {{ __('empty seats') }}
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            <div class="fullwidthbanner-container" style="background-color: #000;">
                <div class="amy-section container">
                    <div class="text-center margin-top-20">
                        <ul class="nav tab-films no-margin">
                            <li class="active">
                                <a data-toggle="tab" class="no-padding">
                                    <h1 style="color: #fff;" class="bold">
                                        {{ __('COMING SOON') }}
                                    </h1>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="top-movie">
                        <div>
                            <div class="amy-shortcode amy-mv-carousel">
                                <div class="slicker">
                                    <?php
                                        /**
                                         * @var \Illuminate\Database\Eloquent\Collection $isComingSoonFilms
                                         */
                                        /** @var \App\Film $film  */
                                    ?>
                                    @foreach ($isComingSoonFilms as $film)
                                            <div class="carousel-item">
                                                <div class="carousel-thumb">
                                                    <a href="{{ route('fe.filmDetail', ['film' => $film->getId(), 'slug' => convert_vi_to_en($film->getTitle())]) }}">
                                                        <img src="{{ $film->getPosterPath() }}"
                                                             class="img-responsive" alt="{{ $film->getTitle() }}"/>
                                                    </a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title">
                                                        <a href="{{ route('fe.filmDetail', ['film' => $film->getId(), 'slug' => convert_vi_to_en($film->getTitle())]) }}">
                                                            {{ $film->getTitle() }}
                                                        </a>
                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">{{ $film->getGenre() }}</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">{{ $film->getFormattedDate() }}</span>
                                                </div>
                                            </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN fast view of a product -->
    <a href="#view-trailer-pop-up"
       id="view-trailer-pop-up"
       data-fancybox data-src="#product-pop-up"
       class="fancybox-fast-view display-none"></a>
    <div id="product-pop-up" style="display: none; width: 65%;">
        <div class="product-page product-pop-up">
            <div class="modal-header">
                <h3 class="no-padding no-margin">TRAILER - <span id="film-name"></span></h3>
            </div>
            <div class="modal-body text-center" id="trailer">

            </div>
        </div>
    </div>
    <!-- END fast view of a product -->
@endsection
