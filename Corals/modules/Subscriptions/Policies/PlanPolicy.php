<?php

namespace Corals\Modules\Subscriptions\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Subscriptions\Models\Plan;

class PlanPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.subscription';

    protected $skippedAbilities = [
        'update', 'destroy'
    ];

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Subscriptions::plan.view')) {
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
        return $user->can('Subscriptions::plan.create');
    }

    /**
     * @param User $user
     * @param Plan $plan
     * @return bool
     */
    public function update(User $user, Plan $plan)
    {
        if ($plan->status == 'deleted') {
            return false;
        }

        return $user->can('Subscriptions::plan.update') || isSuperUser();
    }

    /**
     * @param User $user
     * @param Plan $plan
     * @return bool
     */
    public function destroy(User $user, Plan $plan)
    {
        if ($plan->status == 'deleted') {
            return false;
        }

        return $user->can('Subscriptions::plan.delete') || isSuperUser();
    }

}
