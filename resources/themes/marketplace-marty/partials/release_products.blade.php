@php $products = \Corals\Modules\Marketplace\Models\Product::query()->where('status','active')->get() @endphp
@if(!$products->isEmpty())
    <section class="products section--padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-title-area">
                        <div class="product__title">
                            <h2>
                                @lang('corals-marketplace-marty::labels.template.home.newest_products')
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($products as $product)
                    @include('partials.product_grid_item')
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="more-product">
                        <a href="{{url('shop')}}"
                           class="btn btn--lg btn--round">@lang('corals-marketplace-marty::labels.template.home.view_all_products')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
