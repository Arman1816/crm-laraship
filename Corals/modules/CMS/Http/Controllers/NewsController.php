<?php

namespace Corals\Modules\CMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\CMS\DataTables\NewsDataTable;
use Corals\Modules\CMS\Http\Requests\NewsRequest;
use Corals\Modules\CMS\Models\News;
use Corals\Modules\CMS\Services\NewsService;

class NewsController extends BaseController
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;

        $this->resource_url = config('cms.models.news.resource_url');
        $this->title = 'CMS::module.news.title';
        $this->title_singular = 'CMS::module.news.title_singular';

        parent::__construct();
    }

    /**
     * @param NewsRequest $request
     * @param NewsDataTable $dataTable
     * @return mixed
     */
    public function index(NewsRequest $request, NewsDataTable $dataTable)
    {
        return $dataTable->render('CMS::news.index');
    }

    /**
     * @param NewsRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(NewsRequest $request)
    {
        $news = new News();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('CMS::news.create_edit')->with(compact('news'));
    }

    /**
     * @param NewsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(NewsRequest $request)
    {
        try {
            $news = $this->newsService->store($request, News::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, News::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param NewsRequest $request
     * @param News $news
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show(NewsRequest $request, News $news)
    {
        return redirect('admin-preview/' . $news->slug);
    }

    /**
     * @param NewsRequest $request
     * @param News $news
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(NewsRequest $request, News $news)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $news->title])]);

        return view('CMS::news.create_edit')->with(compact('news'));
    }

    /**
     * @param NewsRequest $request
     * @param News $news
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(NewsRequest $request, News $news)
    {
        try {
            $news = $this->newsService->update($request, $news);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, News::class, 'update');
        }

        return redirectTo($this->resource_url);
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
                        $news = News::findByHash($selection_id);
                        $news_request = new NewsRequest;
                        $news_request->setMethod('DELETE');
                        $this->destroy($news_request, $news);
                    }
                    $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
                    break;

                case 'published' :
                    foreach ($selection as $selection_id) {
                        $news = News::findByHash($selection_id);
                        if (user()->can('CMS::news.update')) {
                            $news->update([
                                'published' => true
                            ]);
                            $news->save();
                            $message = ['level' => 'success', 'message' => trans('CMS::messages.update_published', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('CMS::messages.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;

                case 'draft' :
                    foreach ($selection as $selection_id) {
                        $news = News::findByHash($selection_id);
                        if (user()->can('CMS::news.update')) {
                            $news->update([
                                'published' => false
                            ]);
                            $news->save();
                            $message = ['level' => 'success', 'message' => trans('CMS::messages.update_published', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('CMS::messages.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;
            }


        } catch (\Exception $exception) {
            log_exception($exception, News::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


    public function destroy(NewsRequest $request, News $news)
    {
        try {
            $this->newsService->destroy($request, $news);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, News::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
