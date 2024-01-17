<?php

namespace Corals\Modules\CMS\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\CMS\DataTables\FaqsDataTable;
use Corals\Modules\CMS\Http\Requests\FaqRequest;
use Corals\Modules\CMS\Models\Faq;
use Corals\Modules\CMS\Services\FaqService;
use Corals\Modules\CMS\Transformers\API\FaqPresenter;

class FaqsController extends APIBaseController
{
    protected $faqService;

    /**
     * FaqsController constructor.
     * @param FaqService $faqService
     * @throws \Exception
     */
    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;

        $this->faqService->setPresenter(new FaqPresenter());

        parent::__construct();
    }

    /**
     * @param FaqRequest $request
     * @param FaqsDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(FaqRequest $request, FaqsDataTable $dataTable)
    {
        $faqs = $dataTable->query(new Faq());

        return $this->faqService->index($faqs, $dataTable);
    }

    /**
     * @param FaqRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FaqRequest $request)
    {
        try {
            $faq = $this->faqService->store($request, Faq::class, ['author_id' => user()->id]);

            return apiResponse($this->faqService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $faq->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param FaqRequest $request
     * @param Faq $faq
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FaqRequest $request, Faq $faq)
    {
        try {
            return apiResponse($this->faqService->getModelDetails($faq));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param FaqRequest $request
     * @param Faq $faq
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        try {
            $this->faqService->update($request, $faq);

            return apiResponse($this->faqService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $faq->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param FaqRequest $request
     * @param Faq $faq
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FaqRequest $request, Faq $faq)
    {
        try {
            $this->faqService->destroy($request, $faq);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $faq->title]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
