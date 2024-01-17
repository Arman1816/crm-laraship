<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\Order;

class OrderTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.order.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Order $order
     * @return array
     * @throws \Throwable
     */
    public function transform(Order $order)
    {
        $payment_status = $order->billing['payment_status'] ?? '';

        $currency = strtoupper($order->currency);

        $levels = [
            'pending' => 'info',
            'processing' => 'success',
            'completed' => 'primary',
            'failed' => 'danger',
            'canceled' => 'warning'
        ];
        $user_id = null;
        if ($order->user) {
            if (\Store::isStoreAdmin()) {
                $user_id = "<a target='_blank' href='" . url('users/' . $order->user->hashed_id) . "'> {$order->user->full_name}</a>";
            } else {
                $user_id = $order->user->full_name;
            }
        } else {
            $user_id = 'Guest';
        }


        $payment_levels = [
            'pending' => 'info',
            'submitted' => 'info',
            'paid' => 'success',
            'canceled' => 'danger',
            'refunded' => 'warning',
            'partial_refund' => 'warning'
        ];


        $transformedArray = [
            'status' => formatStatusAsLabels($order->status, ['level' => $levels[$order->status], 'text' => trans('Marketplace::status.order.' . $order->status)]),
            'checkbox' => $this->generateCheckboxElement($order),
            'payment_status' => $payment_status ? formatStatusAsLabels($payment_status, ['level' => $payment_levels[$payment_status], 'text' => trans('Marketplace::status.payment.' . $payment_status)]) : ' -  ',
            'user_id' => $user_id,

            'order_number' => '<a  href="' . url($this->resource_url . '/' . $order->hashed_id) . '">' . $order->order_number . '</a>',
            'id' => $order->id,
            'currency' => $currency,
            'amount' => \Currency::format($order->amount, $currency),
            'store' => $order->store ? '<a target="_blank" href="' . $order->store->getUrl() . '">' . $order->store->name . '</a>' : '-',
            'created_at' => format_date($order->created_at),
            'updated_at' => format_date($order->updated_at),
            'action' => $this->actions($order)
        ];

        return parent::transformResponse($transformedArray);
    }
}
