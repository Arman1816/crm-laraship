<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\Facades\Marketplace;
use Corals\Modules\Marketplace\Http\Requests\API\OrderSubmitRequest;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Services\CheckoutService;
use Corals\Modules\Marketplace\Traits\API\CheckoutControllerCommonFunctions;

class CheckoutController extends APIBaseController
{
    use CheckoutControllerCommonFunctions;

    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;

        parent::__construct();
    }

    /**
     * @param OrderSubmitRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderSubmit(OrderSubmitRequest $request)
    {
        try {
            $user = user();

            $billing_address = $request->get('billing_address');
            $shipping_address = $request->get('shipping_address');
            $payment_status = $request->get('payment_status');

            $billingDetails = $request->only(['billing_address', 'payment_reference', 'gateway', 'payment_status']);

            if ($user && $request->input('save_billing')) {
                $user->saveAddress($billing_address, 'billing');
            }

            if ($user && $request->input('save_shipping')) {
                $user->saveAddress($shipping_address, 'shipping');
            }

            $orderAttributes = [
                'currency' => $request->get('currency', \Payments::session_currency()),
                'user_id' => user()->id,
                'billing' => $billingDetails,
                'status' => $request->get('status'),
            ];

            foreach ($request->get('orders', []) ?? [] as $order) {
                $orderAttributes['order_number'] = Marketplace::createOrderNumber();
                $orderAttributes['amount'] = $order['amount'];
                $orderAttributes['store_id'] = $order['store_id'];

                $orderItems = [];

                foreach ($request->get('order_items', []) ?? [] as $item) {
                    $orderItems[] = $item;
                }

                $order = Order::query()->create($orderAttributes);

                $order->items()->createMany($orderItems);

                $invoice = $this->checkoutService->generateOrderInvoice($order, $payment_status, $user, $billing_address);

                $this->checkoutService->setOrderShippingDetails($order, $shipping_address);

                $this->checkoutService->orderFulfillment($order, $invoice, $user);
            }

            return apiResponse([], trans('Marketplace::labels.order.order_has_been_placed'));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
