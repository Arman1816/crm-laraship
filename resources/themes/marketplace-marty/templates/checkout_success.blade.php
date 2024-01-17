@extends('layouts.public')

@section('content')
    @php \Actions::do_action('pre_content', null, null) @endphp
    <div class="container">
        <div class="row text-center">
            <div class="col-sm-12">
                <br><br>
                <h2 style="color:#0fad00">@lang('corals-marketplace-marty::labels.template.checkout_success.success')</h2>
                <p style="font-size:20px;color:#5C5C5C;">@lang('corals-marketplace-marty::labels.template.checkout_success.order_has_been_placed')</p>
                @auth
                    <a href="{{ url('marketplace/orders/my') }}"
                       class="btn btn-success btn-sm">@lang('corals-marketplace-marty::labels.template.checkout_success.go_my_order')</a>
                    <br><br>
                @else
                    <h5 class="text text-info">@lang('corals-marketplace-marty::labels.template.checkout_success.order_guest_email_sent',['email'=>isset($order) && $order->billing ?$order->billing['billing_address']['email']:''])</h5>
                @endauth
            </div>
        </div>
    </div>
@endsection
