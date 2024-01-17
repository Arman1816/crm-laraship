<div class="col-lg-4 col-md-6">
    <div class="product product--card product--card-small">
        <div class="product__thumbnail">
            <div class="bg custom-grid-photo" data-bg="{{$product->image}}"
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
        <div class="product-desc">
            <a href="{{ url('shop/'.$product->slug) }}" class="product_title">
                <h4>{{$product->name}}</h4>
            </a>
            <ul class="titlebtm">
                <li>
                    <img class="auth-img" src="{{$product->store->thumbnail}}" alt="author image">
                    <p>
                        <a href="{{ $product->store->getUrl() }}">{{$product->store->name}}</a>
                    </p>
                </li>
                <li class="out_of_class_name">
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
                </li>
            </ul>

        </div>
        <div class="product-purchase">
            <div class="price_love">
                @if($product->discount)
                    <span>{{ \Payments::currency($product->regular_price) }}</span>
                @endif
                <span>{!! $product->price !!}</span>
            </div>
            @foreach($product->activeCategories as $category)
                <a class=""
                   href="{{ url('shop?category='.$category->slug) }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>