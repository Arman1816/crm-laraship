<?php

namespace Corals\Modules\Utility\Traits\Wishlist;

trait WishlistCommon
{
    protected $wishlistableClass = null;
    protected $redirectUrl = null;
    protected $addSuccessMessage = 'Utility::messages.wishlist.success.add';
    protected $deleteSuccessMessage = 'Utility::messages.wishlist.success.delete';
    protected $requireLoginMessage = 'Utility::messages.wishlist.require_login';
    protected $wishlistService;

    protected function setCommonVariables()
    {
        $this->wishlistableClass = null;
        $this->redirectUrl = null;
    }
}
