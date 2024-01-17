<div class="section living-room">
    <div class="container">
        <div class="tiva-row-wrap row">
            <div class="groupcategoriestab-vertical col-md-12 col-xs-12">
                <div class="grouptab row">
                    <div class="categoriestab-left product-tab col-md-12 flex-9">
                        <div class="title-tab-content d-flex justify-content-start">
                            <ul class="nav nav-tabs">
                                <li>
                                    <a href="#new" data-toggle="tab"
                                       class="active">@lang('corals-marketplace-pro::labels.partial.new_arrivals')</a>
                                </li>
                                <li>
                                    <a href="#best"
                                       data-toggle="tab">@lang('corals-marketplace-pro::labels.partial.best_rated')</a>
                                </li>
                                <li>
                                    <a href="#sale"
                                       data-toggle="tab">@lang('corals-marketplace-pro::labels.partial.top_sellers')</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div id="new" class="tab-pane fade in active show">
                                <div class="category-product-index owl-carousel owl-theme owl-loaded owl-drag">
                                    @if(!($newArrivalsProducts = \Shop::getNewArrivals(4))->isEmpty())
                                        @foreach($newArrivalsProducts as $product)
                                            <div class="item text-center">
                                                <div class="product-miniature first-item js-product-miniature item-one">
                                                    <div class="thumbnail-container">
                                                        <a href="{{ url('shop/'.$product->slug) }}">
                                                            <img class="img-fluid image-cover"
                                                                 src="{{ $product->image }}" alt="{{ $product->name }}">
                                                            <img class="img-fluid image-secondary"
                                                                 src="{{ $product->non_featured_image }}"
                                                                 alt="{{ $product->name }}">
                                                        </a>
                                                        @if($product->discount)
                                                            <div class="product-flags discount">{{ $product->discount }}
                                                                %Off
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="product-description">
                                                        <div class="product-groups">
                                                            <div class="product-title">
                                                                <a href="{{ url('shop/'.$product->slug) }}">{{ $product->name }}</a>
                                                            </div>
                                                            <div class="rating">
                                                                <div class="star-content">
                                                                    @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                                                                        @include('partials.components.rating',['rating'=> $product->averageRating(1)[0],'rating_count'=>null])
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="product-group-price">
                                                                <div class="product-price-and-shipping">
                                                                    <span class="price">{!! $product->price !!}</span>
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
                                                                           target="_blank" class="add-to-cart"
                                                                           title="Buy Product">
                                                                            @lang('corals-marketplace-pro::labels.partial.buy_product')
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ url('shop/'.$product->slug) }}"
                                                                           class="add-to-cart" data-toggle="tooltip"
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
                                                                               target="_blank" class="add-to-cart"
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
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="best">
                                <div class="category-product-index owl-carousel owl-theme">
                                    @if(!($bestRatedProducts = \Shop::getBestRated())->isEmpty())
                                        @foreach($bestRatedProducts as $product)
                                            <div class="item text-center">
                                                <div class="product-miniature js-product-miniature item-one first-item">
                                                    <div class="thumbnail-container">
                                                        <a href="{{ url('shop/'.$product->slug) }}">
                                                            <img class="img-fluid image-cover"
                                                                 src="{{ $product->image }}" alt="{{ $product->name }}">
                                                            <img class="img-fluid image-secondary"
                                                                 src="{{ $product->non_featured_image }}"
                                                                 alt="{{ $product->name }}">
                                                        </a>
                                                        @if($product->discount)
                                                            <div class="product-flags discount">{{ $product->discount }}
                                                                %Off
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="product-description">
                                                        <div class="product-groups">
                                                            <div class="product-title">
                                                                <a href="{{ url('shop/'.$product->slug) }}">
                                                                    {{ $product->name }}
                                                                </a>
                                                            </div>
                                                            <div class="rating">
                                                                <div class="star-content">
                                                                    @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                                                                        @include('partials.components.rating',['rating'=> $product->averageRating(1)[0],'rating_count'=>null])
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="product-group-price">
                                                                <div class="product-price-and-shipping">
                                                                    <span class="price">{!! $product->price !!}</span>
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
                                                                           target="_blank" class="add-to-cart"
                                                                           title="Buy Product">
                                                                            @lang('corals-marketplace-pro::labels.partial.buy_product')
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ url('shop/'.$product->slug) }}"
                                                                           class="add-to-cart" data-toggle="tooltip"
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
                                                                               target="_blank" class="add-to-cart"
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
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sale">
                                <div class="category-product-index owl-carousel owl-theme">
                                    @if(!($topSellersProducts = \Shop::getTopSellers())->isEmpty())
                                        @foreach($topSellersProducts as $product)
                                            <div class="item text-center">
                                                <div class="product-miniature js-product-miniature item-one first-item">
                                                    <div class="thumbnail-container">
                                                        <a href="{{ url('shop/'.$product->slug) }}">
                                                            <img class="img-fluid image-cover"
                                                                 src="{{ $product->image }}" alt="{{ $product->name }}">
                                                            <img class="img-fluid image-secondary"
                                                                 src="{{ $product->non_featured_image }}"
                                                                 alt="{{ $product->name }}">
                                                        </a>
                                                        @if($product->discount)
                                                            <div class="product-flags discount">{{ $product->discount }}
                                                                %Off
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="product-description">
                                                        <div class="product-groups">
                                                            <div class="product-title">
                                                                <a href="{{ url('shop/'.$product->slug) }}">
                                                                    {{ $product->name }}
                                                                </a>
                                                            </div>
                                                            <div class="rating">
                                                                <div class="star-content">
                                                                    @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                                                                        @include('partials.components.rating',['rating'=> $product->averageRating(1)[0],'rating_count'=>null])
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="product-group-price">
                                                                <div class="product-price-and-shipping">
                                                                    <span class="price">{!! $product->price !!}</span>
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
                                                                           target="_blank" class="add-to-cart"
                                                                           title="Buy Product">
                                                                            @lang('corals-marketplace-pro::labels.partial.buy_product')
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ url('shop/'.$product->slug) }}"
                                                                           class="add-to-cart" data-toggle="tooltip"
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
                                                                               target="_blank" class="add-to-cart"
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
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
