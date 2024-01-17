<header>
    <!-- header left mobie -->
    <div class="header-mobile d-md-none">
        <div class="mobile hidden-md-up text-xs-center d-flex align-items-center justify-content-around">

            <!-- menu left -->
            <div id="mobile_mainmenu" class="item-mobile-top">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </div>

            <!-- logo -->
            <div class="mobile-logo">
                <a href="{{ url('/') }}">
                    <img class="img-fluid" src="{{ \Settings::get('site_logo') }}"
                         alt="{{ \Settings::get('site_name', 'Corals') }}" style="max-width: 150px">
                </a>
            </div>

            <!-- menu right -->
            <div class="mobile-menutop" data-target="#mobile-pagemenu">
                <i class="fa fa-ellipsis-h"></i>
            </div>
        </div>

        <!-- search -->
        <div id="mobile_search" class="d-flex">
            <div id="mobile_search_content">
                <form method="get" action="{{ url('shop') }}">
                    <input type="text" name="search"
                           placeholder="@lang('Marketplace::labels.shop.search')"
                           value="{{request()->get('search')}}" class="ui-autocomplete-input">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="desktop_cart">
                <div class="blockcart block-cart cart-preview tiva-toggle">
                    <a href="#">
                        <div class="header-cart tiva-toggle-btn">
                            <span class="cart-products-count"
                                  id="cart-header-count">{{ \ShoppingCart::countAllInstances() }}</span>
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        </div>
                    </a>
                    <div class="dropdown-content">
                        <div class="cart-content">
                            <table>
                                <tbody>
                                <div class="cart_summary">
                                    @include('partials.cart_summary')
                                </div>

                                <tr class="total">
                                    <td colspan="2">@lang('corals-marketplace-pro::labels.template.cart.subtotal'):
                                    </td>
                                    <td id="cart-header-count">{{ \ShoppingCart::totalAllInstances() }}</td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="d-flex justify-content-center">
                                        <div class="cart-button">
                                            <a href="{{ url('cart') }}"
                                               title="View Cart">@lang('corals-marketplace-pro::labels.template.checkout.detail')</a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header desktop -->
    <div class="d-xs-none " style="background-color: #091E08;">
        <div class="container">
            <div class="row" style="margin-top: -30px; height: 80px;">
                <!-- logo -->
                <div class="col-sm-2 col-md-2 d-flex align-items-center">
                    <div id="logo">
                        <a href="{{ url('/') }}">
                            <img class="img-fluid" src="{{ \Settings::get('site_logo') }}"
                                 alt="{{ \Settings::get('site_name', 'Corals') }}">
                        </a>
                    </div>
                </div>
                <!-- menu -->
                <div class="main-menu col-sm-4 col-md-5 align-items-center justify-content-center navbar-expand-md">
                    <div class="menu navbar collapse navbar-collapse">
                        <ul class="menu-top navbar-nav">
                            @include('partials.menu.menu_item', ['menus' => Menus::getMenu('frontend_top','active')])
                        </ul>
                    </div>
                </div>
                <!-- search-->
                <div id="search_widget" class="col-sm-6 col-md-5 align-items-center justify-content-end d-flex">
                    <form method="get" action="{{ url('shop') }}">
                        <input type="text" name="search"
                               placeholder="@lang('Marketplace::labels.shop.search')"
                               value="{{request()->get('search')}}" class="ui-autocomplete-input auto-complete" autocomplete="off"  data-url="{{ url('shop/autocomeplete') }}">
                        <button type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>

                    <!-- acount  -->
                    <div id="block_myaccount_infos" class="hidden-sm-down dropdown">
                        <div class="myaccount-title">
                            @auth
                                <a href="#acount" data-toggle="collapse" class="acount">
                                    <img style="border-radius:50px; max-width: 22px" src="{{ user()->picture_thumb }}">
                                    <span style="color:white; font-size: 14px">{{ auth()->user()->name }}</span>
                                    <i style="color:white; font-size: 14px;" class="fa fa-angle-down" aria-hidden="true"></i>
                                </a>
                            @else
                                <a href="#acount" data-toggle="collapse" class="acount">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span style="color:white; font-size: 14px">@lang('corals-marketplace-pro::labels.auth.login')</span>
                                    <i style="color:white" class="fa fa-angle-down" aria-hidden="true"></i>
                                </a>
                            @endauth
                        </div>
                        <div id="acount" class="collapse" style="margin-top: -30px; border-radius: 10px;">
                            <div class="account-list-content" style="background-color:#091E08; opacity: 0.9; border-radius: 10px;">
                                @auth
                                    <div>
                                        <a style="color:white" class="login" href="{{ url('dashboard') }}" rel="nofollow"
                                           title="Log in to your customer account">
                                            <i style="color:white" class="fa fa-cog"></i>
                                            <span style="color:white">@lang('corals-marketplace-pro::labels.partial.dashboard')</span>
                                        </a>
                                    </div>
                                    <div>
                                        <a class="login" href="{{ url('profile') }}" rel="nofollow"
                                           title="Log in to your customer account">
                                            <i style="color:white" class="fa fa-sign-in"></i>
                                            <span style="color:white">@lang('corals-marketplace-pro::labels.partial.my_profile')</span>
                                        </a>
                                    </div>
                                    <div>
                                        <a class="register" href="{{ route('logout') }}" data-action="logout"
                                           rel="nofollow"
                                           title="Register Account">
                                            <i style="color:white" class="fa fa-user"></i>
                                            <span style="color:white">@lang('corals-marketplace-pro::labels.partial.logout')</span>
                                        </a>
                                    </div>
                                    <div id="desktop_currency_selector"
                                         class="currency-selector groups-selector hidden-sm-down">
                                        <ul class="list-inline-2">
                                            @php \Actions::do_action('post_display_frontend_menu') @endphp
                                        </ul>
                                    </div>
                                    <div id="desktop_language_selector"
                                         class="currency-selector groups-selector hidden-sm-down"
                                         style="padding-bottom: 50px;">
                                        @if(count(\Settings::get('supported_languages', [])) > 1)
                                            {!! \Language::flags('list-inline','list-inline-item') !!}
                                        @endif
                                    </div>
                                @else
                                    <div>
                                        <a class="check-out" href="{{ route('login') }}" rel="nofollow"
                                           title="Checkout">
                                            <i style="color:white" class="fa fa-check" aria-hidden="true"></i>
                                            <span style="color:white"> @lang('corals-marketplace-pro::labels.partial.login')</span>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('register') }}" title="My Wishlists">
                                            <i style="color:white" class="fa fa-heart"></i>
                                            <span style="color:white"> @lang('corals-marketplace-pro::labels.partial.register')</span>
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="desktop_cart">
                        <div class="blockcart block-cart cart-preview tiva-toggle">
                            <div class="header-cart tiva-toggle-btn" >
                                   
                                <i style="color:white;" class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span style="color:white" class="cart-products-count"
                                          id="cart-header-count">{{ \ShoppingCart::countAllInstances() }}</span>
                            </div>
                            <div class="cart_summary">
                                @include('partials.cart_summary')
                            </div>
                        </div>
                    </div>

                    @auth
                        <div class="desktop_cart">
                            <a class="waves-effect waves-dark"
                               href="{{ url('notifications') }}"
                               aria-expanded="false">
                                <div class="notifications">
                                    <div class="blockcart block-cart cart-preview tiva-toggle">
                                        <div class="header-cart tiva-toggle-btn">
                                            @if($unreadNotifications = user()->unreadNotifications()->count() > 0)
                                                <span class="" style="color:white;">
                                      @if($unreadNotifications = user()->unreadNotifications()->count())
                                                        {{ $unreadNotifications }}
                                                    @endif
                                    </span>
                                            @endif
                                            <i style="color:white" class="fa fa-bell"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>