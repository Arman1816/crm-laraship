<aside class="sidebar sidebar-offcanvas">
    <!-- Widget Search-->
    <div class="sidebar-block">
        <form class="input-group form-group" action="{{ url('blog') }}" method="get">
            <span class="input-group-btn">
              <button type="submit"><i class="icon-search"></i></button>
            </span>
            <input class="form-control" type="text" name="query" placeholder="Search blog">
        </form>

        <div class="title-block m-t-30">@lang('corals-marketplace-pro::labels.partial.categories')</div>
        <div class="block-content">
            @foreach(\CMS::getCategoriesList(true, 'active') as $category)
                <div class="cateTitle hasSubCategory open level1">
                    <a href="{{ url('category/'.$category->slug) }}" class="cateItem">
                        {{ $category->name }}
                    </a>
                    <span>{{ \CMS::getCategoryPostsCount($category) }}</span>
                </div>
            @endforeach
        </div>
    </div>
    <section class="widget widget-tags">
        <h3 class="widget-title">@lang('corals-marketplace-pro::labels.partial.tag_cloud')</h3>
        @foreach(\CMS::getTagsList(true, 'active') as $tag)
            <a class="tag {{ Request::is('tag/'.$tag->slug)?'active':'' }}" href="{{ url('tag/'.$tag->slug) }}">
                {{ $tag->name }}
            </a>
        @endforeach
    </section>
</aside>