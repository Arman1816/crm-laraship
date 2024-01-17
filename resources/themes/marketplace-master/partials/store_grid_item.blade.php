<div class="grid-item">
    <div class="store-card">

        @if(\Settings::get('marketplace_rating_enable',true) == 'true')
            @include('partials.components.rating',['rating'=> $store->averageRating(1)[0],'rating_count'=>null])
        @endif
        <div style="min-height: 170px;" class="mt-4">
            <a class="store-thumb" href="{{ $store->getUrl() }}">
                <img src="{{ $store->thumbnail }}" alt="{{ $store->name }}" class="mx-auto"
                     style="max-height: 150px;width: auto;">
            </a>
        </div>
        <div class="store-buttons">
            @if(\Settings::get('marketplace_wishlist_enable', 'true') == 'true')
                @include('partials.components.store_follow',['wishlist'=> $store->inWishList()])
            @endif

            <a href="{{$store->getUrl()  }}" class="btn btn-outline-primary btn-sm">
                @lang('corals-marketplace-master::labels.template.store.view_store')
            </a>

        </div>
    </div>
</div>
