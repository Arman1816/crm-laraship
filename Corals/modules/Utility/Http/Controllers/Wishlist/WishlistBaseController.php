<?php

namespace Corals\Modules\Utility\Http\Controllers\Wishlist;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Utility\Models\Wishlist\Wishlist;
use Corals\Modules\Utility\Services\Wishlist\WishlistService;
use Corals\Modules\Utility\Traits\Wishlist\WishlistCommon;
use Illuminate\Http\Request;

class WishlistBaseController extends BaseController
{
    use WishlistCommon;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;

        $this->setCommonVariables();

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, ['setWishlist']);

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param $wishlistable_hashed_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setWishlist(Request $request, $wishlistable_hashed_id)
    {
        try {
            [$state, $wishlistable] = $this->wishlistService->setWishlist($wishlistable_hashed_id, $this->wishlistableClass, $this->requireLoginMessage);

            if ($state == 'add') {
                $message = ['level' => 'success', 'message' => trans($this->addSuccessMessage, ['item' => class_basename($this->wishlistableClass)]), 'action' => 'add', 'hashed_id' => $wishlistable->hashed_id];
            } else {
                $message = ['level' => 'success', 'message' => trans($this->deleteSuccessMessage, ['item' => class_basename($this->wishlistableClass)]), 'action' => 'remove', 'hashed_id' => $wishlistable->hashed_id];
            }
        } catch (\Exception $exception) {
            log_exception($exception, get_class($this), 'setWishlist');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        if ($request->ajax() || is_null($this->redirectUrl) || $request->wantsJson()) {
            return response()->json($message);
        } else {
            if ($message['level'] === 'success') {
                flash($message['message'])->success();
            } else {
                flash($message['message'])->error();
            }
            redirectTo($this->redirectUrl);
        }
    }


    /**
     * @param Request $request
     * @param Wishlist $wishlist
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Wishlist $wishlist)
    {
        try {
            $this->wishlistService->destroy($request, $wishlist);

            $message = ['level' => 'success', 'message' => trans($this->deleteSuccessMessage, ['item' => class_basename($this->wishlistableClass)])];
        } catch (\Exception $exception) {
            log_exception($exception, get_class($this), 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
