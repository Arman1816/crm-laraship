<?php

namespace Corals\Modules\Utility\Http\Controllers\API\Wishlist;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Utility\Models\Wishlist\Wishlist;
use Corals\Modules\Utility\Services\Wishlist\WishlistService;
use Illuminate\Http\Request;

class WishlistBaseController extends APIBaseController
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param Wishlist $wishlist
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Wishlist $wishlist)
    {
        try {
            $wishlistable_type = class_basename($wishlist->wishlistable_type);

            $this->wishlistService->destroy($request, $wishlist);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $wishlistable_type]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
