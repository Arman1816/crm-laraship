<?php

namespace Corals\Modules\Foo\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Foo\Models\Bar;
use Corals\User\Models\User;

class BarPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.foo';
    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Foo::bar.view')) {
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
        return $user->can('Foo::bar.create');
    }

    /**
     * @param User $user
     * @param Bar $bar
     * @return bool
     */
    public function update(User $user, Bar $bar)
    {
        if ($user->can('Foo::bar.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Bar $bar
     * @return bool
     */
    public function destroy(User $user, Bar $bar)
    {
        if ($user->can('Foo::bar.delete')) {
            return true;
        }
        return false;
    }

}
