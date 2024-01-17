<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\DataTables\TagsDataTable;
use Corals\Modules\Marketplace\Http\Requests\TagRequest;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\Modules\Marketplace\Services\TagService;
use Corals\Modules\Marketplace\Transformers\API\TagPresenter;

class TagsController extends APIBaseController
{
    protected $tagService;

    /**
     * TagsController constructor.
     * @param TagService $tagService
     * @throws \Exception
     */
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
     * @throws \Exception
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
            return apiResponse($this->tagService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $tag->code]));
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

            return apiResponse($this->tagService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $tag->code]));
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

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $tag->code]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
