@extends('admin.layouts.app')

@section('app.title'){{ __('Store Configuration') }}
@endsection

@section('app.description'){{ __('Store Configuration') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Store Configuration') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Store Configuration') }}
@endsection

@section('head.css')
    <link rel="stylesheet" href="{{asset('adminhtml/assets/plugins/sidebar/sidebar-menu.css')}}">
    <link rel="stylesheet" href="{{asset('adminhtml/assets/plugins/accordion/style.css')}}">
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/bower_components/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('adminhtml/assets/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('adminhtml/assets/plugins/bower_components/jquery/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('adminhtml/assets/plugins/sidebar/sidebar-menu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('adminhtml/js/store_config/general.js') }}"></script>
    <script type="text/javascript" src="{{ asset('adminhtml/assets/plugins/accordion/script.js') }}"></script>

    <script>
        window._translations = {!! cache('translations') !!};
    </script>
    @yield('child.bottom.js')
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <div class="white-box" style="min-height: 500px">
                <h3 class="box-title">{{ __('Store Configuration') }}</h3>
                <div class="row">
                    <section class="col-lg-3 col-sm-4 col-xs-12">
                        <ul class="sidebar-menu">
                            <li class="@yield('section.sales.activeClass')">
                                <a class="dot-dot-dot" title="{{ __('Sales') }}" href="javascript:void(0)">
                                    <i class="fa fa-money"></i>
                                    <span>{{ __("Sales") }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="sidebar-submenu @yield('section.sales.submenu.activeClass')">
                                    <li class="@yield('section.sales.subitem.payment_methods.activeClass')">
                                        <a class="dot-dot-dot" title="{{ __("Payment Methods") }}" href="{{ route('store.configs.payment_methods') }}">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                            {{ __("Payment Methods") }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-files-o"></i>
                                    <span>Layout Options</span>
                                </a>
                                <ul class="sidebar-submenu" style="display: none;">
                                    <li><a href="top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                                    <li><a href="boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                                    <li><a href="fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                                    <li class=""><a href="collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="../widgets.html">
                                    <i class="fa fa-th"></i> <span>Widgets</span>
                                    <small class="label pull-right label-info">new</small>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-pie-chart"></i>
                                    <span>Charts</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="sidebar-submenu">
                                    <li><a href="../charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                                    <li><a href="../charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                                    <li><a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                                    <li><a href="../charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
                                </ul>
                            </li>
                        </ul>
                    </section>
                    <section class="col-lg-9 col-sm-8 col-xs-12">
                        <div class="row">
                            @yield('config.content')
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

@endsection
