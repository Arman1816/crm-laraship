<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\User\Models\User;

class TagPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->can('Marketplace::tag.view');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('Marketplace::tag.create');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function update(User $user, Tag $tag)
    {
        return $user->can('Marketplace::tag.update');
    }

    /**
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function destroy(User $user, Tag $tag)
    {
        return $user->can('Marketplace::tag.delete');
    }

}
