@extends('layouts.public')

@section('css')
    {!! Theme::css('css/custom_checkout.css') !!}
@endsection

@section('content')
    @include('partials.page_header',['title'=>  trans('Marketplace::labels.checkout.title_checkout')  ,'featured_image'=>null])
    @php \Actions::do_action('pre_content', null, null) @endphp
    <div class="container my-5">
        <div id="checkoutWizard">
            <ul>
                @guest
                    <li><a href="#checkout-method"
                           data-content-url="{{url('checkout/step/checkout-method')}}">
                            @lang('corals-marketplace-marty::labels.template.checkout.checkout_method') <br/>
                            <small></small>
                        </a>
                    </li>
                @endguest

                <li><a href="#cart-details" data-content-url="{{url('checkout/step/cart-details')}}">
                        @lang('corals-marketplace-marty::labels.template.checkout.detail')<br/>
                        <small></small>
                    </a></li>

                <li><a href="#billing-shipping-address"
                       data-content-url="{{url('checkout/step/billing-shipping-address')}}">
                        @lang('corals-marketplace-marty::labels.template.checkout.address')<br/>
                        <small></small>
                    </a></li>
                @if($enable_shipping)
                    <li><a href="#shipping-method"
                           data-content-url="{{url('checkout/step/shipping-method')}}">
                            @lang('corals-marketplace-marty::labels.template.checkout.select_shipping')<br/>
                            <small></small>
                        </a></li>
                @endif
                @if($hasPaymentStep)
                    <li><a href="#select-payment" data-content-url="{{url('checkout/step/select-payment')}}">
                            @lang('corals-marketplace-marty::labels.template.checkout.select_payment')<br/>
                            <small></small>
                        </a></li>
                @endif
                <li><a href="#order-review" data-content-url="{{url('checkout/step/order-review')}}">
                        @lang('corals-marketplace-marty::labels.template.checkout.order_review')<br/>
                        <small></small>
                    </a></li>
            </ul>

            <div class="mt-3 box-body" id="checkoutSteps">
                @guest
                    <div id="checkout-method" class="checkoutStep">
                    </div>
                @endguest
                <div id="cart-details" class="checkoutStep">
                </div>
                <div id="billing-shipping-address" class="checkoutStep">
                </div>
                @if($enable_shipping)
                    <div id="shipping-method" class="checkoutStep">
                    </div>
                @endif
                @if($hasPaymentStep)
                    <div id="select-payment" class="checkoutStep">
                    </div>
                @endif
                <div id="order-review" class="checkoutStep">
                </div>
            </div>
        </div>
    </div>
@endsection

@component('components.modal',['id'=>'term','header'=>\Settings::get('site_name').' Terms and policy'])
    {!! \Settings::get('terms_and_policy') !!}
@endcomponent

@section('js')
    @include('Marketplace::checkout.checkout_script')
@endsection
