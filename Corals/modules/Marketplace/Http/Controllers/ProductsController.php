<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Marketplace\Classes\Marketplace;
use Corals\Modules\Marketplace\DataTables\ProductsDataTable;
use Corals\Modules\Marketplace\Http\Requests\ProductRequest;
use Corals\Modules\Marketplace\Jobs\CopyProductJob;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class ProductsController extends BaseController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->resource_url = config('marketplace.models.product.resource_url');
        $this->title = 'Marketplace::module.product.title';
        $this->title_singular = trans('Marketplace::module.product.title_singular');

        parent::__construct();
    }

    /**
     * @param ProductRequest $request
     * @param ProductsDataTable $dataTable
     * @return mixed
     */
    public function index(ProductRequest $request, ProductsDataTable $dataTable)
    {
        return $dataTable->render('Marketplace::products.index');
    }

    /**
     * @param ProductRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ProductRequest $request)
    {
        $product = new Product();
        $sku = new SKU();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::products.create_edit')->with(compact('product', 'sku'));
    }

    /**
     * @param ProductRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function store(ProductRequest $request)
    {
        $redirectURL = $this->resource_url;
        try {
            $product = $this->productService->store($request, Product::class);

            if ($product->type != 'simple') {
                $redirectURL = url('marketplace/products/' . $product->hashed_id . '/sku/bulk-generate');
            }

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Product::class, 'store');
        }

        return redirectTo($redirectURL);
    }

    /**
     * @param Request $request
     * @param $hashed_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile(Request $request, $hashed_id)
    {
        if (!user()->hasPermissionTo('Marketplace::product.update')) {
            abort(403);
        }

        $id = hashids_decode($hashed_id);

        $media = Media::findOrfail($id);

        return response()->download(storage_path($media->getUrl()));
    }

    /**
     * @param BulkRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(BulkRequest $request)
    {
        $action = $request->input('action');

        if ($action === 'clone_products') {
            $request->validate(['store_id' => 'required']);
        }

        try {
            $selection = json_decode($request->input('selection'), true);

            switch ($action) {
                case 'clone_products':

                    $ids = array_map(function ($hashedId) {
                        return hashids_decode($hashedId);
                    }, $selection);

                    $requiredParameters = $request->only('store_id', 'copy_with_media');

                    Product::query()
                        ->whereIn('id', $ids)
                        ->where('store_id', '<>', $request->get('store_id'))
                        ->get()
                        ->each(function (Product $product) use ($requiredParameters) {
                            CopyProductJob::dispatch($product, $requiredParameters);
                        });

                    $message = [
                        'level' => 'success',
                        'message' => trans('Marketplace::messages.product.product_successfully_cloned')
                    ];
                    break;
                case
                'delete':
                    foreach ($selection as $selection_id) {
                        $product = Product::findByHash($selection_id);
                        $product_request = new ProductRequest;
                        $product_request->setMethod('DELETE');
                        $this->destroy($product_request, $product);
                    }
                    $message = [
                        'level' => 'success',
                        'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
                    ];
                    break;
                case 'active' :
                    foreach ($selection as $selection_id) {
                        $product = Product::findByHash($selection_id);
                        if (user()->can('Marketplace::product.update')) {
                            $product->update([
                                'status' => 'active'
                            ]);
                            $product->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Marketplace::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Marketplace::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;

                case 'inActive' :
                    foreach ($selection as $selection_id) {
                        $product = Product::findByHash($selection_id);
                        if (user()->can('Marketplace::product.update')) {
                            $product->update([
                                'status' => 'inactive'
                            ]);
                            $product->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Marketplace::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Marketplace::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;

                case 'deleted' :
                    foreach ($selection as $selection_id) {
                        $product = Product::findByHash($selection_id);
                        if (user()->can('Marketplace::product.update')) {
                            $product->update([
                                'status' => 'deleted'
                            ]);
                            $product->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Marketplace::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Marketplace::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;
            }
        } catch
        (\Exception $exception) {
            log_exception($exception, Product::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }


    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ProductRequest $request, Product $product)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => Str::limit($product->name, 50)])
        ]);

        $this->setViewSharedData(['showModel' => $product]);

        return view('Marketplace::products.show')->with(compact('product'));
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ProductRequest $request, Product $product)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => Str::limit($product->name, 50)])
        ]);
        $sku = $product->sku->first();
        if (!$sku) {
            $sku = new SKU();
        }
        return view('Marketplace::products.create_edit')->with(compact('product', 'sku'));
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $this->productService->update($request, $product);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Product::class, 'update');
        }

        return redirectTo($this->resource_url);
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

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            log_exception($exception, Product::class, 'destroy');
        }

        return response()->json($message);
    }

    /**
     * @param Product $product
     * @param null $gateway
     * @throws \Exception
     */
    protected function createUpdateGatewayProductSend(Product $product, $gateway = null)
    {
        if ($gateway) {
            $gateways = [$gateway];
        } else {
            $gateways = \Payments::getAvailableGateways();
        }

        $exceptionMessage = '';

        foreach ($gateways as $gateway => $gateway_title) {
            try {
                $Marketplace = new Marketplace($gateway);


                if (!$Marketplace->gateway->getConfig('manage_remote_product')) {
                    continue;
                }
                if ($Marketplace->gateway->getGatewayIntegrationId($product)) {
                    $Marketplace->updateProduct($product);
                } else {
                    $Marketplace->createProduct($product);
                }
            } catch (\Exception $exception) {
                $exceptionMessage .= $exception->getMessage();
            }
        }
        if (!empty($exceptionMessage)) {
            throw new \Exception($exceptionMessage);
        }
    }


    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function createGatewayProduct(Request $request, Product $product)
    {
        $gateway = $request->get('gateway');
        user()->can('Marketplace::product.create', Product::class);

        try {
            $this->createUpdateGatewayProductSend($product, $gateway);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.created', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Product::class, 'createGatewayProduct');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function renderPricePerQuantitySectionRow(Request $request)
    {
        $index = $request->get('index');
        $pricePerQuantity = [];
        $deletable = true;
        return view('Marketplace::products.partials.price_per_quantity_row')->with(compact('index', 'pricePerQuantity', 'deletable'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function renderOfferSectionRow(Request $request)
    {
        $index = $request->get('index');
        $offer = [];
        $deletable = true;
        return view('Marketplace::products.partials.offer_row')->with(compact('index', 'offer', 'deletable'));
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function copyProductModal(Request $request, Product $product)
    {
        return view('Marketplace::products.partials.clone_product_modal')
            ->with(compact('product'));
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function copyProduct(Request $request, Product $product)
    {
        $request->validate(['store_id' => 'required']);

        try {

            CopyProductJob::dispatch($product, $request->all());

            $message = [
                'level' => 'success',
                'message' => trans('Marketplace::messages.product.product_successfully_cloned')
            ];

        } catch (\Exception $exception) {
            log_exception($exception, Product::class, 'copyProduct');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            $code = 400;
        }

        return response()->json($message, $code ?? 200);

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getBulkCopyProductsModal()
    {
        return view('Marketplace::products.partials.clone_product_modal')
            ->with([
                'bulk' => true
            ]);
    }
}
