<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Marketplace\Models\Store;

class StorePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    protected $skippedAbilities = [
        'update', 'destroy'
    ];

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Marketplace::store.view')) {
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
        return $user->can('Marketplace::store.create');
    }

    /**
     * @param User $user
     * @param Store $store
     * @return bool
     */
    public function update(User $user, Store $store)
    {
        if ($store->id == 1) {
            return false;
        }
        if ($user->can('Marketplace::store.update') || (isSuperUser() || $user->hasPermissionTo($this->administrationPermission))) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Store $store
     * @return bool
     */
    public function destroy(User $user, Store $store)
    {
        if ($store->id == 1) {
            return false;
        }

        if ($user->can('Marketplace::store.delete') || (isSuperUser() || $user->hasPermissionTo($this->administrationPermission))) {
            return true;
        }
        return false;
    }
}
