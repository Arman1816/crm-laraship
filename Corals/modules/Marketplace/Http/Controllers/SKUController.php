<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Marketplace\Classes\Marketplace;
use Corals\Modules\Marketplace\DataTables\SKUDataTable;
use Corals\Modules\Marketplace\Http\Requests\SKURequest;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Services\SKUService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SKUController extends BaseController
{
    protected $SKUService;

    public function __construct(SKUService $SKUService)
    {
        $this->SKUService = $SKUService;

        $this->resource_url = route(
            config('marketplace.models.sku.resource_route'),
            ['product' => request()->route('product') ?: '_']
        );

        $this->title = 'Marketplace::module.sku.title';
        $this->title_singular = 'Marketplace::module.sku.title_singular';

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
        $this->setViewSharedData([
            'title' => trans('Marketplace::labels.sku.index_title',
                ['name' => Str::limit($product->name, 50), 'title' => $this->title])
        ]);

        return $dataTable->render('Marketplace::sku.index', compact('product'));
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(SKURequest $request, Product $product)
    {
        $sku = new SKU();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::sku.create_edit')->with(compact('sku', 'product'));
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function store(SKURequest $request, Product $product)
    {
        try {
            $sku = $this->SKUService->store($request, SKU::class, ['product_id' => $product->id]);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, SKU::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(SKURequest $request, Product $product, SKU $sku)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => Str::limit($product->name, 50)])
        ]);

        return view('Marketplace::sku.show')->with(compact('sku', 'product'));
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SKURequest $request, Product $product, SKU $sku)
    {
        $this->setViewSharedData(['title_singular' => "Update SKU"]);

        return view('Marketplace::sku.create_edit')->with(compact('sku', 'product'));
    }

    /**
     * @param SKURequest $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function update(SKURequest $request, Product $product, SKU $sku)
    {
        try {
            $this->SKUService->update($request, $sku);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, SKU::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param SKU $sku
     * @param bool $create
     * @param null $gateway
     * @throws \Exception
     */
    protected function createUpdateGatewaySKUSend(SKU $sku, $create = false, $gateway = null)
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


                if (!$Marketplace->gateway->getConfig('manage_remote_sku')) {
                    continue;
                }
                if ($Marketplace->gateway->getGatewayIntegrationId($sku)) {
                    $Marketplace->updateSKU($sku);
                } else {
                    $Marketplace->createSKU($sku);
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
     * @param SKURequest $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SKURequest $request, Product $product, SKU $sku)
    {
        try {
            $this->SKUService->destroy($request, $sku);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, SKU::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function createGatewaySKU(Request $request, Product $product, SKU $sku)
    {
        user()->can('Marketplace::product.create', Product::class);

        $gateway = $request->get('gateway');
        try {
            $this->createUpdateGatewaySKUSend($sku, true, $gateway);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.created', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, SKU::class, 'createGatewaySKU');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param BulkRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bulkAction(BulkRequest $request)
    {
        $this->validate($request, [
            'regular_price' => '',
            'inventory' => '',
            'inventory_value' => 'required_if:inventory,finite,bucket',
            'image' => 'nullable|image|max:' . maxUploadFileSize(),
        ]);

        try {
            $action = $request->input('action');

            $selections = json_decode($request->input('selection'), true);

            $successCount = 0;

            if ($action == 'delete') {
                foreach ($selections as $skuHashedId) {
                    $sku = SKU::findByHash($skuHashedId);
                    $this->SKUService->destroy($request, $sku);
                    $successCount++;
                }
            } elseif ($action == 'updateVariations') {
                $data = array_filter($request->only([
                    'regular_price',
                    'sale_price',
                    'status',
                    'inventory_value',
                    'inventory'
                ]));

                if (empty($data) && !$request->hasFile('image')) {
                    throw new \Exception(trans('Marketplace::exception.sku.nothing_to_update'));
                }

                foreach ($selections as $skuHashedId) {
                    $sku = SKU::findByHash($skuHashedId);

                    if (!empty($data)) {
                        $sku->update($data);
                    }

                    if ($request->hasFile('image')) {
                        $sku->clearMediaCollection($sku->mediaCollectionName);

                        // we use preservingOriginal prevent delete the image
                        //since we need to upload same image multiple time
                        //we will delete it at the the end using unlink()
                        $image = $sku->addMedia($request->file("image"))
                            ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                            ->preservingOriginal()
                            ->toMediaCollection($sku->mediaCollectionName);
                    }

                    $successCount++;
                }
                @unlink($request->file('image'));
            }
            if (!$successCount) {
                $message = ['level' => 'warning', 'message' => trans('Marketplace::messages.sku.no_sku_selected')];
            } else {
                $message = [
                    'level' => 'success',
                    'message' => trans('Marketplace::messages.sku.updated', ['count' => $successCount])
                ];
            }
        } catch (\Exception $exception) {
            log_exception($exception);
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message ?? ['level' => 'info', 'message' => "successfully updated!"]);
    }

    public function getBulkUpdateVariationsModal(Request $request)
    {
        abort_if(!$request->ajax(), 404);

        return view("Marketplace::sku.partials.bulk_update_variations_modal");
    }
}
