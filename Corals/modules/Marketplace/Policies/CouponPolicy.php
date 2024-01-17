<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Marketplace\Models\Coupon;

class CouponPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Marketplace::coupon.view')) {
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
        return $user->can('Marketplace::coupon.create');
    }

    /**
     * @param User $user
     * @param Coupon $coupon
     * @return bool
     */
    public function update(User $user, Coupon $coupon)
    {
        if ($user->can('Marketplace::coupon.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Coupon $coupon
     * @return bool
     */
    public function destroy(User $user, Coupon $coupon)
    {
        if ($user->can('Marketplace::coupon.delete')) {
            return true;
        }
        return false;
    }

}
