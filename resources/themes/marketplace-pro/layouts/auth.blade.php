<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<!--<![endif]-->
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">

<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" href="{{ \Settings::get('site_favicon') }}" type="image/png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

{!! \SEO::generate() !!}

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
{!! \Html::script('assets/corals/js/corals_header.js') !!}
{!! \Html::style('assets/corals/plugins/lightbox2/css/lightbox.min.css') !!}
{!! \Html::style('assets/corals/plugins/icheck/skins/all.css') !!}
{!! Theme::css('css/owl.carousel.min.css') !!}
{!! Theme::css('css/pricingTable.min.css') !!}
{!! Theme::css('css/nouislider.min.css') !!}
{!! Theme::css('css/font-material/css/material-design-iconic-font.min.css') !!}

<!-- Template CSS -->
    {!! Theme::css('css/template/style.css') !!}
    {!! Theme::css('css/template/reponsive.css') !!}


    {!! Theme::css('css/custom.css')  !!}


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
        {!! \Settings::get('custom_admin_css', '') !!}
    </style>
</head>
<body id="product-detail" class="product-grid-sidebar-left">
<div id="page-preloader">
    <div class="page-loading">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>
</div>

<div id="product-sidebar-left" class="product-grid-sidebar-left blog">
    @include('partials.header')

    <div class="main-content">
        <div id="wrapper-site">
            <div id="content-wrapper">
                <div id="main">
                    <div class="">
                        @yield('content_header')
                        @yield('content')
                        <div class="container">
                            <div class="row">
                                <div class="col m-t-10">
                                    @if(count(\Settings::get('supported_languages', [])) > 1)
                                        {!! \Language::flags('list-inline justify-content-center') !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.mobile_menus',['layout'=>'auth'])
@include('partials.footer')

<div class="back-to-top" style="">
    <a href="#">
        <i class="fa fa-long-arrow-up"></i>
    </a>
</div>
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

<!-- Template JS -->
{!! Theme::js('js/theme.js') !!}

{!! Theme::js('js/functions.js') !!}
{!! Theme::js('js/main_public.js') !!}
{!! \Html::script('assets/corals/js/corals_functions.js') !!}
{!! \Html::script('assets/corals/js/corals_main.js') !!}

{!! Assets::js() !!}


@include('Corals::corals_main')

@yield('js')

@php  \Actions::do_action('admin_footer_js') @endphp

<script type="text/javascript">
    {!! \Settings::get('custom_admin_js', '') !!}
</script>

@include('components.modal',['id'=>'global-modal'])
@include('partials.notifications')

</body>
</html>