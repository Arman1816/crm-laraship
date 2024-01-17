@extends('layouts.public')

@section('content')
    
    <div class="product-cart checkout-cart container" style="margin-top: -50px;">
        <div class="row" id="cart">
            <div id="content-wrapper" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 onecol">
                <section id="main">

                    @if (count(ShoppingCart::getAllInstanceItems()) > 0)
                        <div class="cart-grid row" style="padding: 10px;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
                            <div class="col-md-12 col-xs-12 check-info">
       <div class="cart-overview js-cart" >
                                    
                                <h1 class="title-page d-inline-block">@lang('corals-marketplace-pro::labels.template.cart.product')</h1>
                                <a class="btn btn-sm float-right" href="{{ url('cart/empty') }}"
                                   title="Delete" data-action="post" data-page_action="site_reload">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    
                                </a>


                                        <ul class="cart-items" style="margin-top: -40px; border: 1px solid #091E08; border-radius: 5px;">
                                            @foreach (\ShoppingCart::getAllInstanceItems() as $item)
                                                <li style="padding: -20px 0px; !importent" class="cart-item" id="item-{{$item->getHash()}}">
                                                    <div class="row">
                                                        <!--  product left content: image-->
                                                        <div style="margin-top: -20px;" class="product-line-grid-left col-md-2">
                                                            <span class="product-image">
                                                                <a href="{{ url('shop', [$item->id->product->slug]) }}">
                                                                    <img style="height: 90px; width: 90px; object-fit: contain" class="img-fluid" src="{{ $item->id->product->non_featured_image }}"
                                                                         alt="SKU Image">
                                                                </a>
                                                            </span>
                                                        </div>
                                                        <div class="product-line-grid-body col-md-6">
                                                            <div class="product-line-info">
                                                                <a class="label"
                                                                   href="{{ url('shop', [$item->id->product->slug]) }}"
                                                                   data-id_customization="0">
                                                                    {!! $item->id->product->name !!}
                                                                    [{{$item->id->code }}]
                                                                </a>
                                                                {!!  $item->id->presenter()['options']  !!}
                                                                {!! formatArrayAsLabels(\OrderManager::mapSelectedAttributes($item->product_options), 'success',null,true)    !!}
                                                            </div>
                                                            <div class="product-line-info product-price">
                                                                <span class="value"> {{ \Payments::currency( $item->price) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="product-line-grid-right text-center product-line-actions col-md-4">
                                                            <div class="row">
                                                                <div class="col-md-5 col qty">
                                                                    @if(!$item->id->isAvailable($item->qty))
                                                                        @lang('Marketplace::labels.shop.out_stock')
                                                                    @else
                                                                        @if($item->id->allowed_quantity != 1)
                                                                            <form action="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                                  method="POST"
                                                                                  class="ajax-form form-inline"
                                                                                  data-page_action="updateCart">
                                                                                {{ csrf_field() }}
                                                                                <div class="label">@lang('corals-marketplace-pro::labels.template.cart.quantity')</div>
                                                                                <div class="quantity">
                                                                                    <input step="1" min="1"
                                                                                           class="input-group form-control cart-quantity"
                                                                                           type="number"
                                                                                           name="quantity"
                                                                                           data-id="{{ $item->rowId }}"
                                                                                           {!! $item->id->allowed_quantity>0?('max="'.$item->id->allowed_quantity.'"'):'' !!} value="{{ $item->qty }}"/>
                                                                                    <span class="input-group-btn-vertical">

                                                                                <a href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                                   class="btn btn-touchspin js-touchspin bootstrap-touchspin-up"
                                                                                   title="Add One" data-action="post"
                                                                                   data-style="zoom-in"
                                                                                   data-page_action="updateCart"
                                                                                   data-request_data='@json(["action"=>"increaseQuantity"])'>
                                                                                    <i class="fa fa-fw fa-plus"></i>
                                                                                </a>
                                                                                <a href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                                   class="btn btn-touchspin js-touchspin bootstrap-touchspin-down"
                                                                                   title="Remove One"
                                                                                   data-action="post"
                                                                                   data-style="zoom-in"
                                                                                   data-request_data='@json(["action"=>"decreaseQuantity"])'
                                                                                   data-page_action="updateCart">
                                                                                    <i class="fa fa-fw fa-minus"></i>
                                                                                </a>
                                                                            </span>
                                                                                </div>
                                                                            </form>
                                                                        @else
                                                                            <input style="width:40px;text-align: center;"
                                                                                   value="1"
                                                                                   disabled/>

                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-5 col price">
                                                                    <div class="label">@lang('corals-marketplace-pro::labels.template.cart.subtotal')</div>
                                                                    <div class="text-center text-lg text-medium product-price"
                                                                         id="item-total-{{$item->getHash()}}">
                                                                        {{ $item->subtotal() }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col text-xs-right align-self-end">
                                                                    <div class="cart-line-product-actions ">
                                                                        <a class="remove-from-cart" rel="nofollow"
                                                                           href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                           data-action="post" data-style="zoom-in"
                                                                           data-request_data='@json(["action"=>"removeItem"])'
                                                                           data-page_action="updateCart"
                                                                           data-toggle="tooltip"
                                                                           title="Remove item">
                                                                            <i class="fa fa-trash-o"
                                                                               aria-hidden="true"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                
                                <a style="background-color: #091E08; color:white" href="{{ url('shop') }}" class="continue btn btn-primary pull-xs-right">
                                    @lang('corals-marketplace-pro::labels.template.cart.continue_shopping')
                                </a>
                                <span class="col-md-3 col price" style="border: solid 1px #091E08; border-radius: 50px; padding-top: 7px;">
                                    <span class="label">@lang('corals-marketplace-pro::labels.template.cart.subtotal')</span>
                                    <span class="product-price total" id="total">
                                        {{ ShoppingCart::subTotalAllInstances() }}
                                    </span>
                                    @if(Settings::get('marketplace_tax_tax_included_in_price'))
                                        <small style="font-size: 9px">@lang('Marketplace::attributes.product.tax_included') </small>
                                    @endif
                                </span>
                                <a style="background-color: #091E08; color:white" href="{{ url('checkout') }}" class="continue btn btn-primary float-right">
                                    @lang('corals-marketplace-pro::labels.template.cart.checkout')
                                </a>
                                
                            </div>
                        </div>

                    @else
                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-10 text-center" style="padding: 50px; box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;">
                                <h3>@lang('corals-marketplace-pro::labels.template.cart.have_no_item')</h3>

                                <a style="margin-top:30px; background-color: #091E08; color:white" href="{{ url('shop') }}"
                                   class="btn btn-light">@lang('corals-marketplace-pro::labels.template.cart.continue_shopping')</a>
                            </div>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
@stop

