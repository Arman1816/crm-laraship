<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Utility\Models\Wishlist\Wishlist;

class FollowTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.follow.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Wishlist $wishlist
     * @return array
     * @throws \Throwable
     */
    public function transform(Wishlist $follow)
    {
        $transformedArray = [
            'id' => $follow->id,
            'store_logo' => '<img src="' . $follow->wishlistable->thumbnail . "/>",
            'store_name' => '<a href="' . $follow->wishlistable->getShowURL() . '" target="_blank">' . $follow->wishlistable->getIdentifier() . '</a>',
            'user_id' => $follow->user->full_name,
            'created_at' => format_date($follow->created_at),
            'action' => $this->actions($follow)
        ];

        return parent::transformResponse($transformedArray);
    }
}
