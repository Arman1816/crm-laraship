<?php

namespace Corals\Modules\Subscriptions\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Subscriptions\Models\SubscriptionCycle;
use Corals\User\Models\User;

class SubscriptionCyclePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.subscription';

    /**
     * @param User $user
     * @param SubscriptionCycle|null $subscriptionCycle
     * @return bool
     */
    public function view(User $user, SubscriptionCycle $subscriptionCycle = null)
    {
        return $user->can('Subscriptions::subscription_cycle.view');
    }
}
