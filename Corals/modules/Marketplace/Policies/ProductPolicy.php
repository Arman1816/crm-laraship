<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Marketplace\Models\Product;
use Corals\User\Models\User;

class ProductPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    protected $skippedAbilities = [
        'update',
        'destroy',
        'variations',
        'bulkEditSKUs',
    ];

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
     * @param Product $product
     * @return bool
     */
    public function update(User $user, Product $product)
    {
        if ($product->status == 'deleted') {
            return false;
        }

        if ($user->can('Marketplace::product.update') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function destroy(User $user, Product $product)
    {
        if ($product->status == 'deleted' || $product->sku()->count() > 1) {
            return false;
        }

        if ($user->can('Marketplace::product.delete') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function variations(User $user, Product $product)
    {
        return $product->type == "variable";
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function bulkEditSKUs(User $user, Product $product)
    {
        return $this->variations($user, $product) && $product->sku()->count() > 0 && $this->update($user, $product);
    }
}
