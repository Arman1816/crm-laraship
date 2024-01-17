<?php

namespace Corals\Modules\Utility\Classes\Comment;

use Corals\Modules\Utility\Models\Comment\Comment as CommentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentManager
{

    protected $instance, $author;

    /**
     * RatingManager constructor.
     * @param $instance
     * @param $author
     */
    public function __construct($instance, $author)
    {
        $this->instance = $instance;
        $this->author = $author;
    }

    /**
     * @param $data
     * @return CommentModel|Model
     */
    public function createComment($data)
    {
        $data = array_merge([
            'commentable_id' => $this->instance->id,
            'commentable_type' => getModelMorphMap($this->instance),
        ], $data);
        if ($this->author) {
            $data = array_merge([
                'author_id' => $this->author->id,
                'author_type' => getModelMorphMap($this->author),
            ], $data);
        }
        $comment = CommentModel::create($data);


        event('notifications.comment.comment_created', [
            'comment' => $comment,
        ]);

        return $comment;
    }

    /**
     * @param CommentModel $comment
     * @return bool|null
     * @throws \Exception
     */
    public function deleteComment(CommentModel $comment)
    {
        return $comment->delete();
    }

    public static function getCommentableTypes()
    {
        return cache()->remember('getCommentableTypes', 1440, function () {
            $result = DB::table('utility_comments')
                ->distinct()
                ->select('commentable_type')
                ->get();

            $list = [];

            foreach ($result as $record) {
                if ($record->commentable_type) {
                    $list[$record->commentable_type] = basename($record->commentable_type);
                }
            }

            return $list;
        });
    }
}
