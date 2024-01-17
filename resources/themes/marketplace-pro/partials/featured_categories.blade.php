@php $categories = \Shop::getFeaturedCategories(); @endphp
@if(!$categories->isEmpty())

    <div class="featured-category">
        <div class="container">
            <div class="tab-content text-center">
                <div class="title-product">
                    <h2>@lang('corals-marketplace-pro::labels.partial.featured_categories')</h2>
                </div>
                <div class="featured owl-carousel owl-theme">
                    @foreach($categories as $category)

                        <div class="content-category">
                            <div class="content-img">
                                <a href="{{  url('shop?category='.$category->slug) }}">
                                    <img class="img-fluid" src="{{ $category->thumbnail }}" alt="{{ $category->name }}"
                                         title="{{ $category->name }}">
                                </a>
                            </div>
                            <div class="info-category">
                                <h3>
                                    <a href="{{  url('shop?category='.$category->slug) }}">{{ $category->name }}</a>
                                </h3>
                                <p></p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


@endif