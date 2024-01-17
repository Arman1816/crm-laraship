<?php

namespace Corals\Modules\Utility\Http\Controllers\Comment;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Utility\DataTables\Comment\CommentsDataTable;
use Corals\Modules\Utility\Http\Requests\Comment\CommentRequest;
use Corals\Modules\Utility\Models\Comment\Comment;
use Corals\Modules\Utility\Services\Comment\CommentService;
use Corals\Modules\Utility\Traits\Comment\CommentCommon;

class CommentBaseController extends BaseController
{
    use CommentCommon;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;

        $this->setCommonVariables();

        $this->resource_url = config('utility.models.comment.resource_url');
        $this->corals_middleware_except = array_merge($this->corals_middleware_except, ['createComment']);
        $this->title = 'Utility::module.comment.title';
        $this->title_singular = 'Utility::module.comment.title_singular';

        parent::__construct();
    }

    /**
     * @param CommentRequest $request
     * @param CommentsDataTable $dataTable
     * @return mixed
     */
    public function index(CommentRequest $request, CommentsDataTable $dataTable)
    {
        $this->setViewSharedData(['hideCreate' => true]);
        return $dataTable->render('Utility::comment.index');
    }

    /**
     * @param CommentRequest $request
     * @param $commentable_hashed_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createComment(CommentRequest $request, $commentable_hashed_id)
    {
        return $this->doCreate($request, $commentable_hashed_id);
    }

    protected function doCreate($request, $commentable_hashed_id)
    {
        try {
            $data = $request->all();

            if ($this->requireApproval) {
                $data['status'] = 'pending';
            }else{
                $data['status'] = 'published';

            }


            $comment = $this->commentService->createComment($data, $this->commentableClass, $commentable_hashed_id,
                $this->author);

            if ($comment->status == 'pending') {
                $message = $this->successMessageWithPending;
            } else {
                $message = $this->successMessage;
            }

            $message = ['level' => 'success', 'message' => trans($message), 'comment_hashed_id' => $comment->hashed_id];
        } catch (\Exception $exception) {
            log_exception($exception, get_class($this), 'createComment');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        if ($request->ajax() || is_null($this->redirectUrl) || $request->wantsJson()) {
            return response()->json($message);
        } else {
            if ($message['level'] === 'success') {
                flash($message['message'])->success();
            } else {
                flash($message['message'])->error();
            }
            redirectTo($this->redirectUrl);
        }
    }

    public function replyComment(CommentRequest $request, Comment $comment)
    {
        try {
            $data = $request->all();

            $this->commentService->replyComment($data, $comment);

            $message = ['level' => 'success', 'message' => trans($this->successMessage)];
        } catch (\Exception $exception) {
            log_exception($exception, get_class($this), 'createComment');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        if ($request->ajax() || is_null($this->redirectUrl) || $request->wantsJson()) {
            return response()->json($message);
        } else {
            if ($message['level'] === 'success') {
                flash($message['message'])->success();
            } else {
                flash($message['message'])->error();
            }
            redirectTo($this->redirectUrl);
        }
    }

    /**
     * @param BulkRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(BulkRequest $request)
    {
        try {
            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);

            switch ($action) {
                case 'delete':
                    foreach ($selection as $selection_id) {
                        $comment = Comment::findByHash($selection_id);
                        $comment_request = new CommentRequest;
                        $comment_request->setMethod('DELETE');
                        $this->destroy($comment_request, $comment);
                    }
                    $message = [
                        'level' => 'success',
                        'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
                    ];
                    break;
                case 'pending':
                    $message = $this->updateStatus($selection, 'pending') ?? [];
                    break;
                case 'published':
                    $message = $this->updateStatus($selection, 'published') ?? [];
                    break;
                case 'trashed':
                    $message = $this->updateStatus($selection, 'trashed') ?? [];
                    break;
            }
        } catch (\Exception $exception) {
            log_exception($exception, Comment::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }

    public function destroy(CommentRequest $request, Comment $comment)
    {
        try {
            $comment->delete();

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Comment::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    protected function updateStatus($selection, $status)
    {
        $message = null;

        foreach ($selection as $selection_id) {
            $comment = Comment::findByHash($selection_id);

            if (user()->can('Utility::comment.set_status', [$comment, $status])) {
                $comment->update([
                    'status' => $status
                ]);

                $comment->save();

                $message = [
                    'level' => 'success',
                    'message' => trans('Utility::attributes.update_status',
                        ['item' => $this->title_singular])
                ];
            } else {
                $message = [
                    'level' => 'error',
                    'message' => trans('Utility::attributes.no_permission',
                        ['item' => $this->title_singular])
                ];
            }
        }

        return $message;
    }

    /**
     * @param Comment $comment
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Comment $comment, $status)
    {
        try {
            if ($comment->status != $status) {
                $notify = true;
            } else {
                $notify = false;
            }

            $comment->update([
                'status' => $status,
            ]);

            if ($notify) {
                event('notifications.comment.comment_toggle_status', [
                    'comment' => $comment,
                ]);
            }

            $message = ['level' => 'success', 'message' => trans('Utility::messages.comment.success.status_update')];
        } catch (\Exception $exception) {
            log_exception($exception, Comment::class, 'toggleStatus');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
