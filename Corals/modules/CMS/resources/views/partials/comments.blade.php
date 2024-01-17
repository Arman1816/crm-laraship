@section('css')
    <style>

        .hide-me[aria-expanded="true"] {display: none;}
        .comment {
            display: block;
            position: relative;
            margin-bottom: 20px;
            padding-left: 66px;
        }

        .comment .comment-author-ava {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 50px;
            border-radius: 50%;
            overflow: hidden;
        }

        .comment .comment-author-ava > img {
            display: block;
            width: 100%;
        }

        .comment .comment-body {
            position: relative;
            padding: 10px;
            border: 1px solid #e1e7ec;
            border-radius: 7px;
            background-color: #ffffff;
        }

        .comment .comment-body::after, .comment .comment-body::before {
            position: absolute;
            top: 12px;
            right: 100%;
            width: 0;
            height: 0;
            border: solid transparent;
            content: '';
            pointer-events: none;
        }

        .comment .comment-body::after {
            border-width: 9px;
            border-color: transparent;
            border-right-color: #ffffff;
        }

        .comment .comment-body::before {
            margin-top: -1px;
            border-width: 10px;
            border-color: transparent;
            border-right-color: #e1e7ec;
        }

        .comment .comment-title {
            margin-bottom: 8px;
            color: #606975;
            font-size: 14px;
            font-weight: 500;
        }

        .comment .comment-text {
            margin-bottom: 12px;
        }

        .comment .comment-footer {
            display: table;
            width: 100%;
        }

        .comment .comment-footer > .column {
            display: table-cell;
            vertical-align: middle;
        }

        .comment .comment-footer > .column:last-child {
            text-align: right;
        }

        .comment .comment-meta {
            color: #9da9b9;
            font-size: 13px;
        }

        .comment .reply-link {
            transition: color .3s;
            color: #606975;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: .07em;
            text-transform: uppercase;
            text-decoration: none;
        }

        .comment .reply-link > i {
            display: inline-block;
            margin-top: -3px;
            margin-right: 4px;
            vertical-align: middle;
        }

        .comment .reply-link:hover {
            color: #0da9ef;
        }

        .comment.comment-reply {
            margin-top: 30px;
            margin-bottom: 0;
        }

        @media (max-width: 576px) {
            .comment {
                padding-left: 0;
            }

            .comment .comment-author-ava {
                display: none;
            }

            .comment .comment-body {
                padding: 15px;
            }

            .comment .comment-body::before, .comment .comment-body::after {
                display: none;
            }
        }
    </style>
@endsection
<div class="tab-pane show" id="comments" role="tabpanel">
    @if(isset($comments) && count($comments))
        <h4 style="margin:25px 0;">Comments</h4>
        @foreach($comments as $comment)
        <!-- COMMENT -->
            <div class="comment">
                <!-- USER AVATAR -->

            @isset($comment->comment_author)
                    <div class="comment-author-ava">
                        <img src="{{ optional($comment->comment_author)->picture_thumb }}"
                             alt="Review author">
                    </div>
                @endisset
            <!-- /USER AVATAR -->
                <div class="comment-body">
                    <p class="comment-text">
                        {!! nl2br($comment->body) !!}
                    </p>
                    <div class="comment-footer">
                        <div class="comment-meta">
                            {{  $comment->author ? optional($comment->author)->full_name : $comment->getProperty('author_name')  }}
                            - {{ $comment->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @if(count($comment->comments))
                    @foreach($comment->comments as $reply)
                        <div class="comment comment-reply">
                        @isset($comment->comment_author)
                            <!-- USER AVATAR -->
                                <div class="comment-author-ava">
                                    <img src="{{ optional($reply->author)->picture_thumb }}" alt="Reply author">
                                </div>
                            @endisset
                            <div class="comment-body">
                                <p class="comment-text">
                                    {!! nl2br($reply->body) !!}
                                </p>
                                <div class="comment-footer">
                                    <div class="comment-meta">
                                        {{ $reply->author ? optional($reply->author)->full_name :  $comment->getProperty('author_name') }}
                                        - {{ $comment->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if((user() && user()->can('Utility::comment.reply'))  || \Settings::get('cms_comments_allow_guest') )
                    <div class="comment comment-reply">
                        @if(user())
                            <div class="comment-author-ava">
                                <img src="{{ user()->picture_thumb }}" alt="">
                            </div>
                        @else
                        @endif
                        <a class="btn btn-primary hide-me m-0" data-toggle="collapse" href="#replyForm" role="button"
                           aria-expanded="false" aria-controls="replyForm">
                            @lang('CMS::labels.template.add_reply')
                            <i class="fa fa-reply" aria-hidden="true"></i>
                        </a>
                        <div class="collapse" id="replyForm">
                            <form class="custom-form ajax-form comment-reply-form"
                                  action="{{url('cms/'.$comment->hashed_id.'/create-reply' )}}"
                                  method="POST"
                                  data-page_action="site_reload">
                                <div class="form-group required-field">
                                    <input name="properties[author_name]" class="form-control custom-radius"
                                           placeholder="@lang('CMS::labels.template.your_name')"/>
                                </div>
                                <div class="form-group required-field">

                                    <input name="properties[author_email]" class="form-control custom-radius"
                                           placeholder="@lang('CMS::labels.template.your_email')"/>
                                </div>
                                <div class="form-group required-field">
                                <textarea name="body" class="form-control custom-radius" cols="10" rows="2"
                                          style="height: 80px"
                                          placeholder="@lang('CMS::labels.template.add_reply_text')"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="margin: 0;padding:0 15px;">
                                    @lang('CMS::labels.template.add_reply')
                                    <i class="fa fa-reply" aria-hidden="true"></i>
                                </button>
                                <a class="btn btn-secondary" data-toggle="collapse" href="#replyForm" role="button"
                                   aria-expanded="false" aria-controls="replyForm">
                                    @lang('CMS::labels.template.cancel')
                                </a>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
        <hr class="line-separator">
    @endif
    @if((user() && user()->can('Utility::comment.create')  ) || \Settings::get('cms_comments_allow_guest')  )
        <h4>Leave a Comment</h4>
        <div class="comment comment-reply">
            @if(user())
                <div class="comment-author-ava">
                    <img src="{{ user()->picture_thumb }}" alt="">
                </div>
            @endif
            <form class="custom-form ajax-form comment-reply-form"
                  action="{{url('cms/'.$item->hashed_id.'/create-comment' )}}"
                  method="POST"
                  data-page_action="site_reload">
                <div class="form-group required-field">
                    @if(!user())
                        <div class="form-group required-field">
                            <input name="properties[author_name]" class="form-control custom-radius"
                                   placeholder="@lang('CMS::labels.template.your_name')"/>
                        </div>
                        <div class="form-group required-field">

                            <input name="properties[author_email]" class="form-control custom-radius"
                                   placeholder="@lang('CMS::labels.template.your_email')"/>
                        </div>
                    @endif
                    <textarea name="body" class="form-control custom-radius" cols="10" rows="2"
                              style="height: 80px"
                              placeholder="@lang('CMS::labels.template.add_comments')"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="margin: 0;padding:0 15px;">
                    @lang('CMS::labels.template.add_comment')
                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                </button>
            </form>
        </div>
    @endif
</div>
