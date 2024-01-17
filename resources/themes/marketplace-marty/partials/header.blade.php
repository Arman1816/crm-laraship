<div class="menu-area" >
    <div class="top-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-6 v_middle">
                    <div class="logo">
                        <a href="{{url('/')}}">
                            <img src="{{ \Settings::get('site_logo') }}"
                                 alt="{{ \Settings::get('site_name', 'Corals') }}" class="img-fluid">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-1 col-md-9 col-6 v_middle">
                    <div class="author-area">
                        @auth
                            @php $vendor_role = \Settings::get('marketplace_general_vendor_role', '') @endphp
                            @if ($vendor_role && !user()->hasRole($vendor_role))
                                <a href="{{url('marketplace/store/enroll')}}" class="author-area__seller-btn inline">
                                    @lang('corals-marketplace-marty::labels.partial.become_seller')
                                </a>
                            @endif
                        @endauth
                        <div class="author__notification_area">
                            <ul>
                                @auth
                                    <li class="has_dropdown">
                                        <div class="icon_wrap">
                                            <span class="lnr lnr-alarm"></span>
                                            <span class="notification_count noti">
                                            @if($unreadNotifications = user()->unreadNotifications()->count())
                                                    {{ $unreadNotifications }}
                                                @endif
                                        </span>
                                        </div>
                                        <div class="dropdowns notification--dropdown">
                                            <div class="dropdown_module_header text-left">
                                                <a href="{{url('notifications')}}"
                                                   class="btn btn-primary p-2 inline float-none">@lang('corals-marketplace-marty::labels.template.cart.view_all')</a>
                                            </div>
                                        </div>
                                    </li>
                                @endauth
                                <li class="has_dropdown">
                                    <div class="icon_wrap">
                                        <a href="{{url('cart')}}">
                                            <span class="lnr lnr-cart"></span>
                                            <span class="notification_count purch" id="cart-header-count">
                                                {{ \ShoppingCart::countAllInstances() }}
                                            </span>
                                        </a>
                                    </div>

                                    <div class="dropdowns dropdown--cart">
                                        <div class="cart_area cart_summary">
                                            @include('partials.cart_summary')
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="author-author__info inline has_dropdown">
                            @auth
                                <div class="author__avatar" style="max-width: 50px">
                                    <img src="{!! user()->picture_thumb !!}" alt="user avatar">
                                </div>
                                <div class="autor__info">
                                    <p class="name">
                                        {{user()->name}}
                                    </p>
                                    <p class="ammount">
                                        {{ \ShoppingCart::totalAllInstances() }}
                                    </p>
                                </div>
                                <div class="dropdowns dropdown--author">
                                    <ul>
                                        <li>
                                            <a href="{{ url('profile') }}">
                                                <span class="lnr lnr-user"></span>@lang('corals-marketplace-marty::labels.partial.profile')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('dashboard') }}">
                                                <span class="lnr lnr-home"></span> @lang('corals-marketplace-marty::labels.partial.dashboard')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('logout')}}" data-action="logout">
                                                <span class="lnr lnr-exit"></span>@lang('corals-marketplace-marty::labels.partial.logout')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="autor__info">
                                    <p class="name">
                                        @lang('corals-marketplace-marty::labels.auth.my_account')
                                    </p>
                                    <p class="ammount">
                                        {{ \ShoppingCart::totalAllInstances() }}
                                    </p>
                                </div>
                                <div class="dropdowns dropdown--author">
                                    <ul>
                                        <li>
                                            <a href="{{ url('login') }}">
                                                <span class="lnr lnr-user"></span>@lang('corals-marketplace-marty::labels.partial.login')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('register') }}">
                                                <span class="lnr lnr-home"></span>@lang('corals-marketplace-marty::labels.partial.register')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endauth
                        </div>
                    </div>
                    <div class="mobile_content ">
                        <span class="lnr lnr-user menu_icon"></span>
                        <div class="offcanvas-menu closed">
                            <span class="lnr lnr-cross close_menu"></span>
                            @auth
                                <div class="author-author__info">
                                    <div class="author__avatar v_middle" style="max-width: 50px">
                                        <img src="{!! user()->picture_thumb !!}" alt="user avatar">
                                    </div>
                                    <div class="autor__info v_middle">
                                        <p class="name">
                                            {{user()->full_name}}
                                        </p>
                                        <p class="ammount">{{ \ShoppingCart::totalAllInstances() }}</p>
                                    </div>
                                </div>
                                <div class="author__notification_area">
                                    <ul>
                                        <li>
                                            <a href="{{url('notification')}}">
                                                <div class="icon_wrap">
                                                    <span class="lnr lnr-alarm"></span>
                                                    <span class="notification_count noti">
                                                        @if($unreadNotifications = user()->unreadNotifications()->count())
                                                            {{ $unreadNotifications }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('cart')}}">
                                                <div class="icon_wrap">
                                                    <span class="lnr lnr-cart"></span>
                                                    <span class="notification_count purch">
                                                        {{ \ShoppingCart::countAllInstances() }}
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="dropdowns dropdown--author">
                                    <ul>
                                        <li>
                                            <a href="{{url('profile')}}">
                                                <span class="lnr lnr-user"></span>@lang('corals-marketplace-marty::labels.partial.profile')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('dashboard')}}">
                                                <span class="lnr lnr-home"></span> @lang('corals-marketplace-marty::labels.partial.dashboard')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{route('logout')}}" data-action="logout">
                                                <span class="lnr lnr-exit"></span>@lang('corals-marketplace-marty::labels.partial.logout')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                @php $vendor_role = \Settings::get('marketplace_general_vendor_role', '') @endphp
                                @if ($vendor_role && !user()->hasRole($vendor_role))
                                    <a href="{{url('marketplace/store/enroll')}}"
                                       class="author-area__seller-btn inline">
                                        @lang('corals-marketplace-marty::labels.partial.become_seller')
                                    </a>
                                @endif
                            @else
                                <div class="dropdowns dropdown--author">
                                    <ul>
                                        <li>
                                            <a href="{{url('login')}}">
                                                <span class="lnr lnr-user"></span>@lang('corals-marketplace-marty::labels.partial.login')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('register')}}">
                                                <span class="lnr lnr-home"></span> @lang('corals-marketplace-marty::labels.partial.register')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mainmenu">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-header">
                        <div class="mainmenu__search">
                            <form action="{{ url('shop') }}">
                                <div class="searc-wrap">
                                    <input type="text" name="search" value="{{ request()->get('search') }}"
                                           placeholder="Search product">
                                    <button type="submit" class="search-wrap__btn">
                                        <span class="lnr lnr-magnifier"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand-md navbar-light mainmenu__menu">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                                aria-controls="navbarNav" aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                @include('partials.menu.menu_item', ['menus' => Menus::getMenu('frontend_top','active')])
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>