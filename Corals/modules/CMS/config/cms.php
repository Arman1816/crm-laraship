<?php

return [
    'models' => [
        'page' => [
            'presenter' => \Corals\Modules\CMS\Transformers\PagePresenter::class,
            'resource_url' => 'cms/pages',
            'translatable' => ['content', 'title', 'meta_keywords', 'meta_description'],
            'actions' => [
                'edit_in_designer' => [
                    'icon' => 'fa fa-paint-brush fa-fw',
                    'href_pattern' => ['pattern' => '[arg]/design', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('CMS::labels.page.edit_designer');"]],
                    'target' => '_blank',
                    'data' => []
                ]
            ]
        ],
        'post' => [
            'presenter' => \Corals\Modules\CMS\Transformers\PostPresenter::class,
            'resource_url' => 'cms/posts',
        ],
        'category' => [
            'presenter' => \Corals\Modules\CMS\Transformers\CategoryPresenter::class,
            'resource_url' => 'cms/categories',
        ],
        'news' => [
            'presenter' => \Corals\Modules\CMS\Transformers\NewsPresenter::class,
            'resource_url' => 'cms/news',
        ],
        'faq' => [
            'presenter' => \Corals\Modules\CMS\Transformers\FaqPresenter::class,
            'resource_url' => 'cms/faqs',
        ],
        'block' => [
            'presenter' => \Corals\Modules\CMS\Transformers\BlockPresenter::class,
            'resource_url' => 'cms/blocks',
            'translatable' => ['name'],
            'actions' => [
                'widget' => [
                    'icon' => 'fa fa-fw fa-th',
                    'href_pattern' => ['pattern' => '[arg]/widgets', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('CMS::module.widget.title');"]],
                    'data' => []
                ],
                'edit' => [],
                'delete' => []
            ]
        ],
        'widget' => [
            'presenter' => \Corals\Modules\CMS\Transformers\WidgetPresenter::class,
            'resource_route' => 'cms.blocks.widgets.index',
            'resource_relation' => 'block',
            'relation' => 'widget',
            'translatable' => ['title', 'content']
        ],
        'testimonial' => [
            'presenter' => \Corals\Modules\CMS\Transformers\TestimonialPresenter::class,
            'default_image' => 'assets/corals/images/default_image.png',
            'resource_url' => 'cms/testimonials',
        ],
        'download' => [
            'presenter' => \Corals\Modules\CMS\Transformers\DownloadPresenter::class,
            'resource_url' => 'cms/downloads',
        ]
    ],
    'frontend' => [
        'page_limit' => 10,
    ]
];
