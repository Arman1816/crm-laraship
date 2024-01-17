<?php

namespace Corals\Modules\Subscriptions\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Subscriptions\Models\PlanUsage;
use Corals\User\Models\User;

class PlanUsagePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.subscription';

    /**
     * @param User $user
     * @param PlanUsage|null $planUsage
     * @return bool
     */
    public function view(User $user, PlanUsage $planUsage = null)
    {
        return $user->can('Subscriptions::plan_usage.view');
    }
}
