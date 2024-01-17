<?php


return [
    'resource_url' => 'payments',
    'models' => [
        'invoice' => [
            'presenter' => \Corals\Modules\Payment\Common\Transformers\InvoicePresenter::class,
            'resource_url' => 'invoices',
            'statuses' => [
                'paid' => 'Payment::attributes.invoice.invoice_option.paid',
                'failed' => 'Payment::attributes.invoice.invoice_option.failed',
                'pending' => 'Payment::attributes.invoice.invoice_option.pending',
                'cancelled' => 'Payment::attributes.invoice.invoice_option.cancelled',
            ],
            'actions' => [
                'delete' => [],
                'download' => [
                    'href_pattern' => ['pattern' => '[arg]/download', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Corals::labels.download');"]
                    ],
                    'target' => '_blank',
                    'data' => []
                ],
                'pay_order' => [
                    'icon' => 'fa fa-money fa-fw',
                    'href_pattern' => [
                        'pattern' => 'e-commerce/checkout/?order=[arg]',
                        'replace' => ['return $object->invoicable->hashed_id;']
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Payment::labels.invoice.pay');"]
                    ],
                    'policies' => ['payOrder'],
                    'data' => [
                    ]
                ],
                'sendInvoice' => [
                    'href_pattern' => [
                        'pattern' => '[arg]/send-invoice',
                        'replace' => ['return $object->getShowUrl();']
                    ],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Corals::labels.send');"]],
                    'policies' => ['sendInvoice'],
                    'data' => [
                        'action' => 'post',
                        'table' => '.dataTableBuilder',
                        'confirmation_pattern' => [
                            'pattern' => '[arg]',
                            'replace' => ["return trans('Payment::messages.send_invoice');"]
                        ],
                    ]
                ]
            ],
            'ajaxSelectOptions' => [
                'label' => 'Invoice (Payment)',
                'model_class' => \Corals\Modules\Payment\Common\Models\Invoice::class,
                'columns' => ['code'],
            ],
        ],
        'invoice_item' => [
            'presenter' => \Corals\Modules\Payment\Common\Transformers\InvoiceItemPresenter::class,
        ],
        'webhook_call' => [
            'presenter' => \Corals\Modules\Payment\Common\Transformers\WebhookCallPresenter::class,
            'resource_url' => 'webhook-calls',
            'actions' => [
                'process' => [
                    'icon' => 'fa fa-fw fa-send',
                    'href_pattern' => ['pattern' => '[arg]/process', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Payment::attributes.webhook_call.process');"]
                    ],
                    'policies' => ['process'],
                    'data' => [
                        'action' => 'post',
                        'table' => '.dataTableBuilder'
                    ]
                ],
                'edit' => [],
                'delete' => []
            ],
        ],
        'transaction' => [
            'presenter' => \Corals\Modules\Payment\Common\Transformers\TransactionPresenter::class,
            'resource_url' => 'transactions',
            'ajaxSelectOptions' => [
                'label' => 'Transaction (Payment)',
                'model_class' => \Corals\Modules\Payment\Common\Models\Transaction::class,
                'columns' => ['code'],
            ],
            'actions' => [
                'reverse' => [
                    'icon' => 'fa fa-fw fa-backward',
                    'href_pattern' => ['pattern' => '[arg]/reverse', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Payment::labels.transaction.reverse');"]
                    ],
                    'policies' => ['canReverseTransfer'],
                    'data' => [
                        'action' => 'post',
                        'table' => '.dataTableBuilder',
                        'confirmation_pattern' => [
                            'pattern' => '[arg]',
                            'replace' => ["return trans('Payment::messages.reverse_confirmation');"]
                        ],
                    ]
                ]
            ]
        ],
        'tax_class' => [
            'presenter' => \Corals\Modules\Payment\Common\Transformers\TaxClassPresenter::class,
            'resource_url' => 'tax/tax-classes',
            'actions' => [
                'taxes' => [
                    'icon' => 'fa fa-fw fa-money',
                    'href_pattern' => ['pattern' => '[arg]/taxes', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Payment::module.tax.title');"]
                    ],
                    'data' => []
                ],
                'edit' => [
                    'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getEditUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Corals::labels.edit');"]],
                    'data' => [
                        'action' => 'modal-load',
                        'title_pattern' => [
                            'pattern' => 'Edit Tax Class : [arg]',
                            'replace' => ['return $object->name;']
                        ],
                    ]
                ]
            ]
        ],
        'tax' => [
            'presenter' => \Corals\Modules\Payment\Common\Transformers\TaxPresenter::class,
            'resource_route' => 'tax-classes.taxes.index',
            'resource_relation' => 'tax_class',
            'relation' => 'tax'
        ],
        'currency' => [
            'presenter' => \Corals\Modules\Payment\Common\Transformers\CurrencyPresenter::class,
            'resource_url' => 'currencies',
            'actions' => [
                'delete' => []
            ]
        ],
    ]
];

