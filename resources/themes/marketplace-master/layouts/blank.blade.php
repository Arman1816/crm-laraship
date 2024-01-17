<!DOCTYPE html>
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @if($unreadNotifications = user()->unreadNotifications()->count())
            ({{ $unreadNotifications }})
        @endif
        @yield('title') | {{ \Settings::get('site_name', 'Corals') }}
    </title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ \Settings::get('site_favicon') }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! \Assets::css() !!}

    @yield('css')
    @stack('partial_css')

    <script type="text/javascript">
        window.base_url = '{!! url('/') !!}';
    </script>

</head>
<body>

@yield('content')

<!-- corals js -->
{!! Theme::js('js/vendor.min.js') !!}

{!! \Html::script('assets/corals/plugins/lightbox2/js/lightbox.min.js') !!}
{!! \Html::script('assets/corals/plugins/clipboard/clipboard.min.js') !!}
{!! \Html::script('assets/corals/js/corals_functions.js') !!}
{!! \Html::script('assets/corals/js/corals_main.js') !!}

<!-- ================== GLOBAL APP SCRIPTS ==================-->
@include('Corals::corals_main')

@yield('js')

@php  \Actions::do_action('admin_footer_js') @endphp

</body>
</html>