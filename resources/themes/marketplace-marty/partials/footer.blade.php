<footer class="footer-area">
    <div class="footer-big section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="info-footer">
                        <div class="info__logo">
                            <img src="{{ \Settings::get('site_logo') }}" alt="footer logo">
                        </div>
                        <ul class="info-contact">
                            <li>
                                <span class="lnr lnr-phone info-icon"></span>
                                <span class="info">@lang('corals-marketplace-marty::labels.template.contact.phone'): {{ \Settings::get('contact_mobile','+970599593301') }}</span>
                            </li>
                            <li>
                                <span class="lnr lnr-envelope info-icon"></span>
                                <span class="info">{{ \Settings::get('contact_form_email','support@example.com') }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="link-info">
                        <ul class="list-unstyled currencies" style="display: inline-block;">
                            @php \Actions::do_action('post_display_frontend_menu') @endphp
                        </ul><br>


                        @if(count(\Settings::get('supported_languages', [])) > 1)
                            {!! \Language::flags('list-inline') !!}
                        @endif
                    </div>
                    <!-- end /.info-footer -->
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="footer-menu">
                        <h4 class="footer-widget-title text--white">@lang('corals-marketplace-marty::labels.partial.latest_news')</h4>
                        <ul>
                            @foreach(\CMS::getLatestNews(3) as $newsItem)
                                <li>
                                    <a href="{{url($newsItem->slug)}}">{{$newsItem->title}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">

                <div class="footer-menu">
                        <h4 class="footer-widget-title text--white">
                            @lang('corals-marketplace-marty::labels.partial.menu_footer')
                        </h4>
                        <ul>
                            @foreach(Menus::getMenu('frontend_footer','active') as $menu)
                                <li>
                                    <a href="{{url($menu->url)}}">{{$menu->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="newsletter">
                        <h4 class="footer-widget-title text--white">Newsletter</h4>
                        <div class="newsletter__form">
                            {!! Form::open( ['url' => url('utilities/newsletter/subscribe'),'method'=>'POST', 'class'=>'subscribe-form ajax-form','id'=>'subscribe']) !!}
                            <div class="field-wrapper">
                                <input class="relative-field rounded" type="text" name="list_id"
                                       placeholder="Enter email">
                                <button class="btn btn--round" type="submit">
                                    @lang('corals-marketplace-marty::labels.template.home.subscribe')
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="social social--color--filled">
                            <ul>
                                @foreach(\Settings::get('social_links',[]) as $key=>$link)
                                    <li>
                                        <a href="{{ $link }}">
                                            <span class="fa fa-{{ $key }}"></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mini-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright-text">
                        <p>{!! \Settings::get('footer_text','') !!}</p>
                    </div>

                    <div class="go_top">
                        <span class="lnr lnr-chevron-up"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>