
<!-- BEGIN TOP BAR -->
<div class="pre-header">
    <div class="container">
        <div class="row">
            <!-- BEGIN TOP BAR MENU -->
            <div class="col-md-16 col-sm-16 additional-nav">
                <div class="pull-right padding-left-10">
                    @if (app()->getLocale() === \App\Helper\Data::VIETNAM)
                        <a href="{{ route('app.switch-language', ['lang' => 'en']) }}">
                            <img src="{{ asset('Assets/Common/icons/united-kingdom.png') }}" class="img-responsive"/>
                        </a>
                    @else
                        <a href="{{ route('app.switch-language', ['lang' => 'vi']) }}">
                            <img src="{{ asset('Assets/Common/icons/vietnam.png') }}" class="img-responsive"/>
                        </a>
                    @endif
                </div>
                <ul class="list-unstyled list-inline pull-right" style="margin-bottom: 4px;margin-top: 4px;">
                    @guest
                        <li>
                            <a href="{{ route('frontend.login', ['action' => 'login']) }}">{{ __('Login') }}</a>
                        </li>
                        <li style="border-left: 1px solid; padding-left: 10px !important;">
                            <a href="{{ route('frontend.login', ['action' => 'register']) }}">{{ __('Register') }}</a>
                        </li>
                    @else
                        <li class="dropdown dropdown-user">
                            <a href="javascript:void(0);" style="padding: 2px;background-color: transparent;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <span class="username username-hide-on-mobile"> {{ __('Welcome: :name', ['name' => auth()->user()->name ?? 'Unnamed']) }}  </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="{{ route('member.show', ['slug' => __('membership')]) }}"><i class="icon-user"></i> {{ __('My profile') }}</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a onclick="event.preventDefault();$('.logout-form').submit()">
                                        <i class="icon-logout"></i> {{ __('Logout') }}</a>
                                </li>
                                <li class="divider"></li>
                            </ul>
                        </li>


                        <li class="dropdown dropdown-quick-sidebar-toggler">
                            <a href="javascript:void(0);" onclick="event.preventDefault();$('.logout-form').submit();"
                               style="padding: 3px;background-color: transparent;" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li>
                        <form class="logout-form" action="{{ route('frontend.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
            <!-- END TOP BAR MENU -->
        </div>
    </div>
</div>
<!-- END TOP BAR -->
