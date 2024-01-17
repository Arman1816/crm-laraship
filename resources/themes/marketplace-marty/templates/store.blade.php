@extends('layouts.public')

@section('content')
    @include('partials.page_header',['content' => $store->name])
    <section class="author-profile-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <aside class="sidebar sidebar_author">
                        <div class="author-card sidebar-card">
                            <div class="author-infos">
                                <div class="author_avatar">
                                    <img src="{{ $store->thumbnail }}">
                                </div>
                                <div class="author">
                                    <h4>{{$store->user->full_name}}</h4>
                                    <p>{{format_date($store->created_at)}}</p>
                                    @if($store->return_policy)
                                        <div class="text-center">
                                            <a href="{{ request()->url().'/return-policy' }}" class="modal-load"
                                               data-title="@lang('Marketplace::attributes.store.return_policy')">
                                                <small>@lang('Marketplace::attributes.store.return_policy')</small>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="social social--color--filled">
                                    @include('partials.components.social_share',['url'=> URL::current() , 'title'=>$store->name ])                                </div>
                                <div class="author-btn">
                                    <a href="{{ url('marketplace/follow/'.$store->hashed_id) }}"
                                       data-action="post" data-page_action="toggleFollowStore"
                                       data-follow_store_hashed_id="{{$store->hashed_id}}"
                                       class="btn btn--lg btn--round">
                                        {!!   $store->inWishList() ? trans('corals-marketplace-marty::labels.template.store.remove_follow') : trans('corals-marketplace-marty::labels.template.store.add_to_follow') !!}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-card author-menu">
                            <ul>
                                <li>
                                    <a href="{{url('profile')}}"
                                       class="active"> @lang('corals-marketplace-marty::labels.partial.profile')</a>
                                </li>
                                <li>
                                    <a href="#">@lang('corals-marketplace-marty::labels.template.store.author_item_count',['author_items_count'=> $store->products->count()])</a>
                                </li>
                                <li>
                                    <a href="#">@lang('corals-marketplace-marty::labels.template.store.author_reviews_count',['author_reviews_count'=>$store->getStoreReviews(true) ])</a>
                                </li>
                                <li>
                                    <a href="#">@lang('corals-marketplace-marty::labels.template.store.author_followers_count',['author_followers_count'=>$store->wishlists()->count() ])</a>
                                </li>
                            </ul>
                        </div>
                        <div class="sidebar-card message-card">
                            <div class="card-title">
                                <h4>@lang('corals-marketplace-marty::labels.template.home.contact_seller')</h4>
                            </div>
                            <div class="message-form">
                                <div class="msg_submit">
                                    <a href="{{ url('messaging/discussions/create?user='.$store->user->hashed_id) }}"
                                       class="btn btn--md btn--round">
                                        @lang('corals-marketplace-marty::labels.template.store.contact',['store_name'=>$store->name])
                                    </a>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        @php \Actions::do_action('pre_display_shop') @endphp
                        <div class="col-md-4 col-sm-4">
                            <div class="author-info mcolorbg4">
                                <p>@lang('corals-marketplace-marty::labels.template.store.member_since')</p>
                                <h3>{{format_date($store->created_at)}}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="author-info pcolorbg">
                                <p>@lang('corals-marketplace-marty::labels.template.store.total_product')</p>
                                <h3>{{\Corals\Modules\Marketplace\Models\Product::where('store_id',$store->id)->count()}}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="author-info scolorbg">
                                <p>@lang('corals-marketplace-marty::labels.template.store.website')</p>
                                <h3>
                                    {{$store->name}}
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="author_module">
                                <img src="{{ $store->cover_photo }}" alt="author image">
                            </div>
                            <div class="author_module about_author">
                                <h2>About
                                    <span>{{$store->name}}</span>
                                </h2>
                                <p>{!! $store->short_description !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="product-title-area">
                                <div class="product__title">
                                    <h2>@lang('corals-marketplace-marty::labels.template.store.contact',['store_name'=>$store->name])</h2>
                                </div>
                                <a href="{{ url('messaging/discussions/create?user='.$store->user->hashed_id) }}"
                                   class="btn btn--sm">
                                    @lang('corals-marketplace-marty::labels.template.store.contact',['store_name'=>$store->name])
                                </a>
                            </div>
                        </div>
                        @forelse($products as $product)
                            <div class="col-lg-6 col-md-6">
                                <div class="product product--card">
                                    <div class="product__thumbnail">
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                                        <div class="prod_btn">
                                            <a href="{{ url('shop/'.$product->slug) }}"
                                               class="transparent btn--sm btn--round">More
                                                Info</a>
                                            <a href="{{ url('shop') }}" class="transparent btn--sm btn--round">Live
                                                Demo</a>
                                        </div>
                                    </div>
                                    <div class="product-desc">
                                        <a href="{{ url('shop/'.$product->slug) }}"
                                           class="product_title">
                                            <h4>{{$product->name}}</h4>
                                        </a>
                                        <ul class="titlebtm">
                                            <li>
                                                <img class="auth-img" src="{{$product->store->thumbnail}}"
                                                     alt="author image">
                                                <p>
                                                    <a href="#">{{$product->store->name}}</a>
                                                </p>
                                            </li>
                                            <li class="product_cat">
                                                @foreach($product->activeCategories as $category)
                                                    <a class=""
                                                       href="{{ url('shop?category='.$category->slug) }}">
                                                        {{ $category->name }}
                                                    </a>
                                                @endforeach
                                            </li>
                                        </ul>
                                        <p>{!! \Str::limit(strip_tags($product->description),100) !!}</p>
                                    </div>
                                    <div class="product-purchase">
                                        <div class="price_love">
                                            @if($product->discount)
                                                <span>{{ \Payments::currency($product->regular_price) }}</span>
                                            @endif
                                            <span>{!! $product->price !!}</span>
                                            <p>
                                                @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                                                    @include('partials.components.wishlist',['wishlist'=> $product->inWishList()])
                                                @endif
                                            </p>
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
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h4>@lang('corals-marketplace-marty::labels.template.shop.sorry_no_result')</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @parent

    <script type="text/javascript">
        $(document).ready(function () {
            $("#shop_sort").change(function () {
                $("#filterSort").val($(this).val());
                $("#filterForm").submit();
            })
        });

        function toggleFollowStore(response) {
            $follow_item = $('*[data-follow_store_hashed_id="' + response.hashed_id + '"]');
            if (response.action == "add") {
                $follow_item.html("@lang('corals-marketplace-marty::labels.template.store.remove_follow')");
            } else {
                $follow_item.html("@lang('corals-marketplace-marty::labels.template.store.add_to_follow')");
            }
        }
    </script>
@endsection
