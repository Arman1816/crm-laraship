<?php

namespace Corals\Modules\Utility\Policies\Tag;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Utility\Models\Tag\Tag;
use Corals\User\Models\User;

class TagPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.utility';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Utility::tag.view')) {
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
        return $user->can('Utility::tag.create');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function update(User $user, Tag $tag)
    {
        if ($user->can('Utility::tag.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function destroy(User $user, Tag $tag)
    {
        if ($user->can('Utility::tag.delete')) {
            return true;
        }
        return false;
    }
}
