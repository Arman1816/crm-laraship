<?php

return [
    'models' => [
        'discussion' => [
            'presenter' => \Corals\Modules\Messaging\Transformers\DiscussionPresenter::class,
            'resource_url' => 'messaging/discussions',
            'actions' => [
                'edit' => [],
                'read' => [
                    'icon' => 'fa fa-fw fa-envelope-open',
                    'href_pattern' => ['pattern' => '[arg]/markAsRead', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Messaging::attributes.discussion.status_options.read');"]],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'read',
                    'data' => [
                        'action' => "post",
                        'page_action' => 'site_reload'
                    ]
                ],
                'unRead' => [
                    'icon' => 'fa fa-fw fa-envelope',
                    'href_pattern' => ['pattern' => '[arg]/unread', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Messaging::attributes.discussion.status_options.unread');"]],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'unread',
                    'data' => [
                        'action' => "post",
                        'page_action' => 'site_reload'
                    ]
                ],
                'important' => [
                    'icon' => 'fa fa-fw fa-info-circle',
                    'href_pattern' => ['pattern' => '[arg]/important', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Messaging::attributes.discussion.status_options.important');"]],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'important',
                    'data' => [
                        'action' => "post",
                        'page_action' => 'site_reload'
                    ]
                ],
                'star' => [
                    'icon' => 'fa fa-fw fa-star',
                    'href_pattern' => ['pattern' => '[arg]/star', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Messaging::attributes.discussion.status_options.star');"]],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'star',
                    'data' => [
                        'action' => "post",
                        'page_action' => 'site_reload'
                    ]
                ],
            ]
        ],
        'message' => [
            'presenter' => \Corals\Modules\Messaging\Transformers\MessagesPresenter::class,
            'default_image' => 'assets/corals/images/default_product_image.png',
            'resource_url' => 'messaging/messages',
        ],
        'participation' => [
            'presenter' => \Corals\Modules\Messaging\Transformers\ParticipationPresenter::class,
            'resource_url' => 'messaging/participations',
        ],
    ]
];
