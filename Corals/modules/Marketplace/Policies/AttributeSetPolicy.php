<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Marketplace\Models\AttributeSet;

class AttributeSetPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user, AttributeSet $attribute_set = null)
    {
        if ($user->can('Marketplace::attribute.view')) {
            if($attribute_set){
                if (!$attribute_set->store || ($attribute_set->store->user->id == $user->id)) {
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
     * @param AttributeSet $attribute_set
     * @return bool
     */
    public function update(User $user, AttributeSet $attribute_set)
    {
        if ($user->can('Marketplace::attribute.update') && $attribute_set->store && ($attribute_set->store->user->id == $user->id)) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param AttributeSet $attribute_set
     * @return bool
     */
    public function destroy(User $user, AttributeSet $attribute_set)
    {
        if ($user->can('Marketplace::attribute.delete') && $attribute_set->store && ($attribute_set->store->user->id == $user->id)) {
            return true;
        }
        return false;
    }

}
