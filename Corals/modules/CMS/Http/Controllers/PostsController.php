<?php

namespace Corals\Modules\CMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\CMS\DataTables\PostsDataTable;
use Corals\Modules\CMS\Http\Requests\PostRequest;
use Corals\Modules\CMS\Models\Post;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\CMS\Services\PostService;

class PostsController extends BaseController
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;

        $this->resource_url = config('cms.models.post.resource_url');
        $this->title = 'CMS::module.post.title';
        $this->title_singular = 'CMS::module.post.title_singular';

        parent::__construct();
    }

    /**
     * @param PostRequest $request
     * @param PostsDataTable $dataTable
     * @return mixed
     */
    public function index(PostRequest $request, PostsDataTable $dataTable)
    {
        return $dataTable->render('CMS::posts.index');
    }

    /**
     * @param PostRequest $request
     * @return $this
     */
    public function create(PostRequest $request)
    {
        $post = new Post();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('CMS::posts.create_edit')->with(compact('post'));
    }

    /**
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PostRequest $request)
    {
        try {
            $post = $this->postService->store($request, Post::class, ['author_id' => user()->id]);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Post::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return $this
     */
    public function show(PostRequest $request, Post $post)
    {
        return redirect('admin-preview/' . $post->slug);
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return $this
     */
    public function edit(PostRequest $request, Post $post)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $post->title])]);

        return view('CMS::posts.create_edit')->with(compact('post'));
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PostRequest $request, Post $post)
    {
        try {
            $post = $this->postService->update($request, $post);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Post::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param PostRequest $request
     * @param Post $post
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
                        $post = Post::findByHash($selection_id);
                        $post_request = new PostRequest;
                        $post_request->setMethod('DELETE');
                        $this->destroy($post_request, $post);
                    }
                    $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
                    break;

                case 'published' :
                    foreach ($selection as $selection_id) {
                        $post = Post::findByHash($selection_id);
                        if (user()->can('CMS::post.update')) {
                            $post->update([
                                'published' => true
                            ]);
                            $post->save();
                            $message = ['level' => 'success', 'message' => trans('CMS::messages.update_published', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('CMS::messages.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;

                case 'draft' :
                    foreach ($selection as $selection_id) {
                        $post = Post::findByHash($selection_id);
                        if (user()->can('CMS::post.update')) {
                            $post->update([
                                'published' => false
                            ]);
                            $post->save();
                            $message = ['level' => 'success', 'message' => trans('CMS::messages.update_published', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('CMS::messages.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;
            }


        } catch (\Exception $exception) {
            log_exception($exception, Post::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


    public function destroy(PostRequest $request, Post $post)
    {
        try {
            $this->postService->destroy($request, $post);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Post::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
