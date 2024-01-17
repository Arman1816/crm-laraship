<?php

namespace Corals\Modules\Messaging\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Messaging\Models\Discussion;

class DiscussionPolicy extends BasePolicy
{
    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Messaging::discussion.view')) {
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
        return $user->can('Messaging::discussion.create');
    }

    /**
     * @param User $user
     * @param Discussion $discussion
     * @return bool
     */
    public function update(User $user, Discussion $discussion)
    {
        if ($user->can('Messaging::discussion.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Discussion $discussion
     * @return bool
     */
    public function destroy(User $user, Discussion $discussion)
    {
        if ($user->can('Messaging::discussion.delete')) {
            return true;
        }
        return false;
    }

    public function view_all(User $user, Discussion $discussion)
    {
        if ($user->can('Messaging::discussion.view_all')) {
            return true;
        }
        return false;
    }

    public function select_recipient(User $user)
    {
        if ($user->can('Messaging::discussion.select_recipient')) {
            return true;
        }
        return false;
    }
}
