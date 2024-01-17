<?php

namespace Corals\Modules\CMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\CMS\DataTables\PagesDataTable;
use Corals\Modules\CMS\Http\Requests\PageRequest;
use Corals\Modules\CMS\Models\Page;
use Corals\Modules\CMS\Services\PageService;

class PagesController extends BaseController
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;

        $this->resource_url = config('cms.models.page.resource_url');
        $this->title = 'CMS::module.page.title';
        $this->title_singular = 'CMS::module.page.title_singular';

        parent::__construct();
    }

    /**
     * @param PageRequest $request
     * @param PagesDataTable $dataTable
     * @return mixed
     */
    public function index(PageRequest $request, PagesDataTable $dataTable)
    {
        return $dataTable->render('CMS::pages.index');
    }

    /**
     * @param PageRequest $request
     * @return $this
     */
    public function create(PageRequest $request)
    {
        $page = new Page();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('CMS::pages.create_edit')->with(compact('page'));
    }

    /**
     * @param PageRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PageRequest $request)
    {
        try {
            $page = $this->pageService->store($request, Page::class, ['author_id' => user()->id]);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Page::class, 'created');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return $this
     */
    public function show(PageRequest $request, Page $page)
    {
        return redirect('admin-preview/' . $page->slug);
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return $this
     */
    public function design(PageRequest $request, Page $page)
    {
        try {
            \Theme::set(\Settings::get('active_frontend_theme', config('themes.corals_frontend')));
            // Get the theme
            $theme = \Theme::find(\Theme::get());

            return view('CMS::designer.designer')->with(compact('page', 'theme'));
        } catch (\Exception $exception) {
            abort(404);
        }
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDesign(PageRequest $request, Page $page)
    {
        try {
            $page->content = $request->get('content');
            $page->save();

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.updated', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Page::class, 'saveDesign');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return $this
     */
    public function edit(PageRequest $request, Page $page)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $page->title])]);

        return view('CMS::pages.create_edit')->with(compact('page'));
    }

    /**
     * @param PageRequest $request
     * @param Page $page
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PageRequest $request, Page $page)
    {
        try {
            $page = $this->pageService->update($request, $page);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Page::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param PageRequest $request
     * @param Page $page
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
                        $page = Page::findByHash($selection_id);
                        $page_request = new PageRequest;
                        $page_request->setMethod('DELETE');
                        $this->destroy($page_request, $page);
                    }
                    $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
                    break;

                case 'published' :
                    foreach ($selection as $selection_id) {
                        $page = Page::findByHash($selection_id);
                        if (user()->can('CMS::page.update')) {
                            $page->update([
                                'published' => true
                            ]);
                            $page->save();
                            $message = ['level' => 'success', 'message' => trans('CMS::messages.update_published', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('CMS::messages.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;

                case 'draft' :
                    foreach ($selection as $selection_id) {
                        $page = Page::findByHash($selection_id);
                        if (user()->can('CMS::page.update')) {
                            $page->update([
                                'published' => false
                            ]);
                            $page->save();
                            $message = ['level' => 'success', 'message' => trans('CMS::messages.update_published', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('CMS::messages.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;
            }


        } catch (\Exception $exception) {
            log_exception($exception, Page::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function destroy(PageRequest $request, Page $page)
    {
        try {
            $this->pageService->destroy($request, $page);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Page::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
