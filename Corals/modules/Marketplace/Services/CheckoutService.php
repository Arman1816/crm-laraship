<?php


namespace Corals\Modules\Marketplace\Services;


use Carbon\Carbon;
use Corals\Modules\Marketplace\Classes\Coupons\Advanced;
use Corals\Modules\Marketplace\Classes\Marketplace;
use Corals\Modules\Marketplace\Facades\Shipping;
use Corals\Modules\Marketplace\Models\Coupon;
use Corals\Modules\Marketplace\Models\Shipping as ShippingModel;
use Corals\Modules\Marketplace\Transformers\API\CouponPresenter;
use Corals\Modules\Marketplace\Transformers\API\ShippingPresenter;
use Corals\Modules\Payment\Common\Models\Invoice;

class CheckoutService
{
    /**
     * @param $request
     * @param $code
     * @return mixed
     * @throws \Corals\Modules\Marketplace\Exceptions\CouponException
     */
    public function getCouponByCode($request, $code)
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            throw new \Exception(trans('Marketplace::exception.checkout.invalid_coupon', ['code' => $code]));
        }

        $coupon_class = new Advanced($code, $coupon, []);

        $coupon_class->validate(true);

        $coupon->setPresenter(new CouponPresenter());

        return $coupon->presenter();
    }

    /**
     * @param $request
     * @param $countryCode
     * @param $store_id
     * @return mixed
     * @throws \Exception
     */
    public function getAvailableShippingRoles($request, $countryCode, $store_id)
    {
        $shipping_roles = ShippingModel::where(function ($query) use ($countryCode) {
            $query->where('country', $countryCode)
                ->orWhereNull('country');
        })->where(function ($query) use ($store_id) {
            $query->where('store_id', $store_id)
                ->orWhereNull('store_id');
        })->orderBy('exclusive', 'DESC')
            ->orderBy('priority', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return (new ShippingPresenter())->present($shipping_roles)['data'];
    }

    /**
     * @param $order
     * @param $paymentStatus
     * @param $user
     * @param $billingAddress
     * @return mixed
     */
    public function generateOrderInvoice($order, $paymentStatus, $user, $billingAddress)
    {
        $invoice = $order->invoice;


        if (!$invoice) {
            $invoice = Invoice::create([
                'code' => Invoice::getCode('INV'),
                'currency' => $order->currency,
                'status' => $paymentStatus,
                'invoicable_id' => $order->id,
                'invoicable_type' => get_class($order),
                'due_date' => Carbon::now(),
                'invoice_date' => now(),
                'sub_total' => $order->amount,
                'total' => $order->amount,
                'user_id' => $user->id,
                'properties' => ['billing_address' => $billingAddress]
            ]);

            $invoice_items = [];

            foreach ($order->items as $order_item) {
                $invoice_items[] = [
                    'code' => \Str::random(6),
                    'description' => $order_item->description,
                    'amount' => $order_item->amount,
                    'itemable_id' => $order_item->id,
                    'itemable_type' => get_class($order_item),
                ];
            }

            $invoice->items()->createMany($invoice_items);
        } else {
            $invoice->status = $paymentStatus;
            $invoice->save();
        }

        return $invoice;
    }

    /**
     * @param $order
     * @param $shippingAddress
     */
    public function setOrderShippingDetails($order, $shippingAddress)
    {
        $shipping = $order->shipping ?? [];

        foreach ($order->items as $order_item) {
            if ($order_item->type == 'Shipping') {
                try {
                    $shipping['shipping_transactions'] = array_merge(
                        $shipping['shipping_transactions'] ?? [],
                        Shipping::createShippingTransaction($order_item) ?? []
                    );
                } catch (\Exception $exception) {
                    log_exception($exception, 'CreatShippingTransaction', 'Checkout');
                }
            }
        }

        $shipping['shipping_address'] = $shippingAddress;

        $order->shipping = $shipping;

        $order->save();
    }

    /**
     * @param $order
     * @param $invoice
     * @param $user
     * @throws \Exception
     */
    public function orderFulfillment($order, $invoice, $user)
    {
        \Actions::do_action('post_order_received', $order);

        event('notifications.marketplace.order.received', ['user' => $user, 'order' => $order]);
        event('notifications.marketplace.store_order.received', ['user' => $user, 'order' => $order]);

        $marketplace = new Marketplace();
        $marketplace->deductFromInventory($order);
        $marketplace->addContentAccess($order, $user);
        $marketplace->setTransactions($invoice, $order);
        $marketplace->increaseTotalSales($order);
    }
}
