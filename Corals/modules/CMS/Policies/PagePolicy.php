<?php

namespace Corals\Modules\CMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\CMS\Models\Page;

class PagePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.cms';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('CMS::page.view')) {
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
        return $user->can('CMS::page.create');
    }

    /**
     * @param User $user
     * @param Page $page
     * @return bool
     */
    public function update(User $user, Page $page)
    {
        if ($user->can('CMS::page.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Page $page
     * @return bool
     */
    public function destroy(User $user, Page $page)
    {
        if ($user->can('CMS::page.delete')) {
            return true;
        }
        return false;
    }

}
