<?php

return [
    'models' => [
        'subscription' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\SubscriptionPresenter::class,
            'resource_url' => 'subscriptions/subscriptions',
            'statuses' => [
                'active' => 'Subscriptions::attributes.subscription.subscription_statuses.active',
                'canceled' => 'Subscriptions::attributes.subscription.subscription_statuses.cancelled',
                'pending' => 'Subscriptions::attributes.subscription.subscription_statuses.pending'
            ],
            'actions' => [
                'delete' => [],
                'markInvoiceAsPaidAndActive' => [
                    'icon' => 'fa fa-fw fa-check',
                    'class' => 'btn btn-sm btn-success',
                    'href_pattern' => [
                        'pattern' => '[arg]/mark-invoice-as-paid-and-active',
                        'replace' => ['return $object->getShowURL();']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ['return trans("Subscriptions::labels.subscription.mark_invoice_paid_label");']
                    ],
                    'policies' => ['markInvoiceAsPaidAndActive'],
                    'data' => [
                        'action' => 'post',
                        'confirmation_pattern' => [
                            'pattern' => '[arg]',
                            'replace' => ["return trans('Subscriptions::labels.subscription.mark_invoice_paid_confirmation');"]
                        ],
                        'table' => '#SubscriptionsDataTable'
                    ]
                ],
                'renew' => [
                    'icon' => 'fa fa-fw fa-refresh',
                    'class' => 'btn btn-sm btn-success',
                    'href_pattern' => [
                        'pattern' => '[arg]/renew',
                        'replace' => ['return $object->getShowURL();']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ['return trans("Subscriptions::labels.subscription.renew");']
                    ],
                    'policies' => ['renew'],
                    'data' => [
                        'action' => 'post',
                        'confirmation_pattern' => [
                            'pattern' => '[arg]',
                            'replace' => ["return trans('Subscriptions::labels.subscription.renew_subscription_confirmation');"]
                        ],
                        'table' => '#SubscriptionsDataTable'
                    ]
                ],


            ],
            'ajaxSelectOptions' => [
                'label' => 'Subscription',
                'model_class' => \Corals\Modules\Subscriptions\Models\Subscription::class,
                'columns' => ['subscription_reference'],
            ]
        ],
        'product' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\ProductPresenter::class,
            'resource_url' => 'subscriptions/products',
            'default_image' => 'assets/corals/images/default_product_image.png',
            'translatable' => ['name'],
            'actions' => [
                'plans' => [
                    'href_pattern' => ['pattern' => '[arg]/plans', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Subscriptions::labels.feature.plan');"]
                    ],
                    'data' => []
                ],
                'features' => [
                    'href_pattern' => ['pattern' => '[arg]/features', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Subscriptions::labels.plan.features');"]
                    ],
                    'data' => []
                ]
            ]
        ],
        'feature' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\FeaturePresenter::class,
            'resource_route' => 'products.features.index',
            'translatable' => ['name', 'caption'],
            'resource_relation' => 'product',
            'relation' => 'feature',
            'sources_list' => [
                'list_of_values' => 'Subscriptions::labels.feature.sources_list.list_of_values',
                'config' => 'Subscriptions::labels.feature.sources_list.config',
                'settings' => 'Subscriptions::labels.feature.sources_list.settings'
            ]
        ],
        'feature_model' => [],
        'plan' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\PlanPresenter::class,
            'resource_route' => 'products.plans.index',
            'translatable' => ['name'],
            'resource_relation' => 'product',
            'relation' => 'plan'
        ],

        'subscription_cycle' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\SubscriptionCyclePresenter::class,
            'resource_url' => 'subscriptions/subscription-cycles',
        ],
        'plan_usage' => [
            'presenter' => \Corals\Modules\Subscriptions\Transformers\PlanUsagePresenter::class,
            'resource_url' => 'subscriptions/plan-usage',
        ],
    ],

    'features_has_widgets_types' => [
        'boolean',
        'quantity'
    ]
];
