<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Carbon\Carbon;
use Corals\Foundation\DataTables\CoralsBuilder;
use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Marketplace\Classes\Marketplace;
use Corals\Modules\Marketplace\DataTables\MyOrdersDataTable;
use Corals\Modules\Marketplace\DataTables\MyPrivatePagesDataTable;
use Corals\Modules\Marketplace\DataTables\MyStoreOrdersDataTable;
use Corals\Modules\Marketplace\DataTables\OrdersDataTable;
use Corals\Modules\Marketplace\Facades\OrderManager;
use Corals\Modules\Marketplace\Http\Requests\RefundOrderRequest;
use Corals\Modules\Marketplace\Jobs\HandleOrdersWithPayouts;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Traits\OrderCommon;
use Corals\Modules\Payment\Classes\Payments;
use Corals\Modules\Payment\Common\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OrdersController extends BaseController
{
    use OrderCommon;

    protected $shipping;

    public function __construct()
    {
        $this->resource_url = config('marketplace.models.order.resource_url');
        $this->title = 'Marketplace::module.order.title';
        $this->title_singular = 'Marketplace::module.order.title_singular';

        $this->setViewSharedData(['hideCreate' => true]);

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param OrdersDataTable $dataTable
     * @return mixed
     */
    public function index(Request $request, OrdersDataTable $dataTable)
    {
        if (!user()->hasPermissionTo('Administrations::admin.marketplace')) {
            abort(403);
        }

        return $dataTable->render('Marketplace::orders.index');
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Order $order)
    {
        if (!user()->hasPermissionTo('Marketplace::order.update')) {
            abort(403);
        }

        $order_statuses = trans(config('marketplace.models.order.statuses'));
        $shippment_statuses = trans(config('marketplace.models.order.shippment_statuses'));
        $payment_statuses = trans(config('marketplace.models.order.payment_statuses'));

        $this->setViewSharedData(['title_singular' => trans('Marketplace::module.order.update')]);

        return view('Marketplace::orders.edit')->with(compact('order', 'order_statuses', 'shippment_statuses',
            'payment_statuses'));
    }


    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Order $order)
    {
        if (!user()->hasPermissionTo('Marketplace::order.update')) {
            abort(403);
        }

        $this->validate($request, ['status' => 'required']);

        try {
            $data = $request->all();

            $shipping = $order->shipping ?? [];

            if ($request->has('shipping')) {
                $shipping = array_replace_recursive($shipping, $data['shipping']);
            }
            $billing = $order->billing ?? [];

            if (user()->hasPermissionTo('Marketplace::order.update_payment_details')) {
                if ($request->has('billing')) {
                    $billing = array_replace_recursive($billing, $data['billing']);
                }
            }


            if ($data['billing']['payment_status'] == "paid") {
                $order->transactions()->update(['status' => 'completed']);
            } else {
                if ($data['billing']['payment_status'] == "canceled") {
                    $order->transactions()->update(['status' => 'cancelled']);
                } else {
                    if ($data['billing']['payment_status'] == "pending") {
                        $order->transactions()->update(['status' => 'pending']);
                    }
                }
            }

            $order->update([
                'status' => $data['status'],
                'shipping' => $shipping,
                'billing' => $billing,
            ]);


            if ($request->has('notify_buyer')) {
                event('notifications.marketplace.order.updated', ['order' => $order]);
            }
            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.updated', ['item' => $this->title_singular])
            ];

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch
        (\Exception $exception) {
            log_exception($exception, Order::class, 'update');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


    public function bulkAction(BulkRequest $request)
    {
        try {
            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);
            switch ($action) {
                case 'pending' :
                    foreach ($selection as $selection_id) {
                        $order = Order::findByHash($selection_id);
                        if (user()->can('Marketplace::store.update')) {
                            $order->update([
                                'status' => 'pending'
                            ]);
                            $order->save();
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
                case 'processing' :
                    foreach ($selection as $selection_id) {
                        $order = Order::findByHash($selection_id);
                        if (user()->can('Marketplace::store.update')) {
                            $order->update([
                                'status' => 'processing'
                            ]);
                            $order->save();
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
                case 'completed' :
                    foreach ($selection as $selection_id) {
                        $order = Order::findByHash($selection_id);
                        if (user()->can('Marketplace::store.update')) {
                            $order->update([
                                'status' => 'completed'
                            ]);
                            $order->save();
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
                case 'canceled' :
                    foreach ($selection as $selection_id) {
                        $order = Order::findByHash($selection_id);
                        if (user()->can('Marketplace::store.update')) {
                            $order->update([
                                'status' => 'canceled'
                            ]);
                            $order->save();
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
        } catch (\Exception $exception) {
            log_exception($exception, Order::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }


    /**
     * @param RefundOrderRequest $request
     * @param Order $order
     * @return array|string
     * @throws \Throwable
     */
    public function getRefundView(RefundOrderRequest $request, Order $order)
    {
        return view('Marketplace::orders.refund-order', compact('order'))->render();
    }


    /**
     * @param RefundOrderRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function doRefund(RefundOrderRequest $request, Order $order)
    {
        $data = $request->all();
        $amount = $data['amount'];
        $type = $data['type'];
        $cancel = $data['cancel'] ?? false;

        try {
            if (isset($order->billing['payment_status']) && ($order->billing['payment_status'] == 'refunded')) {
                throw new \Exception(trans('Marketplace::exception.misc.already_refunded'));
            }

            if (isset($order->billing['payment_status']) && ($order->billing['payment_status'] != 'paid' && $order->billing['payment_status'] != 'partial_refund')) {
                throw new \Exception(trans('Marketplace::exception.misc.order_not_paid'));
            }


            \Actions::do_action('pre_marketplace_refund_order', $order, $amount);


            if ($type == "online") {
                $marketplace = new Marketplace($order->billing['gateway']);

                if (($type == "online") && !$marketplace->gateway->getConfig('support_online_refund')) {
                    throw new \Exception(trans('Marketplace::exception.misc.online_refund_not_supported',
                        ['gateway' => $marketplace->gateway->getName()]));
                }

                $refund_reference = $marketplace->refundOrder($order, $amount);
            } else {
                $refund_reference = 'offline_' . \Str::random(6);
            }


            $user = user();

            Transaction::create([
                'code' => Transaction::getCode('TR'),
                'owner_type' => getMorphAlias($user),
                'owner_id' => $user->id ?? '',
                'invoice_id' => $order->invoice->id,
                'paid_currency' => $order->currency,
                'paid_amount' => $amount,
                'reference' => $refund_reference,
                'amount' => ($amount * -1),
                'sourcable_id' => $order->id,
                'sourcable_type' => getMorphAlias($order),
                'transaction_date' => Carbon::now(),
                'type' => 'order_refund',
                'notes' => 'Refund for order# ' . $order->id,
            ]);

            if ($order->amount > $order->getPaymentRefundedAmount()) {
                $payment_status = 'partial_refund';
            } else {
                $payment_status = 'refunded';
            }


            $billing = $order->billing;

            $billing['payment_status'] = $payment_status;

            $order->billing = $billing;
            if ($cancel) {
                $order->status = 'canceled';
            }

            $order->save();

            if ($data['reverse_payout']) {
                $payouts = $order->payouts;
                foreach ($payouts as $payout) {
                    if (user()->can('canReverseTransfer', $payout)) {
                        $gateway = $payout->getProperty('gateway');
                        $payments = new Payments($gateway);
                        $payments->reverseTransfer($payout);
                    }
                }
                $order->commissions()->update(['status' => 'cancelled']);
            }


            $message = [
                'level' => 'success',
                'message' => trans('Marketplace::messages.refund.do_refund_order', ['item' => $order])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, 'OrderController', 'RefundOrder');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param MyOrdersDataTable $dataTable
     * @return mixed
     */
    public function myOrders(Request $request, MyOrdersDataTable $dataTable)
    {
        if (!user()->hasPermissionTo('Marketplace::my_orders.access')) {
            abort(403);
        }

        return $dataTable->render('Marketplace::orders.index');
    }

    /**
     * @param Request $request
     * @param MyOrdersDataTable $dataTable
     * @return mixed
     */
    public function storeOrders(Request $request, MyStoreOrdersDataTable $dataTable)
    {
        if (!user()->hasPermissionTo('Marketplace::store_orders.access')) {
            abort(403);
        }

        return $dataTable->render('Marketplace::orders.index');
    }

    /**
     * @param Request $request
     * @param MyOrdersDataTable $dataTable
     * @return mixed
     */
    public function myPrivatePages(Request $request, MyPrivatePagesDataTable $dataTable)
    {
        if (!user()->hasPermissionTo('Marketplace::my_orders.access')) {
            abort(403);
        }

        return $dataTable->render('Marketplace::orders.private_pages');
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function myDownloads(Request $request)
    {
        CoralsBuilder::DataTableScripts();

        if (!user()->hasPermissionTo('Marketplace::my_orders.access')) {
            abort(403);
        }

        $orders = Order::myOrders()->get();

        return view('Marketplace::orders.downloads')->with(compact('orders'));
    }


    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Order $order)
    {
        $this->canAccess($order);

        $this->setViewSharedData(['title_singular' => $order->order_number]);

        return view('Marketplace::orders.show')->with(compact('order'));
    }

    /**
     * @param Request $request
     * @param Order $order
     * @param $hashed_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile(Request $request, Order $order, $hashed_id)
    {
        $this->canAccess($order);

        $id = hashids_decode($hashed_id);

        if (!key_exists($id, OrderManager::getOrderDownloadable($order))) {
            abort(403, 'Unauthorized!!.');
        }

        $media = Media::findOrfail($id);

        return response()->download(storage_path($media->getUrl()));
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function track(Request $request, Order $order, $tracking_number)
    {
        $this->canAccess($order);

        try {
            $tracking_details = optional($order->shipping['shipping_transactions'])[$tracking_number] ?? [];
            $tracking = \Shipping::track($order, $tracking_details);

            return view('Marketplace::orders.track')->with(compact('order', 'tracking'));
        } catch (\Exception $exception) {
            log_exception($exception, 'OrderController', 'Track');
        }
    }

    public function doPayout(Request $request, Order $order)
    {
        $this->authorize('canDoPayout', $order);

        try {
            $this->dispatch(new HandleOrdersWithPayouts($order));

            $message = [
                'level' => 'success',
                'message' => trans('Marketplace::messages.order.payout_submitted')
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Transaction::class, 'reversePayout');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
