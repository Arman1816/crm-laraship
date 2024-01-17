<?php

namespace Corals\Modules\CMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;

class BlockPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.cms';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('CMS::block.view')) {
            return true;
        }
        return false;
    }

}
