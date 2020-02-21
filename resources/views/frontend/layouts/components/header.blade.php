<!-- BEGIN HEADER -->
<div class="header">
    <div class="container">
        <div class="row">
            <a class="site-logo" href="{{ route('frontend.home') }}">
                <img style="height: 55px;" src="{{ asset('images/logo/logo-dm-trim.png') }}" alt="{{ config('app.name') }}"/>
            </a>
            <!-- BEGIN NAVIGATION -->
            <div class="header-navigation pull-right font-transform-inherit">
                <ul>
                    <li>
                        <a href="lich-chieu.html">{{ __('Movies Schedule') }}</a>
                    </li>


                    <li>
                        <a href="phim.html">{{ __('Movies') }}</a>
                    </li>


                    <li>
                        <a href="thong-tin-rap.html">{{ __('Theaters') }}</a>
                    </li>

                    <li>
                        <a href="tin-moi-va-uu-dai.html">{{ __('News and Offers') }}</a>
                    </li>

                    <li>
                        <a href="login-2.html#thongtintaikhoan">{{ __('Membership') }}</a>
                    </li>

                    <!-- BEGIN TOP SEARCH -->

                    <!-- END TOP SEARCH -->
                </ul>
            </div>
            <!-- END NAVIGATION -->
        </div>
    </div>
</div>
<!-- Header END -->
