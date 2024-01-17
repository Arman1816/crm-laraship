<?php

return [
    'rating' => [
        'success' => [
            'add' => 'Your review has been added successfully',
            'add_with_pending' => 'Your review has been added successfully, Please wait for admin approval',
            'status_update' => 'Review status has been update successfully'
        ],
    ],
    'comment' => [
        'success' => [
            'add' => 'Your comment has been added successfully',
            'add_with_pending' => 'Your comment has been added successfully, Please wait for admin approval',
            'status_update' => 'Comment status has been update successfully'
        ],
    ],
    'subscription' => [
        'success' => 'You have been subscribed successfully'

    ],
    'wishlist' => [
        'require_login' => 'You have to login to be able to add :item to wishlist',
        'success' => [
            'add' => ':item has been added to wishlist successfully',
            'delete' => ':item has been removed from wishlist successfully',
        ],
    ],
    'gallery' => [
        'success' => [
            'upload' => 'Image has been uploaded Successfully',
        ]
    ],
    'invite_friends' => [
        'success' => [
            'invitation_sent' => 'Invitation has been sent successfully',
        ]
    ],
    'webhook' => [
        'processed' => 'Webhook has been processed successfully',
        'bulk_processed' => ':processed_count webhooks have been processed. :failed_count webhooks were failed',
        'event_submitted_successfully' => 'Event :name has been submitted successfully.',
    ],
];
