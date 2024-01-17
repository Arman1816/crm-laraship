@extends('layouts.public')

@section('content')
    @include('partials.page_header')
    @php \Actions::do_action('pre_content',$item, $home??null) @endphp
    <div class="main-content" id="home">
        <div class="wrap-banner">
            <!-- menu category -->
            <div class="container position">
                <div class="section menu-banner d-xs-none">
                    <div class="tiva-verticalmenu block">
                        <div class="box-content">
                            <div class="verticalmenu" role="navigation">
                                <ul class="menu level1">
                                    @foreach(\Shop::getActiveCategories() as $category)
                                        <li class="{{ $hasChildren = $category->hasChildren()?'has-children':'' }} item parent @if($loop->index >= 9) toggleable menu-hidden @endif">
                                            @if($hasChildren)
                                                <a href="{{url('shop?category='.$category->slug) }}"
                                                   class="d-inline-block hasicon" title="SIDE TABLE">
                                                    <img src="{{$category->thumbnail}}"
                                                         alt="{{$category->name}}"
                                                         style="width: 21px;height: 21px">{{ $category->name }}</a>
                                                <span>({{
                                            \Shop::getCategoryAvailableProducts($category->id, true)
                                            }})
                                            </span>
                                            @else
                                                <a href="{{url('shop?category='.$category->slug) }}"
                                                   class="d-inline-block hasicon">
                                                    <img src="{{$category->thumbnail}}"
                                                         alt="{{$category->name}}"
                                                         style="width: 21px;height: 21px">
                                                    <label class=""
                                                           for="ex-check-{{ $category->id }}">
                                                        {{ $category->name }}
                                                        ({{ \Shop::getCategoryAvailableProducts($category->id, true)}})
                                                    </label>
                                                </a>
                                            @endif
                                            @if($hasChildren)
                                                <div class="dropdown-menu">
                                                    <div class="menu-items">
                                                        <ul>
                                                            @foreach($category->children as $child)
                                                                <li class="item">
                                                                    <img src="{{$child->thumbnail}}"
                                                                         alt="{{$child->name}}"
                                                                         style="width: 21px;height: 21px">
                                                                    <a href="{{url('shop?category='.$child->slug) }}"
                                                                       class="d-inline-block">
                                                                        <label class=""
                                                                               for="ex-check-{{ $child->id }}">
                                                                            {{ $child->name }}
                                                                            ({{
                                                                \Shop::getCategoryAvailableProducts($child->id, true)
                                                                }})
                                                                        </label>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                    <li class="more item">@lang('corals-marketplace-pro::labels.template.home.show_more')</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- slide show -->
            <div class="section banner">
                <div class="tiva-slideshow-wrapper">
                    <div class="nivoSlider">
                        {!! $item->rendered !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- main -->
        <div id="wrapper-site">
            <div id="content-wrapper" class="full-width">
                <div id="main">
                    <section class="page-home">
                        <div class="container">
                            <!-- delivery form -->
                            <div class="section policy-home col-lg-12 col-xs-12">
                                {!!   \Shortcode::compile( 'block','home-title-page' )  !!}
                            </div>
                        </div>
                        @include('partials.three_column_lists')
                        <div class="container">
                            @include('partials.featured_categories')
                            @include('partials.featured_products')
                        </div>
                        <div class="container">
                            <div class="section testimonial-block col-lg-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 ">
                                        <div class="block">
                                            <div class="owl-carousel owl-theme testimonial-type-one">
                                                {!!   \Shortcode::compile( 'block','pre-home-block-slider' )  !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section spacing-10 groupbanner-special ">
                            {!!   \Shortcode::compile( 'block','shop-the-look' )  !!}
                            {!!   \Shortcode::compile( 'block','pre-home-block' )  !!}

                        </div>
                        <div class="container">
                            <div class="section recent-post">
                                <div class="title-block">@lang('corals-marketplace-pro::labels.template.home.recent_posts')</div>
                                <div class="row">
                                    @foreach(\CMS::getLatestPosts(3) as $post)
                                        <div class="col-md-4">
                                            <div class="item-post">
                                                <div class="thumbnail-img">
                                                    <a href="{{ url($post->slug) }}">
                                                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                                             style="">
                                                    </a>
                                                </div>
                                                <div class="post-content">
                                                    <div class="post-info">
                                                            <span class="comment">
                                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                                        <span>{!!   trans('corals-marketplace-pro::labels.post.show_author',['name' => $post->author->full_name])!!}</span>
                                                    </span>
                                                        <span class="datetime">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                         <span class="date">{{$post->created_at->format('d')}}</span>
                                                         <span class="month">{{$post->created_at->format('M')}}</span>
                                                    </span>
                                                    </div>
                                                    <div class="post-title">
                                                        <a href="{{ url($post->slug) }}">{{ $post->title }}</a>
                                                    </div>
                                                    <div class="post-desc">
                                                        {{ \Str::limit(strip_tags($post->rendered ),80) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @include('partials.featured_brands')
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    @include('partials.news')
@stop
