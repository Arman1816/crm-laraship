<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\DataTables\BrandsDataTable;
use Corals\Modules\Marketplace\Http\Requests\BrandRequest;
use Corals\Modules\Marketplace\Models\Brand;
use Corals\Modules\Marketplace\Services\BrandService;
use Corals\Modules\Marketplace\Transformers\API\BrandPresenter;

class BrandsController extends APIBaseController
{
    protected $brandService;

    /**
     * BrandsController constructor.
     * @param BrandService $brandService
     * @throws \Exception
     */
    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
        $this->brandService->setPresenter(new BrandPresenter());

        parent::__construct();
    }

    /**
     * @param BrandRequest $request
     * @param BrandsDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(BrandRequest $request, BrandsDataTable $dataTable)
    {
        $brands = $dataTable->query(new Brand());

        return $this->brandService->index($brands, $dataTable);
    }

    /**
     * @param BrandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BrandRequest $request)
    {
        try {
            $brand = $this->brandService->store($request, Brand::class);
            return apiResponse($this->brandService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $brand->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param BrandRequest $request
     * @param Brand $brand
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        try {
            $this->brandService->update($request, $brand);

            return apiResponse($this->brandService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $brand->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param BrandRequest $request
     * @param Brand $brand
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BrandRequest $request, Brand $brand)
    {
        try {
            $this->brandService->destroy($request, $brand);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $brand->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
