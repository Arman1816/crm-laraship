<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<!--<![endif]-->
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">

<head>

    <title>
        @if(user() && $unreadNotifications = user()->unreadNotifications()->count())
            ({{ $unreadNotifications }})
        @endif
        @yield('title') | {{ \Settings::get('site_name', 'Corals') }}
    </title>

    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @if(user() && $unreadNotifications = user()->unreadNotifications()->count())
        ({{ $unreadNotifications }})
        @endif
        @yield('title') | {{ \Settings::get('site_name', 'Corals') }}
    </title>
    <link rel="shortcut icon" href="{{ \Settings::get('site_favicon') }}" type="image/png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet">

    <!-- Vendor CSS -->
{!! Theme::css('css/bootstrap.min.css') !!}
{!! Theme::css('font-awesome-4.7.0/css/font-awesome.min.css') !!}
{!! Theme::css('css/nivo-slider.css') !!}
{!! Theme::css('css/animate.css') !!}
{!! Theme::css('css/style.css') !!}
{!! Theme::css('plugins/iziToast/css/iziToast.min.css') !!}
{!! Theme::css('plugins/select2/dist/css/select2.min.css') !!}
{!! Theme::css('plugins/sweetalert2/dist/sweetalert2.css') !!}
{!! Theme::css('css/owl.carousel.min.css') !!}
{!! Theme::css('css/nouislider.min.css') !!}
{!! Theme::css('css/font-material/css/material-design-iconic-font.min.css') !!}

<!-- Template CSS -->
    {!! Theme::css('css/template/style.css') !!}
    {!! Theme::css('css/template/reponsive.css') !!}


    {!! Theme::css('css/custom.css')  !!}
    {!! \Html::style('assets/corals/plugins/lightbox2/css/lightbox.min.css') !!}

    {!! \Html::script('assets/corals/js/corals_header.js') !!}


    <script type="text/javascript">
        window.base_url = '{!! url('/') !!}';
    </script>

    @yield('css')
    @stack('partial_css')

    {!! \Assets::css() !!}

    @if(\Settings::get('google_analytics_id'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id={{ \Settings::get('google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', "{{ \Settings::get('google_analytics_id') }}");
        </script>
    @endif
    <style type="text/css">
        {!! \Settings::get('custom_css', '') !!}
    </style>
</head>
<body id="product-detail" class="product-grid-sidebar-left">

@php \Actions::do_action('after_body_open') @endphp


<div id="page-preloader">
    <div class="page-loading">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>
</div>

@include('partials.header')

<div class="main-content">
    <div id="wrapper-site">
        <div id="content-wrapper">
            <div id="main">
                <div class="">
                    @yield('content_header')
                    <div class="container-fluid  col-md-11 mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                @yield('custom-actions')
                            </div>
                            <div class="col-md-6 text-right" style="padding-bottom: 10px;">
                                @yield('actions')
                            </div>
                        </div>
                        <div class="row" class="my-3">
                            @if(!(isset($hide_sidebar) && $hide_sidebar))
                                <div class="sidebar-3 sidebar-collection col-lg-3 col-md-3 col-sm-4" id="sidebar">
                                    @auth
                                    @php $vendor_role = \Settings::get('marketplace_general_vendor_role', '') @endphp
                                    @if ($vendor_role  && !user()->hasRole($vendor_role))
                                        {!! '<a href="' . url('marketplace/store/enroll') . '" class="btn btn-info btn-block">'.trans('Marketplace::labels.store.become_a_seller').'</a>' !!}

                                    @endif
                                    @endauth
                                    <aside class="user-info-wrapper">
                                        <div class="user-cover"
                                             style="background-image: url({{Theme::url('img/account/user-cover-img.jpg')}});">


                                            @if(\Settings::get('marketplace_checkout_points_redeem_enable',true) )
                                                @php $points= \Referral::getPointsBalance(user()) @endphp
                                                <div class="info-label" data-toggle="tooltip" title=""
                                                     data-original-title="@lang('corals-marketplace-pro::labels.partial.points_spend',['points'=>$points])">
                                                    <i class="far fa-medal"></i><a
                                                            href="{{url('referral/my-referrals')}}">@lang('corals-marketplace-pro::labels.partial.points_balance',['points'=>$points])</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="user-info">
                                            <div class="user-avatar"><a class="edit-avatar"
                                                                        href="{{ url('profile#profile') }}"></a><img
                                                        class="img-fluid" src="{{ user()->picture_thumb }}"
                                                        alt="{{ user()->full_name }}"></div>
                                            <div class="user-data">
                                                <h4>{{ user()->full_name }}</h4>
                                                <span>{{ user()->present('created_at') }}</span>
                                            </div>
                                        </div>
                                    </aside>
                                    <nav class="list-group list-group-root well">
                                        <a class="list-group-item {{ \Request::is('dashboard')?'active':'' }}"
                                           href="{{ url('dashboard') }}">
                                            @lang('corals-marketplace-pro::labels.partial.dashboard')
                                        </a>

                                        @include('partials.menu.admin_menu_item', ['menus'=> \Menus::getMenu('sidebar','active') ])
                                    </nav>
                                </div>
                            @endif
                            <div class="{{ (isset($hide_sidebar) && $hide_sidebar)?'col-md-12':'col-md-9' }}">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.mobile_menus',['layout'=>'dashboard'])
@include('partials.footer')

@yield('after_content')

<!-- Vendor JS -->
{!! Theme::js('js/jquery.min.js') !!}
{!! Theme::js('js/popper.min.js') !!}
{!! Theme::js('js/bootstrap.min.js') !!}
{!! Theme::js('js/jquery.nivo.slider.js') !!}
{!! Theme::js('js/owl.carousel.min.js') !!}
{!! Theme::js('js/nouislider.min.js') !!}
{!! Theme::js('js/scripts.min.js') !!}

{!! Theme::js('plugins/iziToast/js/iziToast.min.js') !!}
{!! \Html::script('assets/corals/plugins/lightbox2/js/lightbox.min.js') !!}
{!! \Html::script('assets/corals/plugins/icheck/icheck.js') !!}

<!-- Ladda -->
{!! Theme::js('plugins/Ladda/spin.min.js') !!}
{!! Theme::js('plugins/Ladda/ladda.min.js') !!}
{!! Theme::js('plugins/select2/dist/js/select2.full.min.js') !!}
{!! Theme::js('plugins/sweetalert2/dist/sweetalert2.min.js') !!}
{!! Theme::js('js/main.js') !!}


<!-- Template JS -->
{!! Theme::js('js/theme.js') !!}

{!! Theme::js('js/functions.js') !!}
{!! \Html::script('assets/corals/js/corals_functions.js') !!}
{!! \Html::script('assets/corals/js/corals_main.js') !!}

{!! Assets::js() !!}

@php \Actions::do_action('admin_footer_js') @endphp

@include('Corals::corals_main')

@yield('js')


<script type="text/javascript">
    {!! \Settings::get('custom_admin_js', '') !!}
</script>

@include('components.modal',['id'=>'global-modal'])
@include('partials.notifications')

</body>
</html>