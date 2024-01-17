@extends('layouts.public')

@section('content')
    @include('partials.page_header', ['content'=>$faq->title])
    <section class="faq_area section--padding">
        <div class="container">
            <div class="row">
                @if(count($categories = \CMS::getCategoriesList(true, null, null, 'faq')))
                    <div class="col-lg-8">
                        <div class="cardify faq_module">
                            <div class="faq-title">
                                <span class="lnr lnr-file-add"></span>
                                <h4>Faqs</h4>
                            </div>
                            <div class="faqs">
                                <div class="panel-group accordion" role="tablist" id="accordion">
                                    @forelse($faqs as $faq)
                                        <div class="panel accordion__single" id="panel-{{$faq->hashed_id}}">
                                            <div class="single_acco_title">
                                                <h4>
                                                    <a data-toggle="collapse" href="#collapse{{$faq->hashed_id}}"
                                                       class="collapsed"
                                                       aria-expanded="false" data-target="#collapse{{$faq->hashed_id}}"
                                                       aria-controls="collapse{{$faq->hashed_id}}">
                                                        <span>{!! $faq->title !!}</span>
                                                        <i class="lnr indicator lnr-chevron-down"></i>
                                                    </a>
                                                </h4>
                                            </div>

                                            <div id="collapse{{$faq->hashed_id}}" class="panel-collapse collapse"
                                                 aria-labelledby="panel-{{$faq->hashed_id}}"
                                                 data-parent="#accordion" style="">
                                                <div class="panel-body">
                                                    <p>{!! $faq->content !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <aside class="sidebar faq--sidebar">
                            <div class="sidebar-card faq--card">
                                <div class="faq-title">
                                    <span class="lnr lnr-file-add"></span>
                                    <h4>@lang('corals-marketplace-marty::labels.post.category')</h4>
                                </div>
                                <div class="collapsible-content">
                                    <ul class="card-content">
                                        @foreach($categories as $category)
                                            <li>
                                                <a href="#{{ $category->slug }}"><span></span>
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </aside>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection