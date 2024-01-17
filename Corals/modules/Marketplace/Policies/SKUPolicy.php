<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\User\Models\User;

class SKUPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Marketplace::product.view')) {
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
        return $user->can('Marketplace::product.create');
    }

    /**
     * @param User $user
     * @param SKU|null $sku
     * @return bool
     */
    public function update(User $user, SKU $sku = null)
    {
        if ($user->can('Marketplace::product.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param SKU $sku
     * @return bool
     */
    public function destroy(User $user, SKU $sku)
    {
        if ($user->can('Marketplace::product.delete')) {
            return true;
        }
        return false;
    }
}
