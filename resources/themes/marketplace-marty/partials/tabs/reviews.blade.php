<ul class="media-list thread-list">
    @foreach($reviews as $review)
        <li class="single-thread">
            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object" src="{{ @$review->author->picture_thumb }}"
                             alt="Commentator Avatar">
                    </a>
                </div>
                <div class="media-body">
                    <div>
                        <div class="media-heading">
                            <a href="#">
                                <h4>{{ $review->title }}</h4>
                            </a>
                            <span>{{ format_date($review->created_at) }}</span>
                        </div>
                        <div class="rating product--rating">
                            <ul>
                                @include('partials.components.rating',['rating'=> $review->rating,'rating_count'=>null ])
                            </ul>
                        </div>
                        <span class="comment-tag buyer">{{ @$review->author->full_name }}</span>
                    </div>
                    <p>{{ $review->body }}</p>
                </div>
            </div>
        </li>
    @endforeach
</ul>
<div class="comment-form-area">
    @if(!user())
        <div class="alert alert-info alert-dismissible fade show text-center margin-bottom-1x"><span
                    class="alert-close"
                    data-dismiss="alert"></span>
            <i class="icon-layers"></i>@lang('corals-marketplace-marty::labels.partial.tabs.need_login_review')
        </div>
    @else
        <h4>@lang('corals-marketplace-marty::labels.partial.tabs.leave_review')</h4>
        <div class="">
            <div class="media-left">
                <a href="#">
                    <img class="media-object" src="{{user()->picture_thumb}}" style="max-width: 70px"
                         alt="Commentator Avatar">
                </a></div>
            {!! Form::open( ['url' => url('shop/'.$product->hashed_id.'/rate'),'method'=>'POST', 'class'=>'ajax-form row','id'=>'checkoutForm','data-page_action'=>"clearForm"]) !!}
            <div class="col-sm-6">
                {!! CoralsForm::text('review_subject','corals-marketplace-marty::attributes.tab.subject',true) !!}
            </div>
            <div class="col-sm-6">
                {!! CoralsForm::select('review_rating', 'corals-marketplace-marty::attributes.tab.rating', trans('corals-marketplace-marty::attributes.tab.rating_option'),true) !!}
            </div>
            <div class="col-12">
                {!! CoralsForm::textarea('review_text','corals-marketplace-marty::attributes.tab.review',true,null,['rows'=>4,'class'=>'bla']) !!}
            </div>
            {!! CoralsForm::button('corals-marketplace-marty::labels.partial.tabs.submit_review',['class'=>'btn btn--md btn--round'], 'submit') !!}

            {!! Form::close() !!}
        </div>
    @endif
</div>
