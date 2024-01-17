<?php

namespace Corals\Modules\Utility\Notifications\Comment;

use Corals\User\Communication\Classes\CoralsBaseNotification;
use Illuminate\Database\Eloquent\Relations\Relation;

class CommentCreated extends CoralsBaseNotification
{
    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        $comment = $this->data['comment'];

        return $this->getNotifiableOwner($comment);
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $comment = $this->data['comment'];

        $author = $comment->author;

        $commentable = $comment->commentable;

        return [
            'commentable_identifier' => $commentable->getIdentifier(),
            'commentable_show_url' => $commentable->getShowURL(),
            'commentable_class' => class_basename($commentable),
            'comment_body' => $comment->body ?? '-',
            'comment_status' => $comment->present('status'),
            'author_name' => isset($author) ? $author->name  : $comment->getProperty('author_name') ,
            'author_email' => isset($author) ? $author->email : $comment->getProperty('author_email'),
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'commentable_identifier' => 'Commentable identifier',
            'commentable_show_url' => 'Commentable Show URL',
            'commentable_class' => 'Commentable class',
            'author_name' => 'Author name',
            'author_email' => 'Author email',
            'comment_body' => 'Body',
            'comment_status' => 'Status',
        ];
    }

    /**
     * @param $comment
     * @return array|\Illuminate\Database\Eloquent\Model|Relation|object|null
     */
    private function getNotifiableOwner($comment)
    {
        $commentable = $comment->commentable;

        if (method_exists($commentable, 'owner')) {

            $owner = $commentable->owner();

            if ($owner instanceof Relation) {
                $owner = $owner->first();
            }
        } else {
            $owner = $commentable->creator;
        }

        //if the owner and auth user are same, skip don't send notification
        //or if the comment is private, don't send!
        if ($owner->id == $commentable->author_id || $comment->is_private) {
            return [];
        }

        return $owner;
    }
}
