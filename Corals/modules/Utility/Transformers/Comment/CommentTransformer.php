<?php

namespace Corals\Modules\Utility\Transformers\Comment;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Utility\Models\Comment\Comment;
use Illuminate\Support\Str;

class CommentTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('utility.models.comment.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Comment $comment
     * @return array
     * @throws \Throwable
     */
    public function transform(Comment $comment)
    {
        $commentable_title = optional($comment->commentable)->getIdentifier();

        $comment_author = optional($comment->comment_author);

        $objectShowURL = $comment->commentable->getShowURL();

        $transformedArray = [
            'id' => $comment->id,
            'checkbox' => $this->generateCheckboxElement($comment),
            'body' => $comment->body ? ((strlen($comment->body) <= 70) ? $comment->body : Str::limit($comment->body,
                70,
                ' ' . generatePopover($comment->body))) : '-',
            'commentable_id' => (strlen($commentable_title) <= 50) ? $commentable_title : Str::limit($commentable_title,
                50,
                ' ' . generatePopover($comment->commentable->title)),
            'commentable_type' => $objectShowURL ? '<a href="' . $objectShowURL . '" target="_blank">' . basename($comment->commentable_type) . '</a>' : basename($comment->commentable_type),
            'author_id' => $comment_author->name ? "<a href='" . url('users/' . $comment_author->hashed_id) . "'> {$comment_author->name}</a>" : "-",
            'status' => formatStatusAsLabels($comment->status ?? ($comment->status ?? 'N/A'),
                $comment->getConfig('status_options')[$comment->status] ?? []),
            'created_at' => format_date_time($comment->created_at),
            'action' => $this->actions($comment)
        ];

        return parent::transformResponse($transformedArray);
    }
}
