<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Marketplace\Models\Order;
use Corals\User\Models\User;

class OrderPolicy extends BasePolicy
{
    protected $skippedAbilities = ['canDoPayout'];

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function refund(User $user, Order $order)
    {
        if ($user->cant('Marketplace::order.update_payment_details')) {
            return false;
        }

        $payment_status = $order->billing['payment_status'] ?? '';

        if ($payment_status && $payment_status != 'refunded' && $order->status != 'canceled') {
            return true;
        }

        return false;
    }

    public function canDoPayout(User $user, Order $order)
    {
        $store = $order->store;

        $billingDetails = $order->billing;

        $gateway = data_get($billingDetails, 'gateway');

        $paymentReference = data_get($order->billing, 'payment_reference');

        if (!$gateway || !$paymentReference) {
            return false;
        }

        $storeUser = $store->user;

        if (!$storeUser) {
            return false;
        }

        if ($storeUser->id != $user->id && !($user->hasPermissionTo($this->administrationPermission) || isSuperUser($user))) {
            return false;
        }

        $gatewayStatus = $storeUser->getGatewayStatus($gateway, 'AccountConnect', true)->first();

        if (!$gatewayStatus || $gatewayStatus->status !== 'PAYOUTS_ENABLED') {
            return false;
        }

        $invoice = $order->invoice;

        if (!$invoice) {
            return false;
        }

        $amount = $invoice->transactions()->where('status', '!=', 'cancelled')->sum('amount');

        if ($amount <= 0) {
            return false;
        }

        return true;
    }
}
