<?php

namespace Corals\Modules\Payment\Common\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Payment\Common\Services\TaxClassService;
use Corals\Modules\Payment\Common\Transformers\API\TaxClassPresenter;
use Corals\Modules\Payment\Common\DataTables\TaxClassesDataTable;
use Corals\Modules\Payment\Common\Http\Requests\TaxClassRequest;
use Corals\Modules\Payment\Common\Models\TaxClass;

class TaxClassesController extends APIBaseController
{
    protected $taxClassService;

    /**
     * TaxClassesController constructor.
     * @param TaxClassService $taxClassService
     * @throws \Exception
     */
    public function __construct(TaxClassService $taxClassService)
    {
        $this->taxClassService = $taxClassService;
        $this->taxClassService->setPresenter(new TaxClassPresenter());

        parent::__construct();
    }

    /**
     * @param TaxClassRequest $request
     * @param TaxClassesDataTable $dataTable
     * @return mixed
     */
    public function index(TaxClassRequest $request, TaxClassesDataTable $dataTable)
    {
        $taxClasses = $dataTable->query(new TaxClass());

        return $this->taxClassService->index($taxClasses, $dataTable);
    }

    /**
     * @param TaxClassRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaxClassRequest $request)
    {
        try {
            $this->taxClassService->store($request, TaxClass::class);

            return apiResponse($this->taxClassService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $taxClass->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param TaxClassRequest $request
     * @param TaxClass $tax_class
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TaxClassRequest $request, TaxClass $tax_class)
    {
        try {
            $this->taxClassService->update($request, $taxClass);

            return apiResponse($this->taxClassService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $taxClass->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param TaxClassRequest $request
     * @param TaxClass $taxClass
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TaxClassRequest $request, TaxClass $taxClass)
    {
        try {
            $this->taxClassService->destroy($request, $taxClass);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $taxClass->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
