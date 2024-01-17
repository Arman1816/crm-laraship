<?php

namespace Corals\Modules\CMS\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\CMS\DataTables\PagesDataTable;
use Corals\Modules\CMS\Http\Requests\PageRequest;
use Corals\Modules\CMS\Models\Page;
use Corals\Modules\CMS\Services\PageService;
use Corals\Modules\CMS\Transformers\API\PagePresenter;

class PagesController extends APIBaseController
{
    protected $pageService;

    /**
     * PagesController constructor.
     * @param PageService $pageService
     * @throws \Exception
     */
    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;

        $this->pageService->setPresenter(new PagePresenter());

        parent::__construct();
    }

    /**
     * @param PageRequest $request
     * @param PagesDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(PageRequest $request, PagesDataTable $dataTable)
    {
        $pages = $dataTable->query(new Page());

        return $this->pageService->index($pages, $dataTable);
    }

    /**
     * @param PageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PageRequest $request)
    {
        try {
            $page = $this->pageService->store($request, Page::class, ['author_id' => user()->id]);

            return apiResponse($this->pageService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $page->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PageRequest $request, Page $page)
    {
        try {
            return apiResponse($this->pageService->getModelDetails($page));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PageRequest $request, Page $page)
    {
        try {
            $this->pageService->update($request, $page);

            return apiResponse($this->pageService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $page->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PageRequest $request, Page $page)
    {
        try {
            $this->pageService->destroy($request, $page);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $page->title]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
