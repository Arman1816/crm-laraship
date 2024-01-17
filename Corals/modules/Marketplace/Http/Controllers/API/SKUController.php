<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\DataTables\SKUDataTable;
use Corals\Modules\Marketplace\Http\Requests\SKURequest;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Services\SKUService;
use Corals\Modules\Marketplace\Transformers\API\SKUPresenter;

class SKUController extends APIBaseController
{
    protected $SKUService;

    /**
     * ProductsController constructor.
     * @param SKUService $SKUService
     * @throws \Exception
     */
    public function __construct(SKUService $SKUService)
    {
        $this->SKUService = $SKUService;
        $this->SKUService->setPresenter(new SKUPresenter());

        parent::__construct();
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @param SKUDataTable $dataTable
     * @return mixed
     */
    public function index(SKURequest $request, Product $product, SKUDataTable $dataTable)
    {
        $skuList = $dataTable->query(new SKU());

        return $this->SKUService->index($skuList, $dataTable);
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SKURequest $request, Product $product)
    {
        try {
            $sku = $this->SKUService->store($request, SKU::class, ['product_id' => $product->id]);

            return apiResponse($this->SKUService->getModelDetails(),
                trans('Corals::messages.success.created', ['item' => $sku->code]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SKURequest $request, Product $product, SKU $sku)
    {
        try {
            $this->SKUService->update($request, $sku);

            return apiResponse($this->SKUService->getModelDetails(),
                trans('Corals::messages.success.updated', ['item' => $sku->code]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SKURequest $request, Product $product, SKU $sku)
    {
        try {
            return apiResponse($this->SKUService->getModelDetails($sku));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SKURequest $request, Product $product, SKU $sku)
    {
        try {
            $code = $sku->code;
            $this->SKUService->destroy($request, $sku);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $code]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
