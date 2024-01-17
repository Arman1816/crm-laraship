<?php

namespace Corals\Modules\CMS\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\CMS\DataTables\PostsDataTable;
use Corals\Modules\CMS\Http\Requests\PostRequest;
use Corals\Modules\CMS\Models\Post;
use Corals\Modules\CMS\Services\PostService;
use Corals\Modules\CMS\Transformers\API\PostPresenter;

class PostsController extends APIBaseController
{
    protected $postService;

    /**
     * PostsController constructor.
     * @param PostService $postService
     * @throws \Exception
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;

        $this->postService->setPresenter(new PostPresenter());

        parent::__construct();
    }

    /**
     * @param PostRequest $request
     * @param PostsDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(PostRequest $request, PostsDataTable $dataTable)
    {
        $posts = $dataTable->query(new Post());

        return $this->postService->index($posts, $dataTable);
    }

    /**
     * @param PostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostRequest $request)
    {
        try {
            $post = $this->postService->store($request, Post::class, ['author_id' => user()->id]);

            return apiResponse($this->postService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $post->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PostRequest $request, Post $post)
    {
        try {
            return apiResponse($this->postService->getModelDetails($post));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PostRequest $request, Post $post)
    {
        try {
            $this->postService->update($request, $post);

            return apiResponse($this->postService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $post->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PostRequest $request, Post $post)
    {
        try {
            $this->postService->destroy($request, $post);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $post->title]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
