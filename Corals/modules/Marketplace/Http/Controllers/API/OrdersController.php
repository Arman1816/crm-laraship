<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\DataTables\MyOrdersDataTable;
use Corals\Modules\Marketplace\DataTables\MyStoreOrdersDataTable;
use Corals\Modules\Marketplace\Facades\OrderManager;
use Corals\Modules\Marketplace\Facades\Shipping;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Services\OrderService;
use Corals\Modules\Marketplace\Traits\OrderCommon;
use Corals\Modules\Marketplace\Transformers\API\OrderItemPresenter;
use Corals\Modules\Marketplace\Transformers\API\OrderPresenter;
use Illuminate\Http\Request;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;

class OrdersController extends APIBaseController
{
    use OrderCommon;
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->orderService->setPresenter(new OrderPresenter());

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param MyOrdersDataTable $dataTable
     * @return mixed
     */
    public function myOrders(Request $request, MyOrdersDataTable $dataTable)
    {
        if (!user()->hasPermissionTo('Marketplace::my_orders.access')) {
            abort(403, 'Forbidden!!');
        }

        $orders = $dataTable->query(new Order());

        return $this->orderService->index($orders, $dataTable);
    }

    /**
     * @param Request $request
     * @param MyOrdersDataTable $dataTable
     * @return mixed
     */
    public function storeOrders(Request $request, MyStoreOrdersDataTable $dataTable)
    {
        if (!user()->hasPermissionTo('Marketplace::store_orders.access')) {
            abort(403, 'Forbidden!!');
        }

        $orders = $dataTable->query(new Order());

        return $this->orderService->index($orders, $dataTable);
    }

    public function show(Request $request, Order $order)
    {
        if (user()->cannot('view', $order)) {
            abort(403, 'Forbidden!!');
        }

        try {
            $downloads = OrderManager::getOrderDownloadable($order);

            $response = [
                'order' => $this->orderService->getModelDetails($order),
                'items' => (new OrderItemPresenter())->present($order->items)['data'],
                'downloads' => $downloads !== false ? $downloads : [],
                'tracking' => Shipping::track($order),
            ];

            return apiResponse($response);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param Order $order
     * @param $mediaId
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile(Request $request, Order $order, $mediaId)
    {
        try {
            $this->canAccess($order);

            if (!key_exists($mediaId, OrderManager::getOrderDownloadable($order))) {
                abort(403, 'Unauthorized!!.');
            }

            $media = Media::findOrfail($mediaId);

            return response()->download(storage_path($media->getUrl()));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function track(Request $request, Order $order, $tracking_number)
    {
        try {
            $this->canAccess($order);

            $tracking_details = optional($order->shipping['shipping_transactions'])[$tracking_number] ?? [];
            $tracking = \Shipping::track($order, $tracking_details);

            return apiResponse($tracking);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
