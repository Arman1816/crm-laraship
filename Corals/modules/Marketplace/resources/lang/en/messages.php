<?php

return [
    'shipping' => [
        'success' => [
            'import' => ':successCount Rules uploaded successfully<br/> :wrongCount Rules contain errors',
        ]
    ],
    'vendor' => [
        'success' => [
            'enroll' => 'You\'re account has been set to Seller, you will still be able to buy from other stores too ',
        ]
    ],
    'store' => [
        'follow' => [
            'require_login' => 'You need to login to be able to add the store to your follow list',
            'added' => 'Store has been added to your follow list',
            'removed' => 'Store has been removed from your follow list',
        ]
    ],
    'refund' => [
        'do_refund_order' => 'Refund from Order has been successfully'
    ],
    'validation' => [
        'greater_than_zero' => 'The amount field it must greater than zero'
    ],
    'product' => [
        'at_least_one_rate' => 'At least one shipping rate should be added.',
        'product_successfully_cloned' => 'Product successfully duplicated!'
    ],
    'order' => [
        'placed' => 'Order has been successfully placed',
        'payout_confirm' => 'You are going to do payout for the selected order.',
        'payout_submitted' => 'Payout request has been submitted successfully.',
        'payment_failed' => 'Failed to authorize your payment details.'
    ],
    'sku' => [
        'updated' => 'Successfully updated [:count] sku',
        'no_sku_selected' => 'No SKU selected'
    ]
];
