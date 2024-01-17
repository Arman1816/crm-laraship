<?php

$preferred_providers = [
    'label' => 'Marketplace::labels.settings.shipping.preferred_providers',
    'type' => 'select',
    'settings_type' => 'select',
    'cast_type' => 'array',
    'attributes' => ['multiple' => true],
    'required' => false,
    'options' => "return \ListOfValues::get('marketplace_shipping_providers');",
];

return [
    'models' => [
        'store' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\StorePresenter::class,
            'resource_url' => 'marketplace/stores',
            'default_image' => 'assets/corals/images/default_product_image.png',
            'default_cover_image' => 'assets/corals/images/default_cover_image.png',
            'statuses' => [
                'active' => 'Marketplace::status.store.active',
                'inactive' => 'Marketplace::status.store.inactive',
                'suspended' => 'Marketplace::status.store.suspended'
            ],
            'translatable' => ['short_description'],
            'sort_options' => 'Marketplace::status.store_order',


        ],
        'attribute_set' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\AttributeSetPresenter::class,
            'resource_url' => 'marketplace/attribute-sets',
        ],
        'product' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\ProductPresenter::class,
            'resource_url' => 'marketplace/products',
            'default_image' => 'assets/corals/images/default_product_image.png',
            'translatable' => ['name', 'description'],
            'bulk_price_options' => [
                'none' => 'Marketplace::labels.product.bulk_price_options.none',
                'price_per_quantity' => 'Marketplace::labels.product.bulk_price_options.price_per_quantity',
                'offers' => 'Marketplace::labels.product.bulk_price_options.offers'
            ],
            'price_per_quantity' => [
                'price_per_quantity_types' => [
                    'percentage' => 'Marketplace::attributes.product.price_per_quantity.price_per_quantity_types.percentage',
                    'fixed' => 'Marketplace::attributes.product.price_per_quantity.price_per_quantity_types.fixed'
                ]
            ],
            'offers' => [
                'offer_types' => [
                    'bundle_price' => 'Marketplace::attributes.product.offers.offer_types.bundle_price',
                    'get_free' => 'Marketplace::attributes.product.offers.offer_types.get_free'
                ]
            ],

            'actions' => [
                'delete' => [
                    'datatable_only' => true,
                    'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getOriginalShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Corals::labels.delete');"]],
                    'policies' => ['destroy'],
                    'data' => [
                        'action' => 'delete',
                        'table' => '.dataTableBuilder'
                    ],
                ],
                'sku' => [
                    'class' => 'btn btn-primary btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/sku',
                        'replace' => ['return $object->getOriginalShowURL();']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.product.variations');"]
                    ],
                    'policies' => ['variations'],
                    'data' => [],
                ],
                'sku_add' => [
                    'class' => 'btn btn-success btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/sku/create',
                        'replace' => ['return $object->getOriginalShowURL();']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.product.variations_create');"]
                    ],
                    'policies' => ['variations'],
                    'data' => [],
                ],
                'bulk-generate' => [
                    'class' => 'btn btn-success btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/sku/bulk-generate',
                        'replace' => ['return $object->getOriginalShowURL();']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.bulkGenerate.bulk-generate');"]
                    ],
                    'policies' => ['variations'],
                    'data' => [],
                ],
                'bulk-edit' => [
                    'class' => 'btn btn-primary btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/sku/bulk-edit',
                        'replace' => ['return $object->getOriginalShowURL();']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.bulkGenerate.bulk-edit');"]
                    ],
                    'policies' => ['bulkEditSKUs'],
                    'data' => [],
                ],

                'copy-product' => [
                    'class' => 'btn btn-primary btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/copy-product-modal',
                        'replace' => ['return $object->getOriginalShowURL();']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.product.clone_product');"]
                    ],
                    'data' => [
                        'action' => 'modal-load',
                        'title_pattern' => [
                            'pattern' => '[arg]',
                            'replace' => ["return trans('Marketplace::labels.product.clone_product');"]
                        ]
                    ],
                ],
            ],
            'ajaxSelectOptions' => [
                'label' => 'Product (Marketplace)',
                'model_class' => \Corals\Modules\Marketplace\Models\Product::class,
                'columns' => ['name'],
            ],
        ],
        'coupon' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\CouponPresenter::class,
            'resource_url' => 'marketplace/coupons',
        ],
        'shipping' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\ShippingPresenter::class,
            'resource_url' => 'marketplace/shippings',
        ],
        'package' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\PackagePresenter::class,
            'resource_url' => 'marketplace/packages',
            'distance_unit_options' => [
                'cm' => 'cm',
                'in' => 'in',
                'ft' => 'ft',
                'mm' => 'mm',
                'm' => 'm',
                'yd' => 'yd'
            ],
            'mass_unit_options' => [
                'g' => 'g',
                'oz' => 'oz',
                'lb' => 'lb',
                'kg' => 'kg',
            ],
        ],
        'order' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\OrderPresenter::class,
            'resource_url' => 'marketplace/orders',
            'statuses' => 'Marketplace::status.order',
            'shipping_statuses' => 'Marketplace::status.shipping',
            'payment_statuses' => 'Marketplace::status.payment',
            'actions' => [
                'edit' => [],
                'delete' => [],
                'change_status' => [
                    'icon' => 'fa fa-fw fa-edit',
                    'href_pattern' => ['pattern' => '[arg]/edit', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.order.update_order');"]
                    ],
                    'permissions' => ['Marketplace::order.update'],
                    'data' => [
                        'action' => 'modal-load',
                        'title_pattern' => [
                            'pattern' => '[arg]',
                            'replace' => ["return trans('Marketplace::labels.order.update_order');"]
                        ],
                    ],
                ],
                'contact' => [
                    'icon' => 'fa fa-fw fa-envelope-o',
                    'href_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ['return url("messaging/discussions/create?user=".optional($object->user)->hashed_id);']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.order.contact_buyer');"]
                    ],
                    'policies_model' => \Corals\Modules\Messaging\Models\Discussion::class,
                    'policies' => ['create'],
                    'data' => [],
                ],
                'refund_order' => [
                    'icon' => 'fa fa-undo',
                    'href_pattern' => [
                        'pattern' => '[arg]/refund-order',
                        'replace' => ['return $object->getShowUrl();']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.order.refund_order');"]
                    ],
                    'policies' => ['refund'],
                    'data' => [
                        'action' => 'modal-load',
                        'title_pattern' => [
                            'pattern' => '[arg]',
                            'replace' => ["return trans('Marketplace::labels.order.refund_order');"]
                        ],
                    ],
                ],
                'doPayout' => [
                    'icon' => 'fa fa-fw fa-exchange',
                    'href_pattern' => ['pattern' => '[arg]/payout', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Marketplace::labels.order.payout');"]
                    ],
                    'policies' => ['canDoPayout'],
                    'data' => [
                        'action' => 'post',
                        'table' => '.dataTableBuilder',
                        'confirmation_pattern' => [
                            'pattern' => '[arg]',
                            'replace' => ["return trans('Marketplace::messages.order.payout_confirm');"]
                        ],
                    ]
                ]
            ],
            'ajaxSelectOptions' => [
                'label' => 'Order (Marketplace)',
                'model_class' => \Corals\Modules\Marketplace\Models\Order::class,
                'columns' => ['order_number'],
            ]
        ],
        'shop' => [
            'sort_options' => 'Marketplace::status.shop_order',
        ],
        'wishlist' => [
            'presenter' => \Corals\Modules\Utility\Transformers\Wishlist\WishlistPresenter::class,
            'resource_url' => 'marketplace/wishlist',
        ],
        'follow' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\FollowPresenter::class,
            'resource_url' => 'marketplace/follow',
            'actions' => [
                'edit' => []
            ]
        ],
        'order_item' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\OrderItemPresenter::class,
        ],
        'category' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\CategoryPresenter::class,
            'resource_url' => 'marketplace/categories',
            'default_image' => 'assets/corals/images/default_product_image.png',
            'translatable' => ['name', 'description']

        ],
        'tag' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\TagPresenter::class,
            'resource_url' => 'marketplace/tags',
        ],
        'brand' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\BrandPresenter::class,
            'resource_url' => 'marketplace/brands',
            'default_image' => 'assets/corals/images/default_product_image.png',
            'translatable' => ['name']
        ],
        'attribute' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\AttributePresenter::class,
            'resource_url' => 'marketplace/attributes',
            'translatable' => ['label']

        ],
        'attribute_option' => [
            'translatable' => ['option_display']

        ],

        'sku' => [
            'presenter' => \Corals\Modules\Marketplace\Transformers\SKUPresenter::class,
            'resource_route' => 'marketplace.products.sku.index',
            'resource_relation' => 'product',
            'relation' => 'sku',
            'default_image' => 'assets/corals/images/default_product_image.png',
            'inventory_options' => [
                'finite' => 'Marketplace::attributes.product.type_options.finite',
                'bucket' => 'Marketplace::attributes.product.type_options.bucket',
                'infinite' => 'Marketplace::attributes.product.type_options.infinite'
            ],
            'bucket' => [
                'in_stock' => 'Marketplace::attributes.product.bucket_options.in_stock',
                'out_of_stock' => 'Marketplace::attributes.product.bucket_options.out_of_stock',
                'limited' => 'Marketplace::attributes.product.bucket_options.limited',
            ]
        ],
        'transaction' => [
            'resource_url' => 'marketplace/transactions',
        ],
        'sku_property' => [],
    ],
    'site_settings' => [
        'General' => [
            /*
            'enable_subdomain' => [
                'label' => 'Marketplace::labels.settings.general.enable_subdomain',
                'validation' => 'required',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'value' => null,
                'required' => true,
                'attributes' => [
                    'help_text' => ''
                ]
            ],
            'enable_domain_parking' => [
                'label' => 'Marketplace::labels.settings.general.enable_domain_parking',
                'validation' => 'required',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'value' => null,
                'required' => true,
                'attributes' => [
                    'help_text' => ''
                ]
            ],
            */
            'vendor_role' => [
                'label' => 'Marketplace::labels.settings.general.vendor_role',
                'validation' => 'required',
                'required' => true,
                'value' => null,
                'settings_type' => 'TEXT',
                'type' => 'select',
                'options' => 'return \Roles::getRolesList(["key"=>"name"]);',
                'attributes' => [
                    'help_text' => ''
                ]
            ],
            'enroll_terms' => [
                'label' => 'Marketplace::labels.settings.general.vendor_agreement_text',
                'validation' => 'required',
                'required' => true,
                'value' => null,
                'settings_type' => 'TEXT',
                'type' => 'textarea',
                'attributes' => [
                    'help_text' => '',
                    'class' => 'ckeditor',
                    'rows' => 5
                ]
            ],
            'vendor_require_subscription' => [
                'label' => 'Marketplace::labels.settings.general.vendor_require_subscription',
                'validation' => 'required',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'value' => null,
                'required' => true,
                'attributes' => [
                    'help_text' => ''
                ]
            ],
            'fixed_commission_percentage' => [
                'label' => 'Marketplace::labels.settings.general.fixed_commission_percentage',
                'validation' => 'required',
                'type' => 'number',
                'settings_type' => 'NUMBER',
                'value' => 3,
                'required' => true,
                'attributes' => [
                    'help_text' => ''
                ]
            ],
            'subscription_product' => [
                'label' => 'Marketplace::labels.settings.general.subscription_product',
                'validation' => 'required',
                'type' => 'select',
                'settings_type' => 'NUMBER',
                'value' => null,
                'required' => true,
                'options' => 'return Corals\Modules\Subscriptions\Facades\SubscriptionProducts::getProductsList();',
                'attributes' => [
                    'help_text' => ''
                ]
            ],
            /*
        'product_limit_feature' => [
            'label' => 'Marketplace::labels.settings.general.product_limit_feature',
            'validation' => 'required',
            'value' => null,
            'settings_type' => 'NUMBER',
            'required' => true,
            'options' => 'return \Corals\Modules\Subscriptions\Facades\SubscriptionProducts::getFeaturesList();',
            'type' => 'select',
            'attributes' => [
                'help_text' => ''
            ]
        ],
        */
            'commission_feature' => [
                'label' => 'Marketplace::labels.settings.general.commission_feature',
                'validation' => 'required',
                'value' => null,
                'required' => true,
                'settings_type' => 'NUMBER',
                'options' => 'return \Corals\Modules\Subscriptions\Facades\SubscriptionProducts::getFeaturesList();',
                'type' => 'select',
                'attributes' => [
                    'help_text' => ''
                ]
            ],
            'fallback_plan' => [
                'label' => 'Marketplace::labels.settings.general.fallback_plan',
                'validation' => 'required',
                'value' => null,
                'settings_type' => 'NUMBER',
                'required' => true,
                'options' => 'return \Corals\Modules\Subscriptions\Facades\SubscriptionProducts::getPlansList();',
                'type' => 'select',
                'attributes' => [
                    'help_text' => ''
                ]
            ],
            'abandoned_cart_email_after' => [
                'label' => 'Marketplace::labels.settings.general.abandoned_cart_email_after',
                'type' => 'number',
                'settings_type' => 'GENERAL',
                'required' => false,
            ],
        ],
        'Tax' => [
            'calculate_tax' => [
                'label' => 'Marketplace::labels.settings.tax.calculate_tax',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'required' => true,
            ],
            'tax_included_in_price' => [
                'label' => 'Marketplace::labels.settings.tax.tax_included_in_price',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'required' => true,
            ],
        ],
        'Rating' => [
            'enable' => [
                'label' => 'Marketplace::labels.settings.rating.enable',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'required' => true,
            ],
            'comment_enable' => [
                'label' => 'Marketplace::labels.settings.rating.enable_comment',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'required' => true,
            ]
        ],
        'Wishlist' => [
            'enable' => [
                'label' => 'Marketplace::labels.settings.wishlist.enable',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'required' => true,
            ]
        ],
        'Appearance' => [
            'page_limit' => [
                'label' => 'Marketplace::labels.settings.appearance.page_limit',
                'type' => 'number',
                'settings_type' => 'GENERAL',
                'required' => false,
            ],
            'enable_tags' => [
                'label' => 'Marketplace::labels.settings.appearance.enable_tags',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'required' => false,
            ]
        ],
        'Search' => [
            'title_weight' => [
                'label' => 'Marketplace::labels.settings.search.title_weight',
                'type' => 'number',
                'settings_type' => 'NUMBER',
                'step' => 0.01,
                'required' => false,
            ],
            'content_weight' => [
                'label' => 'Marketplace::labels.settings.search.content_weight',
                'type' => 'number',
                'settings_type' => 'NUMBER',
                'step' => 0.01,
                'required' => false,
            ],
            'enable_wildcards' => [
                'label' => 'Marketplace::labels.settings.search.enable_wildcards',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'required' => true,
            ]
        ],
        'Shipping' => [
            'weight_unit' => [
                'label' => 'Marketplace::labels.settings.shipping.weight_unit',
                'type' => 'select',
                'settings_type' => 'TEXT',
                'options' => [
                    'kg' => 'kg',
                    'g' => 'g',
                    'lb' => 'lbs',
                    'oz' => 'oz'
                ],
                'required' => true,
            ],
            'dimensions_unit' => [
                'label' => 'Marketplace::labels.settings.shipping.dimensions_unit',
                'type' => 'select',
                'settings_type' => 'TEXT',
                'options' => [
                    'm' => 'm',
                    'cm' => 'cm',
                    'mm' => 'mm',
                    'in' => 'in',
                    'yd' => 'yd'
                ],
                'required' => true,
            ],
            'shippo_live_token' => [
                'label' => 'Marketplace::labels.settings.shipping.shippo_live_token',
                'type' => 'text',
                'settings_type' => 'TEXT',
                'required' => true,
            ],
            'shippo_test_token' => [
                'label' => 'Marketplace::labels.settings.shipping.shippo_test_token',
                'type' => 'text',
                'settings_type' => 'TEXT',
                'required' => true,
            ],
            'shippo_sandbox_mode' => [
                'label' => 'Marketplace::labels.settings.shipping.shippo_sandbox_mode',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
            ],
            'shippo_per_store' => [
                'label' => 'Marketplace::labels.settings.shipping.shippo_per_store',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'default' => true
            ],
            'preferred_providers' => $preferred_providers,
            'enable_shipping_per_product' => [
                'label' => 'Marketplace::labels.settings.shipping.enable_shipping_per_product',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
            ],
            'show_shipping_details' => [
                'label' => 'Marketplace::labels.settings.shipping.show_shipping_details',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
                'default' => true
            ],
        ],
        'Checkout' => [
            'points_redeem_enable' => [
                'label' => 'Marketplace::labels.settings.checkout.points_redeem_enable',
                'type' => 'boolean',
                'settings_type' => 'BOOLEAN',
            ],
        ],
        'Payout' => [
            'payout_mode' => [
                'label' => 'Marketplace::labels.settings.payout.payout_mode',
                'type' => 'select',
                'settings_type' => 'TEXT',
                'options' => [
                    'request' => 'Withdrawal Request',
                    'immediate' => 'Immediate',
                    'periodic' => 'Periodic',
                ],
                'required' => true,

            ],
            'min_transaction_age' => [
                'label' => 'Marketplace::labels.settings.payout.min_transaction_age',
                'type' => 'text',
                'settings_type' => 'NUMBER',
                'required' => true,
            ],
        ],
    ],
    'store_settings' => [
        'Company' => [
            'owner' => [
                'label' => 'Marketplace::labels.settings.company.owner',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
            'name' => [
                'label' => 'Marketplace::labels.settings.company.name',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
            'street1' => [
                'label' => 'Marketplace::labels.settings.company.street',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
            'city' => [
                'label' => 'Marketplace::labels.settings.company.city',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
            'state' => [
                'label' => 'Marketplace::labels.settings.company.state',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
            'zip' => [
                'label' => 'Marketplace::labels.settings.company.zip',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
            'country' => [
                'label' => 'Marketplace::labels.settings.company.country',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
            'phone' => [
                'label' => 'Marketplace::labels.settings.company.phone',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
            'email' => [
                'label' => 'Marketplace::labels.settings.company.email',
                'type' => 'text',
                'required' => true,
                'cast_type' => 'string',

            ],
        ],
        'Shipping' => [
            'weight_unit' => [
                'label' => 'Marketplace::labels.settings.shipping.weight_unit',
                'type' => 'select',
                'cast_type' => 'string',
                'options' => [
                    'kg' => 'kg',
                    'g' => 'g',
                    'lb' => 'lbs',
                    'oz' => 'oz'
                ],
                'required' => true,

            ],
            'dimensions_unit' => [
                'label' => 'Marketplace::labels.settings.shipping.dimensions_unit',
                'type' => 'select',
                'cast_type' => 'string',
                'options' => [
                    'm' => 'm',
                    'cm' => 'cm',
                    'mm' => 'mm',
                    'in' => 'in',
                    'yd' => 'yd'
                ],
                'required' => true,
            ],
            'shippo_live_token' => [
                'label' => 'Marketplace::labels.settings.shipping.shippo_live_token',
                'type' => 'text',
                'cast_type' => 'string',
                'required' => true,
            ],
            'shippo_test_token' => [
                'label' => 'Marketplace::labels.settings.shipping.shippo_test_token',
                'type' => 'text',
                'cast_type' => 'string',
                'required' => true,
            ],
            'shippo_sandbox_mode' => [
                'label' => 'Marketplace::labels.settings.shipping.shippo_sandbox_mode',
                'cast_type' => 'boolean',
                'type' => 'boolean'
            ],
            'preferred_providers' => $preferred_providers,
            'is_shipping_per_product' => [
                'label' => 'Marketplace::labels.settings.shipping.is_shipping_per_product',
                'cast_type' => 'boolean',
                'type' => 'boolean'
            ],
            'shippable_countries' => [
                'label' => 'Marketplace::labels.settings.shipping.shippable_countries',
                'type' => 'select',
                'cast_type' => 'array',
                'attributes' => ['multiple' => true],
                'required' => false,
                'options' => 'return \Settings::getCountriesList();'
            ],
        ],
        'Bank' => [
            'information' => [
                'label' => 'Marketplace::labels.settings.bank_information',
                'type' => 'textarea',
                'cast_type' => 'string',
                'required' => true,
            ]
        ],
    ]
];
