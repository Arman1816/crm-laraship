@php $stores = \Store::getTopRatedStores(6,3.5); @endphp

@if(!$stores->isEmpty())
    <!-- Top Categories-->
    <section class="container padding-top-3x">
        <h3 class="text-center mb-30">@lang('corals-marketplace-master::labels.partial.top_rated_sellers')</h3>
        @php $j=0; @endphp
        @foreach($stores as $store)
            @if($j == 0)
                <div class="row">
                    @endif
                    <div class="col-md-2 col-sm-3">
                        <div class="card mb-30">
                            @include('partials.components.rating',['rating'=> $store->averageRating(1)[0],'rating_count'=>null])
                            <a class="card-img-tiles" href="{{ $store->getUrl() }}">
                                <div class="inner">
                                    <div class="main-img">
                                        <img src="{{ $store->thumbnail }}" alt="Store" class="mx-auto"
                                             style="max-height: 150px;width: auto;">
                                    </div>
                                </div>
                            </a>
                            <div class="card-body text-center">
                                <h6 class="card-title">
                                    <a href="{{ $store->getUrl() }}">{{ $store->name }}</a>
                                </h6>
                                <a class="btn btn-outline-primary btn-sm"
                                   href="{{ $store->getUrl() }}">@lang('corals-marketplace-master::labels.partial.view_store')
                                </a>
                            </div>
                        </div>
                    </div>
                    @if (++$j == 6)
                </div>
                @php $j = 0; @endphp
                @endif
                @endforeach

                @if($j != 0)</div>@endif
            <div class="row">
                <div class="col text-center">
                    <a class="btn btn-outline-secondary margin-top-none" href="{{ url('shop/stores') }}">
                        @lang('corals-marketplace-master::labels.partial.all_stores')
                    </a>
                </div>
            </div>
    </section>
@endif