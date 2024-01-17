<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Marketplace\Models\Shipping;

class ShippingPolicy extends BasePolicy
{

    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Marketplace::shipping.view')) {
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
        return $user->can('Marketplace::shipping.create');
    }

    /**
     * @param User $user
     * @param Shipping $shipping
     * @return bool
     */
    public function update(User $user, Shipping $shipping)
    {
        if ($user->can('Marketplace::shipping.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Shipping $shipping
     * @return bool
     */
    public function destroy(User $user, Shipping $shipping)
    {
        if ($user->can('Marketplace::shipping.delete')) {
            return true;
        }
        return false;
    }
}
