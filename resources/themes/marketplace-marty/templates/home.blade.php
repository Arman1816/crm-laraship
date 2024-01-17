@extends('layouts.public')
@section('css')
    <style>
        .row {
            margin: 0;
        }
    </style>
@endsection
@section('content')

    @php \Actions::do_action('pre_content',$item, $home??null) @endphp

    <section class="hero-area bgimage">
        <div class="bg_image_holder">
            <img src="{!! \Theme::url('images/hero_area_bg1.jpg') !!}" alt="background-image">
        </div>
        <div class="hero-content content_above">
            <div class="content-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="hero__content__title">
                                {!! $item->rendered !!}
                            </div>
                            <div class="hero__btn-area">
                                <a href="{{url('shop')}}"
                                   class="btn btn--round btn--lg">@lang('corals-marketplace-marty::labels.template.home.view_all_products')</a>
                                <a href="{{url('shop?featured=true')}}"
                                   class="btn btn--round btn--lg">@lang('corals-marketplace-marty::labels.template.home.popular_products')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-area">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="search_box">
                            <form action="{{url('shop')}}" method="get">
                                <input type="text" name="search" value="{{request()->get('search')}}" class="text_field"
                                       placeholder="Search your products...">
                                <div class="search__select select-wrap">
                                    <select name="category" class="select--field" id="blah">
                                        @foreach(\Shop::getActiveCategories() as $category)
                                            <option value="{{$category->slug}}">{!! $category->name !!}</option>
                                        @endforeach
                                    </select>
                                    <span class="lnr lnr-chevron-down"></span>
                                </div>
                                <button type="submit" class="search-btn btn--lg">Search Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="features section--padding">
        <div class="container">
            <div class="row">
                {!!   \Shortcode::compile( 'block','blocks-home-marketplace' ) ; !!}
            </div>
        </div>
    </section>
    @include('partials.featured_products')
    @include('partials.release_products')
    <section class="counter-up-area bgimage">
        <div class="bg_image_holder">
            <img src="{!! \Theme::url('images/countbg.jpg') !!}" alt="">
        </div>
        <div class="container content_above">
            <div class="col-md-12">
                <div class="counter-up">
                    <div class="counter mcolor2">
                        <span class="lnr lnr-briefcase"></span>
                        <span class="count">{{$number_of_store = \Corals\Modules\Marketplace\Models\Store::query()->count()}}</span>
                        <p>Number of Stores</p>
                    </div>
                    <div class="counter mcolor3">
                        <span class="lnr lnr-cloud-download"></span>
                        <span class="count">{{$number_of_products = \Corals\Modules\Marketplace\Models\Product::query()->count()}}</span>
                        <p>Number of Products</p>
                    </div>
                    <div class="counter mcolor1">
                        <span class="lnr lnr-smile"></span>
                        <span class="count">{{$number_of_orders = \Corals\Modules\Marketplace\Models\Order::query()->count()}}</span>
                        <p>Orders & Downloaded</p>
                    </div>
                    <div class="counter mcolor4">
                        <span class="lnr lnr-users"></span>
                        <span class="count">{{$number_of_user = \Corals\User\Models\User::query()->count()}}</span>
                        <p>Members</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="why_choose section--padding">
        <div class="container">
            {!!   \Shortcode::compile( 'block','blocks-why-choose' ) ; !!}
            {!!   \Shortcode::compile( 'block','blocks-home-marty' ) ; !!}
        </div>
    </section>
    <section class="proposal-area">
        <div class="container-fluid">
            {!!   \Shortcode::compile( 'block','blocks-home-shopping' ) ; !!}
        </div>
    </section>
    @include('partials.testimonials')
    @include('partials.latest_news')
    <section class="special-feature-area">
        <div class="container">
            {!!   \Shortcode::compile( 'block','blocks-home-support' ) ; !!}
        </div>
    </section>
    <section class="call-to-action bgimage">
        {!!   \Shortcode::compile( 'block','call-to-action' ) ; !!}

    </section>
@endsection
