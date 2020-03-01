@extends('frontend.layouts.app')

@section('app.title')
    {{ __('Dmovie Home Page') }}
    @endsection

@section('app.description')
    {{ __('Dmovie Home Page') }}
    @endsection

@section('content_top')
    <!--//--- Time Panel ---//-->
    <div class="ecm-panel sliderpanel">
        <!-- BEGIN SLIDER -->
    @include('frontend.layouts.components.slider')
    <!-- END SLIDER -->
    </div>
@endsection

@section('content')
    <div class="ecm-panel" style="position: relative;">
        <div class="container">
            <div class="margin-bottom-35">
                <div class="text-center">
                    <ul class="nav nav-tabs tab-films">
{{--                        <li><a href="#coming-soon" data-toggle="tab" id="_getComingSoon">--}}
{{--                                <h1 class="font-30 font-sm-15 font-xs-12">{{ __('COMING SOON') }}</h1>--}}
{{--                            </a></li>--}}
                        <li class="active"><a href="#tab-1" data-toggle="tab" id="dangchieu">
                                <h1 class="font-30 font-sm-15 font-xs-12">{{ __('NOW SHOWING') }}</h1>
                            </a></li>
{{--                        <li><a href="#sneak-show" data-toggle="tab" id="_getNeakShow">--}}
{{--                                <h1 class="font-30 font-sm-15 font-xs-12">{{ __('SNEAK SHOW') }}</h1>--}}
{{--                            </a></li>--}}
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab-1">
                        <div class="row">
                            <?php /** @var \Illuminate\Database\Eloquent\Collection $films */ ?>
                            <?php /** @var \App\Film $film */?>
                            @foreach($films as $film)
                                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                        <div class="row">
                                            <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                                <div class="product-item no-padding">
                                                    <div class="pi-img-wrapper">
                                                        <img class="img-responsive border-radius-20" alt="" src="{{ $film->getPosterPath() }}">
                                                        <span style="position: absolute; top: 10px; left: 10px;">
                                                            <img src="Assets/Common/icons/films/{{$film->getMark()}}.png" class="img-responsive" />
                                                        </span>
                                                        <div class="border-radius-20">
                                                            <a href="#product-pop-up" onclick="viewTrailer('{{$film->getTitle()}}', '{{$film->getTrailer()}}');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                        </div>
                                                    </div>

                                                    <!--
                                                    <div class="sticker sticker-new"></div>
                                                    -->

                                                </div>
                                            </div>
                                            <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                                <div class="film-info film-xs-info">
                                                    <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14"
                                                        style="max-height: 30px; min-height: 30px;">
                                                        <a href="{{ route('fe.filmDetail', ['slug' => convert_vi_to_en($film->getTitle()), 'film' => $film]) }}">{{ $film->getTitle() }}</a>
                                                    </h3>
                                                    <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                        <li style="max-height: 50px;">
                                                            <span class="bold"> {{ __('Genre') }}: </span> {{ $film->getGenre() }}
                                                        </li>
                                                        <li>
                                                            <span class="bold">{{ __('Running time') }}:</span> {{ $film->getRunningTime() }} {{ __('minutes') }}
                                                        </li>
                                                    </ul>
                                                </div>
                                                @if ($film->isAvailableSale())
                                                    <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                                        <a style='display: block;' href="{{ route('fe.filmDetail', ['slug' => convert_vi_to_en($film->getTitle()), 'film' => $film]) }}"
                                                           class="btn btn-2 btn-mua-ve2 fancybox-fast-view">
                                                            <span class="dmovie-ticket-icon-container"><i class="fa fa-ticket dmovie-ticket-icon mr-3"></i></span>
                                                            {{ __('Booking') }}</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="coming-soon">
                    </div>
                    <div class="tab-pane fade" id="sneak-show">
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

@section('bottom.js')
    <script type="text/javascript" src="{{ asset('frontend/js/home.js') }}"></script>
@endsection
