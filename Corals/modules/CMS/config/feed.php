<?php

return [
    'feeds' => [
        'posts' => [
            /*
             * Here you can specify which class and method will return
             * the items that should appear in the feed. For example:
             * 'App\Model@getAllFeedItems'
             *
             * You can also pass an argument to that method:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => '\Corals\Modules\CMS\Models\Post@getFeedItems',

            /*
             * The feed will be available on this url.
             */
            'url' => 'feed/posts',

            'title' => 'CMS::feed.post.title',
            'description' => 'CMS::feed.post.description',
            'language' => app()->getLocale(),

            /*
             * The view that will render the feed.
             */
            'view' => 'CMS::feed.atom',

            /*
             * The type to be used in the <link> tag
             * application/rss+xml
             * application/atom+xml
             */
            'type' => 'application/atom+xml',
        ],
        'news' => [
            /*
             * Here you can specify which class and method will return
             * the items that should appear in the feed. For example:
             * 'App\Model@getAllFeedItems'
             *
             * You can also pass an argument to that method:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => '\Corals\Modules\CMS\Models\News@getFeedItems',

            /*
             * The feed will be available on this url.
             */
            'url' => 'feed/news',

            'title' => 'CMS::feed.news.title',
            'description' => 'CMS::feed.news.description',
            'language' => app()->getLocale(),

            /*
             * The view that will render the feed.
             */
            'view' => 'CMS::feed.atom',

            /*
             * The type to be used in the <link> tag
             * application/rss+xml
             * application/atom+xml
             */
            'type' => 'application/atom+xml',
        ],
    ],
];
