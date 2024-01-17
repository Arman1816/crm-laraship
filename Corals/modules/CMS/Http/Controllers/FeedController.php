<?php

namespace Corals\Modules\CMS\Http\Controllers;

use Corals\Modules\CMS\Classes\Feed\Feed;
use Illuminate\Support\Str;

class FeedController
{
    public function __invoke()
    {
        $feeds = config('feed.feeds');

        $name = Str::after(app('router')->currentRouteName(), 'feeds.');

        $feed = $feeds[$name] ?? null;

        abort_unless($feed, 404);

        return new Feed(
            $feed['title'],
            request()->url(),
            $feed['items'],
            $feed['view'] ?? 'CMS::feed.feed',
            $feed['description'] ?? '',
            $feed['language'] ?? ''
        );
    }
}
