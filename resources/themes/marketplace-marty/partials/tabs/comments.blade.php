<ul class="media-list thread-list">
    @if($comments && count($comments))
        @foreach($comments as $comment)
            <li class="single-thread">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object" src="{{ optional($comment->comment_author)->picture_thumb }}"
                                 alt="Commentator Avatar">
                        </a>
                    </div>
                    <div class="media-body">
                        <div>
                            <div class="media-heading">
                                <a href="#">
                                    <h4>{{optional($comment->comment_author)->full_name}}</h4>
                                </a>
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="#"
                               class="reply-link">@lang('corals-marketplace-marty::labels.template.product_single.reply')</a>
                        </div>
                        <p>{{ $comment->body }}</p>
                    </div>
                </div>
                <ul class="children">
                    @if(count($comment->comments))
                        @foreach($comment->comments as $reply)
                            <li class="single-thread depth-2">
                                <div class="media">
                                    <div class="media-left">
                                        <a href="#">
                                            <img class="media-object"
                                                 src="{{ optional($reply->author)->picture_thumb }}"
                                                 alt="Commentator Avatar">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-heading">
                                            <h4>{{optional($reply->author)->full_name}}</h4>
                                            @if((optional($reply->comment_author)->id) == $product->created_by)
                                                <span>@lang('corals-marketplace-marty::labels.template.product_single.comment_author')</span>
                                            @endif
                                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <span class="comment-tag author">@lang('corals-marketplace-marty::labels.template.product_single.comment_author')</span>
                                        <p>{{$reply->body}}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
                @if(user() && user()->can('Utility::comment.reply') && ((optional($comment->comment_author)->id == user()->id) || ($product->created_by == user()->id ) )   )
                    <div class="media depth-2 reply-comment">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object" src="{{ user()->picture_thumb }}"
                                     alt="Commentator Avatar">
                            </a>
                        </div>
                        <div class="media-body">
                            <form class="comment-reply-form ajax-form"
                                  action="{{url('marketplace/products/'.$comment->hashed_id.'/create-reply' )}}"
                                  method="POST"
                                  data-page_action="site_reload">
                                                        <textarea class="bla" name="body"
                                                                  placeholder="@lang('corals-marketplace-marty::labels.template.product_single.create_reply')"></textarea>
                                <button type="submit"
                                        class="btn btn--md btn--round">@lang('corals-marketplace-marty::labels.template.product_single.create_reply')</button>
                            </form>
                        </div>
                    </div>
                @endif
            </li>
        @endforeach
    @endif
</ul>
@if(user() && user()->can('Utility::comment.create'))
    <div class="comment-form-area">
        <h4>Leave a comment</h4>
        <div class="media comment-form">
            <div class="media-left">
                <a href="#">
                    <img class="media-object" style="max-width: 70px" src="{{ user()->picture_thumb }}"
                         alt="Commentator Avatar">
                </a>
            </div>
            <div class="media-body">
                <form action="{{url('marketplace/products/'.$product->hashed_id.'/create-comment' )}}"
                      method="POST"
                      data-page_action="site_reload" class="comment-reply-form ajax-form">
                    <div class="form-group">
                          <textarea name="body"
                                    placeholder="@lang('corals-marketplace-marty::labels.template.product_single.create_comment')"></textarea>
                    </div>
                    <button type="submit" class="btn btn--sm btn--round">@lang('corals-marketplace-marty::labels.template.product_single.create_comment')</button>
                </form>
            </div>
        </div>
    </div>
@endif