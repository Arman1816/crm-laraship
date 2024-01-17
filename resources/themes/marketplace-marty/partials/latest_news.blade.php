<section class="latest-news section--padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h1>@lang('corals-marketplace-marty::labels.template.home.latest_news')</h1>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach(\CMS::getLatestNews(3) as $news)
                <div class="col-lg-4 col-md-6">
                    <div class="news">
                        <div class="news__content">
                            <a href="{{ url($news->slug) }}" class="news-title">
                                <h4>{{$news->title}}</h4>
                            </a>
                            <p>
                                {!! $news->content !!}
                            </p>
                        </div>
                        <div class="news__meta">
                            <div class="date">
                                <span class="lnr lnr-clock"></span>
                                <p>{{format_date($news->created_at)}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>