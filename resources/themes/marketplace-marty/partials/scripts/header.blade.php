{!! \Theme::css('css/animate.css') !!}
{!! \Theme::css('css/font-awesome.min.css') !!}
{!! Theme::css('plugins/iziToast/css/iziToast.min.css') !!}
{!! Theme::css('plugins/Ladda/ladda-themeless.min.css') !!}
{!! Theme::css('plugins/select2/dist/css/select2.min.css') !!}
{!! Theme::css('plugins/sweetalert2/dist/sweetalert2.css') !!}
{!! \Theme::css('css/fontello.css') !!}
{!! \Theme::css('css/jquery-ui.css') !!}
{!! \Theme::css('css/lnr-icon.css') !!}
{!! \Theme::css('css/owl.carousel.css') !!}
{!! \Theme::css('css/slick.css') !!}
{!! \Theme::css('css/trumbowyg.min.css') !!}
{!! \Theme::css('css/bootstrap/bootstrap.min.css') !!}
{!! \Theme::css('css/style.css') !!}
{!! \Theme::css('css/custom.css') !!}
{!! \Html::script('assets/corals/js/corals_header.js') !!}
<style type="text/css">
    .has-error input {
        border: 1px solid #ff1f1f;
    }
</style>
<script type="text/javascript">
    window.base_url = '{!! url('/') !!}';
</script>

@yield('css')
@stack('partial_css')

{!! \Assets::css() !!}

@if(\Language::isRTL())

@endif

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
