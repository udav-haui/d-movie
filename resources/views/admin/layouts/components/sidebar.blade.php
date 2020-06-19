<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
        <ul class="nav" id="side-menu">
            <li class="user-pro">
                <a href="{{ route('users.getProfile') }}" class="waves-effect">
                    <div class="dmovie-img-cover-container h-30">
                        <img src="{{ auth()->user()->getAvatarPath() }}" alt="user-img" class="img-circle dmovie-img-cover">
                    </div>
                    <span class="hide-menu"> {{ auth()->user()->getName() }}
                        <span class="fa arrow"></span>
                    </span>
                </a>
                <ul class="nav nav-second-level collapse"
                    aria-expanded="false">
                    <li>
                        <a href="{{ route('users.getProfile') }}"
                           id="user-profile">
                            <i class="ti-user"></i>
                            <span class="hide-menu">{{ __('My Profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" onclick="event.preventDefault(); $('.logout-form').submit();">
                            <i class="fa fa-power-off"></i>
                            <span class="hide-menu">{{ __('Logout') }}</span>
                        </a>
                    </li>
                    <form class="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </li>
            @can('view', \App\Dashboard::class)
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="mdi mdi-av-timer fa-fw" data-icon="v"></i>
                        <span class="hide-menu"> {{ __('Dashboard') }} </span>
                    </a>
                </li>
            @endcan
            @can('view', \App\Booking::class)
                <li>
                    <a href="{{ route('bookings.index') }}" class="waves-effect">
                        <i class="mdi mdi-cart-plus fa-fw" data-icon="v"></i>
                        <span class="hide-menu"> {{ __('Sales Bookings') }} </span>
                    </a>
                </li>
            @endcan
            @can('viewAny', \App\Role::class)
                <li> <a href="{{ route('roles.index') }}" class="waves-effect">
                        <i  class="mdi mdi-settings fa-fw"></i>
                        <span class="hide-menu">{{ __('Roles Manage') }}</span>
                    </a>
                </li>
            @endcan
            @can('viewAny', \App\User::class)
                <li> <a href="{{ route('users.index') }}" class="waves-effect">
                        <i  class="mdi mdi-account-multiple fa-fw"></i>
                        <span class="hide-menu">{{ __('User Manage') }}</span>
                    </a>
                </li>
            @endcan
            @can('view', \App\Slider::class)
                <li> <a href="{{ route('sliders.index') }}" class="waves-effect">
                        <i  class="mdi mdi-folder-multiple-image fa-fw"></i>
                        <span class="hide-menu">{{ __('Slider Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\Film::class)
                <li> <a href="{{ route('films.index') }}" class="waves-effect">
                        <i class="mdi mdi-filmstrip"></i>
                        <span class="hide-menu">{{ __('Films Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\Combo::class)
                <li> <a href="{{ route('combos.index') }}" class="waves-effect">
                        <i class="mdi mdi-popcorn"></i>
                        <span class="hide-menu">{{ __('Combos Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\Cinema::class)
                <li> <a href="{{ route('cinemas.index') }}" class="waves-effect">
                        <i class="mdi mdi-film"></i>
                        <span class="hide-menu">{{ __('Cinemas Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\Show::class)
                <li> <a href="{{ route('shows.index') }}" class="waves-effect">
                        <i class="mdi mdi-home-map-marker"></i>
                        <span class="hide-menu">{{ __('Shows Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\FilmSchedule::class)
                <li> <a href="{{ route('fs.index') }}" class="waves-effect">
                        <i class="mdi mdi-timetable"></i>
                        <span class="hide-menu">{{ __('Schedule Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\Contact::class)
                <li> <a href="{{ route('contacts.index') }}" class="waves-effect">
                        <i class="mdi mdi-contacts"></i>
                        <span class="hide-menu">{{ __('Contacts Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\Customer::class)
                <li> <a href="{{ route('users.customer.index') }}" class="waves-effect">
                        <i class="mdi mdi-account-multiple-outline"></i>
                        <span class="hide-menu">{{ __('Customers Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\StaticPage::class)
                <li> <a href="{{ route('static-pages.index') }}" class="waves-effect">
                        <i class="mdi mdi-format-page-break"></i>
                        <span class="hide-menu">{{ __('Static Pages Manage') }}</span>
                    </a>
                </li>
            @endcan

            <li> <a href="{{ route('store.configs') }}" class="waves-effect">
                    <i class="mdi mdi-format-page-break"></i>
                    <span class="hide-menu">{{ __('Configuration') }}</span>
                </a>
            </li>

            @can('view', \App\Log::class)
                <li> <a href="{{ route('logs.index') }}" class="waves-effect">
                        <i class="mdi mdi-tooltip-edit"></i>
                        <span class="hide-menu">{{ __('View System Logs') }}</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>
