<aside class="sidebar sidebar--blog">
    <div class="sidebar-card card--search card--blog_sidebar">
        <div class="card-title">
            <h4>@lang('corals-marketplace-marty::labels.blog.search_blog')</h4>
        </div>
        <div class="card_content">
            <form action="{{ url('blog') }}" method="get">
                <div class="searc-wrap">
                    <input type="text" name="query" placeholder="@lang('corals-marketplace-marty::labels.blog.search_blog')">
                    <button type="submit" class="search-wrap__btn">
                        <span class="lnr lnr-magnifier"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="sidebar-card card--blog_sidebar card--category">
        <div class="card-title">
            <h4>@lang('corals-marketplace-marty::labels.partial.categories')</h4>
        </div>
        <div class="collapsible-content">
            <ul class="card-content">
                @foreach(\CMS::getCategoriesList(true, 'active') as $category)
                    <li>
                        <a href="{{ url('category/'.$category->slug) }}">
                            <span class="lnr lnr-chevron-right"></span>{{ $category->name }}
                            <span class="item-count">{{ \CMS::getCategoryPostsCount($category) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="sidebar-card card--blog_sidebar card--tags">
        <div class="card-title">
            <h4>@lang('corals-marketplace-marty::labels.partial.tag_cloud')</h4>
        </div>
        <ul class="tags">
            @foreach(\CMS::getTagsList(true, 'active') as $tag)
                <li>
                    <a class="{{ Request::is('tag/'.$tag->slug)?'active':'' }}"
                       href="{{ url('tag/'.$tag->slug) }}">
                        {{ $tag->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>
