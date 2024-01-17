<?php

namespace Corals\Modules\Utility\Http\Controllers\API\Tag;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Utility\DataTables\Tag\TagsDataTable;
use Corals\Modules\Utility\Http\Requests\Tag\TagRequest;
use Corals\Modules\Utility\Models\Tag\Tag;
use Corals\Modules\Utility\Services\Tag\TagService;
use Corals\Modules\Utility\Transformers\API\Tag\TagPresenter;

class TagsController extends APIBaseController
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
        $this->tagService->setPresenter(new TagPresenter());

        parent::__construct();
    }

    /**
     * @param TagRequest $request
     * @param TagsDataTable $dataTable
     * @return mixed
     */
    public function index(TagRequest $request, TagsDataTable $dataTable)
    {
        $tags = $dataTable->query(new Tag());

        return $this->tagService->index($tags, $dataTable);
    }

    /**
     * @param TagRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TagRequest $request)
    {
        try {
            $tag = $this->tagService->store($request, Tag::class);
            return apiResponse($this->tagService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $tag->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param TagRequest $request
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TagRequest $request, Tag $tag)
    {
        try {
            return apiResponse($this->tagService->getModelDetails($tag));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param TagRequest $request
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TagRequest $request, Tag $tag)
    {
        try {
            $this->tagService->update($request, $tag);

            return apiResponse($this->tagService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $tag->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param TagRequest $request
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TagRequest $request, Tag $tag)
    {
        try {
            $this->tagService->destroy($request, $tag);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $tag->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
