<?php

namespace Corals\Modules\Utility\Traits\Comment;


trait CommentCommon
{
    protected $commentService;
    protected $commentableClass = null;
    protected $author = null;
    protected $redirectUrl = null;
    protected $successMessage = 'Utility::messages.comment.success.add';
    protected $successMessageWithPending = 'Utility::messages.comment.success.add_with_pending';
    protected $requireApproval = false;

    protected function setCommonVariables()
    {
        $this->commentableClass = null;
        $this->redirectUrl = null;
    }
}
