<?php

namespace Corals\Modules\Subscriptions\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Subscriptions\Models\Feature;

class FeaturePolicy extends BasePolicy
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
        if ($user->can('Subscriptions::feature.view')) {
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
        return $user->can('Subscriptions::feature.create');
    }

    /**
     * @param User $user
     * @param Feature $feature
     * @return bool
     */
    public function update(User $user, Feature $feature)
    {
        if ($feature->status == 'deleted') {
            return false;
        }

        if ($user->can('Subscriptions::feature.update') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Feature $feature
     * @return bool
     */
    public function destroy(User $user, Feature $feature)
    {
        if ($feature->status == 'deleted') {
            return false;
        }

        if ($user->can('Subscriptions::feature.delete') || isSuperUser()) {
            return true;
        }
        return false;
    }

}
