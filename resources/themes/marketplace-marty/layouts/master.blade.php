<!DOCTYPE html>
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">
<head>
    <title>
        @if($unreadNotifications = user()->unreadNotifications()->count())
            ({{ $unreadNotifications }})
        @endif
        @yield('title') | {{ \Settings::get('site_name', 'Corals') }}
    </title>
    @include('partials.scripts.header')
    {!! Theme::url('css/custom_checkout') !!}
</head>
<body>
@php \Actions::do_action('after_body_open') @endphp

<div class="header-wrap">
    @include('partials.header')
</div>
<div id="mobile-menu" class="side-menu left closed">
    <svg class="svg-plus">
        <use xlink:href="#svg-plus"></use>
    </svg>
    <div class="side-menu-header">
        <figure class="logo small">
            <img src="{{ \Settings::get('site_logo') }}" alt="{{ \Settings::get('site_name', 'Corals') }}">
        </figure>
    </div>
    <p class="side-menu-title">Main Links</p>
    <ul class="dropdown dark hover-effect interactive">
    </ul>
</div>
<div id="account-options-menu" class="side-menu right closed">
    <svg class="svg-plus">
        <use xlink:href="#svg-plus"></use>
    </svg>

    <div class="side-menu-header">
        <div class="user-quickview">
            @auth
                <a href="#">
                    <div class="outer-ring">
                        <div class="inner-ring"></div>
                        <figure class="user-avatar">
                            <img src="{{ user()->picture_thumb }}" alt="{{ auth()->user()->name }}">
                        </figure>
                    </div>
                </a>
                <p class="user-name">{{ user()->full_name }}</p>
                <p class="user-money" id="cart-header-total">{{ \ShoppingCart::totalAllInstances() }}</p>
            @else
                <h3><a href="#" data-toggle="collapse" class="user-name">
                        @lang('corals-marketplace-dragon::labels.auth.login')
                    </a></h3>
            @endauth
        </div>
    </div>
    <ul class="dropdown dark hover-effect">
        @auth
            <li class="dropdown-item">
                <a href="{{ url('profile') }}">@lang('corals-marketplace-dragon::labels.partial.my_profile')</a>
            </li>
            <li class="dropdown-item">
                <a href="{{ url('notifications') }}">Notifications</a>
            </li>
            <li class="dropdown-item">
                <a href="{{url('cart')}}">Your Cart</a>
            </li>
        @else
            <li class="dropdown-item">
                <a href="{{url('login')}}">@lang('corals-marketplace-dragon::labels.partial.login')</a>
            </li>
            <li class="dropdown-item">
                <a href="{{url('register')}}">@lang('corals-marketplace-dragon::labels.partial.register')</a>
            </li>
        @endauth
    </ul>
    @auth
        <p class="side-menu-title">
            <a href="{{url('dashboard')}}">@lang('corals-marketplace-dragon::labels.partial.dashboard')</a></p>
        <a href="{{url('logout')}}" data-action="logout"
           class="button medium secondary">@lang('corals-marketplace-dragon::labels.partial.logout')</a>
        @if(!(isset($hide_sidebar) && $hide_sidebar))
            @php $vendor_role = \Settings::get('marketplace_general_vendor_role', '') @endphp
            @if ($vendor_role  && !user()->hasRole($vendor_role))
                {!! '<a href="' . url('marketplace/store/enroll') . '" class="button medium primary">'.trans('Marketplace::labels.store.become_a_seller').'</a>' !!}

            @endif
        @endif
    @endauth
</div>
<div class="main-menu-wrap">
    <div class="menu-bar">
        <nav>
            <ul class="main-menu">
                @include('partials.menu.menu_item', ['menus' => Menus::getMenu('frontend_top','active')])
            </ul>
        </nav>
        <form class="search-form" method="get" action="{{ url('shop') }}">
            <input type="text" class="rounded" name="search" value="{{ request()->get('search') }}"
                   placeholder="Search products here...">
            <input type="image" src="{{\Theme::url('images/search-icon.png')}}" alt="search-icon">
        </form>
    </div>
</div>
@yield('page_header')
@yield('content')
@include('partials.footer')
@include('partials.scripts.footer')
</body>
</html>