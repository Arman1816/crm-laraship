<div class="product-card product-list"><a class="product-thumb" href="{{ $store->getUrl() }}">
        @if(\Settings::get('marketplace_rating_enable',true) == 'true')
            @include('partials.components.rating',['rating'=> $store->averageRating(1)[0],'rating_count'=>null])
        @endif
        <img src="{{ $store->thumbnail }}" alt="Store"></a>
    <div class="product-info">
        <h3 class="product-title"><a href="{{ $store->getUrl()   }}">{{ $store->name }}</a></h3>
        <p class="hidden-xs-down">{{ $store->short_description }}</p>
        <div class="product-buttons">
            @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                @include('partials.components.store_follow',['wishlist'=> $store->inWishList() ])
            @endif

            <a href="{{ $store->getUrl() }}" class="btn btn-outline-primary btn-sm">
                @lang('corals-marketplace-master::labels.template.store.view_store')
            </a>
        </div>
    </div>
</div>
