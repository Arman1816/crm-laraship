<?php

namespace Corals\Modules\Utility\Policies\Wishlist;

use Corals\Modules\Utility\Models\Wishlist\Wishlist;
use Corals\User\Models\User;

class WishlistPolicy
{
    public function destroy(User $user, Wishlist $wishlist)
    {
        return $wishlist->user_id == $user->id;
    }

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->hasPermissionTo('Administrations::admin.utility')) {
            return true;
        }

        return null;
    }
}
