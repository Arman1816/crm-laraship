<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Marketplace\Models\Category;

class CategoryPolicy extends BasePolicy
{

    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Marketplace::category.view')) {
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
        return $user->can('Marketplace::category.create');
    }

    /**
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function update(User $user, Category $category)
    {
        if ($user->can('Marketplace::category.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function destroy(User $user, Category $category)
    {
        if ($user->can('Marketplace::category.delete')) {
            return true;
        }
        return false;
    }

}
