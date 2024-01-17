<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\DataTables\ProductsDataTable;
use Corals\Modules\Marketplace\Http\Requests\ProductRequest;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Services\ProductService;
use Corals\Modules\Marketplace\Transformers\API\ProductPresenter;

class ProductsController extends APIBaseController
{
    protected $productService;

    /**
     * ProductsController constructor.
     * @param ProductService $productService
     * @throws \Exception
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->productService->setPresenter(new ProductPresenter());

        parent::__construct();
    }

    /**
     * @param ProductRequest $request
     * @param ProductsDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(ProductRequest $request, ProductsDataTable $dataTable)
    {
        $products = $dataTable->query(new Product());

        return $this->productService->index($products, $dataTable);
    }

    /**
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = $this->productService->store($request, Product::class);
            return apiResponse($this->productService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $product->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $this->productService->update($request, $product);

            return apiResponse($this->productService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $product->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ProductRequest $request, Product $product)
    {
        try {
            return apiResponse($this->productService->getModelDetails($product));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductRequest $request, Product $product)
    {
        try {
            $this->productService->destroy($request, $product);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $product->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
