@extends('layouts.master')

@section('title',$title)

@section('css')
    <style>
        .shipping-options label {
            display: block;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1 offset-md-1">
            @component('components.box')
                @slot('box_title')
                    @lang('Marketplace::labels.checkout.title_checkout')
                @endslot

                <div id="checkoutWizard">
                    <ul>
                        <li><a href="#cart-details"
                               data-content-url="{{url('marketplace/checkout/step/cart-details')}}">
                                @lang('Marketplace::labels.checkout.cart_detail') <br/>
                                <small></small>
                            </a></li>

                        <li><a href="#billing-shipping-address"
                               data-content-url="{{url('marketplace/checkout/step/billing-shipping-address')}}">
                                @lang('Marketplace::labels.checkout.address_checkout')<br/>
                                <small></small>
                            </a></li>
                        @if($enable_shipping)

                            <li><a href="#shipping-method"
                                   data-content-url="{{url('marketplace/checkout/step/shipping-method')}}">
                                    @lang('Marketplace::labels.checkout.select_shipping')<br/>
                                    <small></small>
                                </a></li>
                        @endif
                        @if($hasPaymentStep)
                            <li><a href="#select-payment"
                                   data-content-url="{{url('marketplace/checkout/step/select-payment')}}">
                                    @lang('Marketplace::labels.checkout.select_payment') <br/>
                                    <small></small>
                                </a></li>
                        @endif
                        <li><a href="#order-review"
                               data-content-url="{{url('marketplace/checkout/step/order-review')}}">
                                @lang('Marketplace::labels.checkout.order_review')<br/>
                                <small></small>
                            </a></li>
                    </ul>

                    <div class="m-t-10 box-body" id="checkoutSteps">
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
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    @include('Marketplace::checkout.checkout_script')
@endsection
