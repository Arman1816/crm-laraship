<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Marketplace\Models\Attribute;

class AttributePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user, Attribute $attribute = null)
    {
        if ($user->can('Marketplace::attribute.view')) {
            if($attribute){
                if (!$attribute->store || ($attribute->store->user->id == $user->id)) {
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
        return $user->can('Marketplace::attribute.create');
    }

    /**
     * @param User $user
     * @param Attribute $attribute
     * @return bool
     */
    public function update(User $user, Attribute $attribute)
    {
        if ($user->can('Marketplace::attribute.update') && $attribute->store && ($attribute->store->user->id == $user->id)) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Attribute $attribute
     * @return bool
     */
    public function destroy(User $user, Attribute $attribute)
    {
        if ($user->can('Marketplace::attribute.delete') && $attribute->store && ($attribute->store->user->id == $user->id)) {
            return true;
        }
        return false;
    }

}
