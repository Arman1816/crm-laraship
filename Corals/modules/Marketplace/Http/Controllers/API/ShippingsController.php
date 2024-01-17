<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\DataTables\ShippingsDataTable;
use Corals\Modules\Marketplace\Http\Requests\ShippingRequest;
use Corals\Modules\Marketplace\Models\Shipping;
use Corals\Modules\Marketplace\Services\ShippingService;
use Corals\Modules\Marketplace\Transformers\API\ShippingPresenter;

class ShippingsController extends APIBaseController
{
    protected $shippingService;

    /**
     * ShippingsController constructor.
     * @param ShippingService $shippingService
     * @throws \Exception
     */
    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
        $this->shippingService->setPresenter(new ShippingPresenter());

        parent::__construct();
    }

    /**
     * @param ShippingRequest $request
     * @param ShippingsDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(ShippingRequest $request, ShippingsDataTable $dataTable)
    {
        $shippings = $dataTable->query(new Shipping());

        return $this->shippingService->index($shippings, $dataTable);
    }

    /**
     * @param ShippingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShippingRequest $request)
    {
        try {
            $shipping = $this->shippingService->store($request, Shipping::class);
            return apiResponse($this->shippingService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $shipping->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param ShippingRequest $request
     * @param Shipping $shipping
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ShippingRequest $request, Shipping $shipping)
    {
        try {
            $this->shippingService->update($request, $shipping);

            return apiResponse($this->shippingService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $shipping->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param ShippingRequest $request
     * @param Shipping $shipping
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ShippingRequest $request, Shipping $shipping)
    {
        try {
            $this->shippingService->destroy($request, $shipping);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $shipping->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
