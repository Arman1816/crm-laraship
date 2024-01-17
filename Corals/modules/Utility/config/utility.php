<?php

return [
    'models' => [
        'rating' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Rating\RatingPresenter::class,
            'resource_url' => 'utilities/ratings',
            'actions' => [
                'pending' => [
                    'icon' => 'fa fa-fw fa-clock-o',
                    'href_pattern' => ['pattern' => '[arg]/pending', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Utility::attributes.rating.status_options.pending');"]],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'pending',
                    'permissions' => [],
                    'data' => [
                        'action' => "post",
                        'table' => "#RatingsDataTable"
                    ],
                ],
                'approved' => [
                    'icon' => 'fa fa-fw fa-check',
                    'href_pattern' => ['pattern' => '[arg]/approved', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Utility::attributes.rating.status_options.approved');"]],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'approved',
                    'permissions' => [],
                    'data' => [
                        'action' => "post",
                        'table' => "#RatingsDataTable",
                    ],
                ],

                'disapproved' => [
                    'icon' => 'fa fa-fw fa-remove',
                    'href_pattern' => ['pattern' => '[arg]/disapproved', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Utility::attributes.rating.status_options.disapproved');"]],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'disapproved',
                    'permissions' => [],
                    'data' => [
                        'action' => "post",
                        'table' => "#RatingsDataTable",
                    ],
                ],
                'spam' => [
                    'icon' => 'fa fa-fw fa-remove',
                    'href_pattern' => ['pattern' => '[arg]/spam', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Utility::attributes.rating.status_options.spam');"]],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'spam',
                    'permissions' => [],
                    'data' => [
                        'action' => "post",
                        'table' => "#RatingsDataTable",
                    ],
                ],
            ],

        ],
        'comment' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Comment\CommentPresenter::class,
            'resource_url' => 'utilities/comments',
            'status_options' => [
                'pending' => [
                    'text' => 'Utility::attributes.comments.status_options.pending',
                    'level' => 'info'
                ],
                'published' => [
                    'text' => 'Utility::attributes.comments.status_options.published',
                    'level' => 'success'
                ],
                'trashed' => [
                    'text' => 'Utility::attributes.comments.status_options.trashed',
                    'level' => 'warning'
                ],
            ],
            'actions' => [
                'edit' => [],
                'pending' => [
                    'icon' => 'fa fa-fw fa-clock-o',
                    'href_pattern' => ['pattern' => '[arg]/pending', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Utility::attributes.comments.status_options.pending');"]
                    ],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'pending',
                    'permissions' => [],
                    'data' => [
                        'action' => "post",
                        'table' => "#CommentsDataTable"
                    ],
                ],
                'published' => [
                    'icon' => 'fa fa-fw fa-check',
                    'href_pattern' => ['pattern' => '[arg]/published', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Utility::attributes.comments.status_options.published');"]
                    ],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'published',
                    'permissions' => [],
                    'data' => [
                        'action' => "post",
                        'table' => "#CommentsDataTable"
                    ],
                ],
                'trashed' => [
                    'icon' => 'fa fa-fw fa-trash-o',
                    'href_pattern' => ['pattern' => '[arg]/trashed', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Utility::attributes.comments.status_options.trashed');"]
                    ],
                    'policies' => ['updateStatus'],
                    'policies_args' => 'trashed',
                    'permissions' => [],
                    'data' => [
                        'action' => "post",
                        'table' => "#CommentsDataTable"
                    ],
                ],
            ],
        ],
        'wishlist' => [
            'resource_url' => 'utilities/wishlist',
            'actions' => [
                'edit' => []
            ]
        ],
        'location' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Address\LocationPresenter::class,
            'resource_url' => 'utilities/address/locations',
        ],
        'tag' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Tag\TagPresenter::class,
            'resource_url' => 'utilities/tags',
            'translatable' => ['name'],
        ],
        'category' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Category\CategoryPresenter::class,
            'resource_url' => 'utilities/categories',
            'default_image' => 'assets/corals/images/default_product_image.png',
            'translatable' => ['name']
        ],
        'attribute' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Category\AttributePresenter::class,
            'resource_url' => 'utilities/attributes',
        ],
        'model_option' => [
        ],
        'invite_friends' => [
            'resource_url' => 'utilities/invite-friends',
        ],
        'seo_item' => [
            'resource_url' => 'utilities/seo-items',
        ],
        'webhook' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Webhook\WebhookPresenter::class,
            'resource_url' => 'utilities/webhooks',
            'events' => [],
            'actions' => [
                'edit' => [],
                'process' => [
                    'icon' => 'fa fa-fw fa-send',
                    'href_pattern' => ['pattern' => '[arg]/process', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Utility::labels.webhook.process');"]],
                    'policies' => ['process'],
                    'data' => [
                        'action' => 'post',
                        'table' => '.dataTableBuilder'
                    ]
                ],
            ]
        ],
        'listOfValue' => [
            'presenter' => \Corals\Modules\Utility\Transformers\ListOfValue\ListOfValuePresenter::class,
            'resource_url' => 'utilities/list-of-values',
            'actions' => []
        ],
        'guide' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Guide\GuidePresenter::class,
            'resource_url' => 'utilities/guides',
            'guide_config' => [
                'title' => 'Guide Config',
                'fields' => [
                    'intro' => [
                        'name' => '[guide_config][{index}][intro]',
                        'type' => 'text',
                        'label' => 'Utility::attributes.guide.intro',
                        'status' => 'active',
                        'validation_rules' => 'required',
                    ],
                    'element' => [
                        'name' => '[guide_config][{index}][element]',
                        'type' => 'text',
                        'label' => 'Utility::attributes.guide.element',
                        'status' => 'active',
                        'validation_rules' => '',
                    ],
                    'position' => [
                        'name' => '[guide_config][{index}][position]',
                        'type' => 'text',
                        'label' => 'Utility::attributes.guide.position',
                        'status' => 'active',
                        'validation_rules' => '',
                    ]
                ]
            ],
        ],
    ],
    'content_consent_settings' => [
        'utility_content_consent_enabled' => [
            'label' => 'Utility::labels.content_consent.enabled',
            'type' => 'boolean',
            'validation' => '',
            'settings_type' => 'BOOLEAN',
        ],
        'utility_content_consent_popup_content' => [
            'label' => 'Utility::labels.content_consent.popup_content',
            'type' => 'textarea',
            'validation' => 'required',
            'settings_type' => 'TEXTAREA',
            'attributes' => [
                'class' => 'ckeditor-simple'
            ]
        ],
        'utility_content_consent_popup_title' => [
            'label' => 'Utility::labels.content_consent.popup_title',
            'type' => 'text',
            'validation' => 'required',
            'settings_type' => 'TEXT',
        ],
        'utility_content_consent_accept_button_text' => [
            'label' => 'Utility::labels.content_consent.accept_button_text',
            'type' => 'text',
            'validation' => 'required',
            'settings_type' => 'TEXT',
            'default' => 'I Agree',
        ],
        'utility_content_consent_reject_button_text' => [
            'label' => 'Utility::labels.content_consent.reject_button_text',
            'type' => 'text',
            'validation' => 'required',
            'settings_type' => 'TEXT',
            'default' => 'I Decline',
        ],
        'utility_content_consent_rejected_redirect_url' => [
            'label' => 'Utility::labels.content_consent.rejected_redirect_url',
            'type' => 'text',
            'validation' => 'required',
            'settings_type' => 'TEXT',
        ],
        'utility_content_consent_ask_every' => [
            'label' => 'Utility::labels.content_consent.ask_every',
            'type' => 'number',
            'default' => 30,
            'validation' => 'required',
            'settings_type' => 'NUMBER',
        ],
    ]
];
