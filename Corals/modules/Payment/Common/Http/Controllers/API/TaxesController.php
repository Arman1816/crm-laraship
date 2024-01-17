<?php

namespace Corals\Modules\Payment\Common\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Payment\Common\Services\TaxService;
use Corals\Modules\Payment\Common\Transformers\API\TaxPresenter;
use Corals\Modules\Payment\Common\DataTables\TaxesDataTable;
use Corals\Modules\Payment\Common\Http\Requests\TaxRequest;
use Corals\Modules\Payment\Common\Models\Tax;
use Corals\Modules\Payment\Common\Models\TaxClass;

class TaxesController extends APIBaseController
{
    protected $taxService;

    /**
     * TaxsController constructor.
     * @param TaxService $taxService
     * @throws \Exception
     */
    public function __construct(TaxService $taxService)
    {
        $this->taxService = $taxService;
        $this->taxService->setPresenter(new TaxPresenter());

        parent::__construct();
    }

    /**
     * @param TaxRequest $request
     * @param TaxClass $tax_class
     * @param TaxesDataTable $dataTable
     * @return mixed
     */
    public function index(TaxRequest $request, TaxClass $tax_class, TaxesDataTable $dataTable)
    {
        $taxes = $dataTable->query(new Tax());

        return $this->taxService->index($taxes, $dataTable);
    }

    /**
     * @param TaxRequest $request
     * @param TaxClass $tax_class
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaxRequest $request, TaxClass $tax_class)
    {
        try {
            $this->taxService->store($request, Tax::class, ['tax_class_id' => $tax_class->id]);

            return apiResponse($this->taxService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $tax->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param TaxRequest $request
     * @param TaxClass $tax_class
     * @param Tax $tax
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TaxRequest $request, TaxClass $tax_class, Tax $tax)
    {
        try {
            $this->taxService->update($request, $tax);

            return apiResponse($this->taxService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $tax->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param TaxRequest $request
     * @param TaxClass $tax_class
     * @param Tax $tax
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TaxRequest $request, TaxClass $tax_class, Tax $tax)
    {
        try {
            $this->taxService->destroy($request, $tax);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $tax->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
