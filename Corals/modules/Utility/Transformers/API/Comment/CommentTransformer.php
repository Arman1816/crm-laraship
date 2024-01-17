<?php

namespace Corals\Modules\Utility\Transformers\API\Comment;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Utility\Models\Comment\Comment;

class CommentTransformer extends APIBaseTransformer
{
    /**
     * @param Comment $comment
     * @return array
     * @throws \Throwable
     */
    public function transform(Comment $comment)
    {
        $commentable_title = optional($comment->commentable)->getIdentifier();
        $comment_author = optional($comment->comment_author);

        $transformedArray = [
            'id' => $comment->id,
            'body' => $comment->body,
            'commentable_id' => $comment->commentable_id,
            'commentable' => $commentable_title,
            'author_id' => $comment->comment_author_id,
            'author' => $comment_author,
            'comments' => (new CommentPresenter())->present($comment->comments)['data'],
            'created_at' => format_date($comment->created_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
