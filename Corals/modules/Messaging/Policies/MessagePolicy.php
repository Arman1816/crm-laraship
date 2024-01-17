<?php

namespace Corals\Modules\Messaging\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Messaging\Models\Message;

class MessagePolicy extends BasePolicy
{

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Messaging::message.view')) {
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
        return $user->can('Messaging::message.create');
    }

    /**
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function update(User $user, Message $message)
    {
        if ($user->can('Messaging::message.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function destroy(User $user, Message $message)
    {
        if ($user->can('Messaging::message.delete')) {
            return true;
        }
        return false;
    }

}
