<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
        <ul class="nav" id="side-menu">
            <li class="user-pro">
                <a href="#" class="waves-effect">
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
                        <a href="{{ route('users.show', ['user' => auth()->user()->getAuthIdentifier()]) }}"
                           id="user-profile"
                           class="{{ request()->segment(2) != 'user' ? '' : 'active' }}">
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

            @can('view', \App\Repositories\Interfaces\FilmInterface::class)
                <li> <a href="{{ route('films.index') }}" class="waves-effect">
                        <i class="mdi mdi-filmstrip"></i>
                        <span class="hide-menu">{{ __('Films Manage') }}</span>
                    </a>
                </li>
            @endcan

            @can('view', \App\Repositories\Interfaces\CinemaInterface::class)
                <li> <a href="{{ route('cinemas.index') }}" class="waves-effect">
                        <i class="mdi mdi-film"></i>
                        <span class="hide-menu">{{ __('Cinemas Manage') }}</span>
                    </a>
                </li>
            @endcan
            <li> <a href="#" class="waves-effect"><i class="mdi mdi-format-color-fill fa-fw"></i> <span class="hide-menu">UI Elements<span class="fa arrow"></span> <span class="label label-rouded label-info pull-right">20</span> </span></a>

            </li>
            <li> <a href="#" class="waves-effect"><i class="mdi mdi-content-copy fa-fw"></i> <span class="hide-menu">Sample Pages<span class="fa arrow"></span><span class="label label-rouded label-warning pull-right">30</span></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="starter-page.html"><i class="ti-layout-width-default fa-fw"></i> <span class="hide-menu">Starter Page</span></a></li>
                    <li><a href="blank.html"><i class="ti-layout-sidebar-left fa-fw"></i> <span class="hide-menu">Blank Page</span></a></li>
                    <li><a href="javascript:void(0)" class="waves-effect"><i class="ti-email fa-fw"></i> <span class="hide-menu">Email Templates</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li> <a href="../email-templates/basic.html"><i class="fa-fw">B</i> <span class="hide-menu">Basic</span></a></li>
                            <li> <a href="../email-templates/alert.html"><i class="ti-alert fa-fw"></i> <span class="hide-menu">Alert</span></a></li>
                            <li> <a href="../email-templates/billing.html"><i class="ti-wallet fa-fw"></i> <span class="hide-menu">Billing</span></a></li>
                            <li> <a href="../email-templates/password-reset.html"><i class="ti-more fa-fw"></i> <span class="hide-menu">Reset Pwd</span></a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)" class="waves-effect"><i class="ti-lock fa-fw"></i><span class="hide-menu">Authentication</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li><a href="login.html"><i class="fa-fw">L</i> <span class="hide-menu">Login Page</span></a></li>
                            <li><a href="login2.html"><i class="fa-fw">L</i> <span class="hide-menu">Login v2</span></a></li>
                            <li><a href="register.html"><i class="fa-fw">R</i> <span class="hide-menu">Register</span></a></li>
                            <li><a href="register2.html"><i class="fa-fw">R</i> <span class="hide-menu">Register v2</span></a></li>
                            <li><a href="register3.html"><i class="fa-fw">3</i> <span class="hide-menu">3 Step Registration</span></a></li>
                            <li><a href="recoverpw.html"><i class="fa-fw">R</i> <span class="hide-menu">Recover Password</span></a></li>
                            <li><a href="lock-screen.html"><i class="fa-fw">L</i> <span class="hide-menu">Lock Screen</span></a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)" class="waves-effect"><i class="ti-info-alt fa-fw"></i><span class="hide-menu">Error Pages</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li><a href="400.html"><i class="ti-info-alt fa-fw"></i> <span class="hide-menu">Error 400</span></a></li>
                            <li><a href="403.html"><i class="ti-info-alt fa-fw"></i> <span class="hide-menu">Error 403</span></a></li>
                            <li><a href="404.html"><i class="ti-info-alt fa-fw"></i> <span class="hide-menu">Error 404</span></a></li>
                            <li><a href="500.html"><i class="ti-info-alt fa-fw"></i> <span class="hide-menu">Error 500</span></a></li>
                            <li><a href="503.html"><i class="ti-info-alt fa-fw"></i> <span class="hide-menu">Error 503</span></a></li>
                        </ul>
                    </li>
                    <li><a href="lightbox.html"><i class="fa-fw">L</i> <span class="hide-menu">Lightbox Popup</span></a></li>
                    <li><a href="treeview.html"><i class="fa-fw">T</i> <span class="hide-menu">Treeview</span></a></li>
                    <li><a href="search-result.html"><i class="fa-fw">S</i> <span class="hide-menu">Search Result</span></a></li>
                    <li><a href="utility-classes.html"><i class="fa-fw">U</i> <span class="hide-menu">Utility Classes</span></a></li>
                    <li><a href="custom-scroll.html"><i class="fa-fw">C</i> <span class="hide-menu">Custom Scrolls</span></a></li>
                    <li><a href="animation.html"><i class="fa-fw">A</i> <span class="hide-menu">Animations</span></a></li>
                    <li><a href="profile.html"><i class="fa-fw">P</i> <span class="hide-menu">Profile</span></a></li>
                    <li><a href="invoice.html"><i class="fa-fw">I</i> <span class="hide-menu">Invoice</span></a></li>
                    <li><a href="faq.html"><i class="fa-fw">F</i> <span class="hide-menu">FAQ</span></a></li>
                    <li><a href="gallery.html"><i class="fa-fw">G</i> <span class="hide-menu">Gallery</span></a></li>
                    <li><a href="pricing.html"><i class="fa-fw">P</i> <span class="hide-menu">Pricing</span></a></li>
                </ul>
            </li>
            <li><a href="inbox.html" class="waves-effect"><i class="mdi mdi-apps fa-fw"></i> <span class="hide-menu">Apps<span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="chat.html"><i class="ti-comments-smiley fa-fw"></i><span class="hide-menu">Chat-message</span></a></li>
                    <li><a href="javascript:void(0)" class="waves-effect"><i class="ti-desktop fa-fw"></i><span class="hide-menu">Inbox</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li> <a href="inbox.html"><i class="ti-email fa-fw"></i><span class="hide-menu">Mail box</span></a></li>
                            <li> <a href="inbox-detail.html"><i class="ti-layout-media-left-alt fa-fw"></i><span class="hide-menu">Inbox detail</span></a></li>
                            <li> <a href="compose.html"><i class="ti-layout-media-center-alt fa-fw"></i><span class="hide-menu">Compose mail</span></a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)" class="waves-effect"><i class="ti-user fa-fw"></i><span class="hide-menu">Contacts</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li> <a href="contact.html"><i class="icon-people fa-fw"></i><span class="hide-menu">Contact1</span></a></li>
                            <li> <a href="contact2.html"><i class="icon-user-follow fa-fw"></i><span class="hide-menu">Contact2</span></a></li>
                            <li> <a href="contact-detail.html"><i class="icon-user-following fa-fw"></i><span class="hide-menu">Contact Detail</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="devider"></li>
            <li> <a href="forms.html" class="waves-effect"><i class="mdi mdi-clipboard-text fa-fw"></i> <span class="hide-menu">Forms<span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="form-basic.html"><i class="fa-fw">B</i><span class="hide-menu">Basic Forms</span></a></li>
                    <li><a href="form-layout.html"><i class="fa-fw">L</i><span class="hide-menu">Form Layout</span></a></li>
                    <li><a href="form-advanced.html"><i class="fa-fw">A</i><span class="hide-menu">Form Addons</span></a></li>
                    <li><a href="form-material-elements.html"><i class="fa-fw">M</i><span class="hide-menu">Form Material</span></a></li>
                    <li><a href="form-float-input.html"><i class="fa-fw">F</i><span class="hide-menu">Form Float Input</span></a></li>
                    <li><a href="form-upload.html"><i class="fa-fw">U</i><span class="hide-menu">File Upload</span></a></li>
                    <li><a href="form-mask.html"><i class="fa-fw">M</i><span class="hide-menu">Form Mask</span></a></li>
                    <li><a href="form-img-cropper.html"><i class="fa-fw">C</i><span class="hide-menu">Image Cropping</span></a></li>
                    <li><a href="form-validation.html"><i class="fa-fw">V</i><span class="hide-menu">Form Validation</span></a></li>
                    <li><a href="form-dropzone.html"><i class="fa-fw">D</i><span class="hide-menu">File Dropzone</span></a></li>
                    <li><a href="form-pickers.html"><i class="fa-fw">P</i><span class="hide-menu">Form-pickers</span></a></li>
                    <li><a href="form-wizard.html"><i class="fa-fw">W</i><span class="hide-menu">Form-wizards</span></a></li>
                    <li><a href="form-typehead.html"><i class="fa-fw">T</i><span class="hide-menu">Typehead</span></a></li>
                    <li><a href="form-xeditable.html"><i class="fa-fw">X</i><span class="hide-menu">X-editable</span></a></li>
                    <li><a href="form-summernote.html"><i class="fa-fw">S</i><span class="hide-menu">Summernote</span></a></li>
                    <li><a href="form-bootstrap-wysihtml5.html"><i class=" fa-fw">W</i><span class="hide-menu">Bootstrap wysihtml5</span></a></li>
                    <li><a href="form-tinymce-wysihtml5.html"><i class="fa-fw">T</i><span class="hide-menu">Tinymce wysihtml5</span></a></li>
                </ul>
            </li>
            <li> <a href="tables.html" class="waves-effect"><i class="mdi mdi-table fa-fw"></i> <span class="hide-menu">Tables<span class="fa arrow"></span><span class="label label-rouded label-danger pull-right">9</span></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="basic-table.html"><i class="fa-fw">B</i><span class="hide-menu">Basic Tables</span></a></li>
                    <li><a href="table-layouts.html"><i class="fa-fw">L</i><span class="hide-menu">Table Layouts</span></a></li>
                    <li><a href="data-table.html"><i class="fa-fw">D</i><span class="hide-menu">Data Table</span></a></li>
                    <li><a href="bootstrap-tables.html"><i class="fa-fw">B</i><span class="hide-menu">Bootstrap Tables</span></a></li>
                    <li><a href="responsive-tables.html"><i class="fa-fw">R</i><span class="hide-menu">Responsive Tables</span></a></li>
                    <li><a href="editable-tables.html"><i class="fa-fw">E</i><span class="hide-menu">Editable Tables</span></a></li>
                    <li><a href="foo-tables.html"><i class="fa-fw">F</i><span class="hide-menu">FooTables</span></a></li>
                    <li><a href="jsgrid.html"><i class="fa-fw">J</i><span class="hide-menu">JsGrid Tables</span></a></li>
                </ul>
            </li>
            <li> <a href="#" class="waves-effect"><i class="mdi mdi-chart-bar fa-fw"></i> <span class="hide-menu">Charts<span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level">
                    <li> <a href="flot.html"><i class="fa-fw">F</i><span class="hide-menu">Flot Charts</span></a> </li>
                    <li><a href="morris-chart.html"><i class="fa-fw">M</i><span class="hide-menu">Morris Chart</span></a></li>
                    <li><a href="chart-js.html"><i class="fa-fw">P</i><span class="hide-menu">Chart-js</span></a></li>
                    <li><a href="peity-chart.html"><i class="fa-fw">P</i><span class="hide-menu">Peity Charts</span></a></li>
                    <li><a href="chartist-js.html"><i class="fa-fw">C</i><span class="hide-menu">Chartist-js</span></a></li>
                    <li><a href="knob-chart.html"><i class="fa-fw">K</i><span class="hide-menu">Knob Charts</span></a></li>
                    <li><a href="sparkline-chart.html"><i class="fa-fw">S</i><span class="hide-menu">Sparkline charts</span></a></li>
                    <li><a href="extra-charts.html"><i class="fa-fw">E</i><span class="hide-menu">Extra Charts</span></a></li>
                </ul>
            </li>
            <li class="devider"></li>
            <li> <a href="widgets.html" class="waves-effect"><i  class="mdi mdi-settings fa-fw"></i> <span class="hide-menu">Widgets</span></a> </li>
            <li> <a href="#" class="waves-effect"><i class="mdi mdi-emoticon fa-fw"></i> <span class="hide-menu">Icons<span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level">
                    <li> <a href="fontawesome.html"><i class="fa-fw">F</i><span class="hide-menu">Font awesome</span></a> </li>
                    <li> <a href="themifyicon.html"><i class="fa-fw">T</i><span class="hide-menu">Themify Icons</span></a> </li>
                    <li> <a href="simple-line.html"><i class="fa-fw">S</i><span class="hide-menu">Simple line Icons</span></a> </li>
                    <li> <a href="material-icons.html"><i class="fa-fw">M</i><span class="hide-menu">Material Icons</span></a> </li>
                    <li><a href="linea-icon.html"><i class="fa-fw">L</i><span class="hide-menu">Linea Icons</span></a></li>
                    <li><a href="weather.html"><i class="fa-fw">W</i><span class="hide-menu">Weather Icons</span></a></li>
                </ul>
            </li>
            <li> <a href="map-google.html" class="waves-effect"><i class="mdi mdi-google-maps fa-fw"></i><span class="hide-menu">Google Map</span></a> </li>
            <li> <a href="map-vector.html" class="waves-effect"><i class="mdi mdi-map-marker fa-fw"></i><span class="hide-menu">Vector Map</span></a> </li>
            <li> <a href="calendar.html" class="waves-effect"><i class="mdi mdi-calendar-check fa-fw"></i> <span class="hide-menu">Calendar</span></a></li>
            <li> <a href="javascript:void(0)" class="waves-effect"><i class="mdi mdi-checkbox-multiple-marked-outline fa-fw"></i> <span class="hide-menu">Multi-Level Dropdown<span class="fa arrow"></span></span></a>
                <ul class="nav nav-second-level">
                    <li> <a href="javascript:void(0)"><i data-icon="/" class="linea-icon linea-basic fa-fw"></i><span class="hide-menu">Second Level Item</span></a> </li>
                    <li> <a href="javascript:void(0)"><i data-icon="7" class="linea-icon linea-basic fa-fw"></i><span class="hide-menu">Second Level Item</span></a> </li>
                    <li> <a href="javascript:void(0)" class="waves-effect"><i data-icon="&#xe008;" class="linea-icon linea-basic fa-fw"></i><span class="hide-menu">Third Level </span><span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li> <a href="javascript:void(0)"><i class=" fa-fw">T</i><span class="hide-menu">Third Level Item</span></a> </li>
                            <li> <a href="javascript:void(0)"><i class=" fa-fw">M</i><span class="hide-menu">Third Level Item</span></a> </li>
                            <li> <a href="javascript:void(0)"><i class=" fa-fw">R</i><span class="hide-menu">Third Level Item</span></a> </li>
                            <li> <a href="javascript:void(0)"><i class=" fa-fw">G</i><span class="hide-menu">Third Level Item</span></a> </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="login.html" class="waves-effect"><i class="mdi mdi-logout fa-fw"></i> <span class="hide-menu">Log out</span></a></li>
            <li class="devider"></li>
            <li><a href="documentation.html" class="waves-effect"><i class="fa fa-circle-o text-danger"></i> <span class="hide-menu">Documentation</span></a></li>
            <li><a href="gallery.html" class="waves-effect"><i class="fa fa-circle-o text-info"></i> <span class="hide-menu">Gallery</span></a></li>
            <li><a href="faq.html" class="waves-effect"><i class="fa fa-circle-o text-success"></i> <span class="hide-menu">Faqs</span></a></li>
        </ul>
    </div>
</div>
