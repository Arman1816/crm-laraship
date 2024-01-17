<?php
/**
 * Created by PhpStorm.
 * User: iMak
 * Date: 11/19/17
 * Time: 9:19 AM
 */

namespace Corals\Modules\CMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\CMS\Models\News;

class NewsPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.cms';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('CMS::news.view')) {
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
        return $user->can('CMS::news.create');
    }

    /**
     * @param User $user
     * @param News $news
     * @return bool
     */
    public function update(User $user, News $news)
    {
        if ($user->can('CMS::news.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param News $news
     * @return bool
     */
    public function destroy(User $user, News $news)
    {
        if ($user->can('CMS::news.delete')) {
            return true;
        }
        return false;
    }

}
