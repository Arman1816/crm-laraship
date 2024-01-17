<?php

namespace Corals\Modules\Subscriptions\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Subscriptions\Facades\SubscriptionsManager;
use Corals\Modules\Subscriptions\Models\Subscription;
use Corals\User\Models\User;

class SubscriptionPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.subscription';

    /**
     * @var array
     */
    protected $skippedAbilities = [
        'markInvoiceAsPaidAndActive',
        'renew'
    ];


    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Subscriptions::subscription.view')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('Subscriptions::subscription.create');
    }

    /**
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function update(User $user, Subscription $subscription)
    {
        if ($user->can('Subscriptions::subscription.update') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function destroy(User $user, Subscription $subscription)
    {
        if ($user->can('Subscriptions::subscription.delete')) {
            return true;
        }
        return false;
    }


    /**
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function markInvoiceAsPaidAndActive(User $user, Subscription $subscription)
    {
        if (!$this->update($user, $subscription)) {
            return false;
        }

        if (SubscriptionsManager::isSubscriptionHasPendingInvoice($subscription) && $subscription->status != 'active') {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function renew(User $user, Subscription $subscription)
    {
        if ($subscription->invoice && $subscription->invoice->status == 'pending') {
            return false;
        }

        $gateway = $subscription->gateway();

        return $gateway && $gateway->getConfig('subscription_self_managed') && $subscription->active();
    }
}
