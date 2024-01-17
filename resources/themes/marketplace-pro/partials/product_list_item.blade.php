<div id="list" class="related tab-pane fade active show">
    <div class="row">
        <div class="item col-md-12">
            <div class="product-miniature js-product-miniature item-one first-item">
                <div class="row">
                    <div class="col-md-4">
                        <div class="thumbnail-container border">
                            <a href="{{ url('shop/'.$product->slug) }}">
                                <img class="img-fluid image-cover" src="{{ $product->image }}"
                                     alt="{{ $product->name }}">
                                <img class="img-fluid image-secondary" src="{{ $product->non_featured_image }}"
                                     alt="{{ $product->name }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
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
                                        <span class="price">{!!   $product->price !!}</span>
                                    </div>
                                </div>
                                <div class="discription">
                                    {!! \Str::limit(strip_tags($product->description),150) !!}
                                </div>
                            </div>
                            <div class="product-buttons d-flex">
                                <form class="formAddToCart">
                                    @if(!$product->isSimple || $product->attributes()->count())
                                        @if($product->external_url)
                                            <a href="{{ $product->external_url }}" target="_blank" class="add-to-cart"
                                               title="Buy Product">
                                                @lang('corals-marketplace-master::labels.partial.buy_product')
                                            </a>
                                        @else
                                            <a href="{{ url('shop/'.$product->slug) }}" class="add-to-cart">
                                                @lang('corals-marketplace-pro::labels.partial.add_to_cart')
                                            </a>
                                        @endif
                                    @else
                                        @php $sku = $product->activeSKU(true); @endphp
                                        @if($sku->stock_status == "in_stock")
                                            @if($product->external_url)
                                                <a href="{{ $product->external_url }}" target="_blank"
                                                   class="add-to-cart"
                                                   title="Buy Product">
                                                    @lang('corals-marketplace-master::labels.partial.buy_product')
                                                </a>
                                            @else
                                                <a href="{{ url('cart/'.$product->hashed_id.'/add-to-cart/'.$sku->hashed_id) }}"
                                                   data-action="post" data-page_action="updateCart"
                                                   class="add-to-cart">
                                                    @lang('corals-marketplace-pro::labels.partial.add_to_cart')
                                                </a>
                                            @endif
                                        @else
                                            <a href="#" class="btn btn-sm btn-outline-danger"
                                               title="Out Of Stock">
                                                @lang('corals-marketplace-pro::labels.partial.out_stock')
                                            </a>
                                        @endif
                                    @endif

                                </form>
                                @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                                    @include('partials.components.wishlist',['wishlist'=> $product->inWishList()])
                                @endif
                                <a href="{{ url('shop/'.$product->slug) }}" class="quick-view hidden-sm-down"
                                   data-toggle="tooltip"
                                   title="@lang('corals-marketplace-pro::labels.partial.show_details')"
                                   data-link-action="quickview">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
