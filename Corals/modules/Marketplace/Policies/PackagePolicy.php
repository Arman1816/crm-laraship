<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Marketplace\Models\Package;
use Corals\User\Models\User;

class PackagePolicy extends BasePolicy
{

    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Marketplace::package.view')) {
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
        return $user->can('Marketplace::package.create');
    }

    /**
     * @param User $user
     * @param Package $package
     * @return bool
     */
    public function update(User $user, Package $package)
    {
        if ($user->can('Marketplace::package.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Package $package
     * @return bool
     */
    public function destroy(User $user, Package $package)
    {
        if ($user->can('Marketplace::package.delete')) {
            return true;
        }
        return false;
    }
}
