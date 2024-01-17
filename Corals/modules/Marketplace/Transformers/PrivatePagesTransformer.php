<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\CMS\Models\Content;
use Corals\Modules\Marketplace\Models\Order;

class PrivatePagesTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.order.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param $postable
     * @return array
     * @throws \Throwable
     */
    public function transform($postable)
    {

        $order = $postable->sourcable_type == Order::class ? Order::find($postable->sourcable_id) : null;
        $post = Content::find($postable->content_id);

        $transformedArray = [
            'id' => $postable->id,
            'order_number' => $order ? $order->present('order_number') : ' -',
            'page_link' => "<a target='_blank' href='" . url($post->slug) . "'>" . $post->title . "</a>",
        ];

        return parent::transformResponse($transformedArray);
    }
}
