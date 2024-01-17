{!! \Theme::js('js/vendor/jquery/jquery-1.12.3.js') !!}
{!! \Theme::js('js/vendor/jquery/popper.min.js') !!}
{!! \Theme::js('js/vendor/jquery/uikit.min.js') !!}
{!! \Theme::js('js/vendor/bootstrap.min.js') !!}
{!! \Theme::js('js/vendor/chart.bundle.min.js') !!}
{!! \Theme::js('js/vendor/grid.min.js') !!}
{!! \Theme::js('js/vendor/jquery-ui.min.js') !!}
{!! \Theme::js('js/vendor/jquery.barrating.min.js') !!}
{!! \Theme::js('js/vendor/jquery.countdown.min.js') !!}
{!! \Theme::js('js/vendor/jquery.counterup.min.js') !!}
{!! \Theme::js('js/vendor/jquery.easing1.3.js') !!}
{!! \Theme::js('js/vendor/owl.carousel.min.js') !!}
{!! \Theme::js('js/vendor/slick.min.js') !!}
{!! \Theme::js('js/vendor/tether.min.js') !!}
{!! \Theme::js('js/vendor/trumbowyg.min.js') !!}
{!! \Theme::js('js/vendor/waypoints.min.js') !!}
{!! \Theme::js('js/dashboard.js') !!}
{!! \Theme::js('js/main.js') !!}

<!-- Ladda -->
{!! Theme::js('plugins/Ladda/spin.min.js') !!}
{!! Theme::js('plugins/Ladda/ladda.min.js') !!}
{!! Theme::js('plugins/iziToast/js/iziToast.min.js') !!}
{!! Theme::js('plugins/select2/dist/js/select2.full.min.js') !!}
{!! Theme::js('plugins/sweetalert2/dist/sweetalert2.min.js') !!}
<!-- Jquery BlockUI -->
{!! Theme::js('plugins/jquery-block-ui/jquery.blockUI.min.js') !!}

{!! Theme::js('js/functions.js') !!}
{!! Theme::js('js/main_public.js') !!}
{!! \Html::script('assets/corals/js/corals_functions.js') !!}
{!! \Html::script('assets/corals/js/corals_main.js') !!}

@include('Marketplace::cart.cart_script')

{!! Assets::js() !!}

@php  \Actions::do_action('footer_js') @endphp

@yield('js')

<script type="text/javascript">
    {!! \Settings::get('custom_js', '') !!}
</script>
@include('components.modal',['id'=>'global-modal'])
@include('partials.notifications')
@include('Corals::corals_main')
