<?php

namespace Corals\Modules\Subscriptions\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Subscriptions\Models\Product;

class ProductPolicy extends BasePolicy
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
        if ($user->can('Subscriptions::product.view')) {
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
        return $user->can('Subscriptions::product.create');
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function update(User $user, Product $product)
    {
        if ($product->status == 'deleted') {
            return false;
        }

        return $user->can('Subscriptions::product.update') || isSuperUser();
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function destroy(User $user, Product $product)
    {
        if ($product->status == 'deleted') {
            return false;
        }

        return $user->can('Subscriptions::product.delete') || isSuperUser();
    }
}
