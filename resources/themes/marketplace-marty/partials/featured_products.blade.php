@php $products = \Shop::getFeaturedProducts(); @endphp

@if(!$products->isEmpty())
    <section class="featured-products bgcolor  section--padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-title-area ">
                        <div class="product__title">
                            <h2>@lang('corals-marketplace-marty::labels.partial.featured_products')</h2>
                        </div>

                        <div class="product__slider-nav rounded">
                            <span class="lnr lnr-chevron-left nav_left"></span>
                            <span class="lnr lnr-chevron-right nav_right"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 no0-padding">
                    <div class="featured-product-slider prod-slider1">
                        @foreach($products as $product)
                            <div class="featured__single-slider">
                                <div class="featured__preview-img">
                                    <img src="{{$product->image}}" alt="Featured products">
                                    <div class="prod_btn">
                                        <a href="{{ url('shop/'.$product->slug) }}"
                                           class="transparent btn--sm btn--round">
                                            @lang('corals-marketplace-marty::labels.template.home.show_more')
                                        </a>
                                        <a href="{{ $product->demo_url}}" class="transparent btn--sm btn--round">
                                            @lang('corals-marketplace-marty::labels.template.home.live_demo')
                                        </a>
                                    </div>
                                </div>
                                <div class="featured__product-description">
                                    <div class="product-desc desc--featured">

                                        <a href="{{ url('shop/'.$product->slug) }}" class="product_title">
                                            <h4>
                                                @if($product->is_featured)
                                                    <span class="lnr  lnr-diamond"></span>
                                                @endif
                                                {{$product->name}}

                                            </h4>
                                        </a>
                                        <ul class="titlebtm">
                                            <li>
                                                <img class="auth-img" src="{{$product->store->thumbnail}}"
                                                     alt="author image">
                                                <p>
                                                    <a href="{{ $product->store->getUrl() }}">{{$product->store->name}}</a>
                                                </p>
                                            </li>


                                        </ul>

                                        <p>{!! \Str::limit(strip_tags($product->description),300) !!}</p>
                                    </div>
                                    <div class="product_data">
                                        <div class="tags tags--round">
                                            <ul>
                                                @foreach($product->activeCategories as $category)
                                                    <li>
                                                        <a href="{{ url('shop?category='.$category->slug) }}">{{$category->name}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <!-- end /.tags -->
                                        <div class="product-purchase featured--product-purchase">
                                            <div class="price_love">
                                                <span>{!! $product->price !!}</span>
                                                <p>
                                                    @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                                                        @include('partials.components.wishlist',['wishlist'=> $product->inWishList()])
                                                    @endif
                                                    {{$product->wishlists()->count()}}
                                                </p>
                                            </div>
                                            <div class="sell">
                                                @php $sku = $product->activeSKU(true); @endphp
                                                @if($sku->stock_status == "in_stock")
                                                    @if($product->external_url)
                                                        <a href="{{ $product->external_url }}" target="_blank"
                                                           class=""
                                                           title="@lang('corals-marketplace-marty::labels.partial.buy_product')">
                                                            <p><span class="lnr lnr-cart"></span>
                                                        </a>
                                                    @else
                                                        <a href="{{ url('cart/'.$product->hashed_id.'/add-to-cart/'.$sku->hashed_id) }}"
                                                           data-style="zoom-in" data-action="post"
                                                           data-page_action="updateCart">
                                                            <div class="sell" id="custom-cart">
                                                                <p>
                                                                    <span class="lnr lnr-cart"></span>
                                                                </p>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="#" class="btn btn-sm btn-outline-danger tooltip"
                                                       title="@lang('corals-marketplace-marty::labels.partial.out_stock')">
                                                        <div class="circle tiny error" id="custom-cart">
                                                            <span class="icon-exclamation"></span>
                                                        </div>
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="rating product--rating">
                                                @php
                                                    $rating = $product->averageRating(1)[0] ;
                                                    $rating_count = $product->ratings->count();
                                                @endphp
                                                <ul>
                                                    @if(\Settings::get('marketplace_rating_enable',true))
                                                        @include('partials.components.rating',['rating'=> $rating ,'rating_count'=>$rating_count])
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif