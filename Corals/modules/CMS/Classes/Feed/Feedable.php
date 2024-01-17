<?php

namespace Corals\Modules\CMS\Classes\Feed;

interface Feedable
{
    /**
     * @return array|\Corals\Modules\CMS\Classes\Feed\FeedItem
     */
    public function toFeedItem();
}
