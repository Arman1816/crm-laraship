@extends('layouts.public')

@section('content')
    @include('partials.page_header',['content' => '<h3><i class="fa fa-shopping-cart fa-fw"></i> Cart</h3>'])
    @php \Actions::do_action('pre_content', null, null) @endphp

    <section class="cart_area section--padding2 bgcolor">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product_archive added_to__cart">
                        <div class="title_area">
                            <div class="row">
                                <div class="col-md-5">
                                    <h4>@lang('corals-marketplace-marty::labels.template.cart.product')</h4>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="add_info">
                                        @lang('corals-marketplace-marty::labels.template.cart.quantity')
                                    </h4>
                                </div>
                                <div class="col-md-2">
                                    <h4>@lang('corals-marketplace-marty::labels.template.cart.price')</h4>
                                </div>
                                <div class="col-md-2">
                                    <h4>@lang('corals-marketplace-marty::labels.template.cart.clear_cart')</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach(\ShoppingCart::getAllInstanceItems() as $item)
                                <div class="col-md-12" id="item-{{$item->getHash()}}">
                                    <div class="single_product clearfix">
                                        <div class="col-lg-5 col-md-7 v_middle">
                                            <div class="product__description">
                                                <img src="{{ $item->id->image }}" alt="SKU Image"
                                                     style="max-width: 150px">
                                                <div class="short_desc">
                                                    <a href="{{ url('shop', [$item->id->product->slug]) }}">
                                                        <h4> {!! $item->id->product->name !!} [{{$item->id->code }}
                                                            ]</h4>
                                                    </a>
                                                    <p>{!! \Str::limit(strip_tags($item->id->product->description),60) !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-2 v_middle">
                                            <div class="product__additional_info">
                                                <div class="">
                                                    @if(!$item->id->isAvailable($item->qty))
                                                        @lang('Marketplace::labels.shop.out_stock')
                                                    @else
                                                        @if($item->id->allowed_quantity != 1)
                                                            <form action="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                  method="POST"
                                                                  class="ajax-form form-inline"
                                                                  data-page_action="updateCart">
                                                                {{ csrf_field() }}
                                                                <a href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                   class="btn btn-sm btn-warning"
                                                                   style="line-height: 32px;padding: 0 15px"
                                                                   title="Remove One" data-action="post"
                                                                   data-style="zoom-in"
                                                                   data-request_data='@json(["action"=>"decreaseQuantity"])'
                                                                   data-page_action="updateCart">
                                                                    <i class="fa fa-fw fa-minus"></i>
                                                                </a>
                                                                <input step="1" min="1"
                                                                       class="form-control form-control-sm cart-quantity"
                                                                       style="width:70px;margin:0 3px;display: inline;text-align:center"
                                                                       type="number"
                                                                       name="quantity"
                                                                       data-id="{{ $item->rowId }}"
                                                                       {!! $item->id->allowed_quantity>0?('max="'.$item->id->allowed_quantity.'"'):'' !!} value="{{ $item->qty }}"/>
                                                                <a href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                                   class="btn btn-success btn-sm"
                                                                   data-style="zoom-in"
                                                                   style="line-height: 32px;padding: 0 15px"
                                                                   title="Add One" data-action="post"
                                                                   data-page_action="updateCart"
                                                                   data-request_data='@json(["action"=>"increaseQuantity"])'>
                                                                    <i class="fa fa-fw fa-plus"></i>
                                                                </a>
                                                            </form>
                                                        @else
                                                            <input style="width:40px;text-align: center;"
                                                                   value="1"
                                                                   disabled/>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-3 v_middle">
                                            <div class="product__price_download">
                                                <div class="item_price v_middle">
                                                    <span id="item-total-{{$item->getHash()}}">{{ $item->subtotal() }}</span>
                                                </div>
                                                <div class="item_action v_middle">
                                                    <a href="{{ url('cart/quantity', [$item->getHash()]) }}"
                                                       data-action="post" data-style="zoom-in"
                                                       data-request_data='@json(["action"=>"removeItem"])'
                                                       data-page_action="updateCart"
                                                       data-toggle="tooltip"
                                                       title="Remove item"
                                                       class="remove_from_cart">
                                                        <span class="lnr lnr-trash"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="cart_calculation">
                                    <div class="cart--total">
                                        <p class="d-inline-block">
                                            <span>@lang('corals-marketplace-marty::labels.template.cart.subtotal')</span>
                                        </p>
                                        <p class="d-inline-block" id="total">
                                            {{ ShoppingCart::subTotalAllInstances() }}
                                        </p>
                                        @if(Settings::get('marketplace_tax_tax_included_in_price'))
                                            <small style="font-size: 9px">@lang('Marketplace::attributes.product.tax_included') </small>
                                        @endif
                                    </div>
                                    <a href="{{ url('checkout') }}" class="btn btn--round btn--md checkout_link">
                                        @lang('corals-marketplace-marty::labels.template.cart.checkout')
                                    </a>
                                    <a href="{{ url('shop') }}" class="btn btn--round btn--md checkout_link">
                                        @lang('corals-marketplace-marty::labels.template.cart.back_shopping')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
