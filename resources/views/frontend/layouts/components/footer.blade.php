<!-- BEGIN PRE-FOOTER -->
<div class="pre-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-16 pre-footer-col">
                <div id="BodyContent_ctl00_bottomPanel" class="ecm-panel">
                    <ul class="list-unstyled">
                        <li class="col-lg-16 col-md-16 col-xs-16 margin-xs-bottom-10">
                            <a class="site-logo" href="home.html"><img style="width: 120px;" alt="" src="{{ asset('images/logo/logo-dm-trim-text.png') }}" alt="{{ config('app.name') }}"/></a>
                        </li>
                        <?php /** @var \App\StaticPage $page */ ?>
                        @foreach($activePagesGlobal as $page)
                            <li class="col-lg-16 col-md-8 col-sm-8 col-xs-8">
                                <i class="fa fa-angle-right"></i>
                                <a href="{{ route('fe.showStaticPage', ['pageSlug' => $page->getSlug()]) }}">{{ $page->getName() }}</a>
                            </li>
                        @endforeach
                        <li class="col-lg-16 col-md-8 col-sm-8 col-xs-8">
                            <i class="fa fa-angle-right"></i>
                            <a href="{{ route('fe.showStaticPage', ['pageSlug' => convert_vi_to_en(__('contact'))]) }}">{{ __('Contact') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-13 col-sm-16 pre-footer-col">
                <div id="BodyContent_ctl00_bottomRightPanel" class="ecm-panel">
                    <div class="col-md-8 col-sm-16 pre-footer-col">
                        <h2 class="display-block">{{ __('D-Theaters') }}</h2>
                        <ul class="col-md-16 list-unstyled" style="float: left;">
                            <?php /** @var \App\Cinema $cinema */ ?>
                            @foreach($activeCinemasGlobal as $cinema)
                                <li>
                                    <i class="fa fa-angle-right"></i>
                                    {{ $cinema->getName() }} - {{ $cinema->getPhone() }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-4 col-sm-16 pre-footer-col">
                        <h2>{{ __('Connect with us') }}</h2>
                        <ul class="social-icons">
                            <li><a class="facebook" data-original-title="facebook" href="https://www.facebook.com/dmovie.vn/"></a></li>
                            <li><a class="youtube" data-original-title="youtube" href="https://www.youtube.com/channel/dmovie.vn"></a></li>
                            <li><a class="twitter" data-original-title="twitter" href="https://twitter.com/dmovie.vn"></a></li>
                            <li><a class="instagram" data-original-title="instagram" href="https://www.instagram.com/dmovie.vn/"></a></li>
                            <li><a class="zalo" data-original-title="zalo" href="https://zalo.me/dmovie.vn"></a></li>
                        </ul>
{{--                        <img style="width: 180px;" alt="" src="{{ asset('Assets/Common/logo/dathongbao.png') }}" alt="D-Movie"/>--}}
                    </div>
                    <div class="col-md-4 col-sm-16 pre-footer-col">
                        <h2>{{ __('Contact') }}</h2>
                        <div style="float: left;">
                            <h4 class="no-margin">{{ __('DMedia JSC') }}</h4>
                            <h6>
                                {{ __('Business registration certificate: :number', ['number' => '012345678']) }}
                            </h6>
                            <h6>{{ __(':floorNumber Floor, No. :buildingNumber, :streetName St., :wardName Ward, :distName Dist., :cityName', ['floorNumber' => 'xx', 'buildingNumber' => 'xx', 'streetName' => 'xxxx xxxx', 'wardName' => 'xxxx xxxx', 'distName' => 'xxx xxx', 'cityName' => __('Ha Noi')]) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PRE-FOOTER -->
