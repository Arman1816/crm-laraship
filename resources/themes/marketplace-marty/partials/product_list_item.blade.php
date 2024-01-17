<div class="product product--list product--list-small">
    <div class="product__thumbnail">
        <div class="bg custom-list-photo" data-bg="{{$product->image}}"
             data-scrollax="properties: { translateY: '30%' }"></div>
        <div class="prod_btn">
            <a href="{{ url('shop/'.$product->slug) }}" class="transparent btn--sm btn--round">
                @lang('corals-marketplace-marty::labels.template.home.show_more')
            </a>
            <a href="{{ $product->demo_url}}" target="_blank" class="transparent btn--sm btn--round">
                @lang('corals-marketplace-marty::labels.template.home.live_demo')
            </a>
        </div>
    </div>
    <div class="product__details">
        <div class="product-desc">
            <a href="#" class="product_title">
                <h4>{{$product->name}}</h4>
            </a>
            <p>{!! \Str::limit(strip_tags($product->description),50) !!}</p>

            <ul class="titlebtm">
                @foreach($product->activeCategories as $category)
                    <li class="product_cat">
                        <a href="#">
                            <span class="lnr lnr-book"></span>{{$category->name}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="product-meta">
            <div class="author">
                <img class="auth-img" src="{{$product->store->thumbnail}}" alt="author image">
                <p>
                    <a href="{{$product->store->getUrl()}}">{{$product->store->name}}</a>
                </p>
            </div>
            <div class="love-comments">
                <p>
                    @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                        @include('partials.components.wishlist',['wishlist'=> $product->inWishList()])
                    @endif
                    {{$product->wishlists()->count()}}
                </p>
                <p>
                    <span class="lnr lnr-bubble"></span> {{$product->comments()->count()}} Comments</p>
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
        <div class="product-purchase">
            <div class="price_love">
                <span>{!! $product->price !!}</span>
            </div>
            <div class="sell">
                @php $sku = $product->activeSKU(true); @endphp
                @if($sku->stock_status == "in_stock")
                    @if($product->external_url)
                        <a href="{{ $product->external_url }}" target="_blank"
                           class=""
                           title="@lang('corals-marketplace-marty::labels.partial.buy_product')">
                            <p><span class="lnr lnr-cart"></span></p>
                        </a>
                    @else
                        <a href="{{ url('cart/'.$product->hashed_id.'/add-to-cart/'.$sku->hashed_id) }}"
                           data-style="zoom-in" data-action="post" data-page_action="updateCart">
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
        </div>
    </div>
</div>