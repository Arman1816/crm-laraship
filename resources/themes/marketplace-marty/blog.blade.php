@extends('layouts.public')

@section('content')
    @include('partials.page_header', ['item'=>$blog, 'content'=> (empty($blog->rendered)?('<h1>'.$blog->title.'</h1>'):$blog->rendered).(isset($title)?('<br/>'.$title):'')])
    <section class="blog_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="{{ !in_array($blog->template, ['right', 'left'])?'':'col-lg-8' }} {{ $blog->template =='left' ? 'order-lg-2':'' }}">
                    @forelse($posts as $post)
                        <div class="single_blog blog--default">
                            <figure>
                                @if($post->featured_image)
                                    <img src="{{ $post->featured_image }}" alt="Post">
                                @endif
                                <figcaption>
                                    <div class="blog__content">
                                        <a href="{{ url($post->slug) }}" class="blog__title">
                                            <h4>{{ $post->title }}</h4>
                                        </a>

                                        <div class="blog__meta">
                                            <div class="author">
                                                <span class="lnr lnr-user"></span>
                                                <p>
                                                    <a href="#">{{ $post->author->full_name }}</a>
                                                </p>
                                            </div>
                                            <div class="date_time">
                                                <span class="lnr lnr-clock"></span>
                                                <p>&nbsp;{{ format_date($post->published_at) }}</p>
                                            </div>
                                            <div class="comment_view">
                                                <p class="comment">
                                                    @foreach($post->activeCategories as $category)
                                                        <a href="{{ url('category/'.$category->slug) }}">
                                                            &nbsp;{{ $category->name }}
                                                        </a>,
                                                    @endforeach
                                                </p>
                                                @if(count($activeTags = $post->activeTags))
                                                    <p class="view">
                                                        @foreach($activeTags as $tag)
                                                            <a href="{{ url('tag/'.$tag->slug) }}">&nbsp;{{ $tag->name }}</a>
                                                            ,
                                                        @endforeach
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn_text">
                                        <p>{{ \Str::limit(strip_tags($post->rendered ),250) }}</p>
                                        <a href="{{ url($post->slug) }}"
                                           class="btn btn--md btn--round">@lang('corals-marketplace-marty::labels.blog.read_more')</a>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            <h4>@lang('corals-marketplace-marty::labels.blog.no_posts_found')</h4>
                        </div>
                    @endforelse
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