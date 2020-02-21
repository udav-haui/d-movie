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
                        <li><a href="#tab-2" data-toggle="tab" id="sapchieu">
                                <h1 class="font-30 font-sm-15 font-xs-12">
                                    PHIM SẮP CHIẾU</h1>
                            </a></li>
                        <li class="active"><a href="#tab-1" data-toggle="tab" id="dangchieu">
                                <h1 class="font-30 font-sm-15 font-xs-12">
                                    PHIM ĐANG CHIẾU</h1>
                            </a></li>
                        <li><a href="#tab-3" data-toggle="tab" id="dacbiet">
                                <h1 class="font-30 font-sm-15 font-xs-12">
                                    SUẤT CHIẾU ĐẶC BIỆT</h1>
                            </a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab-1">
                        <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/14/cau-be-ma-154628-140220-88.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-18.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Cậu Bé Ma 2', 'https://www.youtube.com/watch?v=N36w14vqzZ4');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                            <div class="sticker sticker-new"></div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phim0003.html?gf=0ebd6e82-3d4e-4633-883a-00248758fc56">Cậu Bé Ma 2</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Kinh dị</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 85
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '0ebd6e82-3d4e-4633-883a-00248758fc56', 'Cậu Bé Ma 2', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/17/untitled-1-131956-170220-28.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/p.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Nhím Sonic', 'https://www.youtube.com/watch?v=rVffX3rORNI');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                            <div class="sticker sticker-new"></div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phimd370.html?gf=51d792a2-e5e0-466a-b78d-9f0dc6913a4b">Nhím Sonic</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Hành động, Phiêu lưu</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 107
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '51d792a2-e5e0-466a-b78d-9f0dc6913a4b', 'Nhím Sonic', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/01/16/untitled-1-140223-160120-97.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-16.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Tiếng Gọi Nơi Hoang Dã', 'https://www.youtube.com/watch?v=uY1JV9nZcSI');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                            <div class="sticker sticker-new"></div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phima2b1.html?gf=591380ed-cdf9-4fc7-846d-85a6a86b3c12">Tiếng Gọi Nơi Hoang Dã</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Hồi Hộp, Phiêu lưu</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 105
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '591380ed-cdf9-4fc7-846d-85a6a86b3c12', 'Tiếng Gọi Nơi Hoang Dã', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/17/untitled-1-172004-170220-72.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-16.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Gái Già Lắm Chiêu 3', 'https://www.youtube.com/watch?v=zlVtqlV3YT4');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                            <div class="sticker sticker-new"></div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phim2d53.html?gf=f0a2a971-2dc6-4a27-bcfc-21cf953c403b">Gái Già Lắm Chiêu 3</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Hồi Hộp</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 116
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', 'f0a2a971-2dc6-4a27-bcfc-21cf953c403b', 'Gái Già Lắm Chiêu 3', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/20/untitled-1-133506-200220-32.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-18.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Đôi Mắt Âm Dương', 'https://www.youtube.com/watch?v=smonsZ4WArA');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phim5d23.html?gf=9272de4f-d618-4b0d-aa48-fe202ad66e5a">Đôi Mắt Âm Dương</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Kinh dị</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 101
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '9272de4f-d618-4b0d-aa48-fe202ad66e5a', 'Đôi Mắt Âm Dương', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2019/12/27/untitled-1-175412-271219-31.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-18.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Birds of Prey: Cuộc Lột Xác Huy Hoàng Của Harley Quinn', 'https://www.youtube.com/watch?v=SSh0T6aZK1Q');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phimfe9a.html?gf=43623351-f679-4883-8209-27152d7dc756">Birds of Prey: Cuộc Lột Xác Hu...</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Hành động, Phiêu lưu</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 111
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '43623351-f679-4883-8209-27152d7dc756', 'Birds of Prey: Cuộc Lột Xác Huy Hoàng Của Harley Quinn', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/04/untitled-2-133150-040220-37.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-18.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Đảo Kinh Hoàng', 'https://www.youtube.com/watch?v=WnfvxH_qrnU');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phimc43c.html?gf=ef4ccdf2-0369-4cf0-999c-43aa9b8aa6db">Đảo Kinh Hoàng</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Kinh dị, Phiêu lưu</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 113
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', 'ef4ccdf2-0369-4cf0-999c-43aa9b8aa6db', 'Đảo Kinh Hoàng', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/15/untitled-1567-114844-150220-33.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-18.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Joker', 'https://www.youtube.com/watch?v=BWNgsCl_tzU');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phim661e.html?gf=933c102c-3440-4bff-a5ca-c021e15f4497">Joker</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Hồi Hộp, Tội Phạm, Tâm lý</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 120
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: none;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '933c102c-3440-4bff-a5ca-c021e15f4497', 'Joker', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/15/untitled-1-143015-150220-29.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-18.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Ký Sinh Trùng', 'https://www.youtube.com/watch?v=cJcj5B8hEto');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phim4b7e.html?gf=62a910d2-7ffd-4461-8a4f-23f8cfcc6f01">Ký Sinh Trùng</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Tâm lý</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 132
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: none;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '62a910d2-7ffd-4461-8a4f-23f8cfcc6f01', 'Ký Sinh Trùng', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/01/poster-howling-village-093007-010220-95.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-18.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Ngôi Làng Tử Khí', 'https://www.youtube.com/watch?v=7LXgjkw0s2Y');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phim6808.html?gf=491571b7-77e7-41fa-ba37-0ff943ef4415">Ngôi Làng Tử Khí</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Kinh dị</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 105
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '491571b7-77e7-41fa-ba37-0ff943ef4415', 'Ngôi Làng Tử Khí', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/01/16/sac-dep-doi-tra-115146-160120-64.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-16.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Sắc Đẹp Dối Trá', 'https://www.youtube.com/watch?v=KASqIulqlrs');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phimc1a4.html?gf=82770e8a-8fd1-4bfd-80ab-71ff95a31749">Sắc Đẹp Dối Trá</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Hài hước, Hành động</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 91
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: block;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '82770e8a-8fd1-4bfd-80ab-71ff95a31749', 'Sắc Đẹp Dối Trá', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/04/so-thu-thoat-e-140844-040220-62.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-13.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Sở Thú Thoát Ế', 'https://www.youtube.com/watch?v=epjhU1_pxSs');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phim73bd.html?gf=3bef084b-d3a7-4ea7-9264-97e645572853">Sở Thú Thoát Ế</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Hài hước</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 117
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: none;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '3bef084b-d3a7-4ea7-9264-97e645572853', 'Sở Thú Thoát Ế', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-16 padding-right-30 padding-left-30 padding-bottom-30">
                                <div class="row">
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="product-item no-padding">
                                            <div class="pi-img-wrapper">
                                                <img class="img-responsive border-radius-20" alt="" src="../files.betacorp.vn/files/media/images/2020/02/04/happy-au-year-140327-040220-29.jpg">
                                                <span style="position: absolute; top: 10px; left: 10px;">
                                                <img src="Assets/Common/icons/films/c-16.png" class="img-responsive" />
                                            </span>
                                                <div class="border-radius-20">
                                                    <a href="#product-pop-up" onclick="viewTrailer('Tháng Năm Hạnh Phúc Ta Từng Có', 'https://www.youtube.com/watch?v=0-wXvUIQoXg');" class="fancybox-fast-view"><i class="fa fa-play-circle"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-16 col-md-16 col-sm-8 col-xs-8">
                                        <div class="film-info film-xs-info">
                                            <h3 class="text-center text-sm-left text-xs-left bold margin-top-5 font-sm-18 font-xs-14" style="max-height: 30px; min-height: 30px;"><a href="chi-tiet-phim3c05.html?gf=4d5ea569-4587-4944-98db-32ecd0987577">Tháng Năm Hạnh Phúc Ta Từng Có</a>
                                            </h3>
                                            <ul class="list-unstyled font-lg font-family-san font-sm-15 font-xs-14">
                                                <li style="max-height: 50px;"><span class="bold">
                                                Thể loại:</span> Hài hước, Lãng mạn</li>
                                                <li><span class="bold">
                                                Thời lượng:</span> 115
                                                    phút
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-center padding-bottom-30" style='min-height: 85px;'>
                                            <a style='display: none;' href="#showtimes-pop-up" onclick="viewsShowtimes('dfd9306f-fbc8-4807-a8c6-5e6c3f7ad71c', '4d5ea569-4587-4944-98db-32ecd0987577', 'Tháng Năm Hạnh Phúc Ta Từng Có', 'Beta Thái Nguyên');" class="btn btn-2 btn-mua-ve2 fancybox-fast-view"><span><i class="fa fa-ticket mr3"></i></span>
                                                MUA VÉ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-2">
                    </div>
                    <div class="tab-pane fade" id="tab-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom.js')
    <script type="text/javascript" src="{{ asset('frontend/js/home.js') }}"></script>
@endsection
