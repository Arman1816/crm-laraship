<?php

namespace Corals\Foundation\Policies;

use Corals\User\Models\User;

class BasePolicy
{
    /**
     * @var array
     */
    protected $skippedAbilities = [];

    /**
     * @var string
     */
    protected $administrationPermission = '';

    /**
     * @param $user
     * @param $ability
     * @return bool
     */


    public function __call($name, $arguments)
    {
        return false;
    }

    public function before($user, $ability)
    {
        if (in_array($ability, $this->skippedAbilities)) {
            return null;
        }

        if ((!empty($this->administrationPermission) && $user->hasPermissionTo($this->administrationPermission)) || isSuperUser($user)) {
            return true;
        }

        return null;
    }

}
