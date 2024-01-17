<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Marketplace\DataTables\TagsDataTable;
use Corals\Modules\Marketplace\Http\Requests\TagRequest;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\Modules\Marketplace\Services\TagService;

class TagsController extends BaseController
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;

        $this->resource_url = config('marketplace.models.tag.resource_url');
        $this->title = 'Marketplace::module.tag.title';
        $this->title_singular = 'Marketplace::module.tag.title_singular';

        parent::__construct();
    }

    /**
     * @param TagRequest $request
     * @param TagsDataTable $dataTable
     * @return mixed
     */
    public function index(TagRequest $request, TagsDataTable $dataTable)
    {
        return $dataTable->render('Marketplace::tags.index');
    }

    /**
     * @param TagRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(TagRequest $request)
    {
        $tag = new Tag();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('Marketplace::tags.create_edit')->with(compact('tag'));
    }

    /**
     * @param TagRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TagRequest $request)
    {
        try {
            $this->tagService->store($request, Tag::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Tag::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param TagRequest $request
     * @param Tag $tag
     * @return Tag
     */
    public function show(TagRequest $request, Tag $tag)
    {
        return $tag;
    }

    /**
     * @param TagRequest $request
     * @param Tag $tag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TagRequest $request, Tag $tag)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $tag->name])]);

        return view('Marketplace::tags.create_edit')->with(compact('tag'));
    }

    /**
     * @param TagRequest $request
     * @param Tag $tag
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(TagRequest $request, Tag $tag)
    {
        try {
            $this->tagService->update($request, $tag);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Tag::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param TagRequest $request
     * @param Tag $tag
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
                        $tag = Tag::findByHash($selection_id);
                        $tag_request = new TagRequest;
                        $tag_request->setMethod('DELETE');
                        $this->destroy($tag_request, $tag);
                    }
                    $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
                    break;
                case 'active' :
                    foreach ($selection as $selection_id) {
                        $tag = Tag::findByHash($selection_id);
                        if (user()->can('Marketplace::tag.update')) {
                            $tag->update([
                                'status' => 'active'
                            ]);
                            $tag->save();
                            $message = ['level' => 'success', 'message' => trans('Marketplace::attributes.update_status', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('Marketplace::attributes.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;

                case 'inActive' :
                    foreach ($selection as $selection_id) {
                        $tag = Tag::findByHash($selection_id);
                        if (user()->can('Marketplace::tag.update')) {
                            $tag->update([
                                'status' => 'inactive'
                            ]);
                            $tag->save();
                            $message = ['level' => 'success', 'message' => trans('Marketplace::attributes.update_status', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('Marketplace::attributes.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;
            }
        } catch (\Exception $exception) {
            log_exception($exception, Tag::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }


    public function destroy(TagRequest $request, Tag $tag)
    {
        try {
            $this->tagService->destroy($request, $tag);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Tag::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
