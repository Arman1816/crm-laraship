@extends('layouts.public')

@section('content')
    <section class="single-product-desc">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="item-preview">
                        <div class="item__preview-slider">
                            @if(!($medias = $product->getMedia('marketplace-product-gallery'))->isEmpty())
                                @foreach($medias as $media)
                                    <div class="prev-slide {{ $media->getCustomProperty('featured', false)?'active':'' }}">
                                        <img src="{{ $media->getUrl() }}"
                                             alt="image">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="item__preview-thumb">
                            @if(!($medias = $product->getMedia('marketplace-product-gallery'))->isEmpty())
                                <div class="prev-thumb">
                                    <div class="thumb-slider">
                                        @foreach($medias as $media)
                                            <div class="item-thumb">
                                                <img src="{{ $media->getUrl() }}"
                                                     alt="This is the thumbnail of the item">
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                    <div class="prev-nav thumb-nav">
                                        <span class="lnr nav-left lnr-arrow-left"></span>
                                        <span class="lnr nav-right lnr-arrow-right"></span>
                                    </div>
                                </div>
                                <div class="item-action">
                                    <div class="action-btns">
                                        <a href="{{$product->demo_url}}" target="_blank" class="btn btn--round btn--lg">
                                            @lang('corals-marketplace-marty::labels.template.home.live_demo')
                                        </a>
                                        <a href="{{ url('marketplace/wishlist/'.$product->hashed_id) }}"
                                           data-action="post" data-page_action="toggleWishListProduct"
                                           data-wishlist_product_hashed_id="{{$product->hashed_id}}"
                                           class="btn btn--round btn--lg btn--icon wishlist">
                                            <span class="lnr lnr-heart"></span>@lang('corals-marketplace-marty::labels.template.product_single.add_to_favorites')
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="item-info">
                        <div class="item-navigation">
                            <ul class="nav nav-tabs">
                                <li>
                                    <a href="#product-details" class="active" aria-controls="product-details" role="tab"
                                       data-toggle="tab">
                                        @lang('corals-marketplace-marty::labels.template.product_single.item_details')
                                    </a>
                                </li>
                                @if($product->present('options'))
                                    <li>
                                        <a aria-controls="product-specs" role="tab" href="#product-specs">
                                            @lang('corals-marketplace-marty::labels.template.product_single.product_options')
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="#product-comment" aria-controls="product-comment" role="tab"
                                       data-toggle="tab">
                                        @lang('corals-marketplace-marty::labels.template.product_single.comments')
                                    </a>
                                </li>
                                <li>
                                    <a href="#product-support" aria-controls="product-support" role="tab"
                                       data-toggle="tab">
                                        @lang('corals-marketplace-marty::labels.template.product_single.support')
                                    </a>
                                </li>
                                <li>
                                    <a href="#product-review" aria-controls="product-review" role="tab"
                                       data-toggle="tab">@lang('corals-marketplace-marty::labels.template.product_single.reviews',['count'=>$product->ratings->count()])
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="fade show tab-pane product-tab active" id="product-details">
                                <div class="tab-content-wrapper">
                                    {!! $product->description !!}
                                </div>
                            </div>
                            @if($product->present('options'))
                                <div id="product-specs" class="tab-pane product-tab fade">
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
                            <div class="fade tab-pane product-tab" id="product-comment">
                                <div class="thread">
                                    @if(\Settings::get('marketplace_rating_comment_enable',true))
                                        @include('partials.tabs.comments',['comments'=>$product->comments])
                                    @endif
                                </div>
                            </div>
                            <div class="fade tab-pane product-tab" id="product-review">
                                <div class="thread thread_review">
                                    @if(\Settings::get('marketplace_rating_enable',true))
                                        @include('partials.tabs.reviews',['reviews'=>$product->ratings])
                                    @endif
                                </div>
                            </div>
                            <div class="fade tab-pane product-tab" id="product-support">
                                @if(\Settings::get('marketplace_messaging_is_enable',false) && (!user() || user()->can('create', Corals\Modules\Messaging\Models\Discussion::class)))
                                    <a href="{{ url('messaging/discussions/create?user='.user()->hashed_id) }}"
                                       class="btn btn--round btn--lg">@lang('corals-marketplace-marty::labels.template.product_single.send_message')
                                        <i class="fa fa-angle-right"></i></a>
                                @else
                                    <div class="support">
                                        <div class="support__title">
                                            <h3>@lang('corals-marketplace-marty::labels.template.product_single.contact_author')</h3>
                                        </div>
                                        <div class="support__form">
                                            <div class="usr-msg">
                                                <form class="support_form ajax-form" id="main-contact-form"
                                                      name="contact-form"
                                                      action="{{url('marketplace/product/contact')}}" method="POST"
                                                      data-page_action="clearForm">
                                                    <input type="hidden" value="{{csrf_token()}}" name="_token">
                                                    <input type="hidden"
                                                           value="{{$product->name}}"
                                                           name="product_name">
                                                    <input type="hidden"
                                                           value="{{$product->store->user->email}}"
                                                           name="store_email">
                                                    <div class="form-group">
                                                        <label for="subj">Name:</label>
                                                        <input type="text" name="name" id="subj" class="text_field"
                                                               placeholder="Enter your name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email:</label>
                                                        <input type="email" name="email" id="email" class="text_field"
                                                               placeholder="Enter your email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Phone:</label>
                                                        <input type="text" name="phone" class="text_field"
                                                               placeholder="Enter your phone">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">company:</label>
                                                        <input type="text" name="company" class="text_field"
                                                               placeholder="Enter your company">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">subject:</label>
                                                        <input type="text" name="subject" class="text_field"
                                                               placeholder="Enter your subject">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="supmsg">Messages : </label>
                                                        <textarea class="text_field" id="supmsg" name="message"
                                                                  placeholder="Enter your text..."></textarea>
                                                    </div>
                                                    <div class="form-group">

                                                        {!! NoCaptcha::display() !!}

                                                    </div>
                                                    <button type="submit" class="btn btn--lg btn--round">Submit Now
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="sidebar sidebar--single-product">
                        <div class="sidebar-card card-pricing">
                            <div class="price">
                                <h1 id="sku-price">
                                    {!!  $product->price!!}
                                </h1>
                            </div>
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

                            <div class="purchase-button">
                                @includeWhen($product->price_per_quantity, "templates.partials.price_per_quantity_description",['pricePerQuantity'=>$product->price_per_quantity])
                                @includeWhen($product->offers, "templates.partials.offers_list",['offers'=>$product->offers])

                                {!! CoralsForm::number('quantity','corals-marketplace-marty::attributes.template.quantity', false, 1, ['min' => 1,'class'=>'form-control form-control-sm']) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="offers-check-result"></div>
                                    </div>
                                </div>
                                @if($product->external_url)
                                    <a href="{{ $product->external_url }}" target="_blank" class="btn btn-success"
                                       title="@lang('corals-marketplace-marty::labels.template.product_single.buy_product')">
                                        <i class="fa fa-fw fa-cart-plus"
                                           aria-hidden="true"></i> @lang('corals-marketplace-marty::labels.template.product_single.buy_product')
                                    </a>
                                @elseif($product->isSimple && $product->activeSKU(true)->stock_status != "in_stock")
                                    <a href="#" class="btn btn-sm btn-outline-danger"
                                       title="Out Of Stock">
                                        @lang('corals-marketplace-marty::labels.partial.out_stock')
                                    </a>
                                @else
                                    {!! CoralsForm::button('corals-marketplace-marty::labels.partial.add_to_cart',
                                    ['class'=>'btn btn--lg btn--round cart-btn'], 'submit') !!}
                                @endif
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="sidebar-card card--metadata">
                            <ul class="data">
                                <li>
                                    <p>
                                        <span class="lnr lnr-cart pcolor"></span>@lang('corals-marketplace-marty::labels.template.product_single.total_sales')
                                    </p>
                                    <span>{{ \ShoppingCart::totalAllInstances() }}</span>
                                </li>
                                <li>
                                    <p>
                                        <span class="lnr lnr-heart scolor"></span>@lang('corals-marketplace-marty::labels.template.product_single.favorites')
                                    </p>
                                    <span>{{$product->wishlists()->count() }}</span>
                                </li>
                                <li>
                                    <p>
                                        <span class="lnr lnr-bubble mcolor3"></span>@lang('corals-marketplace-marty::labels.template.product_single.comments')
                                    </p>
                                    <span>{{$product->comments()->count() }}</span>
                                </li>
                            </ul>


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
                        <div class="sidebar-card card--product-infos">
                            <div class="card-title">
                                <h4>@lang('corals-marketplace-marty::labels.template.product_single.product_information')</h4>
                            </div>

                            <ul class="infos">
                                <li>
                                    <p class="data-label">@lang('corals-marketplace-marty::labels.template.product_single.released')</p>
                                    <p class="info">{{$product->present('created_at')}}</p>
                                </li>
                                <li>
                                    <p class="data-label">@lang('corals-marketplace-marty::labels.template.product_single.updated')</p>
                                    <p class="info">{{$product->present('updated_at')}}</p>
                                </li>
                                @foreach($product->activeCategories as $category)
                                    <li>
                                        <p class="data-label">@lang('corals-marketplace-marty::labels.template.product_single.category')</p>
                                        <p class="info">{{ $category->name }}</p>
                                    </li>
                                @endforeach
                                <li>
                                    <p class="data-label">@lang('corals-marketplace-marty::labels.template.product_single.store')</p>
                                    <p class="info">{{$product->store->name}}
                                        @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                                            @include('partials.components.rating',[
                                            'rating'=> $product->store->averageRating(1)[0],
                                            'rating_count'=> optional($product->store->countRating())[0]])
                                        @endif
                                    </p>
                                </li>
                                <li>
                                    <p class="data-label">@lang('corals-marketplace-marty::labels.template.product_single.tag')</p>
                                    <p class="info">
                                        <a href="#">{!! implode(',',$product->tags->pluck('name')->toArray()) !!}</a>,
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="author-card sidebar-card ">
                            <div class="card-title">
                                <h4>@lang('corals-marketplace-marty::labels.template.product_single.product_information')</h4>
                            </div>

                            <div class="author-infos">
                                <div class="author_avatar">
                                    <img src="{{$product->store->thumbnail}}"
                                         alt="Presenting the broken author avatar :D">
                                </div>

                                <div class="author">
                                    <h4>{{ $product->store->name }}</h4>
                                    <p>{{format_date($product->store->created_at)}}</p>
                                </div>
                                <div class="social social--color--filled">
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <span class="fa fa-facebook"></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="fa fa-twitter"></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="fa fa-dribbble"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="author-btn">
                                    <a href="{{ $product->store->getUrl() }}" class="btn btn--sm btn--round">
                                        @lang('corals-marketplace-marty::labels.partial.profile')
                                    </a>
                                    <a href="{{ url('messaging/discussions/create?user='.$product->store->user->hashed_id) }}"
                                       class="btn btn--sm btn--round">
                                        @lang('corals-marketplace-marty::labels.template.product_single.message')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
    </section>
@endsection
@section('js')

    {!! NoCaptcha::renderJs() !!}

@endsection
