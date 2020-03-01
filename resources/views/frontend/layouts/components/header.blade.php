<!-- BEGIN HEADER -->
<div class="header">
    <div class="container">
        <div class="row">
            <a class="site-logo" href="{{ route('frontend.home') }}">
                <img style="height: 55px;" src="{{ asset('images/logo/logo-dm-trim-text.png') }}" alt="{{ config('app.name') }}"/>
            </a>
            <!-- BEGIN NAVIGATION -->
            <div class="header-navigation pull-right font-transform-inherit">
                <ul class="dmovie-nav">
                    <li>
                        <a href="{{ route('frontend.home') }}">{{ __('Movies Schedule') }}</a>
                    </li>


                    <li>
                        <a href="{{ route('frontend.home') }}">{{ __('Movies') }}</a>
                    </li>


                    <li>
                        <a href="{{ route('frontend.home') }}">{{ __('Theaters') }}</a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.home') }}">{{ __('News and Offers') }}</a>
                    </li>

                    <li>
                        <a href="{{ route('member.show', ['slug' => __('membership')]) }}">{{ __('Membership') }}</a>
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
