<?php

namespace Corals\Modules\CMS\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\CMS\DataTables\NewsDataTable;
use Corals\Modules\CMS\Http\Requests\NewsRequest;
use Corals\Modules\CMS\Models\News;
use Corals\Modules\CMS\Services\NewsService;
use Corals\Modules\CMS\Transformers\API\NewsPresenter;

class NewsController extends APIBaseController
{
    protected $newsService;

    /**
     * NewsController constructor.
     * @param NewsService $newsService
     * @throws \Exception
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;

        $this->newsService->setPresenter(new NewsPresenter());

        parent::__construct();
    }

    /**
     * @param NewsRequest $request
     * @param NewsDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(NewsRequest $request, NewsDataTable $dataTable)
    {
        $news = $dataTable->query(new News());

        return $this->newsService->index($news, $dataTable);
    }

    /**
     * @param NewsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NewsRequest $request)
    {
        try {
            $news = $this->newsService->store($request, News::class, ['author_id' => user()->id]);

            return apiResponse($this->newsService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $news->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param NewsRequest $request
     * @param News $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(NewsRequest $request, News $news)
    {
        try {
            return apiResponse($this->newsService->getModelDetails($news));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param NewsRequest $request
     * @param News $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(NewsRequest $request, News $news)
    {
        try {
            $this->newsService->update($request, $news);

            return apiResponse($this->newsService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $news->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param NewsRequest $request
     * @param News $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(NewsRequest $request, News $news)
    {
        try {
            $this->newsService->destroy($request, $news);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $news->title]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
