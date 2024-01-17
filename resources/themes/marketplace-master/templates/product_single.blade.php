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

@section('editable_content')
    @php \Actions::do_action('pre_content',$product, null) @endphp

    <!-- Page Content-->
    <div class="container padding-bottom-3x my-5">
        <div class="row">
            <!-- Product Gallery-->
            <div class="col-md-6">
                <div class="product-gallery">
                    @if($product->discount)
                        <div class="product-badge text-danger">{{ $product->discount }}% Off</div>
                    @endif
                    @if(!($medias = $product->getMedia('marketplace-product-gallery'))->isEmpty())
                        <div class="product-carousel owl-carousel text-center">
                            @foreach($medias as $media)
                                <a href="{{ $media->getUrl() }}" data-hash="gItem_{{ $media->id }}"
                                   data-lightbox="product-gallery">
                                    <img src="{{ $media->getUrl() }}" class="mx-auto" alt="Product"
                                         style="max-height: 300px;width: auto;"/>
                                </a>
                            @endforeach
                        </div>
                        <ul class="product-thumbnails">
                            @foreach($medias as $media)
                                <li class="{{ $media->getCustomProperty('featured', false)?'active':'' }}">
                                    <a href="#gItem_{{ $media->id }}">
                                        <img src="{{ $media->getUrl() }}" alt="Product"
                                             style="max-height: 100px;width: auto;"></a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted">
                            <small>@lang('corals-marketplace-master::labels.template.product_single.image_unavailable')</small>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Product Info-->
            <div class="col-md-6">
                <div class="padding-top-2x mt-2 hidden-md-up"></div>
                @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                    @include('partials.components.rating',['rating'=> $product->averageRating(1)[0],'rating_count'=> $product->countRating()])
                @endif
                <h1 class="padding-top-1x text-normal">{{ $product->name }}</h1>

                <span class="h2 d-block" id="sku-price">
                    {!! $product->price  !!}
                </span>
                <h4 class=" text-normal">{{ $product->caption }}</h4>
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
                    <input type="hidden" name="sku_hash" value="{{ $product->activeSKU(true)->hashed_id }}"/>
                @endif
                <div class="row m-t-20">
                    <div class="col-md-4">
                        {!! CoralsForm::number('quantity','corals-marketplace-master::attributes.template.quantity', false, 1, ['min' => 1,'class'=>'form-control form-control-sm']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @includeWhen($product->price_per_quantity, "templates.partials.price_per_quantity_description",['pricePerQuantity'=>$product->price_per_quantity])
                        @includeWhen($product->offers, "templates.partials.offers_list",['offers'=>$product->offers])

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="offers-check-result"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                            @include('partials.components.wishlist',['wishlist'=> $product->inWishList() ])
                        @endif

                        @if($product->external_url)
                            <a href="{{ $product->external_url }}" target="_blank" class="btn btn-success"
                               title="@lang('corals-marketplace-master::labels.template.product_single.buy_product')">
                                <i class="fa fa-fw fa-cart-plus"
                                   aria-hidden="true"></i> @lang('corals-marketplace-master::labels.template.product_single.buy_product')
                            </a>
                        @elseif($product->isSimple && $product->activeSKU(true)->stock_status != "in_stock")
                            <a href="#" class="btn btn-sm btn-outline-danger"
                               title="Out Of Stock">
                                @lang('corals-marketplace-master::labels.partial.out_stock')
                            </a>
                        @else
                            {!! CoralsForm::button('corals-marketplace-master::labels.partial.add_to_cart',
                            ['class'=>'btn add-to-cart btn-sm btn-primary'], 'submit') !!}
                        @endif
                    </div>
                </div>

                {{ Form::close() }}
                <div class="mb-2">
                <span class="text-medium"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    @lang('corals-marketplace-master::labels.template.product_single.category')</span>
                    @foreach($product->activeCategories as $category)
                        <a class=""
                           href="{{ url('shop?category='.$category->slug) }}"><b>{{ $category->name }}</b></a>
                        &nbsp;&nbsp;
                    @endforeach
                </div>
                <div class="mb-2">
                <span class="text-medium"><i
                            class="fa fa-home"></i> @lang('corals-marketplace-master::labels.template.product_single.store')</span>
                    <a class="" href="{{ $product->store->getUrl() }}"><b>{{ $product->store->name }}</b></a>
                    &nbsp;&nbsp;
                    @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                        @include('partials.components.rating',[
                        'rating'=> $product->store->averageRating(1)[0],
                        'rating_count'=> optional($product->store->countRating())[0]])
                    @endif
                </div>
                @if($product->activeTags->count())
                    <div class="padding-bottom-1x mb-2">
                    <span class="text-medium"><i class="fa fa-tags" aria-hidden="true"></i>
                        @lang('corals-marketplace-master::labels.template.product_single.tag')</span>
                        @foreach($product->activeTags as $tag)
                            <a class="" href="{{ url('shop?tag='.$tag->slug) }}"><b>{{ $tag->name }}</b></a>
                            &nbsp;&nbsp;
                        @endforeach
                    </div>
                @endif
                <hr class="mb-3">
                <div class="d-flex flex-wrap justify-content-between">
                    @include('partials.components.social_share',['url'=> URL::current() , 'title'=>$product->name ])

                </div>
            </div>
        </div>
        <!-- Product Tabs-->
        <div class="row padding-top-3x mb-3">
            <div class="col-lg-10 offset-lg-1">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#description" data-toggle="tab"
                                            role="tab">@lang('corals-marketplace-master::labels.template.product_single.description')</a>
                    </li>
                    @if($product->present('options'))
                        <li>
                            <a data-toggle="tab" class="nav-link" href="#product-specs">
                                @lang('corals-marketplace-master::labels.template.product_single.product_options')
                            </a>
                        </li>
                    @endif
                    @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                        <li class="nav-item"><a class="nav-link" href="#reviews" data-toggle="tab"
                                                role="tab">@lang('corals-marketplace-master::labels.template.product_single.reviews',['count'=>$product->ratings->count()])</a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <div>
                            {!! $product->description !!}
                        </div>
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
                    @if(\Settings::get('marketplace_rating_enable',true) == 'true')
                        <br>
                        @include('partials.tabs.reviews',['reviews'=>$product->ratings])
                    @endif

                </div>
            </div>
        </div>

        @include('partials.featured_products',['title'=>trans('corals-marketplace-master::labels.template.product_single.title')])
    </div>
@stop
