@extends('layouts.public')

@section('css')
    <style type="text/css">
        .sku-item {
            position: relative;
        }

        .sku-item .badge {
            font-size: 75%;
            /*width: 100%;*/
        }

        .img-radio {
            max-height: 100px;
            margin: 5px auto;
        }

        .middle {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .selected-radio > img {
            opacity: .45;
        }

        .selected-radio .middle {
            opacity: 1;
        }
    </style>
@endsection
@section('content')
    @php \Actions::do_action('pre_content',$product, null) @endphp
    <div class="container" style="margin-top: -40px;">
        <div class="content">
            <div class="row">
                @include('partials.shop_filter')
                <div class="col-sm-8 col-lg-9 col-md-9" style="box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;">
                    <div class="main-product-detail">
                        <h2>{{ $product->name }}</h2> 
                        <div class="product-single row">
                            <div class="product-detail col-xs-12 col-md-5 col-sm-5">
                                <div class="page-content" id="content">
                                    <div class="images-container">
                                        <div class="js-qv-mask mask tab-content" style="border-radius:10px; border:solid 1px #091E08">
                                            @if(!($medias = $product->getMedia('marketplace-product-gallery'))->isEmpty())
                                                @foreach($medias as $media)
                                                    @if($loop->first)
                                                        <div id="gItem_{{ $media->id }}"
                                                             class="tab-pane fade active in show">
                                                            <img style="height: 250px; margin-top: -30px; border-radius: 5px;" src="{{$media->getFullUrl()}}" alt="img">
                                                        </div>
                                                    @else
                                                        <div id="gItem_{{ $media->id }}"
                                                             class="tab-pane fade in show">
                                                            <img style="height: 250px;  margin-top: -30px; border-radius: 5px;" src="{{$media->getFullUrl()}}" alt="img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="layer hidden-sm-down" data-toggle="modal"
                                                     data-target="#product-modal">
                                                    <i class="fa fa-expand"></i>
                                                </div>
                                            @else
                                                <div class="text-center text-muted">
                                                    <small>@lang('corals-marketplace-pro::labels.template.product_single.image_unavailable')</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($medias)
                                        <ul class="product-tab nav nav-tabs d-flex">
                                            @foreach($medias as $media)
                                                <li class="{{ $media->getCustomProperty('featured', false)?'active':'' }} col">
                                                    <a style="border: solid 1px #091E08;" href="#gItem_{{ $media->id }}" data-hash="gItem_{{ $media->id }}"
                                                       data-toggle="tab">
                                                        <img style="height: 90px; width: 90px;; border-radius: 5px;" src="{{ $media->getFullUrl() }}" alt="img">
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <div class="modal fade" id="product-modal" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div class="modal-body">
                                                        <div class="product-detail">
                                                            <div>
                                                                <div class="images-container">
                                                                    <div class="js-qv-mask mask tab-content">
                                                                        @foreach($medias as $media)
                                                                            @if($loop->first)
                                                                                <div id="modal_{{ $media->id }}"
                                                                                     class="tab-pane fade active in show">
                                                                                    <img src="{{$media->getFullUrl()}}"
                                                                                         alt="img">
                                                                                </div>
                                                                            @else
                                                                                <div id="modal_{{ $media->id }}"
                                                                                     class="tab-pane fade in show">
                                                                                    <img src="{{$media->getFullUrl()}}"
                                                                                         alt="img">
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                    <ul class="product-tab nav nav-tabs">
                                                                        @foreach($medias as $media)
                                                                            <li class="{{ $media->getCustomProperty('featured', false)?'active':'' }}">
                                                                                <a href="#modal_{{ $media->id }}"
                                                                                   data-toggle="tab"
                                                                                   aria-expanded="true"
                                                                                   class="">
                                                                                    <img src="{{ $media->getUrl() }}"
                                                                                         alt="img">
                                                                                </a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-info col-xs-12 col-md-7 col-sm-7" style="border-top: 1px solid #091E08; border-radius: 10px;">
                                <div class="detail-description">
                                    <div class="price-del" style="margin-top: 10px;">
                                        <span style="color:black" class="price" id="sku-price">{!! $product->price !!}</span>
                                    </div>
                                    <p class="description" style="color:black; font-family: sans-serif; font-size: 14px;">
                                       <strong>Product Name:</strong> {!! $product->caption !!} <br />
                                        @foreach($product->activeCategories as $category)
                                                   <Strong style="margin-top: -40px;">Category: </strong> <a href="{{ url('shop?category='.$category->slug) }}">{{ $category->name }}</a>
    </strong> @endforeach 
    <br/>
    <strong>
  <i
                                                    class="fa fa-home"></i> Store: </strong>
                                            
                                            <span class="content2">
                                    <a
                                            href="{{ $product->store->getUrl() }}">{{ $product->store->name }}</a>
                                </span>
                                            @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                                                @include('partials.components.rating',[
                                                'rating'=> $product->store->averageRating(1)[0],
                                                'rating_count'=> optional($product->store->countRating())[0]])
                                            @endif
                                       
                                    </p>
                             
                                    {!! Form::open(['url'=>'cart/'.$product->hashed_id.'/add-to-cart','method'=>'POST','class'=> 'ajax-form','id'=>'sku-form','data-page_action'=>"updateCart"]) !!}
                                    @if(!$product->isSimple)
                                        @switch($productSKUsDisplayMethod = $product->getProperty('show_skus_as'))
                                            @case('radio_skus')
                                            @include("templates.partials.".$productSKUsDisplayMethod)
                                            @break
                                            @case('select_skus')
                                            @include("templates.partials.".$productSKUsDisplayMethod)
                                            @break
                                            @case('options_skus')
                                            {!! $product->renderProductOptions('variation_options',null,['as_filter'=>true])  !!}
                                            <input type="hidden" name="sku_hash" value="" id="sku_hash_id"/>
                                            @break
                                            @default
                                            @include("templates.partials.radio_skus")
                                        @endswitch
                                    @else
                                        <input type="hidden" name="sku_hash"
                                               value="{{ $product->activeSKU(true)->hashed_id }}"/>
                                    @endif
                                    <div class="has-border cart-area">
                                        @includeWhen($product->price_per_quantity, "templates.partials.price_per_quantity_description",['pricePerQuantity'=>$product->price_per_quantity])
                                        @includeWhen($product->offers, "templates.partials.offers_list",['offers'=>$product->offers])
                               
                                        <div class="product-quantity">
                                            <div class="qty">
                                                <div class="input-group">
                                                    @if(!$product->external_url)
                                                        <div class="quantity">
                                                            <span class="control-label"></span>
                                                            <input style="border: solid 1px #091E08"  type="number" name="quantity" id="quantity_wanted"
                                                                   value="1"
                                                                   min="1" class="input-group form-control">
                                                            <span class="input-group-btn-vertical">
                                                <button style="border: solid 1px #091E08; color: #091E08" class="btn btn-touchspin  js-touchspin bootstrap-touchspin-up"
                                                        type="button">
                                                    +
                                                </button>
                                                <button style="border: solid 1px #091E08; color: #091E08" class="btn btn-touchspin js-touchspin bootstrap-touchspin-down"
                                                        type="button">
                                                    -
                                                </button>
                                            </span>

                                                        </div>
                                                    @endif
                                                    <span class="add" >
                                           @if($product->external_url)
                                                            <a  style="background-color: #091E08; color:white;"  href="{{ $product->external_url }}" target="_blank"
                                                               class=""
                                                               title="@lang('corals-marketplace-pro::labels.template.product_single.buy_product')">
                                                            <i class="fa fa-fw fa-cart-plus"  aria-hidden="true"></i>
</a>
                                                        @elseif($product->isSimple && $product->activeSKU(true)->stock_status != "in_stock")
                                                            <a style="background-color: #091E08; color:white;"  href="#" class="btn btn-sm"
                                                               title="Out Of Stock">
@lang('corals-marketplace-pro::labels.partial.out_stock')
</a>
                                                        @else
                                                        
                                                            {!! CoralsForm::button('corals-marketplace-pro::labels.partial.add_to_cart',
                                                            ['class'=>'btn add-to-cart btn-sm btn-success'],  'submit') !!}
                                                            
                                                        @endif
                                                        @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                                                            @include('partials.components.wishlist',['wishlist'=> $product->inWishList() ])
                                                        @endif
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="offers-check-result"></div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="product-minimal-quantity">
                                        </p>
                                    </div>
                                    {!! Form::close() !!}
                                    
                                   
                                </div>
                            </div>
                        </div>
                        <div class="review">
                            <ul class="nav nav-tabs" style="border-bottom: 1px solid #091E08">
                                <li class="active">
                                    <a data-toggle="tab" href="#description" class="active show">
                                        @lang('corals-marketplace-pro::labels.template.product_single.description')
                                    </a>
                                </li>
                                @if($product->present('options'))
                                    <li>
                                        <a data-toggle="tab" href="#product-specs">
                                            @lang('corals-marketplace-pro::labels.template.product_single.product_options')
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a data-toggle="tab" href="#review">
                                        @lang('corals-marketplace-pro::labels.template.product_single.reviews',['count'=>$product->ratings->count()])
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" style="border-radius:5px;border: 1px solid #091E08; padding: 10px;">
                                <div  id="description" class="tab-pane fade in active show">
                                    <p>
                                        {!! $product->description !!}
                                    </p>
                                </div>
                                @if($product->present('options'))
                                    <div id="product-specs" class="tab-pane fade">
                                        <table class="table table-striped">
                                            @foreach($product->present('options') as $optionLabel => $optionsValue)
                                                <tr>
                                                    <td>{{ $optionLabel }}</td>
                                                    <td>{!! $optionsValue !!}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @endif
                                @if(\Settings::get('marketplace_rating_enable',true) ==  true)
                                    <br>
                                    @include('partials.tabs.reviews',['reviews'=>$product->ratings])
                                @endif
                            </div>
                        </div>
                        <div class="related">
                            <div class="title-tab-content  text-center">
                                <div class="title-product justify-content-start" style="border-bottom: 1px solid #091E08">
                                    <h2>@lang('corals-marketplace-pro::labels.partial.featured_products')</h2>
                                </div>
                            </div>
                            @php $products = \Shop::getFeaturedProducts();@endphp
                            @if(!$products->isEmpty())
                                <div class="section best-sellers col-lg-12 col-xs-12" style="padding-bottom: 20px;">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div>
                                                <div class="row align-items-center">
                                                    <!-- column 4 -->
                                                    <div class="col-lg-12 flex-12">
                                                        <div class="tab-content">
                                                            <div class="tab-pane fade in active show">
                                                                <div class="category-product-index owl-carousel owl-theme owl-loaded owl-drag">
                                                                    @foreach($products as $product)
                                                                        <div class="item text-center" style="border: solid 1px #091E08; border-radius:10px;">
                                                                            <div class="product-miniature js-product-miniature item-one first-item">
                                                                                <div class="thumbnail-container" style="margin-top: -10px;">
                                                                                    <a href="{{ url('shop/'.$product->slug) }}">
                                                                                        <img style="height:200px; margin-top: -10px;" class="img-fluid image-cover"
                                                                                             src="{{ $product->non_featured_image }}"
                                                                                             alt="">
                                                                                        <img style="height: 200px; margin-top: -10px;" class="img-fluid image-secondary"
                                                                                             src="{{ $product->non_featured_image }}"
                                                                                             alt="">
                                                                                    </a>
                                                                                </div>
                                                                                <div class="product-description">
                                                                                    <div class="product-groups">
                                                                                        <div class="product-title">
                                                                                            <a style="color: black;" href="{{ url('shop/'.$product->slug) }}">{{ $product->name }}</a>
                                                                                        </div>
                                                                                        <div class="product-group-price">
                                                                                            <div class="product-price-and-shipping">
                                                                                                <span style="color:black;" class="price">{!! $product->price !!}</span>
                                                                                                @if($product->discount)
                                                                                                    <del class="regular-price">{{ \Payments::currency($product->regular_price) }}</del>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="product-buttons d-flex justify-content-center">
                                                                                        <form class="formAddToCart">
                                                                                            @if(!$product->isSimple || $product->attributes()->count())
                                                                                                @if($product->external_url)
                                                                                                    <a href="{{ $product->external_url }}"
                                                                                                       target="_blank"
                                                                                                       class="add-to-cart"
                                                                                                       title="Buy Product">
                                                                                                        @lang('corals-marketplace-pro::labels.partial.buy_product')
                                                                                                    </a>
                                                                                                @else
                                                                                                    <a href="{{ url('shop/'.$product->slug) }}"
                                                                                                       class="add-to-cart"
                                                                                                       data-toggle="tooltip"
                                                                                                       title="@lang('corals-marketplace-pro::labels.partial.add_cart')">
                                                                                                        <i class="fa fa-shopping-cart"
                                                                                                           aria-hidden="true"></i>
                                                                                                    </a>
                                                                                                @endif
                                                                                            @else
                                                                                                @php $sku = $product->activeSKU(true); @endphp
                                                                                                @if($sku->stock_status == "in_stock")
                                                                                                    @if($product->external_url)
                                                                                                        <a href="{{ $product->external_url }}"
                                                                                                           target="_blank"
                                                                                                           class="add-to-cart"
                                                                                                           title="Buy Product">
                                                                                                            <i class="fa fa-shopping-cart"
                                                                                                               aria-hidden="true"></i>
                                                                                                        </a>
                                                                                                    @else
                                                                                                        <a href="{{ url('cart/'.$product->hashed_id.'/add-to-cart/'.$sku->hashed_id) }}"
                                                                                                           data-action="post"
                                                                                                           data-page_action="updateCart"
                                                                                                           data-toggle="tooltip"
                                                                                                           title="@lang('corals-marketplace-pro::labels.partial.add_cart')"
                                                                                                           class="add-to-cart">
                                                                                                            <i class="fa fa-shopping-cart"
                                                                                                               aria-hidden="true"></i>
                                                                                                        </a>
                                                                                                    @endif
                                                                                                @else
                                                                                                    <a href="#"
                                                                                                       class="btn btn-sm btn-outline-danger"
                                                                                                       title="Out Of Stock">
                                                                                                        @lang('corals-marketplace-pro::labels.partial.out_stock')
                                                                                                    </a>
                                                                                                @endif
                                                                                            @endif

                                                                                        </form>
                                                                                        @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                                                                                            @include('partials.components.wishlist',['wishlist'=> $product->inWishList()])
                                                                                        @endif
                                                                                        <a href="{{ url('shop/'.$product->slug) }}"
                                                                                           class="quick-view hidden-sm-down"
                                                                                           data-toggle="tooltip"
                                                                                           title="@lang('corals-marketplace-pro::labels.partial.show_details')"
                                                                                           data-link-action="quickview">
                                                                                            <i class="fa fa-eye"
                                                                                               aria-hidden="true"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop
