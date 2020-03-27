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
    <script type="text/javascript" src="{{ asset('Assets/Common/Plugins/amy-movie/js/vendor/slick.minaff7aff7.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Assets/Common/Plugins/amy-movie/js/script8a548a54.js') }}"></script>
    <script src="{{ asset('Assets/Common/Plugins/jquery.easing.min.js') }}"></script>
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
                            <li class="active"><a data-toggle="tab" class="no-padding">
                                    <h1 style="color: #fff;" class="bold">
                                        PHIM SẮP CHIẾU</h1>
                                </a></li>
                        </ul>
                    </div>
                    <div class="top-movie">
                        <div>
                            <div class="amy-shortcode amy-mv-carousel">
                                <div class="amy-slick slick-initialized slick-slider">
                                    <button type="button" data-role="none" class="slick-prev slick-arrow"
                                            aria-label="Previous" role="button" style="display: block;">Previous
                                    </button>


                                    <div aria-live="polite" class="slick-list draggable" style="padding: 0px 50px;">
                                        <div class="slick-track"
                                             style="opacity: 1; width: 4526px; transform: translate3d(-730px, 0px, 0px);"
                                             role="listbox">
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="-8" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=cae54d4d-8813-4b9a-8f9e-2227811df69f"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/13/khu-vuon-huyen-bi-104141-130320-43.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=cae54d4d-8813-4b9a-8f9e-2227811df69f"
                                                            tabindex="-1">Khu Vườn Huyền Bí</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Gia đình, Tâm lý</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">24/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="-7" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=d56b73c0-e42f-40aa-8715-aecb2534f5c9"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/11/untitled-1-100716-110320-35.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=d56b73c0-e42f-40aa-8715-aecb2534f5c9"
                                                            tabindex="-1">Black Widow: Góa Phụ Đen</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">30/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="-6" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=9d4df0bb-a929-421b-99e7-06f079502519"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/11/untitled-1-100736-110320-96.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=9d4df0bb-a929-421b-99e7-06f079502519"
                                                            tabindex="-1">Black Widow: Góa Phụ Đen</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">30/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="-5" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=a749f92c-0c6f-41d5-a64a-7a387abc3f3d"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/01/21/untitled-1-095607-210120-15.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=a749f92c-0c6f-41d5-a64a-7a387abc3f3d"
                                                            tabindex="-1">Lật Mặt 5</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động, Kinh
                                                        dị</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">30/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="-4" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=82dc0389-c4e8-41fb-a52e-0d136d38d84c"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/02/22/untitled-1-092648-220220-25.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=82dc0389-c4e8-41fb-a52e-0d136d38d84c"
                                                            tabindex="-1">Truyền Thuyết Về Quán Tiên</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Bí ẩn, Kinh dị</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">30/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned slick-active"
                                                 style="width: 146px;" data-slick-index="-3" aria-hidden="false"
                                                 tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=0774f9a1-2179-4655-bc60-1a4226e78d8c"
                                                       tabindex="0">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2019/12/25/untitled-1-142839-251219-49.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=0774f9a1-2179-4655-bc60-1a4226e78d8c"
                                                            tabindex="0">Phi Công Siêu Đẳng Maverick</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">26/06/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned slick-active"
                                                 style="width: 146px;" data-slick-index="-2" aria-hidden="false"
                                                 tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=530b9ba2-123e-461f-89a4-5e2a4a8f59e8"
                                                       tabindex="0">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/02/19/tiec-tang-mau-poster-105550-190220-50.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=530b9ba2-123e-461f-89a4-5e2a4a8f59e8"
                                                            tabindex="0">Tiệc Trăng Máu</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hài hước</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">28/08/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned slick-active"
                                                 style="width: 146px;" data-slick-index="-1" aria-hidden="false"
                                                 tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=4116e5b0-07fe-40b4-94fa-b5048e319695"
                                                       tabindex="0">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2019/08/22/king-s-man-104931-220819-10.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=4116e5b0-07fe-40b4-94fa-b5048e319695"
                                                            tabindex="0">Kingsman: Khởi Nguồn</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">18/09/2020</span>
                                                </div>
                                            </div>
                                            <div
                                                class="carousel-item slick-slide slick-current slick-active slick-center"
                                                style="width: 146px;" data-slick-index="0" aria-hidden="false"
                                                tabindex="-1" role="option" aria-describedby="slick-slide00">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=56bb35e8-159a-4c61-b099-7577f94df637"
                                                       tabindex="0">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/13/ba-hoafngh-noi-doi-poster-1-103702-130320-22.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=56bb35e8-159a-4c61-b099-7577f94df637"
                                                            tabindex="0">Bà Hoàng Nói Dối</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hài hước</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">20/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-active" style="width: 146px;"
                                                 data-slick-index="1" aria-hidden="false" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide01">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=99b755ce-41d9-45c4-a563-4467dc30710a"
                                                       tabindex="0">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/12/untitled-1-104125-120320-25.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=99b755ce-41d9-45c4-a563-4467dc30710a"
                                                            tabindex="0">Sa Mạc Chết</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Khoa học, viễn
                                                        tưởng</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">20/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-active" style="width: 146px;"
                                                 data-slick-index="2" aria-hidden="false" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide02">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=18562184-7942-4136-b211-810f11be2d3c"
                                                       tabindex="0">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/02/24/ac-quy-rung-sau-poster-web-111820-240220-63.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=18562184-7942-4136-b211-810f11be2d3c"
                                                            tabindex="0">Baba Yaga: Ác Quỷ Rừng Sâu</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Kinh dị, Hồi Hộp</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">27/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-active" style="width: 146px;"
                                                 data-slick-index="3" aria-hidden="false" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide03">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=d184e1f3-f3cd-4ac1-9a7b-79079ac27359"
                                                       tabindex="0">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/04/untitled-1-120427-040320-33.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=d184e1f3-f3cd-4ac1-9a7b-79079ac27359"
                                                            tabindex="0">Cơn Mưa Tình Đầu</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Lãng mạn</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">27/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="4" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide04">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=1bc7a731-5657-4bd3-b181-b2879fcbd001"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/16/untitled-1-100130-160320-12.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=1bc7a731-5657-4bd3-b181-b2879fcbd001"
                                                            tabindex="-1">When She Wakes</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Kinh dị</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">27/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="5" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide05">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=57f25b99-5a49-49f6-a356-bbb1d333615f"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/13/phi-vu-dao-tau-400-x-633-114621-130320-79.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=57f25b99-5a49-49f6-a356-bbb1d333615f"
                                                            tabindex="-1">Phi Vụ Đào Tẩu</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Tội Phạm, Hành
                                                        động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">02/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="6" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide06">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=f9d2cfb1-9103-47fa-8b78-796c6a123de7"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/01/21/untitled-2-103548-210120-25.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=f9d2cfb1-9103-47fa-8b78-796c6a123de7"
                                                            tabindex="-1">Vô Diện Sát Nhân</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Kinh dị</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">17/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="7" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide07">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=cae54d4d-8813-4b9a-8f9e-2227811df69f"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/13/khu-vuon-huyen-bi-104141-130320-43.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=cae54d4d-8813-4b9a-8f9e-2227811df69f"
                                                            tabindex="-1">Khu Vườn Huyền Bí</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Gia đình, Tâm lý</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">24/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="8" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide08">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=d56b73c0-e42f-40aa-8715-aecb2534f5c9"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/11/untitled-1-100716-110320-35.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=d56b73c0-e42f-40aa-8715-aecb2534f5c9"
                                                            tabindex="-1">Black Widow: Góa Phụ Đen</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">30/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="9" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide09">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=9d4df0bb-a929-421b-99e7-06f079502519"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/11/untitled-1-100736-110320-96.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=9d4df0bb-a929-421b-99e7-06f079502519"
                                                            tabindex="-1">Black Widow: Góa Phụ Đen</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">30/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="10" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide010">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=a749f92c-0c6f-41d5-a64a-7a387abc3f3d"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/01/21/untitled-1-095607-210120-15.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=a749f92c-0c6f-41d5-a64a-7a387abc3f3d"
                                                            tabindex="-1">Lật Mặt 5</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động, Kinh
                                                        dị</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">30/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="11" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide011">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=82dc0389-c4e8-41fb-a52e-0d136d38d84c"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/02/22/untitled-1-092648-220220-25.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=82dc0389-c4e8-41fb-a52e-0d136d38d84c"
                                                            tabindex="-1">Truyền Thuyết Về Quán Tiên</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Bí ẩn, Kinh dị</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">30/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="12" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide012">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=0774f9a1-2179-4655-bc60-1a4226e78d8c"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2019/12/25/untitled-1-142839-251219-49.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=0774f9a1-2179-4655-bc60-1a4226e78d8c"
                                                            tabindex="-1">Phi Công Siêu Đẳng Maverick</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">26/06/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="13" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide013">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=530b9ba2-123e-461f-89a4-5e2a4a8f59e8"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/02/19/tiec-tang-mau-poster-105550-190220-50.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=530b9ba2-123e-461f-89a4-5e2a4a8f59e8"
                                                            tabindex="-1">Tiệc Trăng Máu</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hài hước</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">28/08/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide" style="width: 146px;"
                                                 data-slick-index="14" aria-hidden="true" tabindex="-1" role="option"
                                                 aria-describedby="slick-slide014">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=4116e5b0-07fe-40b4-94fa-b5048e319695"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2019/08/22/king-s-man-104931-220819-10.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=4116e5b0-07fe-40b4-94fa-b5048e319695"
                                                            tabindex="-1">Kingsman: Khởi Nguồn</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hành động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">18/09/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned slick-center"
                                                 style="width: 146px;" data-slick-index="15" aria-hidden="true"
                                                 tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=56bb35e8-159a-4c61-b099-7577f94df637"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/13/ba-hoafngh-noi-doi-poster-1-103702-130320-22.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=56bb35e8-159a-4c61-b099-7577f94df637"
                                                            tabindex="-1">Bà Hoàng Nói Dối</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Hài hước</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">20/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="16" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=99b755ce-41d9-45c4-a563-4467dc30710a"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/12/untitled-1-104125-120320-25.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=99b755ce-41d9-45c4-a563-4467dc30710a"
                                                            tabindex="-1">Sa Mạc Chết</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Khoa học, viễn
                                                        tưởng</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">20/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="17" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=18562184-7942-4136-b211-810f11be2d3c"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/02/24/ac-quy-rung-sau-poster-web-111820-240220-63.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=18562184-7942-4136-b211-810f11be2d3c"
                                                            tabindex="-1">Baba Yaga: Ác Quỷ Rừng Sâu</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Kinh dị, Hồi Hộp</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">27/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="18" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=d184e1f3-f3cd-4ac1-9a7b-79079ac27359"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/04/untitled-1-120427-040320-33.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=d184e1f3-f3cd-4ac1-9a7b-79079ac27359"
                                                            tabindex="-1">Cơn Mưa Tình Đầu</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Lãng mạn</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">27/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="19" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=1bc7a731-5657-4bd3-b181-b2879fcbd001"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/16/untitled-1-100130-160320-12.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=1bc7a731-5657-4bd3-b181-b2879fcbd001"
                                                            tabindex="-1">When She Wakes</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Kinh dị</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">27/03/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="20" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=57f25b99-5a49-49f6-a356-bbb1d333615f"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/13/phi-vu-dao-tau-400-x-633-114621-130320-79.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=57f25b99-5a49-49f6-a356-bbb1d333615f"
                                                            tabindex="-1">Phi Vụ Đào Tẩu</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Tội Phạm, Hành
                                                        động</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">02/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="21" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=f9d2cfb1-9103-47fa-8b78-796c6a123de7"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/01/21/untitled-2-103548-210120-25.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=f9d2cfb1-9103-47fa-8b78-796c6a123de7"
                                                            tabindex="-1">Vô Diện Sát Nhân</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Kinh dị</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">17/04/2020</span>
                                                </div>
                                            </div>
                                            <div class="carousel-item slick-slide slick-cloned" style="width: 146px;"
                                                 data-slick-index="22" aria-hidden="true" tabindex="-1">
                                                <div class="carousel-thumb">
                                                    <a href="/chi-tiet-phim.htm?gf=cae54d4d-8813-4b9a-8f9e-2227811df69f"
                                                       tabindex="-1">
                                                        <img
                                                            src="https://files.betacorp.vn/files/media/images/2020/03/13/khu-vuon-huyen-bi-104141-130320-43.jpg"
                                                            class="img-responsive" alt=""></a>
                                                </div>
                                                <div class="carousel-content">
                                                    <h3 class="carousel-title"><a
                                                            href="/chi-tiet-phim.htm?gf=cae54d4d-8813-4b9a-8f9e-2227811df69f"
                                                            tabindex="-1">Khu Vườn Huyền Bí</a>

                                                    </h3>
                                                    <h4 class="font-family-san no-margin font-12">Gia đình, Tâm lý</h4>
                                                    <span class="font-family-oswa color1" style="font-size: 20px;">24/04/2020</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-role="none" class="slick-next slick-arrow"
                                            aria-label="Next" role="button" style="display: block;">Next
                                    </button>
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
