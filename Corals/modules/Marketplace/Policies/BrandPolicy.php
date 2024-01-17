<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Marketplace\Models\Brand;

class BrandPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user, Brand $brand = null)
    {
        if ($user->can('Marketplace::brand.view')) {
            if($brand){
                if (!$brand->store || ($brand->store->user->id == $user->id)) {
                    return true;
                }
            }else{
                return true;
            }

        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('Marketplace::brand.create');
    }

    /**
     * @param User $user
     * @param Brand $brand
     * @return bool
     */
    public function update(User $user, Brand $brand)
    {
        if ($user->can('Marketplace::brand.update') && $brand->store && ($brand->store->user->id == $user->id)) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Brand $brand
     * @return bool
     */
    public function destroy(User $user, Brand $brand)
    {
        if ($user->can('Marketplace::brand.delete') && $brand->store && ($brand->store->user->id == $user->id)) {
            return true;
        }
        return false;
    }

}
