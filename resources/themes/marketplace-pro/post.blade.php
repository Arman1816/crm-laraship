@extends('layouts.public')

@section('content')
    @include('partials.page_header',['title'=>$item->title,'featured_image'=>null])
    <div class="container padding-bottom-3x mb-1" id="blog-detail">
        <div class="content">
            <div class="row {{ !in_array($blog->template, ['right', 'left'])?'justify-content-center':'' }}">
                <div class="{{ !in_array($blog->template, ['right', 'left'])?'col-lg-10':'col-sm-8 col-lg-9 col-md-9 flex-xs-first main-blogs' }} {{ $blog->template =='left' ? 'order-lg-2':'' }}">
                    <!-- Post-->
                    <h3>{!! $item->title !!}</h3>
                    <div class="hover-after">
                        @if($featured_image)
                            <img src="{{ $featured_image }}"
                                 class="img-fluid"
                                 alt="Post" style="width: 100%;">
                        @endif
                    </div>
                    <div class="late-item">
                        {!! $item->rendered !!}
                        <div class="border-detail">
                            <p class="post-info float-left">
                                <span>{{ format_date($item->published_at) }}</span>
                                @foreach($item->post->activeCategories as $category)
                                    <a href="{{ url('category/'.$category->slug) }}">
                                        &nbsp;<span>{{ $category->name }}</span>
                                    </a>,&nbsp;
                                @endforeach
                            </p>
                            <div class="btn-group">
                                {!!   trans('corals-marketplace-pro::labels.post.show_author',['name' => $item->author->full_name])!!}
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

                </div>
                @if(in_array($blog->template, ['right', 'left']))
                    <div class="sidebar-3 sidebar-collection col-lg-3 col-md-3 col-sm-4  {{ $blog->template =='left' ? 'order-lg-1':'' }}">
                        @include('partials.blog_sidebar')
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
