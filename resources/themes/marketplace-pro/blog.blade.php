@extends('layouts.public')

@section('content')
    @include('partials.page_header', ['item'=>$blog, 'content'=> (empty($blog->rendered)?('<h1>'.$blog->title.'</h1>'):$blog->rendered).(isset($title)?('<br/>'.$title):'')])

    <div class="container" id="blog-detail">
        <div class="content">
            <div class="row">
                @if(in_array($blog->template, ['right', 'left']))
                    <div class="sidebar-3 sidebar-collection col-lg-3 col-md-3 col-sm-4 {{ $blog->template =='right' ? 'order-lg-2':'' }}">
                        @include('partials.blog_sidebar')
                    </div>
                @endif
                <div class="{{ !in_array($blog->template, ['right', 'left'])?'col-lg-12':'col-md-9 flex-xs-first main-blogs' }} {{ $blog->template =='right' ? 'order-lg-1':'' }}">
                    @forelse($posts as $post)
                        @if($loop->first)
                            <h2> @lang('corals-marketplace-pro::labels.blog.recent_posts')
                            </h2>
                        @endif
                        <div class="list-content row" style="margin-bottom: 2.5rem">
                            @if($post->featured_image)
                                <div class="hover-after col-md-5 col-xs-12">
                                    <a href="{{ url($post->slug) }}"><img
                                                src="{{ $post->featured_image }}"
                                                alt="Post" class="img-fluid"></a>
                                </div>
                            @endif

                            <div class="late-item col-md-7 col-xs-12" style="margin: 0 !important;">
                                <p class="content-title m-t-0">
                                    <a href="{{ url($post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </p>
                                <p class="post-info">
                                    <span>{{ format_date($post->published_at) }}</span>
                                    <span>{!!   trans('corals-marketplace-pro::labels.post.show_author',['name' => $post->author->full_name])!!}</span>
                                    @foreach($post->activeCategories as $category)
                                        <a href="{{ url('category/'.$category->slug) }}" style="color: #999999;">
                                            <span>{{ $category->name }}</span>
                                        </a>
                                    @endforeach
                                </p>
                                <p>
                                    {{ \Str::limit(strip_tags($post->rendered ),150) }}
                                </p>
                                <span class="view-more">
                                  <a href='{{ url($post->slug) }}'>
                                      @lang('corals-marketplace-pro::labels.blog.read_more')
                                  </a>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            <h4>@lang('corals-marketplace-pro::labels.blog.no_posts_found')</h4>
                        </div>
                    @endforelse
                </div>
            </div>
            {{ $posts->links('partials.paginator') }}
        </div>
    </div>
@endsection
