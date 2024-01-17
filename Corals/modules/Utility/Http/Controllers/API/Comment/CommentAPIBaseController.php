<?php

namespace Corals\Modules\Utility\Http\Controllers\API\Comment;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Utility\Http\Requests\Comment\CommentRequest;
use Corals\Modules\Utility\Models\Comment\Comment;
use Corals\Modules\Utility\Services\Comment\CommentService;
use Corals\Modules\Utility\Traits\Comment\CommentCommon;

class CommentAPIBaseController extends APIBaseController
{
    use CommentCommon;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;

        $this->setCommonVariables();

        parent::__construct();
    }

    /**
     * @param CommentRequest $request
     * @param $commentable_hashed_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createComment(CommentRequest $request, $commentable_hashed_id)
    {
        try {
            $this->commentService->createComment($request, $this->commentableClass, $commentable_hashed_id);

            return apiResponse([], trans($this->successMessage));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function replyComment(CommentRequest $request, Comment $comment)
    {
        try {
            $this->commentService->replyComment($request, $comment);

            return apiResponse([], trans($this->successMessage));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
