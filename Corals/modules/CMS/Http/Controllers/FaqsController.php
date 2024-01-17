<?php

namespace Corals\Modules\CMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\CMS\DataTables\FaqsDataTable;
use Corals\Modules\CMS\Http\Requests\FaqRequest;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\CMS\Models\Faq;
use Corals\Modules\CMS\Services\FaqService;

class FaqsController extends BaseController
{
    protected $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;

        $this->resource_url = config('cms.models.faq.resource_url');
        $this->title = 'CMS::module.faq.title';
        $this->title_singular = 'CMS::module.faq.title_singular';

        parent::__construct();
    }

    /**
     * @param FaqRequest $request
     * @param FaqsDataTable $dataTable
     * @return mixed
     */
    public function index(FaqRequest $request, FaqsDataTable $dataTable)
    {
        return $dataTable->render('CMS::faqs.index');
    }

    /**
     * @param FaqRequest $request
     * @return $this
     */
    public function create(FaqRequest $request)
    {
        $faq = new Faq();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('CMS::faqs.create_edit')->with(compact('faq'));
    }

    /**
     * @param FaqRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(FaqRequest $request)
    {
        try {
            $faqs = $this->faqService->store($request, Faq::class, ['author_id' => user()->id]);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Faq::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param FaqRequest $request
     * @param Faq $faq
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show(FaqRequest $request, Faq $faq)
    {
        return redirect('admin-preview/' . $faq->slug);
    }

    /**
     * @param FaqRequest $request
     * @param Faq $faq
     * @return $this
     */
    public function edit(FaqRequest $request, Faq $faq)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $faq->title])]);

        return view('CMS::faqs.create_edit')->with(compact('faq'));
    }

    /**
     * @param FaqRequest $request
     * @param Faq $faq
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        try {
            $faqs = $this->faqService->update($request, $faq);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Faq::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param FaqRequest $request
     * @param Faq $faq
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
                        $faq = Faq::findByHash($selection_id);
                        $faq_request = new FaqRequest();
                        $faq_request->setMethod('DELETE');
                        $this->destroy($faq_request, $faq);
                    }
                    $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
                    break;

                case 'published' :
                    foreach ($selection as $selection_id) {
                        $faq = Faq::findByHash($selection_id);
                        if (user()->can('CMS::faq.update')) {
                            $faq->update([
                                'published' => true
                            ]);
                            $faq->save();
                            $message = ['level' => 'success', 'message' => trans('CMS::messages.update_published', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('CMS::messages.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;

                case 'draft' :
                    foreach ($selection as $selection_id) {
                        $faq = Faq::findByHash($selection_id);
                        if (user()->can('CMS::faq.update')) {
                            $faq->update([
                                'published' => false
                            ]);
                            $faq->save();
                            $message = ['level' => 'success', 'message' => trans('CMS::messages.update_published', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('CMS::messages.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;
            }


        } catch (\Exception $exception) {
            log_exception($exception, Faq::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function destroy(FaqRequest $request, Faq $faq)
    {
        try {
            $this->faqService->destroy($request, $faq);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $faq->title])];
        } catch (\Exception $exception) {
            log_exception($exception, Faq::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
