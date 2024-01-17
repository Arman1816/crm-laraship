<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Marketplace\DataTables\ShippingsDataTable;
use Corals\Modules\Marketplace\Excel\ShippingsImport;
use Corals\Modules\Marketplace\Http\Requests\ShippingRequest;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\Shipping;
use Corals\Modules\Marketplace\Models\Store;
use Corals\Modules\Marketplace\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ShippingsController extends BaseController
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;

        $this->resource_url = config('marketplace.models.shipping.resource_url');
        $this->title = 'Marketplace::module.shipping.title';
        $this->title_singular = 'Marketplace::module.shipping.title_singular';
        parent::__construct();
    }

    /**
     * @param ShippingRequest $request
     * @param ShippingsDataTable $dataTable
     * @return mixed
     */
    public function index(ShippingRequest $request, ShippingsDataTable $dataTable)
    {
        return $dataTable->render('Marketplace::shippings.index');
    }

    /**
     * @param ShippingRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ShippingRequest $request)
    {
        $shipping = new Shipping();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::shippings.create_edit')->with(compact('shipping'));
    }

    /**
     * @param ShippingRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function store(ShippingRequest $request)
    {
        try {
            $this->shippingService->store($request, Shipping::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Shipping::class, 'store');

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => trans('validation.message'),
                    'errors' => $exception->validator->getMessageBag()
                ], 422);
            }
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param ShippingRequest $request
     * @param Shipping $shipping
     * @return Shipping
     */
    public function show(ShippingRequest $request, Shipping $shipping)
    {
        return $shipping;
    }

    /**
     * @param ShippingRequest $request
     * @param Shipping $shipping
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ShippingRequest $request, Shipping $shipping)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::shippings.create_edit')->with(compact('shipping'));
    }

    /**
     * @param ShippingRequest $request
     * @param Shipping $shipping
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ShippingRequest $request, Shipping $shipping)
    {
        try {
            $this->shippingService->update($request, $shipping);

            flash(trans('Corals::messages.success.updated',
                ['item' => trans('Marketplace::module.shipping.index_title')]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Shipping::class, 'update');
        }

        return redirectTo($this->resource_url);
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

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted',
                    ['item' => trans('Marketplace::module.shipping.index_title')])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Shipping::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload()
    {
        if (!user()->hasPermissionTo('Marketplace::shipping.upload')) {
            abort(403);
        }

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.upload_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::shippings.upload');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function doUpload(Request $request)
    {
        if (!user()->hasPermissionTo('Marketplace::shipping.upload')) {
            abort(403);
        }

        $this->validate($request, [
            'shipping_import_file' => 'required|mimes:xlsx'
        ]);

        $store = null;

        if (!\Store::isStoreAdmin()) {
            $store = \Store::getVendorStore();
            if (!$store) {
                $validator = \Validator::make([], []); // Empty data and rules fields
                $validator->errors()->add('store_id', trans('Marketplace::exception.store.invalid_store'));
                return response()->json([
                    'message' => trans('validation.message'),
                    'errors' => $validator->getMessageBag()
                ], 422);
            }
        }

        try {
            if ($store) {
                Shipping::where('store_id', $store->id)->delete();
            } else {
                Shipping::whereNull('store_id')->delete();
            }

            Excel::import(new ShippingsImport($store), $request->file('shipping_import_file'));
        } catch (\Exception $exception) {
            log_exception($exception, Shipping::class, 'importShipping');
        }
        return redirectTo($this->resource_url);
    }

    /**
     * @param $action
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function importShippingReport($action)
    {
        if (!user()->hasPermissionTo('Marketplace::shipping.upload')) {
            abort(403);
        }

        switch ($action) {
            case 'download':
                $file = session('shipping-rules-report');

                if (\File::exists($file)) {
                    return response()->download($file);
                }

                flash(trans('Marketplace::exception.shipping.no_report_file'))->warning();

                return redirectTo($this->resource_url);
                break;
            case 'clear':
                @unlink(session('shipping-rules-report'));
                session()->forget('shipping-rules-report');
                return redirectTo($this->resource_url);
                break;
        }
    }

    public function getRatePriceLocationForm(ShippingRequest $request, $index)
    {
        $rate = [
            'name' => null,
            'one_item_price' => null,
            'country' => null,
            'shipping_method' => null,
            'shipping_provider' => null,
            'other_shipping_provider' => null,
            'additional_item_price' => null,
        ];

        $store = null;
        $product = null;

        if ($productHashedId = $request->get('product')) {
            $product = Product::findByHash($productHashedId);
        } elseif ($storeId = $request->get('storeId')) {
            $store = Store::find($storeId);
        } else {
            $store = \Store::getVendorStore();
        }

        return view('Marketplace::shippings.partials.flat_rate_prices_location')
            ->with(compact('index', 'rate', 'product', 'store'))
            ->render();
    }
}
