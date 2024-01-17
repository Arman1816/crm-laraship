@php $stores = \Store::getFeaturedStores(); @endphp

@if(!$stores->isEmpty())
    <!-- Featured Stores-->

    <section class="container pt-30">
        <h3 class="text-center mb-30">{{  trans('corals-marketplace-master::labels.partial.featured_stores') }}</h3>
        <div class="row">
            @foreach($stores as $store)

                <div class="col-md-6 mb-30"><a class="category-card flex-wrap flex-lg-nowrap"
                                               href="{{ $store->getUrl() }}">
                        <div class="category-card-info align-self-center">
                            <h3 class="category-card-title">{{$store->name}}</h3>
                            <p class="category-card-subtitle">{!! $store->short_description !!}</p>
                            <small class="d-inline-block bg-info text-white mt-3">
                                &nbsp;&nbsp;@lang('corals-marketplace-master::labels.partial.shop_now')<i
                                        class="icon-chevron-right d-inline-block align-middle"></i>&nbsp;</small>
                        </div>
                        <div class="category-card-thumb"><img src="{{$store->thumbnail }}" alt="Store"></div>
                    </a></div>
            @endforeach

        </div>
    </section>
@endif

