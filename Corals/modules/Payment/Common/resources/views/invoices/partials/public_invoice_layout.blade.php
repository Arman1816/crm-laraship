<!DOCTYPE html>
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>
        Invoice {{$invoice->code}} | {{ \Settings::get('site_name', 'Corals') }}
    </title>

    <link rel="shortcut icon" href="{{ \Settings::get('site_favicon') }}" type="image/png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fira+Sans">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 3.3.7 -->
{!! Theme::css('plugins/bootstrap/dist/css/bootstrap.min.css') !!}
<!-- Font Awesome -->
{!! Theme::css('plugins/font-awesome/css/font-awesome.min.css') !!}

{!! Theme::css('plugins/select2/dist/css/select2.min.css') !!}
<!-- Theme style -->
<!-- iCheck -->
{!! Theme::css('plugins/iCheck/all.css') !!}
<!-- AdminLTE Skins. Choose a skin from the css/skins -->

    <!-- Pace style -->
{!! Theme::css('plugins/pace/pace.min.css') !!}

<!-- Ladda  -->
{!! Theme::css('plugins/Ladda/ladda-themeless.min.css') !!}


<!-- toastr -->
{!! Theme::css('plugins/toastr/toastr.min.css') !!}
<!-- sweetalert2 -->
{!! Theme::css('plugins/sweetalert2/dist/sweetalert2.css') !!}

{!! Theme::css('css/custom.css') !!}


{!! \Assets::css() !!}

@if(\Language::isRTL())
    {!! Theme::css('css/style-rtl.css') !!}
    {!! Theme::css('plugins/bootstrap/dist/css/bootstrap-rtl.css') !!}

@endif


@yield('css')
@stack('partial_css')

<!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <script type="text/javascript">
        window.base_url = '{!! url('/') !!}';
    </script>

    {!! \Html::script('assets/corals/js/corals_header.js') !!}


    @yield('css')

    @stack('partial_css')

</head>
<body>
<!-- Site wrapper -->
<div class="container">

@yield('content')

<!-- jQuery 3 -->
{!! Theme::js('plugins/jquery/dist/jquery.min.js') !!}
<!-- Bootstrap 3.3.7 -->
{!! Theme::js('plugins/bootstrap/dist/js/bootstrap.min.js') !!}
{!! Theme::js('assets/corals/plugins/lodash/lodash.js') !!}

{!! Assets::js() !!}

<!-- iCheck -->
{!! Theme::js('plugins/iCheck/icheck.min.js') !!}
<!-- Pace -->
{!! Theme::js('plugins/pace/pace.min.js') !!}

<!-- Jquery BlockUI -->
{!! Theme::js('plugins/jquery-block-ui/jquery.blockUI.min.js') !!}

<!-- Ladda -->
{!! Theme::js('plugins/Ladda/spin.min.js') !!}
{!! Theme::js('plugins/Ladda/ladda.min.js') !!}

<!-- toastr -->
{!! Theme::js('plugins/toastr/toastr.min.js') !!}


{!! Theme::js('plugins/sweetalert2/dist/sweetalert2.all.min.js') !!}
{!! Theme::js('plugins/select2/dist/js/select2.full.min.js') !!}
<!-- AdminLTE App -->

{!! Theme::js('js/functions.js') !!}
{!! Theme::js('js/main.js') !!}
<!-- corals js -->
    {!! Theme::js('assets/corals/plugins/lodash/lodash.js') !!}
    {!! \Html::script('assets/corals/plugins/lightbox2/js/lightbox.min.js') !!}
    {!! \Html::script('assets/corals/js/corals_functions.js') !!}
    {!! \Html::script('assets/corals/js/corals_main.js') !!}

    @include('Corals::corals_main')

    @yield('js')


    @include('partials.notifications')


</div>
</body>
</html>
