@extends('layouts.public')

@section('content')
    @include('partials.page_header',['title'=>$item->title,'featured_image'=>null])
    <section class="blog_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 {{ !in_array($blog->template, ['right', 'left'])?'col-lg-12':'col-lg-8' }} {{ $blog->template =='left' ? 'order-lg-2':'' }}">
                    <div class="single_blog blog--default">
                        <article>
                            @if($featured_image)
                                <figure>
                                    <img src="{{ $featured_image }}" alt="Post">
                                </figure>
                            @endif
                            <div class="blog__content">
                                <a href="#" class="blog__title">
                                    <h4>{!! $item->title !!}</h4>
                                </a>

                                <div class="blog__meta">
                                    <div class="author">
                                        <span class="lnr lnr-user"></span>
                                        <p>
                                            <a href="#">{!!  trans('corals-marketplace-marty::labels.post.show_author',['name' => $item->author->full_name]) !!}</a>
                                        </p>
                                    </div>
                                    <div class="date_time">
                                        <span class="lnr lnr-clock"></span>
                                        <p>{{ format_date($item->published_at) }}</p>
                                    </div>
                                    <div class="comment_view">
                                        <p class="comment">
                                            @foreach($item->post->activeCategories as $category)
                                                <a href="{{ url('category/'.$category->slug) }}">
                                                    &nbsp;{{ $category->name }}
                                                </a>,
                                            @endforeach
                                        </p>
                                        @if(count($activeTags = $item->post->activeTags))
                                            <p class="view">
                                                @foreach($activeTags as $tag)
                                                    <a href="{{ url('tag/'.$tag->slug) }}">&nbsp;{{ $tag->name }}</a>
                                                    ,
                                                @endforeach
                                            </p>
                                        @endif                        </div>
                                </div>
                            </div>
                            <div class="single_blog_content">
                                {!! $item->rendered !!}
                                <div class="share_tags">
                                    <div class="share">
                                        <p>Share this post</p>
                                    @include('partials.components.social_share',['url'=> URL::current() , 'title'=>$item->name ])
                                    </div>
                                </div>

                                @if(\Settings::get('cms_comments_enabled'))
                                    <div class="row">
                                        <div class="col-md-12">
                                            @include('CMS::partials.comments',['comments'=>$item->publishedComments])
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </article>
                    </div>
                </div>
                @if(in_array($blog->template, ['right', 'left']))
                    <div class="co-lg-4 {{ $blog->template =='left' ? 'order-lg-1':'' }}">
                        @include('partials.blog_sidebar')
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
