<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Modules\Marketplace\Models\Store;
use Corals\Modules\Utility\Http\Controllers\API\Wishlist\WishlistAPIBaseController;

class FollowController extends WishlistAPIBaseController
{
    protected function setCommonVariables()
    {
        $this->wishlistableClass = Store::class;
    }
}
