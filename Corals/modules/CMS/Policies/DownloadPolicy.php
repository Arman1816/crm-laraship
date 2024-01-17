<?php

namespace Corals\Modules\CMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\CMS\Models\Download;

class DownloadPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.cms';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('CMS::download.view')) {
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
        return $user->can('CMS::download.create');
    }

    /**
     * @param User $user
     * @param Download $download
     * @return bool
     */
    public function update(User $user, Download $download)
    {
        if ($user->can('CMS::download.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Download $download
     * @return bool
     */
    public function destroy(User $user, Download $download)
    {
        if ($user->can('CMS::download.delete')) {
            return true;
        }
        return false;
    }

}
