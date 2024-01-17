<!DOCTYPE html>
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">
<head>

    {!! \SEO::generate() !!}
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{ \Settings::get('site_favicon') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.scripts.header')
</head>
<body class="preload home1 mutlti-vendor">
@php \Actions::do_action('after_body_open') @endphp
@include('partials.header')
@yield('content')
@include('partials.footer')
@include('partials.scripts.footer')
</body>
</html>