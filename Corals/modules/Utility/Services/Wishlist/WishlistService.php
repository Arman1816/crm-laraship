<?php

namespace Corals\Modules\Utility\Services\Wishlist;


use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Utility\Classes\Wishlist\WishlistManager;

class WishlistService extends BaseServiceClass
{

    /**
     * @param $wishlistable_hashed_id
     * @param $wishlistableClass
     * @param $requireLoginMessage
     * @return array
     * @throws \Exception
     */
    public function setWishlist($wishlistable_hashed_id, $wishlistableClass, $requireLoginMessage)
    {
        if (is_null($wishlistableClass)) {
            abort(400, 'Wishlistable class is null');
        }

        $wishlistable = $wishlistableClass::findByHash($wishlistable_hashed_id);

        if (!$wishlistable) {
            abort(404, 'Not Found!!');
        }

        if (!user()) {
            throw new \Exception(trans($requireLoginMessage, ['item' => class_basename($wishlistableClass)]));
        }

        $wishlistManager = new WishlistManager($wishlistable, user());

        return [$wishlistManager->handleWishlistItem(), $wishlistable];
    }

    /**
     * @param $request
     * @param $model
     */
    public function destroy($request, $model)
    {
        if (user()->cannot('destroy', $model)) {
            abort(403, 'Forbidden!!');
        }

        $model->delete();
    }
}
